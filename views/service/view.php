<?php

/** @var yii\web\View $this */
/** @var app\models\Service $service */
/** @var app\models\SiteSettings $settings */
/** @var app\models\Project[] $relatedProjects */
/** @var app\models\UserQuery $inquiryModel */

use app\components\Media;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = $service->meta_title ?: ($service->title . ' Services');
$this->params['meta_description'] = $service->meta_description ?: $service->short_description;

$deliverables = $service->getDeliverables();
$tiers = $service->getMaterialTiers();
$gallery = $service->getGallery();
?>

<!-- Hero Banner -->
<section class="relative py-20 md:py-28 border-b border-white/5 overflow-hidden">
    <div class="absolute inset-0 z-0">
        <img src="<?= $service->getImageUrl() ?>" 
             alt="<?= Html::encode($service->title) ?>"
             class="w-full h-full object-cover opacity-20 filter brightness-50">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/85 to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <a href="<?= Url::to(['/service/index']) ?>" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-gray-400 hover:text-[#D4AF37] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>All Services</span>
        </a>

        <div class="max-w-3xl">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 rounded-xl bg-white/5 border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] p-2">
                    <?= $service->icon_svg_or_class ?: '◆' ?>
                </div>
                <span class="text-xs uppercase font-bold tracking-[0.22em] text-[#D4AF37]">Signature Discipline</span>
            </div>
            
            <h1 class="text-3xl sm:text-5xl font-serif font-black text-white tracking-wide">
                <?= Html::encode($service->title) ?>
            </h1>
            <p class="text-base sm:text-lg text-gray-300 mt-4 font-light leading-relaxed">
                <?= Html::encode($service->short_description) ?>
            </p>
            
            <div class="mt-6 flex items-center gap-6 text-sm">
                <div class="flex flex-col">
                    <span class="text-[10px] uppercase tracking-wider text-gray-300 font-bold">Starting Investment</span>
                    <span class="text-lg font-serif font-bold text-gold-gradient"><?= $service->getPriceLabel() ?></span>
                </div>
                <button type="button" 
                        onclick="document.getElementById('service-inquiry-box').scrollIntoView({ behavior: 'smooth' })"
                        class="btn-gold btn-sm">
                    <span>Book This Service</span>
                </button>
            </div>
        </div>
    </div>
</section>

