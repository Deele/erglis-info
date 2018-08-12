<?php
namespace app\widgets\events_map\assets;

use yii\web\AssetBundle;

/**
 * Events map widget asset bundle.
 */
class EventsMapAssetBundle extends AssetBundle
{
    public $sourcePath = '@app/widgets/events_map/assets/dist';
    public $css = [
        'eventsMap.css',
    ];
    public $js = [
        'eventsMap.js',
    ];
    public $depends = [
        'app\assets\AppAsset',
    ];

    public function init()
    {
        parent::init();
<<<<<<< .merge_file_a25336
        $this->js[] = 'https://maps.googleapis.com/maps/api/js?key=' . \Yii::$app->params['googleApiKey'] . '&callback=initMap';
=======
        $this->js[] = 'https://maps.googleapis.com/maps/api/js?key=' . \Yii::$app->params['googleApiKey'];
>>>>>>> .merge_file_a25176
    }
}
