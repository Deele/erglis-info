<?php

namespace app\controllers;

use app\base\web\Controller;
use app\modules\events\models\Event;
use Yii;
use yii\filters\AccessControl;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;

class SiteController extends Controller
{

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * Displays a single event.
     *
     * @param $id
     *
     * @return string
     * @throws NotFoundHttpException
     */
    public function actionEvent($id)
    {
        $event = Event::find()->id($id)->one();
        if (is_null($event)) {
            throw new NotFoundHttpException('Event not found');
        }
        return $this->render(
            'event',
            [
                'event' => $event
            ]
        );
    }
}
