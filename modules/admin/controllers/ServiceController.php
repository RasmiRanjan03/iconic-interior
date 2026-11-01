<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Service;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ServiceController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => ['delete' => ['post']],
            ],
        ];
    }

    public function actionIndex()
    {
        $dataProvider = new ActiveDataProvider([
            'query' => Service::find()->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Service(['is_active' => 1, 'sort_order' => 10]);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveWithUploads()) {
            Yii::$app->session->setFlash('success', 'Service created successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveWithUploads()) {
            Yii::$app->session->setFlash('success', 'Service updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Service deleted.');
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Service
    {
        if (($model = Service::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested service does not exist.');
    }
}
