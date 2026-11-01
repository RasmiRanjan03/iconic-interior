<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\HeroStyle;
use app\models\LoginForm;
use app\models\Project;
use app\models\Service;
use app\models\UserQuery;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\Response;

class DefaultController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * Executive Dashboard (/admin)
     */
    public function actionIndex()
    {
        $kpis = [
            'total_queries' => (int) UserQuery::find()->count(),
            'unread_queries' => (int) UserQuery::find()->where(['is_read' => 0])->count(),
            'active_services' => (int) Service::find()->where(['is_active' => 1])->count(),
            'portfolio_projects' => (int) Project::find()->where(['is_active' => 1])->count(),
            'hero_styles' => (int) HeroStyle::find()->where(['is_active' => 1])->count(),
        ];

        // Recent 10 queries
        $recentQueries = UserQuery::find()
            ->orderBy(['created_at' => SORT_DESC])
            ->limit(10)
            ->all();

        return $this->render('index', [
            'kpis' => $kpis,
            'recentQueries' => $recentQueries,
        ]);
    }

    /**
     * Admin Login (/admin/login)
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }

        $this->layout = false; // Isolated luxury login screen
        $model = new LoginForm();

        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack(['/admin/default/index']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Admin Logout (/admin/logout)
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->redirect(['/admin/default/login']);
    }
}
