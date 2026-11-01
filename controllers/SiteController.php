<?php

namespace app\controllers;

use Yii;
use app\models\CmsPage;
use app\models\HeroStyle;
use app\models\Project;
use app\models\Service;
use app\models\SiteSettings;
use app\models\Testimonial;
use app\models\UserQuery;
use yii\web\Controller;
use yii\web\ErrorAction;

class SiteController extends Controller
{
    public function actions(): array
    {
        return [
            'error' => [
                'class' => ErrorAction::class,
                'layout' => 'main',
            ],
        ];
    }

    /**
     * Home Page (/)
     */
    public function actionIndex()
    {
        $page = CmsPage::findBySlug('home');
        $settings = SiteSettings::get();
        $heroPresets = HeroStyle::activeOrdered()->all();
        $services = Service::active()->limit(6)->all();
        $projects = Project::find()->where(['is_active' => 1, 'is_featured' => 1])
            ->orderBy(['id' => SORT_DESC])->limit(6)->all();
        $testimonials = Testimonial::find()->where(['is_active' => 1])->all();

        // Prepare client-ready array of hero presets for Alpine
        $heroPresetsJson = array_map(static fn($p) => $p->toClientArray(), $heroPresets);

        return $this->render('index', [
            'page' => $page,
            'settings' => $settings,
            'heroPresets' => $heroPresets,
            'heroPresetsJson' => $heroPresetsJson,
            'services' => $services,
            'projects' => $projects,
            'testimonials' => $testimonials,
        ]);
    }

    /**
     * About Us Page (/about)
     */
    public function actionAbout()
    {
        $page = CmsPage::findBySlug('about');
        $settings = SiteSettings::get();
        $extra = $page ? $page->getExtra() : [];

        return $this->render('about', [
            'page' => $page,
            'settings' => $settings,
            'extra' => $extra,
        ]);
    }

    /**
     * Contact & Consultation Page (/contact)
     */
    public function actionContact()
    {
        $page = CmsPage::findBySlug('contact');
        $settings = SiteSettings::get();
        $model = new UserQuery(['scenario' => UserQuery::SCENARIO_CONTACT]);
        $model->query_source = UserQuery::SOURCE_CONTACT;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', "Thank you, {$model->full_name}. Your consultation request has been received! Santosh Kumar Maharana and our design team will contact you within 24 hours.");
            return $this->refresh();
        }

        return $this->render('contact', [
            'page' => $page,
            'settings' => $settings,
            'model' => $model,
        ]);
    }
}
