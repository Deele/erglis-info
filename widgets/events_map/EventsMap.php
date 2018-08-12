<?php
namespace app\widgets\events_map;

use app\modules\events\models\Event;
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
<<<<<<< HEAD
        Html::addCssClass($this->options, $this->name . '-widget');
        EventsMapAssetBundle::register($this->view);
=======
        $options = $this->options;
        $options['id'] = $this->id;
        Html::addCssClass($options, $this->name . '-widget');
        $assets = EventsMapAssetBundle::register($this->view);
>>>>>>> events-cms
        $clientOptions = [
            'widgetType' => [
                'name' => $this->name
            ],
            'id' => $this->id,
            'isDebugModeEnabled' => YII_DEBUG,
<<<<<<< HEAD
=======
            'baseUrl' => $assets->baseUrl,
            'locations' => [],
>>>>>>> events-cms
        ];
        $this->prepareEventData($clientOptions);
        $clientOptions = Json::htmlEncode($clientOptions);
        \Yii::$app->view->registerJs(
            "window.yii.app.registerWidget('eventsMapWidget', {$clientOptions});"
        );

        return Html::tag(
            'header',
            $this->render(
                'eventsMap'
            ),
            $options
        );
    }

    protected function prepareEventData(&$clientOptions)
    {
        $eventsByLocation = [];
        foreach (Event::find()->all() as $event) {
            if (!isset($eventsByLocation[$event->location_degrees])) {
                $eventsByLocation[$event->location_degrees] = [];
            }
            $eventsByLocation[$event->location_degrees][] = [
                'id' => $event->id,
                'title' => $event->about,
                'url' => \Yii::$app->urlManager->createUrl([
                    'site/event',
                    'id' => $event->id
                ])
            ];
        }
        foreach ($eventsByLocation as $locationDegrees => $events) {
            list($lat, $lon) = explode(',', $locationDegrees);
            $clientOptions['locations'][] = [
                '',
                [
                    'lng' => (float) $lon,
                    'lat' => (float) $lat,
                ],
                $events
            ];
        }
    }
}
