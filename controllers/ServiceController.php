<?php

namespace app\controllers;

use Yii;
use app\models\CmsPage;
use app\models\Service;
use app\models\SiteSettings;
use app\models\UserQuery;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ServiceController extends Controller
{
    /**
     * Services Directory (/services)
     */
    public function actionIndex()
    {
        $page = CmsPage::findBySlug('services');
        $settings = SiteSettings::get();
        $services = Service::active()->all();

        return $this->render('index', [
            'page' => $page,
            'settings' => $settings,
            'services' => $services,
        ]);
    }

    /**
     * Dedicated Service Detail Page (/services/<slug>)
     */
    public function actionView(string $slug)
    {
        $service = Service::findOne(['slug' => $slug, 'is_active' => 1]);
        if (!$service) {
            throw new NotFoundHttpException('The requested service could not be found.');
        }

        $settings = SiteSettings::get();
        $relatedProjects = $service->getProjects()->where(['is_active' => 1])->limit(4)->all();

        $inquiryModel = new UserQuery(['scenario' => UserQuery::SCENARIO_SERVICE]);
        $inquiryModel->query_source = UserQuery::SOURCE_SERVICE;
        $inquiryModel->selected_service = $service->title;

        if ($inquiryModel->load(Yii::$app->request->post()) && $inquiryModel->save()) {
            Yii::$app->session->setFlash('success', "Thank you, {$inquiryModel->full_name}. Your enquiry for {$service->title} has been logged. Our design team will contact you shortly!");
            return $this->refresh();
        }

        return $this->render('view', [
            'service' => $service,
            'settings' => $settings,
            'relatedProjects' => $relatedProjects,
            'inquiryModel' => $inquiryModel,
        ]);
    }
}
