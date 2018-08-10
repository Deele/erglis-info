<?php
namespace app\widgets\user_login_register\models;

use app\models\user\User;
use Yii;
use yii\base\Model;
use yii\helpers\VarDumper;

/**
 * RegisterForm is the model behind the register form.
 */
class RegisterForm extends Model
{
    public $username;
    public $password;
    public $password_repeat;
    public $accept_terms_conditions;
    public $accept_newsletters;

    private $_user = false;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['username', 'password', 'password_repeat', 'accept_terms_conditions'], 'required'],
            ['username', 'email'],
            [
                'password_repeat',
                'compare',
                'compareAttribute' => 'password'
            ],
            [
                'username',
                'unique',
                'targetClass'     => User::class,
                'targetAttribute' => 'username',
                'message'         => 'This username has already been taken'
            ],
            [['accept_terms_conditions', 'accept_newsletters'], 'boolean'],
            [
                'accept_terms_conditions',
                'compare',
                'compareValue' => '1',
                'message' => 'You must accept conditions to continue'
            ],
        ];
    }

    public function attributeLabels()
    {
        return [
            'username' => 'Username (e-mail)',
            'password' => 'Password',
            'password_repeat' => 'Repeat password',
            'accept_terms_conditions' => 'Accept terms and conditions',
            'accept_newsletters' => 'Agree to receive newsletters, marketing or promotional materials and other information that may be of interest to you.',
        ];
    }

    /**
     * Register in a user using the provided username and password.
     * @return bool whether the user is logged in successfully
     */
    public function register()
    {
        if ($this->validate()) {
            $this->_user = new User([
                'username' => $this->username,
                'password' => $this->password,
                'accepts_newsletters' => ($this->accept_newsletters == '1'),
            ]);
            if ($this->_user->save()) {
                return true;
            } else {
                Yii::error(
                    'Could not save User: ' .
                    VarDumper::dumpAsString($this->_user->errors)
                );
            }
        }
        return false;
    }
}
