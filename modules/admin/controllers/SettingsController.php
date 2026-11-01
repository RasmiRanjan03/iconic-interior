<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\SiteSettings;
use yii\web\Controller;

class SettingsController extends Controller
{
    /**
     * Edit global site settings & cost estimator base rates (/admin/settings)
     */
    public function actionIndex()
    {
        $model = SiteSettings::get();

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->save()) {
            Yii::$app->session->setFlash('success', 'Global site settings and estimator rates updated.');
            return $this->refresh();
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }
}
