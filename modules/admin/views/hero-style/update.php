<?php
/** @var yii\web\View $this */
/** @var app\models\HeroStyle $model */

use yii\helpers\Html;

$this->title = 'Update Preset: ' . $model->style_name;
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Update Style Preset: <?= Html::encode($model->style_name) ?></h2>
        <p class="text-xs text-gray-400 mt-0.5">Room: <strong class="text-[#D4AF37]"><?= Html::encode($model->room_category) ?></strong></p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
