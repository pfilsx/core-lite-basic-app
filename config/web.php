<?php

$config = [
    'db' => require __DIR__ . '/db.php',
    'basePath' => dirname(__DIR__),
    'routing' => [
            'defaultPath' => 'default/index',
            'controllersNamespace' => 'app\controllers',
            'rules' => [
            ]
    ],
    'view' => [
        'viewsPath' => '@app/views',
        'layout' => '@app/layouts/default',
    ],
    'assets' => [
        'bundles' => [
            'app\assets\WebAssets'
        ]
    ],
    'modules' => [
        ['class' => 'app\modules\crl_debug\CrlDebug', 'storageType' => 'file']
    ]
];

if (CRL_ENV == 'dev'){
    $config['modules'][] = [
        'class' => 'core\generator\Generator'
    ];
}
return $config;