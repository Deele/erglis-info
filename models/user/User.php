<?php

namespace app\models\user;

use deele\devkit\base\HasStatusesTrait;
use deele\devkit\cache\CachedByTagTrait;
use InvalidArgumentException;
use Yii;
use yii\db\ActiveRecord;
use yii\db\AfterSaveEvent;
use yii\helpers\ArrayHelper;
use yii\helpers\VarDumper;
use yii\rbac\Role;

/**
 * This is the model class for table "{{%user}}".
 *
 * @property integer $id
 * @property string $username
 * @property string $name
 * @property string $surname
 * @property string $password_hash
 * @property integer $status
 * @property integer $accepts_newsletters
 * @property string $auth_key
 * @property string $created_at Created at
 * @property string $updated_at Updated at
 *
 * @property-read string $title {@link User::getTitle()}
 * @property-read bool $hasPendingStatus {@link User::getHasPendingStatus()}
 * @property-read bool $hasEnabledStatus {@link User::getHasEnabledStatus()}
 * @property-read bool $hasBannedStatus {@link User::getHasBannedStatus()}
 * @property-read bool $hasDisabledStatus {@link User::getHasDisabledStatus()}
 * @property-write string $password {@link User::setPassword()}
 * @property-read bool $hasPassword {@link User::getHasPassword()}
 */
class User extends ActiveRecord
{
    use CachedByTagTrait;
    use HasStatusesTrait;

    const STATUS_PENDING = 1;
    const STATUS_ENABLED = 2;
    const STATUS_BANNED = 3;
    const STATUS_DISABLED = 4;
    const STATUS_PENDING_PASSWORD_CHANGE = 5;

    const SCENARIO_CREATE = 'create';
    const SCENARIO_CHANGE_PASSWORD = 'changePassword';
    const SCENARIO_REGISTER = 'register';

    const EVENT_AFTER_PENDING_STATUS = 'afterPendingStatus';
    const EVENT_AFTER_ENABLED_STATUS = 'afterEnabledStatus';
    const EVENT_AFTER_BANNED_STATUS = 'afterBannedStatus';
    const EVENT_AFTER_DISABLED_STATUS = 'afterDisabledStatus';

    const EVENT_AFTER_PASSWORD_CHANGED = 'afterPasswordChanged';

    const ROLE_GUEST = 'guest';
    const ROLE_REGISTERED = 'registered';

    protected $_roles;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * Attaching Event Handlers
     */
    public function init()
    {
        $this->listenForChangesToInvalidateCache();
        $this->listenForStatusChanges();
//        $this->on(
//            static::getEventAfterStatusChangeName(),
//            [$this, 'handleStatusChange']
//        );
//        $this->on(
//            self::EVENT_AFTER_INSERT,
//            [$this, 'assignDefaultRoles']
//        );
        $this->on(
            self::EVENT_AFTER_UPDATE,
            [$this, 'handleChangesAfterUpdate']
        );

        parent::init();
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [
                [
                    'username',
                ],
                'required'
            ],
            [
                'password',
                'passwordHashShouldBeSetValidator',
                'on'     => [
                    self::SCENARIO_CREATE,
                    self::SCENARIO_REGISTER,
                    self::SCENARIO_CHANGE_PASSWORD
                ]
            ],
            [
                'password',
                'safe'
            ],
            [
                'username',
                'string',
                'min' => 5,
                'max' => 100
            ],
            [
                'username',
                'filter',
                'filter' => 'trim'
            ],
            [
                'username',
                'unique',
                'message' => Yii::t(
                    'app.users.User',
                    'This username has already been taken'
                ),
                'filter'  => ['!=', 'username', $this->getOldAttribute('username')]
            ],
            [
                [
                    'password_hash',
                ],
                'string',
                'max' => 100
            ],
            [
                'auth_key',
                'string',
                'max' => 32
            ],
            [
                'accepts_newsletters',
                'boolean'
            ],
            [
                'status',
                'default',
                'value' => self::STATUS_ENABLED
            ],
            [
                'status',
                'in',
                'range' => array_keys(self::getStatuses())
            ],
//            [
//                'roles',
//                'safe'
//            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return ArrayHelper::merge(
            parent::attributeLabels(),
            [
                'id'                   => Yii::t(
                    'app.users.User',
                    'User ID'
                ),
                'username'             => Yii::t(
                    'app.users.User',
                    'Username'
                ),
                'password_hash'        => Yii::t(
                    'app.users.User',
                    'Password hash'
                ),
                'status'               => Yii::t(
                    'app.users.User',
                    'Status'
                ),
                'password'             => Yii::t(
                    'app.users.User',
                    'Password'
                ),
                'auth_key'             => Yii::t(
                    'app.users.User',
                    'Authentication key'
                ),
                'accepts_newsletters'             => Yii::t(
                    'app.users.User',
                    'Accepts newsletters'
                ),
                'created_at' => Yii::t('app.common', 'Created at'),
                'updated_at' => Yii::t('app.common', 'Updated at'),
            ]
        );
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @param int $status
     *
     * @return User|null
     */
    public static function findByUsername($username, $status = User::STATUS_ENABLED)
    {
        return static::findOne(
            [
                'username' => $username,
                'status'   => $status
            ]
        );
    }

