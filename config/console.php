<?php

$config = [
    'id'                  => 'console',
    'controllerNamespace' => 'app\commands',
    'components'          => [
        'log'   => [
            'traceLevel' => 0,
            'targets'    => [
                'logfile' => [
                    'class'   => 'yii\log\FileTarget',
                    'logVars' => [/*'_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER'*/],
                    'levels'  => ['error', 'warning'],
                ],
            ],
        ],
    ],
    'controllerMap' => [
        'migrate' => [
            'class' => 'yii\console\controllers\MigrateController',
            'migrationPath' => null,
            'migrationNamespaces' => [
                'app\migrations',
            ],
        ],
        /*
        'fixture' => [ // Fixture generation command line.
            'class' => 'yii\faker\FixtureController',
        ],
        */
    ],
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
