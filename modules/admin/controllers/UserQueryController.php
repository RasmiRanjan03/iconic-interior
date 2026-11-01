<?php

namespace app\modules\admin\controllers;

use Yii;
use app\models\UserQuery;
use app\models\UserQuerySearch;
use yii\filters\VerbFilter;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\web\Response;

class UserQueryController extends Controller
{
    public function behaviors(): array
    {
        return [
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'delete' => ['post'],
                    'update-status' => ['post'],
                ],
            ],
        ];
    }

    /**
     * User Queries GridView with filtering (/admin/user-query/index)
     */
    public function actionIndex()
    {
        $searchModel = new UserQuerySearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('index', [
            'searchModel' => $searchModel,
            'dataProvider' => $dataProvider,
        ]);
    }

    /**
     * View detailed query + auto mark read + notes + WhatsApp reply (/admin/user-query/view?id=X)
     */
    public function actionView(int $id)
    {
        $model = $this->findModel($id);

        // Auto mark as read on view
        if ($model->is_read === 0) {
            $model->is_read = 1;
            $model->save(false);
        }

        // Handle updating status & admin_notes
        if (Yii::$app->request->isPost) {
            $model->scenario = UserQuery::SCENARIO_ADMIN;
            if ($model->load(Yii::$app->request->post()) && $model->save()) {
                Yii::$app->session->setFlash('success', 'Lead status and internal notes updated successfully.');
                return $this->refresh();
            }
        }

        return $this->render('view', [
            'model' => $model,
        ]);
    }

    /**
     * Quick status update endpoint
     */
    public function actionUpdateStatus(int $id, string $status)
    {
        $model = $this->findModel($id);
        if (in_array($status, UserQuery::STATUSES, true)) {
            $model->status = $status;
            $model->is_read = 1;
            $model->save(false);
            Yii::$app->session->setFlash('success', "Lead #{$id} status updated to {$status}.");
        }
        return $this->redirect(Yii::$app->request->referrer ?: ['index']);
    }

    /**
     * Export filtered user queries to CSV (/admin/user-query/export-csv)
     */
    public function actionExportCsv()
    {
        $searchModel = new UserQuerySearch();
        $query = $searchModel->buildQuery(Yii::$app->request->queryParams);
        $records = $query->orderBy(['created_at' => SORT_DESC])->all();

        $filename = 'iconic_leads_' . date('Ymd_His') . '.csv';

        header('Content-Type: text/csv; charset=utf-8');
        header('Content-Disposition: attachment; filename=' . $filename);
        header('Pragma: no-cache');
        header('Expires: 0');

        $out = fopen('php://output', 'w');
        // Add BOM for Excel UTF-8 compliance
        fprintf($out, chr(0xEF) . chr(0xBB) . chr(0xBF));

        fputcsv($out, [
            'Lead ID',
            'Full Name',
            'Phone',
            'Email',
            'Source',
            'Selected Service',
            'Hero Style',
            'Property Type',
            'Carpet Area (sqft)',
            'Finish Tier',
            'Estimated Budget',
            'Location',
            'Message',
            'Status',
            'Admin Notes',
            'Read Status',
            'IP Address',
            'Received Date',
        ]);

        foreach ($records as $r) {
            fputcsv($out, [
                $r->id,
                $r->full_name,
                $r->phone,
                $r->email,
                $r->query_source,
                $r->selected_service,
                $r->selected_hero_style,
                $r->property_type,
                $r->carpet_area_sqft,
                $r->finish_tier,
                $r->estimated_budget,
                $r->preferred_location,
                $r->message,
                $r->status,
                $r->admin_notes,
                $r->is_read ? 'Read' : 'Unread',
                $r->ip_address,
                $r->created_at,
            ]);
        }

        fclose($out);
        exit;
    }

    /**
     * Delete a query
     */
    public function actionDelete(int $id)
    {
        $model = $this->findModel($id);
        $model->delete();
        Yii::$app->session->setFlash('success', "Lead #{$id} deleted.");
        return $this->redirect(['index']);
    }

    protected function findModel(int $id): UserQuery
    {
        if (($model = UserQuery::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested inquiry does not exist.');
    }
}