    /**
     * Finds if user exists by username
     *
     * @param string $username
     *
     * @return bool
     */
    public static function existsByUsername($username)
    {
        return static
            ::find()
            ->where(
                [
                    'username' => $username
                ]
            )
            ->exists();
    }

    /**
     * @param string $password
     *
     * @return string
     */
    public static function generatePasswordHash($password)
    {
        return Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * @return string
     */
    public static function generateAuthKey()
    {
        return Yii::$app->security->generateRandomString();
    }

    /**
     * @inheritdoc
     */
    public static function getStatuses($language = null)
    {
        return [
            static::STATUS_PENDING  => Yii::t(
                'app.users.User',
                'Pending',
                [],
                $language
            ),
            static::STATUS_ENABLED  => Yii::t(
                'app.users.User',
                'Enabled',
                [],
                $language
            ),
            static::STATUS_BANNED   => Yii::t(
                'app.users.User',
                'Banned',
                [],
                $language
            ),
            static::STATUS_DISABLED => Yii::t(
                'app.users.User',
                'Disabled',
                [],
                $language
            ),
            static::STATUS_PENDING_PASSWORD_CHANGE => Yii::t(
                'app.users.User',
                'Pending password change',
                [],
                $language
            ),
        ];
    }

    /**
     * @param $status
     *
     * @return int
     */
    public static function countUsersWithStatus($status)
    {
        $statuses = self::getStatuses();
        if (isset($statuses[$status])) {
            return User::find()
                       ->where(['status' => $status])
                       ->count();
        } else {
            Yii::error('Unknown status: ' . VarDumper::dumpAsString($status));
            throw new InvalidArgumentException('Unknown status');
        }
    }

    /**
     * @param $id
     *
     * @return mixed|string
     */
    public static function usernameById($id)
    {
        $username = self
            ::find()
            ->where(['id' => $id])
            ->select(['username'])
            ->asArray()
            ->column();

        return (isset($username[0]) ? $username[0] : null);
    }

    /**
     * @param User|int $user
     *
     * @return int
     */
    public static function ensureId($user)
    {
        if ($user instanceof User) {
            $userId = $user->id;
        } else {
            $userId = $user;
        }

        return $userId;
    }

    /**
     * @param $permissionName
     * @param User|int $user
     * @param array $params
     *
     * @return bool
     */
    public static function checkAccessByUser(
        $permissionName,
        $user,
        $params = []
    ) {
        if (($authManager = Yii::$app->getAuthManager()) === null) {
            if (YII_DEBUG) {
                Yii::warning(
                    "AuthManager not configured, $permissionName permission denied by default."
                );
            }

            return false;
        }
        $checkAccess = $authManager->checkAccess(
            self::ensureId($user),
            $permissionName,
            $params
        );

        return $checkAccess;
    }

    /**
     * @param AfterSaveEvent $event
     */
    public function handleChangesAfterUpdate($event)
    {
        if (isset($event->changedAttributes['password_hash'])) {
            $this->trigger(self::EVENT_AFTER_PASSWORD_CHANGED);
        }
    }

    /**
     * @return Role[]
     */
    public static function getDefaultRoles()
    {
        $auth = Yii::$app->authManager;
        return [
            User::ROLE_REGISTERED => $auth->getRole(User::ROLE_REGISTERED)
        ];
    }

    /**
     * Asiign default user roles
     */
    public function assignDefaultRoles()
    {
        $auth = Yii::$app->authManager;
        $defaultRoles = static::getDefaultRoles();
        foreach ($defaultRoles as $name => $role) {
            $auth->assign($role, $this->id);
        }
    }

    /**
     * @param $attribute
     */
    public function passwordHashShouldBeSetValidator($attribute)
    {
        if (is_null($this->password_hash)) {
            $this->addError(
                $attribute,
                Yii::t(
                    'yii',
                    '{attribute} cannot be blank.',
                    ['attribute' => $attribute]
                )
            );
        }
    }

    /**
     * Changes password to new one
     *
     * @param string $password
     *
     * @param bool $saveAfterwards
     *
     * @return bool
     */
    public function setPassword($password, $saveAfterwards = false)
    {
        Yii::info('User ' . $this->id . ' password changed');
        $this->password_hash = self::generatePasswordHash($password);
        $this->password = '';
        if ($saveAfterwards) {
            return $this->save(
                false,
                ['password']
            );
        }

        return true;
    }

    /**
     * @return string
     */
    public function getPassword()
    {
        return '';
    }

    /**
     * @param string $password
     *
     * @return bool
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword(
            $password,
            $this->password_hash
        );
    }

    /**
     * Generates "remember me" authentication key
     */
    public function resetAuthKey()
    {
        $this->auth_key = self::generateAuthKey();
    }

    /**
     * @return bool
     */
    public function getHasPassword()
    {
        return (
            !is_null($this->password_hash) &&
            strlen($this->password_hash) > 0
        );
    }

    /**
     * @return bool
     */
    public function getHasPendingStatus()
    {
        return ($this->status == self::STATUS_PENDING);
    }

    /**
     * @return bool
     */
    public function getHasEnabledStatus()
    {
        return ($this->status == self::STATUS_ENABLED);
    }

    /**
     * @return bool
     */
    public function getHasBannedStatus()
    {
        return ($this->status == self::STATUS_BANNED);
    }

    /**
     * @return bool
     */
    public function getHasDisabledStatus()
    {
        return ($this->status == self::STATUS_DISABLED);
    }

    /**
     * @param $permissionName
     * @param array $params
     *
     * @return bool
     */
    public function checkAccess($permissionName, $params = [])
    {
        return self::checkAccessByUser(
            $permissionName,
            $this,
            $params
        );
    }

    /**
     * @return array
     */
    public function getRoles()
    {
        if (is_null($this->_roles)) {
            $this->_roles = [];
            $am = Yii::$app->authManager;
            $allRoles = $am->getRoles();
            $userRoles = $am->getRolesByUser($this->id);
            foreach ($allRoles as $role) {
                $this->_roles[$role->name] = (isset($userRoles[$role->name]));
            }
        }

        return $this->_roles;
    }

    /**
     * @param int $newStatus
     *
     * @return bool|null
     */
    public function changeStatus($newStatus)
    {
        if (!array_key_exists($newStatus, $this->statuses)) {
            throw new \InvalidArgumentException();
        }
        if (!$this->status != $newStatus) {
            $this->status = $newStatus;
            if ($this->save()) {
                return true;
            } else {
                Yii::error(
                    'Could not save User: ' .
                    VarDumper::dumpAsString($this->errors)
                );

                return false;
            }
        }

        return null;
    }

    /**
     * @return bool|null
     */
    public function enable()
    {
        return $this->changeStatus(self::STATUS_ENABLED);
    }

    /**
     * @return bool|null
     */
    public function ban()
    {
        return $this->changeStatus(self::STATUS_BANNED);
    }

    /**
     * @return bool|null
     */
    public function disable()
    {
        return $this->changeStatus(self::STATUS_DISABLED);
    }
}
