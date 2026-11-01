<?php

/** @var yii\web\View $this */
/** @var app\models\Service $model */
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
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Service Title *</label>
            <?= $form->field($model, 'title', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. Modular Kitchens',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Starting Investment (₹)</label>
            <?= $form->field($model, 'starting_price', ['template' => '{input}{error}'])->textInput([
                'type' => 'number',
                'placeholder' => '185000',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Short Teaser Summary (Homepage & Cards) *</label>
        <?= $form->field($model, 'short_description', ['template' => '{input}{error}'])->textarea([
            'rows' => 2,
            'placeholder' => 'Concise 1-2 sentence description for cards...',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Detailed Scope & Philosophy (Rich Text / HTML)</label>
        <?= $form->field($model, 'full_description', ['template' => '{input}{error}'])->textarea([
            'rows' => 6,
            'placeholder' => '<p>Detailed paragraphs explaining your process, hardware brands, warranty...</p>',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-4 text-white font-mono outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <!-- Icon SVG code -->
    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Icon SVG Markup</label>
        <?= $form->field($model, 'icon_svg_or_class', ['template' => '{input}{error}'])->textarea([
            'rows' => 3,
            'placeholder' => '<svg ...>...</svg>',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white font-mono outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <!-- Featured Image -->
    <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5">
        <label class="block uppercase font-bold tracking-wider text-[#D4AF37] mb-2">Featured Cover Image</label>
        <?php if ($model->featured_image): ?>
            <div class="w-48 aspect-[16/10] rounded-xl overflow-hidden mb-3 border border-white/10">
                <img src="<?= Media::url($model->featured_image) ?>" alt="" class="w-full h-full object-cover">
            </div>
        <?php endif; ?>
        <?= $form->field($model, 'featuredImageFile', ['template' => '{input}{error}'])->fileInput([
            'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#D4AF37]/20 file:text-[#D4AF37] hover:file:bg-[#D4AF37]/30'
        ]) ?>
    </div>

    <!-- Deliverables & Material Tiers -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Deliverables</label>
            <span class="text-[10px] text-gray-400 block mb-2">One deliverable per line</span>
            <?= $form->field($model, 'deliverablesInput', ['template' => '{input}{error}'])->textarea([
                'rows' => 6,
                'placeholder' => "3D visualisation per room\nItemised BOQ\nBWP Ply Carcass\nSoft-close hardware",
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Material Tiers</label>
            <span class="text-[10px] text-gray-400 block mb-2">Format: "Tier: Description"</span>
            <?= $form->field($model, 'tiersInput', ['template' => '{input}{error}'])->textarea([
                'rows' => 6,
                'placeholder' => "Essential: Laminates + standard hardware\nLuxury Gold: Acrylic/PU + Blum\nBespoke Signature: Stone, brass & veneer",
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <!-- Existing Gallery Images -->
    <?php $gallery = $model->getGallery(); ?>
    <?php if (!empty($gallery)): ?>
        <div class="p-4 rounded-2xl bg-white/[0.02] border border-white/5">
            <label class="block uppercase font-bold tracking-wider text-gray-300 mb-3">Current Gallery Images (Check to remove)</label>
            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3">
                <?php foreach ($gallery as $img): ?>
                    <label class="relative block rounded-xl overflow-hidden border border-white/10 cursor-pointer group">
                        <img src="<?= Media::url($img) ?>" alt="" class="w-full aspect-[16/10] object-cover">
                        <div class="absolute inset-x-0 bottom-0 p-2 bg-black/80 flex items-center justify-between text-[11px] text-red-400">
                            <span>Remove</span>
                            <input type="checkbox" name="Service[removeGallery][]" value="<?= Html::encode($img) ?>" class="w-4 h-4 text-red-500 rounded">
                        </div>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    <?php endif; ?>

    <!-- Add More Gallery Images -->
    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Add New Gallery Images</label>
        <?= $form->field($model, 'galleryFiles[]', ['template' => '{input}{error}'])->fileInput([
            'multiple' => true,
            'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-gray-300 hover:file:bg-white/20'
        ]) ?>
    </div>

    <!-- SEO & Visibility -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5 pt-4 border-t border-white/5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Meta Title</label>
            <?= $form->field($model, 'meta_title', ['template' => '{input}{error}'])->textInput([
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Meta Description</label>
            <?= $form->field($model, 'meta_description', ['template' => '{input}{error}'])->textInput([
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2 text-white outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Sort Order</label>
            <?= $form->field($model, 'sort_order', ['template' => '{input}{error}'])->textInput([
                'type' => 'number',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div class="flex items-center gap-3 pt-6">
            <?= Html::activeCheckbox($model, 'is_active', [
                'label' => 'Service Active & Published',
                'class' => 'w-4 h-4 rounded text-[#D4AF37] bg-white/10 border-white/20'
            ]) ?>
        </div>
    </div>

    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-ghost btn-sm">Cancel</a>
        <button type="submit" class="btn-gold btn-sm">Save Service</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
