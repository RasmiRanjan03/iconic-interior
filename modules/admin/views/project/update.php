<?php
/** @var yii\web\View $this */
/** @var app\models\Project $model */

use yii\helpers\Html;

$this->title = 'Update Case Study: ' . $model->title;
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Update Project: <?= Html::encode($model->title) ?></h2>
        <p class="text-xs text-gray-400 mt-0.5">Category: <strong class="text-[#D4AF37]"><?= Html::encode($model->category) ?></strong></p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
