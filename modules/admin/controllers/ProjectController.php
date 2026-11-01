<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\Project;
use yii\data\ActiveDataProvider;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;

class ProjectController extends Controller
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
            'query' => Project::find()->orderBy(['is_featured' => SORT_DESC, 'id' => SORT_DESC]),
            'pagination' => ['pageSize' => 20],
        ]);

        return $this->render('index', [
            'dataProvider' => $dataProvider,
        ]);
    }

    public function actionCreate()
    {
        $model = new Project(['is_active' => 1, 'category' => 'Residential', 'completion_year' => (int)date('Y')]);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveWithUploads()) {
            Yii::$app->session->setFlash('success', 'Portfolio project created successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('create', ['model' => $model]);
    }

    public function actionUpdate(int $id)
    {
        $model = $this->findModel($id);

        if (Yii::$app->request->isPost && $model->load(Yii::$app->request->post()) && $model->saveWithUploads()) {
            Yii::$app->session->setFlash('success', 'Portfolio project updated successfully.');
            return $this->redirect(['index']);
        }

        return $this->render('update', ['model' => $model]);
    }

    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', 'Project deleted.');
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): Project
    {
        if (($model = Project::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested project does not exist.');
    }
}
