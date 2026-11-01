<?php
/** @var yii\web\View $this */
/** @var app\models\CmsPage $model */

use yii\helpers\Html;

$this->title = 'Edit Page: ' . $model->title;
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Edit Page Content: <?= Html::encode($model->title) ?></h2>
        <p class="text-xs text-gray-400 mt-0.5">Slug: <strong class="text-[#D4AF37] font-mono">/<?= Html::encode($model->slug) ?></strong></p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
