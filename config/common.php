<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';
$urlRules = require __DIR__ . '/urlRules.php';

$config = [
    'name'       => 'Ērglis',
    'basePath'   => dirname(__DIR__),
    'bootstrap'  => [
        'log',
    ],
    'timeZone'   => 'Europe/Riga',
    'aliases'    => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
    ],
    'components' => [
        'cache'        => [
            'class' => 'yii\caching\FileCache',
        ],
        'mailer'       => [
            'class'            => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
        ],
        'db'           => $db,
        'urlManager'   => [
            'class'               => 'yii\web\UrlManager',
            'hostInfo'            => 'http://erglis.info',
            'baseUrl'             => '',
            'scriptUrl'           => '/index.php',
            'enablePrettyUrl'     => true,
            'showScriptName'      => false,
            'enableStrictParsing' => false,
            'rules'               => $urlRules
        ],
        'assetManager' => [
            'dirMode'         => 0755,
            'appendTimestamp' => true,
            'forceCopy'       => YII_ENV_DEV,
            'linkAssets'      => (DIRECTORY_SEPARATOR != '\\'),
        ],
        'i18n'         => [
            'translations' => [
                'app.*' => [
                    'class'          => 'yii\i18n\PhpMessageSource',
                    'sourceLanguage' => 'en-US',
                    'basePath'       => '@app/messages'
                ],
            ],
        ],
    ],
    'params'     => $params,
];

return $config;
