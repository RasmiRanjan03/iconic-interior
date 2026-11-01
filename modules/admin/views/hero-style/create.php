<?php
/** @var yii\web\View $this */
/** @var app\models\HeroStyle $model */

$this->title = 'Create Hero Style Preset';
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Add New Style Preset</h2>
        <p class="text-xs text-gray-400 mt-0.5">Configure room association, lighting mode, and material specs.</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
