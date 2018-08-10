<?php

namespace app\models\user;

use base\models\user\UserSessionQuery;
use Yii;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * This is the model class for table "{{%user__session}}".
 *
 * @property string $id User session ID
 * @property string $ip_address IP address
 * @property int $related_user_id User ID
 * @property int $expire Expiration data
 * @property resource $data Session data
 * @property string $created_at Created at
 * @property string $updated_at Updated at
 *
 * @property User $relatedUser
 */
class UserSession extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return '{{%user__session}}';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id'], 'required'],
            [['related_user_id', 'expire'], 'integer'],
            [['data'], 'string'],
            [['created_at', 'updated_at'], 'safe'],
            [['id'], 'string', 'max' => 100],
            [['ip_address'], 'string', 'max' => 50],
            [['id'], 'unique'],
            [['related_user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['related_user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app.UserSession', 'User session ID'),
            'ip_address' => Yii::t('app.UserSession', 'IP address'),
            'related_user_id' => Yii::t('app.UserSession', 'User ID'),
            'expire' => Yii::t('app.UserSession', 'Expiration data'),
            'data' => Yii::t('app.UserSession', 'Session data'),
            'created_at' => Yii::t('app.common', 'Created at'),
            'updated_at' => Yii::t('app.common', 'Updated at'),
        ];
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getRelatedUser()
    {
        return $this->hasOne(User::class, ['id' => 'related_user_id']);
    }

    /**
     * {@inheritdoc}
     * @return UserSessionQuery the active query used by this AR class.
     */
    public static function find()
    {
        return new UserSessionQuery(get_called_class());
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'Timestamp' => [
                'class' => TimestampBehavior::class,
                'value' => new Expression('UTC_TIMESTAMP()'),
            ]
        ];
    }

    /**
     * @param $date
     * @param null|string $format
     * @param null|string $timeZone
     *
     * @return \DateTime|string
     */
    public static function formatUtcDate($date, $format = null, $timeZone = null)
    {
        if (is_string($date) && strlen($date) > 0 && $date != '0000-00-00 00:00:00') {
            $date = new \DateTime($date, new \DateTimeZone('UTC'));
            if (is_null($timeZone)) {
                $timeZone = date_default_timezone_get();
            }
            $timeZone = new \DateTimeZone($timeZone);
            $date->setTimezone($timeZone);
            if (!is_null($format)) {
                return $date->format($format);
            }

            return $date;
        }

        return '';
    }

    /**
     * Reloads dates
     */
    public function reloadDates()
    {
        $query = static
            ::find()
            ->id($this->primaryKey)
            ->asArray();
        $query->select([
            $query->columnName('created_at'),
            $query->columnName('updated_at')
        ]);
        $dateData = $query->one();
        if (!empty($dateData)) {
            if (isset($dateData['created_at'])) {
                $this->created_at = $dateData['created_at'];
            }
            if (isset($dateData['updated_at'])) {
                $this->updated_at = $dateData['updated_at'];
            }
        }
    }

    /**
     * Returns "created at" value as formatted date string or as an DateTime
     * object
     *
     * @param string|null $format
     * @param bool $reload
     *
     * @return string|\DateTime If format is null, DateTime object is returned.
     * @link http://php.net/manual/en/datetime.format.php
     */
    public function getFormattedCreatedAt($format = 'c', $reload = false)
    {
        if ($reload) {
            $this->reloadDates();
        }

        return static::formatUtcDate($this->created_at, $format);
    }

    /**
     * Returns "updated at" value as formatted date string or as an DateTime
     * object
     *
     * @param string|null $format
     * @param bool $reload
     *
     * @return string|\DateTime If format is null, DateTime object is returned.
     * @link http://php.net/manual/en/datetime.format.php
     */
    public function getFormattedUpdatedAt($format = 'c', $reload = false)
    {
        if ($reload) {
            $this->reloadDates();
        }

        return static::formatUtcDate($this->updated_at, $format);
    }

    /**
     * Touches date updated at
     *
     * @param bool $autoSave
     *
     * @return bool
     */
    public function touchUpdatedAt($autoSave = false)
    {

        /**
         * @var TimestampBehavior $tb
         */
        $tb = $this->getBehavior('Timestamp');
        $tb->touch('updated_at');
        if ($autoSave) {
            if ($this->save(false, ['updated_at'])) {
                $this->reloadDates();
                return true;
            }

            return false;
        }

        return true;
    }
}
