<?php

return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=u1288569_warstory_alexei',
//            'dsn' => 'mysql:host=localhost;dbname=warstory_alexei',
//            'username' => 'warstory_alexei',
            'username' => 'u1288569_warstory_alexei',
//            'password' => 'ut9fpj34',
            'password' => '2h_ayr_J',
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
