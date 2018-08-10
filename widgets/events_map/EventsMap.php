<?php
namespace app\widgets\events_map;

use app\widgets\events_map\assets\EventsMapAssetBundle;
use yii\base\Widget;
use yii\helpers\Html;
use yii\helpers\Json;

class EventsMap extends Widget
{
    public $name = 'events-map';
    public $options = [];

    /**
     * {@inheritdoc}
     */
    public function run()
    {
        Html::addCssClass($this->options, $this->name . '-widget');
        EventsMapAssetBundle::register($this->view);
        $clientOptions = [
            'widgetType' => [
                'name' => $this->name
            ],
            'id' => $this->id,
            'isDebugModeEnabled' => YII_DEBUG,
        ];
        $clientOptions = Json::htmlEncode($clientOptions);
        \Yii::$app->view->registerJs(
            "window.yii.app.registerWidget('eventsMapWidget', {$clientOptions});"
        );

        return Html::tag(
            'header',
            $this->render(
                'eventsMap'
            ),
            $this->options
        );
    }
}
