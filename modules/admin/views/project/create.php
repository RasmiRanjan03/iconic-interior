<?php
/** @var yii\web\View $this */
/** @var app\models\Project $model */

$this->title = 'Create Portfolio Case Study';
?>
<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Add New Case Study</h2>
        <p class="text-xs text-gray-400 mt-0.5">Upload cover, before shot, project parameters, and case study narrative.</p>
    </div>
    <?= $this->render('_form', ['model' => $model]) ?>
</div>
