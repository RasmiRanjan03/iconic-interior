<?php

namespace app\controllers;

use Yii;
use app\models\UserQuery;
use yii\web\Controller;
use yii\web\Response;

class InquiryController extends Controller
{
    /**
     * Universal submission endpoint for user inquiries from modals or hero customizer
     */
    public function actionSubmit()
    {
        $model = new UserQuery();
        $post = Yii::$app->request->post('UserQuery', []);
        
        // Dynamically assign scenario based on query_source
        $source = $post['query_source'] ?? UserQuery::SOURCE_CONTACT;
        if (!in_array($source, UserQuery::SOURCES, true)) {
            $source = UserQuery::SOURCE_CONTACT;
        }

        $scenarioMap = [
            UserQuery::SOURCE_CONTACT => UserQuery::SCENARIO_CONTACT,
            UserQuery::SOURCE_HERO => UserQuery::SCENARIO_HERO,
            UserQuery::SOURCE_SERVICE => UserQuery::SCENARIO_SERVICE,
            UserQuery::SOURCE_ESTIMATOR => UserQuery::SCENARIO_ESTIMATOR,
        ];
        $model->scenario = $scenarioMap[$source] ?? UserQuery::SCENARIO_DEFAULT;
        $model->query_source = $source;

        if ($model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash(
                'success',
                "Thank you, {$model->full_name}. We have received your request for {$model->query_source}! Santosh Kumar Maharana & the ICONIC team will get in touch shortly."
            );
        } else {
            $errors = $model->getFirstErrors();
            $msg = reset($errors) ?: 'Could not save your inquiry. Please check the inputs.';
            Yii::$app->session->setFlash('error', $msg);
        }

        // Return back to referring page or home
        return $this->goBack(Yii::$app->request->referrer ?: ['/site/index']);
    }
}
