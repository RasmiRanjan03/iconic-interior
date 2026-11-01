<?php

namespace app\modules\admin;

use Yii;
use yii\base\Module as BaseModule;
use yii\filters\AccessControl;

/**
 * Admin CMS Module for ICONIC INTERIORS
 */
class Module extends BaseModule
{
    public $controllerNamespace = 'app\modules\admin\controllers';
    public $layout = 'admin';

    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'except' => ['default/login', 'default/error'],
                'rules' => [
                    [
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
        ];
    }

    public function init()
    {
        parent::init();
        Yii::$app->user->loginUrl = ['/admin/default/login'];
    }
}
