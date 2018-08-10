<?php
namespace app\widgets\user_login_register;

use app\widgets\user_login_register\assets\UserLoginRegisterAssetBundle;
use app\widgets\user_login_register\models\LoginForm;
use app\widgets\user_login_register\models\RegisterForm;
use Yii;
use yii\base\Widget;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;

class UserLoginRegister extends Widget
{
    public $name = 'user-login-register';
    public $options = [];
    public $clientOptions = [];

    /**
     * @var LoginForm
     */
    public $loginModel;

    /**
     * @var RegisterForm
     */
    public $registerModel;

    public function init()
    {
        parent::init();
        if (!array_key_exists('id', $this->options)) {
            $this->options['id'] = $this->id;
        }
        $this->loginModel = new LoginForm();
        $this->registerModel = new RegisterForm();
    }

    public function runLogic()
    {
        $postData = Yii::$app->request->post();
        if ($this->loginModel->load($postData) && $this->loginModel->login()) {
            return 'login';
        }
        $this->loginModel->password = '';

        if ($this->registerModel->load($postData) && $this->registerModel->register()) {
            return 'register';
        }
        $this->registerModel->password = '';
        $this->registerModel->password_repeat = '';

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Html::addCssClass($this->options, $this->name . '-widget');
        UserLoginRegisterAssetBundle::register($this->view);
//        $clientOptions = $this->clientOptions;
//        $clientOptions['widgetType'] = [
//            'name' => $this->name,
//        ];
//        $clientOptions['id'] = $this->id;
//        $this->view->registerJs(
//            'yii.raphaelJsWidget.register(' . Json::encode($clientOptions) . ');'
//        );

        $tag = ArrayHelper::remove($this->options, 'tag', 'div');
        return Html::tag(
            $tag,
            $this->render(
                'userLoginRegister'
            ),
            $this->options
        );
    }
}
