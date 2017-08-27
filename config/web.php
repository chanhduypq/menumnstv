<?php

$params = require(__DIR__ . '/params.php');

$config = [
    'id' => 'basic',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
	'language' => 'en', 		// QV
    'components' => [
        'request' => [
            // !!! insert a secret key in the following (if it is empty) - this is required by cookie validation
            'cookieValidationKey' => 'ucQSQjM9dTgg8PF2_ZEJaGb36mOretli',
        ],
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'user' => [
            'identityClass' => 'app\models\User',
            'enableAutoLogin' => true,
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
        'authClientCollection' => [
            'class' => 'yii\authclient\Collection',
            'clients' => [
                'facebook' => [
                    'class' => 'yii\authclient\clients\Facebook',
                    'authUrl' => 'https://www.facebook.com/dialog/oauth?display=popup',
                    'clientId' => '',
                    'clientSecret' => '',
                    'attributeNames' => [
                        'id',
                        'name',
                        'first_name',
                        'last_name',
                        'link',
                        'about',
                        'work',
                        'education',
                        'gender',
                        'email',
                        'timezone',
                        'locale',
                        'verified',
                        'updated_time',
                    ],
                ],
            ],
        ],
        'mailer' => [
            'class' => 'yii\swiftmailer\Mailer',
            'transport' => [
                'class' => 'Swift_SmtpTransport',
                'host' => 'mail.mns.tv',
                'username' => 'hello@mns.tv',
                'password' => '}3DfZNvjd~7t', 
                'port' => '25',
                'encryption' => 'tls',
//				'encryption' => 'ssl'
				 'streamOptions' => [ 'ssl' =>
						[ 'allow_self_signed' => true,
							'verify_peer' => false,
							'verify_peer_name' => false,
						],
					]
            ],
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
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
		
		
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'basePath' => '@app/messages',
					//'basePath' => 'messages',
					'fileMap' => [
						'app' => 'app.php',
						'app/error' => 'error.php',
					],
				],
			],
		],
	
		'urlManager'=>array(
			'class' => 'yii\web\UrlManager',
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules'=>array( 
				'/' => 'site/index',
				'page/<url:[\w\-]+>' => 'site/page',
				'profile' => 'site/profile',
				'plaza' => 'site/plaza',
				'confirmcart' => 'site/confirmcart',
				'showconfirmcart' => 'site/showconfirmcart',
				'contact' => 'site/contact',
				'/' => 'site/index',
                'detail/<url:[\w\-]+>' => 'site/detail',
				'login' => 'site/login',
				'logout' => 'site/logout',
				'lostpassword' => 'site/lostpassword',
                                'download' => 'site/download',
                                'resetpassword' => 'site/resetpassword',
                                'ordersession' => 'site/ordersession',
                                'ordersessionoption' => 'site/ordersessionoption',
                                'deleteordersession' => 'site/deleteordersession',
                                'registerpay' => 'site/registerpay',
                                'getsumallen' => 'site/getsumallen',
                                'getservices' => 'site/getservices',  
                                'comment' => 'site/comment',
                                'rating' => 'site/rating',
                                'bought' => 'site/bought',
                            
                            
			),
		),

    ],
    'params' => $params,
];

if (YII_ENV_DEV) {
    // configuration adjustments for 'dev' environment
    $config['bootstrap'][] = 'debug';
    $config['modules']['debug'] = [
        'class' => 'yii\debug\Module',
    ];

    $config['bootstrap'][] = 'gii';
    $config['modules']['gii'] = [
        'class' => 'yii\gii\Module',
    ];
}

return $config;
