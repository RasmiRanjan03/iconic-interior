<?php
/** @var yii\web\View $this */
/** @var app\models\Service $model */

use yii\helpers\Html;

$this->title = 'Update Service: ' . $model->title;
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Update Service: <?= Html::encode($model->title) ?></h2>
        <p class="text-xs text-gray-400 mt-0.5">Edit content, images, and material specifications.</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
