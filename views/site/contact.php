<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage|null $page */
/** @var app\models\SiteSettings $settings */
/** @var app\models\UserQuery $model */

use app\models\Service;
use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = $page ? $page->title : 'Contact & Consultation';
$this->params['meta_description'] = $page?->meta_description;
$serviceList = Service::titleList();
?>

<!-- Header -->
<section class="relative py-16 md:py-24 border-b border-white/5 bg-[#0C0C0C] overflow-hidden text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="kicker text-[11px] mb-3">GET IN TOUCH</span>
        <h1 class="text-3xl sm:text-5xl font-serif font-black text-white tracking-wide">
            Begin Your <span class="text-gold-gradient italic">Iconic Journey</span>
        </h1>
        <p class="text-sm sm:text-base text-gray-300 mt-4 max-w-xl mx-auto font-light leading-relaxed">
            <?= Html::encode($page ? $page->subtitle : 'Book a complimentary consultation. Santosh Kumar Maharana and our team respond within 24 hours.') ?>
        </p>
    </div>
</section>

<!-- Main Contact Section -->
<section class="py-16 md:py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">
            
            <!-- Left Contact Info & Studio Map (5 cols) -->
            <div class="lg:col-span-5 flex flex-col gap-8 reveal">
                
                <div class="glass rounded-3xl p-8 border border-white/10 flex flex-col gap-6">
                    <span class="kicker text-[11px]">STUDIO COORDINATES</span>
                    <h2 class="text-2xl font-serif font-bold text-white">
                        Direct Channels
                    </h2>

                    <div class="flex flex-col gap-5 text-sm">
                        <!-- Founder & Principal Designer -->
                        <div class="p-4 rounded-2xl bg-white/[0.02] border border-[#D4AF37]/30 flex flex-col gap-1">
                            <span class="text-[10px] uppercase font-bold tracking-widest text-[#D4AF37]">Studio Founder</span>
                            <span class="text-base font-serif font-bold text-white"><?= Html::encode($settings->owner_name) ?></span>
                            <span class="text-xs text-gray-400">Available for personal architectural consultations</span>
                        </div>

                        <!-- Phone Numbers -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-[#D4AF37] shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Phone Calls</span>
                                <a href="<?= $settings->telHref($settings->phone_primary) ?>" class="text-white hover:text-[#D4AF37] font-semibold text-base transition-colors">
                                    <?= Html::encode($settings->phone_primary) ?>
                                </a>
                                <?php if ($settings->phone_secondary): ?>
                                    <a href="<?= $settings->telHref($settings->phone_secondary) ?>" class="text-gray-300 hover:text-[#D4AF37] text-sm transition-colors">
                                        <?= Html::encode($settings->phone_secondary) ?>
                                    </a>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- WhatsApp -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-xl bg-[#25D366]/15 border border-[#25D366]/30 flex items-center justify-center text-emerald-400 shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Instant Chat</span>
                                <a href="<?= $settings->whatsappUrl('Hello Santosh Kumar Maharana & team, I am contacting you from your website contact page.') ?>" 
                                   target="_blank" rel="noopener noreferrer"
                                   class="text-emerald-400 hover:text-emerald-300 font-semibold transition-colors">
                                    +<?= $settings->getWhatsappDigits() ?> (WhatsApp)
                                </a>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-[#D4AF37] shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Email</span>
                                <a href="mailto:<?= Html::encode($settings->email) ?>" class="text-white hover:text-[#D4AF37] transition-colors break-all">
                                    <?= Html::encode($settings->email) ?>
                                </a>
                            </div>
                        </div>

                        <!-- Address -->
                        <div class="flex items-start gap-3.5">
                            <div class="w-9 h-9 rounded-xl bg-white/5 border border-white/10 flex items-center justify-center text-[#D4AF37] shrink-0 mt-0.5">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                </svg>
                            </div>
                            <div class="flex flex-col">
                                <span class="text-xs text-gray-400 font-medium uppercase tracking-wider">Studio Location</span>
                                <span class="text-white"><?= Html::encode($settings->address) ?></span>
                                <span class="text-xs text-gray-400 mt-0.5"><?= Html::encode($settings->working_hours) ?></span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Google Map Embed -->
                <?php if ($settings->map_embed_url): ?>
                <div class="rounded-3xl overflow-hidden gold-frame h-64 shadow-2xl relative">
                    <iframe src="<?= Html::encode($settings->map_embed_url) ?>" 
                            width="100%" height="100%" style="border:0;" allowfullscreen="" loading="lazy" 
                            referrerpolicy="no-referrer-when-downgrade"
                            title="ICONIC INTERIORS Location Map"
                            class="grayscale contrast-125 invert-[.85] hover:filter-none transition-all duration-700"></iframe>
                </div>
                <?php endif; ?>

            </div>

            <!-- Right ActiveForm (7 cols) -->
            <div class="lg:col-span-7 reveal">
                <div class="card-lux p-8 sm:p-12 border border-[#D4AF37]/30 shadow-2xl relative">
                    <div class="corner-marks"></div>

                    <div class="mb-8">
                        <span class="kicker text-[11px] mb-2">ONLINE CONSULTATION FORM</span>
                        <h2 class="text-2xl sm:text-3xl font-serif font-bold text-white tracking-wide">
                            Book a Design Meeting
                        </h2>
                        <p class="text-xs sm:text-sm text-gray-400 mt-1 font-light">
                            Fill out the form below. We will arrange a site visit or virtual consultation at your convenience.
                        </p>
                    </div>

                    <?php $form = ActiveForm::begin([
                        'id' => 'contact-inquiry-form',
                        'options' => ['class' => 'lux-form flex flex-col gap-4'],
                        'enableClientValidation' => true,
                    ]); ?>

                    <!-- Honeypot -->
                    <?= $form->field($model, 'website', ['template' => '{input}'])->textInput(['class' => 'hp-field', 'tabindex' => -1, 'autocomplete' => 'off']) ?>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <?= $form->field($model, 'full_name')->textInput(['placeholder' => 'e.g. Santosh Jena', 'class' => 'field-input'])->label('Your Full Name *') ?>
                        <?= $form->field($model, 'phone')->textInput(['placeholder' => '+91 9778646212', 'class' => 'field-input'])->label('Phone / WhatsApp *') ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <?= $form->field($model, 'email')->textInput(['placeholder' => 'you@example.com', 'class' => 'field-input'])->label('Email Address') ?>
                        <?= $form->field($model, 'preferred_location')->textInput(['placeholder' => 'e.g. Patia, Bhubaneswar', 'class' => 'field-input'])->label('Project Location') ?>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-5">
                        <?= $form->field($model, 'selected_service')->dropDownList(
                            ['' => 'Select a Service'] + $serviceList,
                            ['class' => 'field-input']
                        )->label('Primary Discipline') ?>

                        <?= $form->field($model, 'property_type')->dropDownList(
                            ['' => 'Select Property Type'] + array_map(fn($t) => $t['label'], Yii::$app->params['propertyTypes']),
                            ['class' => 'field-input']
                        )->label('Property Type') ?>
                    </div>

                    <?= $form->field($model, 'estimated_budget')->textInput(['placeholder' => 'e.g. ₹15 - 25 Lakhs', 'class' => 'field-input'])->label('Approximate Budget Range') ?>

                    <?= $form->field($model, 'message')->textarea(['rows' => 4, 'placeholder' => 'Describe your property, carpet area, aesthetic aspirations, or preferred timeline...', 'class' => 'field-input'])->label('Project Scope & Vision') ?>

                    <div class="pt-4">
                        <button type="submit" class="btn-gold w-full py-4 text-center text-xs font-bold tracking-widest uppercase">
                            <span>Submit Consultation Request</span>
                            <svg class="w-4 h-4 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </button>
                    </div>

                    <?php ActiveForm::end(); ?>

                </div>
            </div>

        </div>

    </div>
</section>
