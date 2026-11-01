<?php

/** @var yii\web\View $this */
/** @var app\models\SiteSettings $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Global Site & Estimator Settings';
?>

<div class="space-y-6 max-w-4xl">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Global Configuration</h2>
        <p class="text-xs text-gray-400 mt-0.5">Manage brand coordinates, phone numbers, founder attribution, and Cost Estimator per-sq.ft. base rates.</p>
    </div>

    <div class="lux-card p-6 sm:p-8 border border-white/10">
        
        <?php $form = ActiveForm::begin([
            'options' => ['class' => 'space-y-6 text-xs'],
        ]); ?>

        <!-- Brand Identity -->
        <div>
            <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37] block mb-3">BRAND IDENTITY</span>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Company Name *</label>
                    <?= $form->field($model, 'company_name', ['template' => '{input}{error}'])->textInput([
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Tagline</label>
                    <?= $form->field($model, 'tagline', ['template' => '{input}{error}'])->textInput([
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div class="sm:col-span-2">
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Founder & Principal Designer Name</label>
                    <?= $form->field($model, 'owner_name', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => 'Santosh Kumar Maharana',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Contact Channels -->
        <div class="pt-4 border-t border-white/5">
            <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37] block mb-3">CONTACT CHANNELS</span>
            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Primary Phone *</label>
                    <?= $form->field($model, 'phone_primary', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => '+91 9778646212',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Secondary Phone</label>
                    <?= $form->field($model, 'phone_secondary', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => '+91 9337080037',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">WhatsApp (Digits with Country Code) *</label>
                    <?= $form->field($model, 'whatsapp_number', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => '919778646212',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-emerald-400 font-mono outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div class="sm:col-span-2">
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Official Email *</label>
                    <?= $form->field($model, 'email', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => 'iconicinterior2026@gmail.com',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Working Hours</label>
                    <?= $form->field($model, 'working_hours', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => 'Mon – Sat · 10:00 AM – 7:30 PM',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Studio Address & Google Map -->
        <div class="pt-4 border-t border-white/5">
            <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37] block mb-3">STUDIO LOCATION & EMBED</span>
            <div class="space-y-4">
                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Physical Address</label>
                    <?= $form->field($model, 'address', ['template' => '{input}{error}'])->textInput([
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Google Map Embed URL (iframe src)</label>
                    <?= $form->field($model, 'map_embed_url', ['template' => '{input}{error}'])->textInput([
                        'placeholder' => 'https://www.google.com/maps?q=...',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono text-xs outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-400 mb-2">Footer Narrative Note</label>
                    <?= $form->field($model, 'footer_text', ['template' => '{input}{error}'])->textarea([
                        'rows' => 2,
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3 text-white outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>
            </div>
        </div>

        <!-- Cost Estimator Base Rates Configuration -->
        <div class="pt-4 border-t border-white/5 p-5 rounded-2xl bg-[#D4AF37]/5 border border-[#D4AF37]/25">
            <div class="mb-3">
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37] block">COST ESTIMATOR CALIBRATION</span>
                <p class="text-gray-400 text-xs">Update the base cost per square foot without modifying code. The calculator updates automatically.</p>
            </div>

            <div class="grid grid-cols-1 sm:grid-cols-3 gap-5">
                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-300 mb-2">Essential (₹ / sq.ft.) *</label>
                    <?= $form->field($model, 'rate_essential', ['template' => '{input}{error}'])->textInput([
                        'type' => 'number',
                        'class' => 'w-full bg-[#141414] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono font-bold outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-[#D4AF37] mb-2">Luxury Gold (₹ / sq.ft.) *</label>
                    <?= $form->field($model, 'rate_luxury_gold', ['template' => '{input}{error}'])->textInput([
                        'type' => 'number',
                        'class' => 'w-full bg-[#141414] border border-[#D4AF37]/40 rounded-xl px-4 py-2.5 text-[#D4AF37] font-mono font-bold outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>

                <div>
                    <label class="block uppercase font-bold tracking-wider text-gray-300 mb-2">Bespoke Signature (₹ / sq.ft.) *</label>
                    <?= $form->field($model, 'rate_bespoke_signature', ['template' => '{input}{error}'])->textInput([
                        'type' => 'number',
                        'class' => 'w-full bg-[#141414] border border-white/10 rounded-xl px-4 py-2.5 text-white font-mono font-bold outline-none focus:border-[#D4AF37]'
                    ]) ?>
                </div>
            </div>
        </div>

        <div class="pt-4 border-t border-white/10 flex items-center justify-end">
            <button type="submit" class="btn-gold py-3 px-8 text-xs uppercase tracking-widest font-bold">
                Save Global Settings
            </button>
        </div>

        <?php ActiveForm::end(); ?>

    </div>
</div>
