<?php

namespace app\controllers;

use Yii;
use app\components\Estimator;
use app\models\CmsPage;
use app\models\SiteSettings;
use app\models\UserQuery;
use yii\web\Controller;

class EstimatorController extends Controller
{
    /**
     * Interactive Cost Estimator Page (/estimator)
     */
    public function actionIndex()
    {
        $page = CmsPage::findBySlug('estimator');
        $settings = SiteSettings::get();
        $types = Estimator::propertyTypes();
        $rates = $settings->getEstimatorRates();
        $breakdown = Estimator::breakdownShares();

        $model = new UserQuery(['scenario' => UserQuery::SCENARIO_ESTIMATOR]);
        $model->query_source = UserQuery::SOURCE_ESTIMATOR;
        $model->property_type = '3BHK';
        $model->carpet_area_sqft = 1400;
        $model->finish_tier = 'Luxury Gold';

        if ($model->load(Yii::$app->request->post())) {
            // Authoritative server-side calculation
            $calc = Estimator::calculate(
                (string)$model->property_type,
                (int)$model->carpet_area_sqft,
                (string)$model->finish_tier
            );
            $model->estimated_budget = $calc['label'];

            if ($model->save()) {
                Yii::$app->session->setFlash(
                    'success',
                    "Thank you, {$model->full_name}! Your custom cost estimate ({$calc['label']}) has been saved. Our senior design team has been notified."
                );
                return $this->refresh();
            }
        }

        return $this->render('index', [
            'page' => $page,
            'settings' => $settings,
            'types' => $types,
            'rates' => $rates,
            'breakdown' => $breakdown,
            'model' => $model,
        ]);
    }
}
