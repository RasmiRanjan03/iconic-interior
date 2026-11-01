<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage|null $page */
/** @var app\models\SiteSettings $settings */
/** @var array $types */
/** @var array $rates */
/** @var array $breakdown */
/** @var app\models\UserQuery $model */

use app\components\Estimator;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\widgets\ActiveForm;

$this->title = $page ? $page->title : 'Interior Cost Estimator';
$this->params['meta_description'] = $page?->meta_description;

$configJson = [
    'types' => $types,
    'rates' => $rates,
    'breakdown' => $breakdown,
    'whatsappNumber' => $settings->getWhatsappDigits(),
];
?>

<!-- Header -->
<section class="relative py-16 md:py-24 border-b border-white/5 bg-[#0C0C0C] text-center overflow-hidden">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="kicker text-[11px] mb-3">INSTANT TRANSPARENT ESTIMATION</span>
        <h1 class="text-3xl sm:text-5xl font-serif font-black text-white tracking-wide">
            Interior Cost <span class="text-gold-gradient italic">Estimator</span>
        </h1>
        <p class="text-sm sm:text-base text-gray-300 mt-4 max-w-xl mx-auto font-light leading-relaxed">
            <?= Html::encode($page ? $page->subtitle : 'Calculate an itemised, transparent budget range for your home or office in under 60 seconds.') ?>
        </p>
    </div>
</section>

