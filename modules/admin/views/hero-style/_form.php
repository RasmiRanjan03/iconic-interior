<?php

/** @var yii\web\View $this */
/** @var app\models\HeroStyle $model */
/** @var yii\widgets\ActiveForm $form */

use app\components\Media;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$rooms = Yii::$app->params['heroRooms'];
$lightingModes = Yii::$app->params['lightingModes'];
?>

<div class="lux-card p-6 sm:p-8 border border-white/10 max-w-4xl">
    
    <?php $form = ActiveForm::begin([
        'options' => ['enctype' => 'multipart/form-data', 'class' => 'space-y-6 text-xs'],
    ]); ?>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Design Style Name *</label>
            <?= $form->field($model, 'style_name', ['template' => '{input}{error}'])->textInput([
                'placeholder' => 'e.g. Royal Noir & Gold',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Room Category *</label>
            <?= $form->field($model, 'room_category', ['template' => '{input}{error}'])->dropDownList(
                array_combine($rooms, $rooms),
                ['class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]']
            ) ?>
        </div>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Tagline</label>
        <?= $form->field($model, 'tagline', ['template' => '{input}{error}'])->textInput([
            'placeholder' => 'e.g. Midnight drama, gilded edges.',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Description</label>
        <?= $form->field($model, 'description', ['template' => '{input}{error}'])->textarea([
            'rows' => 3,
            'placeholder' => 'Brief architectural mood summary for the customizer bottom bar...',
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-4 text-white outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <!-- Images: After & Before -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 p-4 rounded-2xl bg-white/[0.02] border border-white/5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-[#D4AF37] mb-2">Designed / After Image</label>
            <?php if ($model->after_image): ?>
                <div class="aspect-[16/9] rounded-xl overflow-hidden mb-3 border border-white/10">
                    <img src="<?= Media::url($model->after_image) ?>" alt="Current After" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <?= $form->field($model, 'afterImageFile', ['template' => '{input}{error}'])->fileInput([
                'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-[#D4AF37]/20 file:text-[#D4AF37] hover:file:bg-[#D4AF37]/30'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Raw Shell / Before Image (For Split Slider)</label>
            <?php if ($model->before_image): ?>
                <div class="aspect-[16/9] rounded-xl overflow-hidden mb-3 border border-white/10">
                    <img src="<?= Media::url($model->before_image) ?>" alt="Current Before" class="w-full h-full object-cover">
                </div>
            <?php endif; ?>
            <?= $form->field($model, 'beforeImageFile', ['template' => '{input}{error}'])->fileInput([
                'class' => 'w-full text-xs text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-white/10 file:text-gray-300 hover:file:bg-white/20'
            ]) ?>
        </div>
    </div>

    <!-- Palette & Materials Inputs -->
    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Color Palette Hex Codes</label>
            <span class="text-[10px] text-gray-400 block mb-2">Comma separated: #0A0A0A, #D4AF37, #F5F0E6</span>
            <?= $form->field($model, 'paletteInput', ['template' => '{input}{error}'])->textInput([
                'placeholder' => '#0A0A0A, #1C1C1C, #D4AF37, #F5F0E6',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Default Lighting Mode</label>
            <span class="text-[10px] text-gray-400 block mb-2">Initial mood filter when this preset is selected</span>
            <?= $form->field($model, 'lighting_mode_default', ['template' => '{input}{error}'])->dropDownList(
                $lightingModes,
                ['class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]']
            ) ?>
        </div>
    </div>

    <div>
        <label class="block uppercase font-bold tracking-wider text-gray-400 mb-1">Material Specifications</label>
        <span class="text-[10px] text-gray-400 block mb-2">One line per spec formatted as "Key: Value" (e.g. Flooring: Nero Marquina marble)</span>
        <?= $form->field($model, 'materialsInput', ['template' => '{input}{error}'])->textarea([
            'rows' => 5,
            'placeholder' => "Flooring: Nero Marquina marble\nWall Finish: Matte black fluted panels\nLighting: 2700K cove lighting\nHardware: Brushed gold PVD",
            'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-4 text-white font-mono outline-none focus:border-[#D4AF37]'
        ]) ?>
    </div>

    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
        <div>
            <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Display Sort Order</label>
            <?= $form->field($model, 'sort_order', ['template' => '{input}{error}'])->textInput([
                'type' => 'number',
                'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
            ]) ?>
        </div>

        <div class="flex items-center gap-3 pt-6">
            <?= Html::activeCheckbox($model, 'is_active', [
                'label' => 'Preset Active & Visible on Website',
                'class' => 'w-4 h-4 rounded text-[#D4AF37] bg-white/10 border-white/20'
            ]) ?>
        </div>
    </div>

    <div class="pt-4 border-t border-white/10 flex items-center justify-end gap-3">
        <a href="<?= \yii\helpers\Url::to(['index']) ?>" class="btn-ghost btn-sm">Cancel</a>
        <button type="submit" class="btn-gold btn-sm">Save Style Preset</button>
    </div>

    <?php ActiveForm::end(); ?>

</div>
