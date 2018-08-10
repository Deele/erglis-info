<?php

// comment out the following two lines when deployed to production
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

if (YII_DEBUG) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    ini_set('display_startup_errors', 0);
    error_reporting(0);
}

require __DIR__ . '/../vendor/autoload.php';
require __DIR__ . '/../vendor/yiisoft/yii2/Yii.php';

Yii::$classMap['app\base\web\Application'] = __DIR__ . '/../base/web/Application.php';

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../config/common.php'),
    require(__DIR__ . '/../config/web.php')
);

(new app\base\web\Application($config))->run();
