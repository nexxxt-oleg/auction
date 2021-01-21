<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            //'dsn' => 'mysql:host=localhost;dbname=warstory',
            'dsn' => 'mysql:host=localhost;dbname=warstory_alexei',
            'username' => 'warstory_alexei',
            //'username' => 'root',
            'password' => 'ut9fpj34',
            //'password' => '',
            'charset' => 'utf8',
            'tablePrefix' => 'au_',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'useFileTransport' => true,
            'htmlLayout' => '@common/mail/layouts/html',
            'messageConfig' => [
                'from' => ['noreply.asap@adc.spb.ru'],
            ],
        ],
    ],
];
