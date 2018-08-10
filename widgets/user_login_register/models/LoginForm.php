<?php
namespace app\widgets\user_login_register\models;

use app\base\web\WebUserIdentity;
use Yii;
use yii\base\Model;

/**
 * LoginForm is the model behind the login form.
 *
 * @property-read WebUserIdentity|null $user This property is read-only.
 */
class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public $sessionDuration = 3600*24*30;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            ['rememberMe', 'boolean'],
            [
                'password',
                'validatePassword',
                'message' => 'Incorrect username or password.'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username (e-mail)',
            'password' => 'Password',
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();

            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, $params['message']);
            }
        }
    }

    /**
     * Logs in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login(
                $this->getUser(),
                $this->rememberMe ? $this->sessionDuration : 0
            );
        }
        return false;
    }

    /**
     * Finds user by [[username]]
     *
     * @return WebUserIdentity|null
     */
    public function getUser()
    {
        if ($this->_user === false) {
            $this->_user = WebUserIdentity::findByUsername($this->username);
        }

        return $this->_user;
    }
}
