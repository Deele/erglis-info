<?php

$config = [
    'id'         => 'web',
    'components' => [
        'request'       => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => '',
        ],
        'user'          => [
            'class'           => 'app\base\web\WebUser',
            'identityClass'   => 'app\base\web\WebUserIdentity',
            'enableAutoLogin' => true,
            'loginUrl'        => ['user/login']
        ],
        'session'       => [
            'class' => 'app\base\web\DbSession',
        ],
        'errorHandler'  => [
            'errorAction' => 'system/errors/error',
        ],
        'log'           => [
            'flushInterval' => 1,
            'traceLevel'    => (YII_DEBUG ? 7 : 0),
            'targets'       => [
                'logfile' => [
                    'class'          => 'yii\log\FileTarget',
                    'exportInterval' => 1,
                    'levels'         => ['error', 'warning'],
                    'except'         => [
                        'application.caching',
                    ],
                ],
            ],
        ],
    ],
    'modules'    => [
        'gridview' => [
            'class' => '\kartik\grid\Module'
            // 'downloadAction' => 'gridview/export/download',
            // 'i18n' => []
        ]
    ]
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class'      => 'yii\debug\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class'      => 'yii\gii\Module',
        'allowedIPs' => ['127.0.0.1', '::1'],
    ];
}

return $config;
