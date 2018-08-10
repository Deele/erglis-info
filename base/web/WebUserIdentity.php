<?php
/**
 * Contains \app\base\web\WebUserIdentity
 */

namespace app\base\web;

use app\models\user\User;
use Yii;
use yii\base\NotSupportedException;
use yii\base\BaseObject;
use yii\web\IdentityInterface;

/**
 * Class WebUserIdentity
 *
 * @property-read string $id {@link WebUserIdentity::getId()}
 * @property-read string $username {@link WebUserIdentity::getUsername()}
 */
class WebUserIdentity extends BaseObject implements IdentityInterface
{

    /**
     * @var User
     */
    public $user;

    /**
     * @inheritdoc
     * @return WebUserIdentity|null
     */
    public static function findIdentity($id)
    {
        if ($user = User::findOne(['id' => $id])) {
            $identity = new WebUserIdentity();
            $identity->user = $user;

            return $identity;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException;
    }

    /**
     * Finds user by username
     *
     * @param  string $username
     *
     * @return WebUserIdentity|null
     */
    public static function findByUsername($username)
    {
        if ($user = User::findOne(['username' => $username])) {
            $identity = new WebUserIdentity();
            $identity->user = $user;

            return $identity;
        }

        return null;
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return ($this->user ? $this->user->getPrimaryKey() : null);
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return ($this->user ? $this->user->auth_key : null);
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return ($this->user ? $this->getAuthKey() === $authKey : null);
    }

    /**
     * Validates password
     *
     * @param  string $password password to validate
     *
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return ($this->user ? $this->user->validatePassword($password) : null);
    }

    /**
     * Returns current user username
     *
     * @return null|string
     */
    public function getUsername()
    {
        return ($this->user ? $this->user->username : null);
    }

    /**
     * @inheritdoc
     */
    public function getRateLimit()
    {
        return [100, 600];
    }

    /**
     * @inheritdoc
     */
    public function loadAllowance($request)
    {
        $cache = \Yii::$app->cache;

        return [
            $cache->get($request->pathInfo . $request->method . '_remaining'),
            $cache->get($request->pathInfo . $request->method . '_ts')
        ];
    }

    /**
     * @inheritdoc
     */
    public function saveAllowance(
        $request,
        /** @noinspection PhpUnusedParameterInspection */
        $action,
        $allowance,
        $timestamp
    ) {
        $cache = \Yii::$app->cache;
        $cache->set($request->pathInfo . $request->method . '_remaining', $allowance);
        $cache->set($request->pathInfo . $request->method . '_ts', $timestamp);
    }

    /**
     * Logs in a user.
     *
     * @param int $duration
     *
     * @return bool
     */
    public function login($duration = 0)
    {
        return Yii::$app->user->login($this, $duration);
    }
}
