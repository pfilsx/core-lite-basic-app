<?php

return [
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
        'generator' => [
            'class' => 'core\generator\Generator'
        ]
    ]
];