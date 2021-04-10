<?php
use \kartik\datecontrol\Module;
$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'app-practical-b',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'ru-RU',
    'sourceLanguage' => 'en-US',
    'name' => 'Аукцион военного антиквариата',
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'PEi6ICsok3vWirJSJJtQV2JZ6D-jk5gkh',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\auth\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => true,
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.hosting.reg.ru',
                'username' => 'auction@auction.warstory.ru',
                'password' => '2h_ayr_J',
                'port' => '587',
                'encryption' => 'tls',
            ],
//            'useFileTransport' => true,
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => require(__DIR__ . '/db.php'),
        'authManager' => [
            'class' => 'yii\rbac\PhpManager',
            'defaultRoles' => ['user','moder','admin'],
            'itemFile' => '@app/components/rbac/items.php',
            'assignmentFile' => '@app/components/rbac/assignments.php',
            'ruleFile' => '@app/components/rbac/rules.php',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
        ],
        'cart' => [
            'class' => 'app\components\shop\MyShoppingCart',
            'cartId' => 'app_cart',
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],

        'formatter' => [
            'class' => 'yii\i18n\Formatter',
            'timeZone' => 'GMT+0',
            'dateFormat' => 'php:d.M.Y',
            'datetimeFormat' => 'php:d.M.Y H:i:s',
            'timeFormat' => 'php:H:i:s',
            'decimalSeparator' => '.',
            'thousandSeparator' => ' ',
            'currencyCode' => '',
        ],
        'session' => [
            'class' => 'yii\web\Session',
            'cookieParams' => ['httponly' => true, 'lifetime' => 3600 * 24 * 5],
            'timeout' => 3600 * 24 * 5,
            'useCookies' => true,
        ],
        'i18n' => [
            'translations' => [
                'app*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    //'basePath' => '@app/messages',
                    //'sourceLanguage' => 'en-US',
                    'fileMap' => [
                        'app' => 'app.php',
                        'app/error' => 'error.php',
                    ],
                ],
            ],
        ],
    ],
    'params' => $params,
    'modules' => [
        'datecontrol' =>  [
            'class' => 'kartik\datecontrol\Module',

            'displaySettings' => [
                Module::FORMAT_DATE => 'dd.MM.yyyy',
                Module::FORMAT_TIME => 'HH:mm:ss a',
                Module::FORMAT_DATETIME => 'dd.MM.yyyy HH:mm:ss',
            ],

            'saveSettings' => [
                Module::FORMAT_DATE => /*'dd.MM.yyyy'*/'yyyy-MM-dd',
                Module::FORMAT_TIME => 'HH:mm:ss a',
                Module::FORMAT_DATETIME => 'yyyy-MM-dd HH:mm:ss',
            ],

            // set your display timezone
            'displayTimezone' => 'Europe/Moscow',

            // set your timezone for date saved to db
            'saveTimezone' => 'Europe/Moscow',

            // automatically use kartik\widgets for each of the above formats
            //'autoWidget' => true,

            // default settings for each widget from kartik\widgets used when autoWidget is true
            'autoWidgetSettings' => [
                Module::FORMAT_DATE => ['pluginOptions'=>['autoclose'=>true]],
                Module::FORMAT_DATETIME => [], // setup if needed
                Module::FORMAT_TIME => [], // setup if needed
            ],

            // custom widget settings that will be used to render the date input instead of kartik\widgets,
            // this will be used when autoWidget is set to false at module or widget level.
            'widgetSettings' => [
                Module::FORMAT_DATE => [
                    'class' => 'yii\jui\DatePicker', // example
                    'options' => [
                        'dateFormat' => 'dd.MM.yyyy',
                        'options' => ['class'=>'form-control'],
                    ]
                ]
            ]
            // other settings
        ],
        'gridview' =>  [
            'class' => '\kartik\grid\Module'
        ],
        'admin' => [
            'class' => 'app\modules\admin\Module'
        ],
    ]

];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
        'allowedIPs' => [ '127.0.0.1', '::1'],
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
        'allowedIPs' => [ '127.0.0.1', '::1'],
    ];
}

return $config;