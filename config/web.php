<?php

$params = require __DIR__ . '/params.php';
$db = require __DIR__ . '/db.php';

/*
 * When the app is served from a sub-folder (e.g. http://localhost/interior/) through the
 * root .htaccess that forwards into /web, hide the "/web" segment from generated URLs.
 */
$baseUrl = null;
$scriptName = $_SERVER['SCRIPT_NAME'] ?? '';
$requestUri = $_SERVER['REQUEST_URI'] ?? '';
if (preg_match('#^(.*)/web/index\.php$#', $scriptName, $m) && strpos($requestUri, $m[1] . '/web/') !== 0) {
    $baseUrl = $m[1];
}

$config = [
    'id' => 'iconic-interiors',
    'name' => 'ICONIC INTERIORS',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'language' => 'en-IN',
    'timeZone' => 'Asia/Kolkata',
    'container' => [
        'singletons' => [
            \yii\mail\MailerInterface::class => [
                'class' => \yii\symfonymailer\Mailer::class,
                'useFileTransport' => true,
                'viewPath' => '@app/mail',
            ],
        ],
    ],
    'aliases' => [
        '@bower' => '@vendor/bower-asset',
        '@npm'   => '@vendor/npm-asset',
        '@uploads' => '@app/web/uploads',
    ],
    'modules' => [
        'admin' => [
            'class' => \app\modules\admin\Module::class,
        ],
    ],
    'components' => [
        'request' => [
            'cookieValidationKey' => 'Otakw8zLJjQGXwEvq7STNlMn-CK-4NQO',
            'baseUrl' => $baseUrl,
            'enableCsrfValidation' => true,
        ],
        'cache' => [
            'class' => \yii\caching\FileCache::class,
        ],
        'user' => [
            'identityClass' => \app\models\User::class,
            'enableAutoLogin' => true,
            'loginUrl' => ['/admin/default/login'],
            'identityCookie' => ['name' => '_iconic_identity', 'httpOnly' => true],
        ],
        'session' => [
            'name' => 'ICONICSESSID',
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'mailer' => \yii\mail\MailerInterface::class,
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => \yii\log\FileTarget::class,
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'db' => $db,
        'formatter' => [
            'defaultTimeZone' => 'Asia/Kolkata',
            'currencyCode' => 'INR',
            'datetimeFormat' => 'php:d M Y, h:i A',
            'dateFormat' => 'php:d M Y',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '' => 'site/index',
                'about' => 'site/about',
                'contact' => 'site/contact',
                'services' => 'service/index',
                'services/<slug:[a-z0-9\-]+>' => 'service/view',
                'portfolio' => 'portfolio/index',
                'portfolio/<slug:[a-z0-9\-]+>' => 'portfolio/view',
                'estimator' => 'estimator/index',
                'inquiry' => 'inquiry/submit',

                // Shop routes
                'shop' => 'product/index',
                'cart' => 'product/cart',
                'shop/cart' => 'product/cart',
                'shop/cart/add' => 'product/add-to-cart',
                'shop/cart/remove' => 'product/remove-from-cart',
                'shop/cart/update' => 'product/update-cart',
                'shop/<slug:[a-z0-9\-]+>' => 'product/view',

                'admin' => 'admin/default/index',
                'admin/login' => 'admin/default/login',
                'admin/logout' => 'admin/default/logout',
                'admin/settings' => 'admin/settings/index',
            ],
        ],
        'assetManager' => [
            'appendTimestamp' => true,
        ],
    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => \yii\debug\Module::class,
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => \yii\gii\Module::class,
    ];
}

return $config;
