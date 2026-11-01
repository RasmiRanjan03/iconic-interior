<?php
/** @var yii\web\View $this */
/** @var app\models\Service $model */

$this->title = 'Create Service';
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Create New Service</h2>
        <p class="text-xs text-gray-400 mt-0.5">Define deliverables, material tiers, gallery, and pricing starting point.</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