<!-- Content Body & Specifications -->
<section class="py-16 md:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left: Description, Deliverables, Material Tiers, Gallery (7 cols) -->
            <div class="lg:col-span-7 flex flex-col gap-12">
                
                <!-- Detailed Description -->
                <?php if ($service->full_description): ?>
                    <div class="prose-lux text-base leading-relaxed">
                        <h2 class="text-2xl font-serif font-bold text-white mb-4">Service Scope & Philosophy</h2>
                        <?= $service->full_description ?>
                    </div>
                <?php endif; ?>

                <!-- Deliverables List -->
                <?php if (!empty($deliverables)): ?>
                    <div class="glass rounded-3xl p-8 border border-white/10">
                        <span class="kicker text-[11px] mb-2">WHAT WE DELIVER</span>
                        <h3 class="text-xl font-serif font-bold text-white mb-6">Scope & Deliverables</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php foreach ($deliverables as $del): ?>
                                <div class="flex items-start gap-3 p-3.5 rounded-xl bg-white/[0.02] border border-white/5">
                                    <span class="w-5 h-5 rounded-full bg-[#D4AF37]/20 text-[#D4AF37] flex items-center justify-center text-xs shrink-0 mt-0.5 font-bold">✓</span>
                                    <span class="text-xs text-gray-200 font-medium"><?= Html::encode($del) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Material Tiers -->
                <?php if (!empty($tiers)): ?>
                    <div>
                        <span class="kicker text-[11px] mb-2">SPECIFICATION TIERS</span>
                        <h3 class="text-xl font-serif font-bold text-white mb-6">Finishes & Material Choices</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                            <?php foreach ($tiers as $tier): ?>
                                <div class="card-lux p-5 border border-white/10 flex flex-col justify-between">
                                    <div>
                                        <span class="text-xs font-serif font-bold text-[#D4AF37] uppercase tracking-wider block mb-2">
                                            <?= Html::encode($tier['name']) ?>
                                        </span>
                                        <p class="text-xs text-gray-400 font-light leading-relaxed">
                                            <?= Html::encode($tier['text']) ?>
                                        </p>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

                <!-- Gallery Showcase -->
                <?php if (!empty($gallery)): ?>
                    <div>
                        <span class="kicker text-[11px] mb-2">VISUAL DETAILS</span>
                        <h3 class="text-xl font-serif font-bold text-white mb-6">Service Gallery</h3>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <?php foreach ($gallery as $gImg): ?>
                                <div class="rounded-2xl overflow-hidden aspect-[16/10] gold-frame">
                                    <img src="<?= Media::url($gImg) ?>" 
                                         alt="<?= Html::encode($service->title) ?> Gallery"
                                         class="w-full h-full object-cover hover:scale-105 transition-transform duration-500">
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                <?php endif; ?>

            </div>

            <!-- Right: Pre-filled Booking Form (5 cols sticky) -->
            <div class="lg:col-span-5 lg:sticky lg:top-28" id="service-inquiry-box">
                <div class="card-lux p-8 border border-[#D4AF37]/30 shadow-2xl relative">
                    <div class="corner-marks"></div>

                    <div class="mb-6">
                        <span class="kicker text-[11px] mb-1">DEDICATED INQUIRY</span>
                        <h3 class="text-2xl font-serif font-bold text-white">
                            Book <?= Html::encode($service->title) ?>
                        </h3>
                        <p class="text-xs text-gray-400 mt-1">
                            Share your requirement. Santosh Kumar Maharana & team will schedule a site visit.
                        </p>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'service-view-inquiry-form',
                        'options' => ['class' => 'lux-form flex flex-col gap-4'],
                        'enableClientValidation' => true,
                    ]); ?>

                    <?= $form->field($inquiryModel, 'website', ['template' => '{input}'])->textInput(['class' => 'hp-field', 'tabindex' => -1, 'autocomplete' => 'off']) ?>
                    <?= $form->field($inquiryModel, 'selected_service', ['template' => '{input}'])->hiddenInput(['value' => $service->title]) ?>

                    <?= $form->field($inquiryModel, 'full_name')->textInput(['placeholder' => 'e.g. Priyabrata Mohapatra', 'class' => 'field-input'])->label('Your Full Name *') ?>
                    <?= $form->field($inquiryModel, 'phone')->textInput(['placeholder' => '+91 9778646212', 'class' => 'field-input'])->label('Phone / WhatsApp *') ?>
                    <?= $form->field($inquiryModel, 'email')->textInput(['placeholder' => 'you@example.com', 'class' => 'field-input'])->label('Email Address') ?>
                    <?= $form->field($inquiryModel, 'preferred_location')->textInput(['placeholder' => 'e.g. Bhubaneswar / Cuttack', 'class' => 'field-input'])->label('Project Location') ?>
                    
                    <?= $form->field($inquiryModel, 'message')->textarea(['rows' => 3, 'placeholder' => "Tell us about your space and requirements for {$service->title}...", 'class' => 'field-input'])->label('Requirements & Notes') ?>

                    <div class="pt-2">
                        <button type="submit" class="btn-gold w-full py-3.5 text-center text-xs font-bold tracking-widest uppercase">
                            <span>Request Service Consultation</span>
                        </button>
                    </div>

                    <div class="pt-2 text-center">
                        <a href="<?= $settings->whatsappUrl("Hello Santosh Kumar Maharana, I would like to enquire about your {$service->title} service.") ?>" 
                           target="_blank" rel="noopener noreferrer"
                           class="text-xs text-emerald-400 hover:text-emerald-300 font-medium inline-flex items-center gap-1.5">
                            <span>Or discuss directly on WhatsApp →</span>
                        </a>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>

    </div>
</section>
