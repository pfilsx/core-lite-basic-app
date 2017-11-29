<?php

$config = [
    'db' => require __DIR__ . '/db.php',
    'basePath' => dirname(__DIR__),
    'routing' => [
            'defaultPath' => 'default/index',
            'controllersNamespace' => 'app\controllers',
            'viewsPath' => '@app/views',
            'layout' => '@app/layouts/default',
            'rules' => [
            ]
    ],
    'modules' => [
    ]
];

if (CRL_ENV == 'dev'){
    $config['modules']['generator'] = [
        'class' => 'core\generator\Generator'
    ];
}
return $config;