<?php

namespace app\assets;

use app\base\web\Application;
use app\base\web\WebUserIdentity;
use yii\helpers\Json;
use yii\web\AssetBundle;
use yii\web\View;

/**
 * Main application asset bundle.
 */
class AppAsset extends AssetBundle
{
    public $sourcePath = '@app/assets/dist';
    public $css = [
        'css/site.css',
    ];
    public $js = [
        'https://cdnjs.cloudflare.com/ajax/libs/es6-shim/0.35.3/es6-shim.min.js',
        'helpers.js',
        'app.js',
    ];
    public $depends = [
        'yii\web\YiiAsset',
        'yii\bootstrap\BootstrapAsset',
    ];

    /**
     * @inheritdoc
     */
    public function publish($am)
    {
        parent::publish($am);

        /**
         * @var Application $app
         */
        $app = \Yii::$app;
        $appOptions = [
            'id' => $app->id,
            'name' => $app->name,
            'baseUrl' => $app->urlManager->hostInfo . $app->urlManager->baseUrl,
            'currentLanguage' => (strlen($app->language) > 2 ?
                substr(strlen($app->language), 0, 2) :
                $app->language
            ),
            'webUser' => [
                'id' => null,
                'name' => null,
                'sessionTimeoutSeconds' => $app->session->timeout,
                'loginUrl' => $app->urlManager->createUrl($app->user->loginUrl),
            ],
            'options' => [
                'isDebugModeEnabled' => YII_DEBUG,
            ],
        ];
        if (!$app->user->isGuest) {

            /**
             * @var WebUserIdentity $identity
             */
            $identity = $app->user->identity;
            $appOptions['webUser']['id'] = $identity->user->id;
            $appOptions['webUser']['name'] = $identity->user->name;
        }
        $appOptions = Json::htmlEncode($appOptions);
        $app->view->registerJs(
            "var appInitData = {$appOptions};",
            View::POS_HEAD,
            'app-init-data'
        );
    }
}
