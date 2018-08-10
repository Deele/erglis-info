<?php

namespace app\base\web;

use app\widgets\Heading;
use Yii;
use yii\filters\auth\HttpBasicAuth;
use yii\helpers\ArrayHelper;
use yii\web\Controller as YiiController;

/**
 * Class Controller
 *
 * @property-read Application $app
 * @property-read string $heading
 * @property-read string $title
 *
 * @package app\base\web
 */
abstract class Controller extends YiiController
{

    /**
     * @var array
     */
    public $headingWidgetOptions = [
        'heading' => '',
        'headingDisplay' => Heading::HEADING_DISPLAY_SHOW,
    ];

    public function init()
    {
        parent::init();
    }

    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        $behaviors = [];

        return $behaviors;
    }

    /**
     * Hides heading
     *
     * @return Controller
     */
    public function hideHeading()
    {
        $this->headingWidgetOptions['headingDisplay'] = Heading::HEADING_DISPLAY_HIDE;

        return $this;
    }

    /**
     * @return string
     */
    public function getHeading()
    {
        return ArrayHelper::getValue($this->headingWidgetOptions, 'heading');
    }

    /**
     * @param string $value
     *
     * @return Controller
     */
    public function setHeading($value)
    {
        $this->headingWidgetOptions['heading'] = $value;

        return $this;
    }

    /**
     * @return string
     */
    public function getTitle()
    {
        return strtr(
            (!is_null($this->view->title) ? $this->view->title : '{appName}'),
            [
                '{appName}' => $this->app->name
            ]
        );
    }

    /**
     * @param string $value
     *
     * @return Controller
     */
    public function setTitle($value)
    {
        $this->view->title = $value;

        return $this;
    }

    /**
     * @return \app\base\web\Application|\yii\web\Application
     */
    public function getApp()
    {
        return Yii::$app;
    }
}
