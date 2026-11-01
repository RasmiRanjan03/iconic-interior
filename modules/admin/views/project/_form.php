<?php

/** @var yii\web\View $this */
/** @var app\models\Project $model */
/** @var yii\widgets\ActiveForm $form */

use app\components\Media;
use app\models\Service;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$categories = Yii::$app->params['projectCategories'];
$services = Service::find()->select(['title', 'id'])->indexBy('id')->column();
?>

<div class="lux-card p-6 sm:p-8 border border-white/10 max-w-4xl">
    
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'space-y-6 text-xs'],
    ]); ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Project Title *</label>
            <?= $form->field($model, 'title', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. The Obsidian Penthouse',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Category *</label>
            <?= $form->field($model, 'category', ['template' => '{input}{error}'])->dropDownList(
                array_combine($categories, $categories),
                ['class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]']
            ) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Associated Service</label>
            <?= $form->field($model, 'service_id', ['template' => '{input}{error}'])->dropDownList(
                ['' => 'None / Standalone'] + $services,
                ['class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]']
            ) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Location</label>
            <?= $form->field($model, 'location', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. Patia, Bhubaneswar',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Carpet Area (sq.ft.)</label>
            <?= $form->field($model, 'carpet_area', ['template' => '{input}{error}'])->textInput([
                'type' => 'number',
                'placeholder' => '3200',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Budget Tier</label>
            <?= $form->field($model, 'budget_tier', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. Bespoke Signature',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Completion Time</label>
            <?= $form->field($model, 'completion_time', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. 120 Days / 45 Days',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Completion Year</label>
            <?= $form->field($model, 'completion_year', ['template' => '{input}{error}'])->textInput([
                'type' => 'number',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Client Name (Optional / Displayed in Case Study)</label>
        <?= $form->field($model, 'client_name', ['template' => '{input}{error}'])->textInput([
            'placeholder' => 'e.g. Mr. & Mrs. Mohanty',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <!-- Images: Cover & Before -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-[#D4AF37] mb-2">Cover / Handover Photo</label>
            <?php if ($model->cover_image): ?>
                <div class="aspect-[16/10] rounded-xl overflow-hidden mb-3 border border-white/10">
                    <img src="<?= Media::url($model->cover_image) ?>" alt="" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <?= $form->field($model, 'coverImageFile', ['template' => '{input}{error}'])->fileInput([
                'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#D4AF37]/20 file:text-[#D4AF37] hover:file:bg-[#D4AF37]/30'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Before Renovation Photo (For Split Slider)</label>
            <?php if ($model->before_image): ?>
                <div class="aspect-[16/10] rounded-xl overflow-hidden mb-3 border border-white/10">
                    <img src="<?= Media::url($model->before_image) ?>" alt="" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <?= $form->field($model, 'beforeImageFile', ['template' => '{input}{error}'])->fileInput([
                'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-gray-300 hover:file:bg-white/20'
            ]) ?>
        </div>
    </div>

    <!-- Gallery Multi-Upload -->
    <?php $gallery = $model->getGallery(); ?>
    <?php if (!empty($gallery)): ?>
        <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5">
            <label class="block uppercase font-bold tracking-wider text-gray-300 mb-3">Current Gallery Photos (Check to remove)</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <?php foreach ($gallery as $img): ?>
                    <label class="relative block rounded-xl overflow-hidden border border-white/10 cursor-pointer group">
                        <img src="<?= Media::url($img) ?>" alt="" class="w-full aspect-[16/10] object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-2 bg-black/80 flex items-center justify-between text-[11px] text-red-400">
                            <span>Remove</span>
                            <input type="checkbox" name="Project[removeGallery][]" value="<?= Html::encode($img) ?>" class="w-4 h-4 text-red-500 rounded">
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Add New Gallery Images</label>
        <?= $form->field($model, 'galleryFiles[]', ['template' => '{input}{error}'])->fileInput([
            'multiple' => true,
            'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-gray-300 hover:file:bg-white/20'
        ]) ?>
    </div>

    <!-- Narrative fields -->
    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Summary</label>
        <?= $form->field($model, 'summary', ['template' => '{input}{error}'])->textarea([
            'rows' => 3,
            'placeholder' => 'Short executive summary of the project...',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Client Requirements / Brief</label>
        <?= $form->field($model, 'client_requirements', ['template' => '{input}{error}'])->textarea([
            'rows' => 4,
            'placeholder' => 'What did the homeowner or company require for their space...',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Challenge & Solution</label>
        <?= $form->field($model, 'challenge_and_solution', ['template' => '{input}{error}'])->textarea([
            'rows' => 4,
            'placeholder' => 'Architectural challenges faced (glare, MEP, acoustics) and how ICONIC resolved them...',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <!-- Featured & Active -->
    <div class="flex items-center gap-8 pt-4 border-t border-white/5">
        <?= Html::activeCheckbox($model, 'is_featured', [
            'label' => 'Feature on Homepage Grid',
            'class' => 'w-4 h-4 rounded text-[#D4AF37] bg-white/10 border-white/20'
        ]) ?>

        <?= Html::activeCheckbox($model, 'is_active', [
            'label' => 'Published & Active in Portfolio',
            'class' => 'w-4 h-4 rounded text-[#D4AF37] bg-white/10 border-white/20'
        ]) ?>
    </div>

    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-ghost btn-sm">Cancel</a>
        <button type="submit" class="btn-gold btn-sm">Save Project</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
