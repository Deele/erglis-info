<?php

namespace app\controllers;

use app\widgets\user_login_register\UserLoginRegister;
use Yii;
use yii\filters\AccessControl;
use app\base\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;

/**
 * User controller handles general user account related actions
 *
 * @author Nils (Deele) <deele@tuta.io>
 *
 * @package app\controllers
 */
class UserController extends Controller
{
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = parent::behaviors();
        $behaviors[] = [
            'class' => AccessControl::class,
            'except'  => ['login'],
            'rules' => [
                [
                    'allow'   => true,
                    'roles'   => ['@'],
                ],
            ],
        ];
        $behaviors[] = [
            'class'   => VerbFilter::class,
            'actions' => [
                'logout' => ['post'],
            ],
        ];

        return $behaviors;
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $this
            ->setTitle('Account - User | {appName}')
            ->setHeading('Account');

        return $this->render('index');
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $userLoginRegisterWidget = UserLoginRegister::begin([

        ]);
        switch ($userLoginRegisterWidget->runLogic()) {
            case 'login':
                return $this->goBack();
                break;
            case 'register':
                Yii::$app->session->addFlash(
                    'success',
                    'You have successfully registered new account, please, log in now.'
                );
                return $this->refresh();
                break;
        }

        $this
            ->setTitle('Login or register - User | {appName}')
            ->setHeading('Login or register');

        return $this->render('login', [
            'userLoginRegisterWidget' => $userLoginRegisterWidget->run(),
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }
}