<!-- Calculator Section (Alpine.js + ActiveForm) -->
<section class="py-16 md:py-28 relative"
         x-data="costEstimator(<?= Html::encode(Json::encode($configJson)) ?>)">
    
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left Calculator Controls & Inquiry Form (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-8">
                
                <!-- 1. Property Type Selector -->
                <div class="card-lux p-7 border border-white/10">
                    <span class="kicker text-[11px] mb-2">STEP 01</span>
                    <h3 class="text-xl font-serif font-bold text-white mb-4">Select Property Type</h3>
                    
                    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
                        <template x-for="(cfg, key) in types" :key="key">
                            <button type="button" 
                                    @click="propertyType = key; updateDefaultArea();"
                                    class="opt-card flex flex-col items-start text-left"
                                    :class="propertyType === key ? 'is-active' : ''">
                                <span class="text-sm font-bold text-white mb-1" x-text="key"></span>
                                <span class="text-[11px] text-gray-400" x-text="cfg.label"></span>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 2. Carpet Area Slider -->
                <div class="card-lux p-7 border border-white/10">
                    <div class="flex items-center justify-between mb-2">
                        <span class="kicker text-[11px]">STEP 02</span>
                        <span class="font-mono text-xl font-bold text-[#D4AF37]">
                            <span x-text="area"></span> sq.ft.
                        </span>
                    </div>
                    <h3 class="text-xl font-serif font-bold text-white mb-6">Carpet Area</h3>

                    <input type="range" min="200" max="8000" step="50" 
                           x-model.number="area" 
                           class="lux-range w-full cursor-pointer"
                           aria-label="Carpet area slider">

                    <div class="flex items-center justify-between text-xs text-gray-400 mt-3 font-mono">
                        <span>200 sq.ft.</span>
                        <span>2,500 sq.ft.</span>
                        <span>5,000 sq.ft.</span>
                        <span>8,000+ sq.ft.</span>
                    </div>
                </div>

                <!-- 3. Finish & Specification Tier -->
                <div class="card-lux p-7 border border-white/10">
                    <span class="kicker text-[11px] mb-2">STEP 03</span>
                    <h3 class="text-xl font-serif font-bold text-white mb-4">Specification Tier</h3>

                    <div class="grid grid-cols-1 sm:grid-cols-3 gap-3.5">
                        <template x-for="t in ['Essential', 'Luxury Gold', 'Bespoke Signature']" :key="t">
                            <button type="button" 
                                    @click="tier = t"
                                    class="opt-card flex flex-col justify-between"
                                    :class="tier === t ? 'is-active' : ''">
                                <div>
                                    <span class="text-sm font-bold text-white block mb-1" x-text="t"></span>
                                    <span class="text-xs text-[#D4AF37] font-mono block mb-2" x-text="'₹' + rates[t] + '/sq.ft.'"></span>
                                    <p class="text-[11px] text-gray-400 font-light" 
                                       x-text="t === 'Essential' ? 'BWP Ply + Laminates, branded soft-close, clean modern finishes.' : (t === 'Luxury Gold' ? 'Acrylic / PU finishes, veneer accents, profile lights, Blum fittings.' : 'Imported marble, brass inlays, bespoke furniture & designer lighting.')"></p>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- 4. Save & Lock Estimate Inquiry Form -->
                <div class="card-lux p-8 border border-[#D4AF37]/30 shadow-2xl relative">
                    <div class="corner-marks"></div>

                    <div class="mb-6">
                        <span class="kicker text-[11px] mb-1">LOCK YOUR CALCULATION</span>
                        <h3 class="text-2xl font-serif font-bold text-white">Save Estimate & Book Site Visit</h3>
                        <p class="text-xs text-gray-400 mt-1 font-light">
                            Submit to log your calculation into our system. We will verify your floor plan and share an exact itemised BOQ.
                        </p>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'estimator-inquiry-form',
                        'options' => ['class' => 'lux-form flex flex-col gap-4'],
                        'enableClientValidation' => true,
                    ]); ?>

                    <?= $form->field($model, 'website', ['template' => '{input}'])->textInput(['class' => 'hp-field', 'tabindex' => -1, 'autocomplete' => 'off']) ?>
                    
                    <!-- Hidden Bound Inputs from Alpine State -->
                    <input type="hidden" name="UserQuery[property_type]" :value="propertyType">
                    <input type="hidden" name="UserQuery[carpet_area_sqft]" :value="area">
                    <input type="hidden" name="UserQuery[finish_tier]" :value="tier">
                    <input type="hidden" name="UserQuery[estimated_budget]" :value="formattedRange">

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= $form->field($model, 'full_name')->textInput(['placeholder' => 'e.g. Satyabrata Das', 'class' => 'field-input', 'x-model' => 'fullName'])->label('Your Full Name *') ?>
                        <?= $form->field($model, 'phone')->textInput(['placeholder' => '+91 9778646212', 'class' => 'field-input', 'x-model' => 'phone'])->label('Phone / WhatsApp *') ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <?= $form->field($model, 'email')->textInput(['placeholder' => 'you@example.com', 'class' => 'field-input', 'x-model' => 'email'])->label('Email Address') ?>
                        <?= $form->field($model, 'preferred_location')->textInput(['placeholder' => 'e.g. Bhubaneswar / Cuttack', 'class' => 'field-input', 'x-model' => 'location'])->label('Property Location') ?>
                    </div>

                    <?= $form->field($model, 'message')->textarea(['rows' => 3, 'placeholder' => 'Any specific room priorities (e.g. modular kitchen first, master suite with walk-in closet)...', 'class' => 'field-input'])->label('Specific Preferences') ?>

                    <div class="pt-2 flex flex-col sm:flex-row gap-4">
                        <button type="submit" class="btn-gold flex-1 py-4 text-center text-xs font-bold tracking-widest uppercase">
                            <span>Save Estimate & Schedule Consultation</span>
                        </button>

                        <!-- Direct 1-Click WhatsApp Forward Button -->
                        <a :href="whatsappForwardUrl" 
                           target="_blank" rel="noopener noreferrer"
                           class="btn-ghost flex-1 py-4 text-center text-xs font-bold tracking-widest uppercase text-emerald-400 border-emerald-500/40 hover:border-emerald-400 hover:text-emerald-300">
                            <span>Forward to WhatsApp</span>
                            <svg class="w-4 h-4 ml-1" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                            </svg>
                        </a>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>

            </div>

            <!-- Right Live Calculation Display & Itemised Breakdown (5 cols sticky) -->
            <div class="lg:col-span-5 lg:sticky lg:top-28 flex flex-col gap-6">
                
                <div class="glass rounded-3xl p-8 border border-[#D4AF37]/40 shadow-2xl relative overflow-hidden">
                    <div class="absolute -top-20 -right-20 w-40 h-40 bg-[#D4AF37]/15 rounded-full blur-3xl pointer-events-none"></div>

                    <span class="kicker text-[11px] mb-2">LIVE ESTIMATE</span>
                    <span class="text-xs uppercase tracking-wider text-gray-400 block">Indicative Budget Range</span>

                    <!-- Big Range Display -->
                    <div class="my-4">
                        <span class="text-3xl sm:text-4xl font-serif font-black text-gold-gradient block"
                              x-text="formattedRange">
                        </span>
                        <span class="text-xs text-gray-400 font-mono mt-1 block"
                              x-text="'(' + formattedDetailedRange + ')'"></span>
                    </div>

                    <div class="pt-4 border-t border-white/10 flex items-center justify-between text-xs text-gray-300">
                        <span>Selected Scope:</span>
                        <span class="font-bold text-white" x-text="propertyType + ' · ' + area + ' sq.ft.'"></span>
                    </div>
                    <div class="mt-1 flex items-center justify-between text-xs text-gray-300">
                        <span>Finish Level:</span>
                        <span class="font-bold text-[#D4AF37]" x-text="tier"></span>
                    </div>

                    <!-- Itemised Category Breakdown -->
                    <div class="mt-8 pt-6 border-t border-white/10">
                        <h4 class="text-xs font-bold tracking-[0.2em] uppercase text-white mb-4">
                            Typical Category Allocation
                        </h4>

                        <div class="space-y-3.5">
                            <template x-for="item in itemizedBreakdown" :key="item.label">
                                <div>
                                    <div class="flex items-center justify-between text-xs mb-1">
                                        <span class="text-gray-300 font-medium" x-text="item.label"></span>
                                        <span class="font-mono text-gray-200" x-text="item.formatted"></span>
                                    </div>
                                    <!-- Progress bar -->
                                    <div class="w-full h-1.5 rounded-full bg-white/5 overflow-hidden">
                                        <div class="h-full rounded-full bg-gradient-to-r from-[#D4AF37] to-[#F6E7A8]" :style="'width: ' + item.pct + '%'"></div>
                                    </div>
                                </div>
                            </template>
                        </div>
                    </div>

                    <p class="text-[11px] text-gray-400 mt-6 font-light leading-relaxed">
                        * Estimates include civil finishing, modular units, lighting, false ceiling, and project management. Final itemised quote provided post site-measurement.
                    </p>
                </div>

            </div>

        </div>

    </div>
</section>
