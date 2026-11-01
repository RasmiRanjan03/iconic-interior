<?php

namespace app\controllers;

use Yii;
use app\models\CmsPage;
use app\models\Project;
use app\models\SiteSettings;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class PortfolioController extends Controller
{
    /**
     * Portfolio Gallery Directory (/portfolio)
     */
    public function actionIndex()
    {
        $page = CmsPage::findBySlug('portfolio');
        $settings = SiteSettings::get();
        $categories = Yii::$app->params['projectCategories'];
        $projects = Project::active()->all();

        return $this->render('index', [
            'page' => $page,
            'settings' => $settings,
            'categories' => $categories,
            'projects' => $projects,
        ]);
    }

    /**
     * Individual Project Case Study Page (/portfolio/<slug>)
     */
    public function actionView(string $slug)
    {
        $project = Project::findOne(['slug' => $slug, 'is_active' => 1]);
        if (!$project) {
            throw new NotFoundHttpException('The requested project could not be found.');
        }

        $settings = SiteSettings::get();
        $moreProjects = Project::find()
            ->where(['is_active' => 1])
            ->andWhere(['!=', 'id', $project->id])
            ->andFilterWhere(['category' => $project->category])
            ->limit(3)
            ->all();

        return $this->render('view', [
            'project' => $project,
            'settings' => $settings,
            'moreProjects' => $moreProjects,
        ]);
    }
}
