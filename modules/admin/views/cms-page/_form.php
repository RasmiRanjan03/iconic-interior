<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage $model */
/** @var yii\widgets\ActiveForm $form */

use app\components\Media;
use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="lux-card p-6 sm:p-8 border border-white/10 max-w-4xl">
    
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'space-y-6 text-xs'],
    ]); ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Page Slug (Permanent)</label>
            <input type="text" value="<?= Html::encode($model->slug) ?>" readonly class="w-full bg-[#141414] border border-white/5 rounded-xl px-4 py-2.5 text-gray-400 font-mono">
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Page Heading / Title *</label>
            <?= $form->field($model, 'title', ['template' => '{input}{error}'])->textInput([
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Subtitle / Lead In</label>
        <?= $form->field($model, 'subtitle', ['template' => '{input}{error}'])->textarea([
            'rows' => 2,
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <!-- Hero Banner Image -->
    <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5">
        <label class="block uppercase font-bold tracking-wider text-[#D4AF37] mb-2">Hero Header Banner</label>
        <?php if ($model->hero_banner_image): ?>
            <div class="w-56 aspect-[16/9] rounded-xl overflow-hidden mb-3 border border-white/10">
                <img src="<?= Media::url($model->hero_banner_image) ?>" alt="" class="w-full h-full object-cover">
            </div>
        <?php endif; ?>
        <?= $form->field($model, 'heroBannerFile', ['template' => '{input}{error}'])->fileInput([
            'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#D4AF37]/20 file:text-[#D4AF37] hover:file:bg-[#D4AF37]/30'
        ]) ?>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Content Body (HTML / Rich Text)</label>
        <?= $form->field($model, 'content_body', ['template' => '{input}{error}'])->textarea([
            'rows' => 8,
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-4 text-white font-mono outline-none focus:border-[#D4AF37] text-xs'
        ]) ?>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Structured JSON Data</label>
        <span class="text-[10px] text-gray-400 block mb-2">Stats, milestones, founder details, breakdown shares (valid JSON)</span>
        <?= $form->field($model, 'extra_json', ['template' => '{input}{error}'])->textarea([
            'rows' => 8,
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-4 text-gray-200 font-mono outline-none focus:border-[#D4AF37] text-xs'
        ]) ?>
    </div>

    <!-- SEO Meta Tags -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-white/5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">SEO Meta Title</label>
            <?= $form->field($model, 'meta_title', ['template' => '{input}{error}'])->textInput([
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">SEO Meta Description</label>
            <?= $form->field($model, 'meta_description', ['template' => '{input}{error}'])->textInput([
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-ghost btn-sm">Cancel</a>
        <button type="submit" class="btn-gold btn-sm">Save Page Content</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
