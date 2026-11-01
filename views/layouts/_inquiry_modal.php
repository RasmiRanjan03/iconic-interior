<?php

use app\models\Service;
use yii\helpers\Html;
use yii\helpers\Url;

$serviceList = Service::titleList();
?>

<div x-data="inquiryModal()" 
     x-show="open" 
     x-cloak
     class="fixed inset-0 z-[100] flex items-center justify-center p-4 sm:p-6"
     role="dialog" aria-modal="true">
    
    <!-- Backdrop -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0"
         x-transition:enter-end="opacity-100"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100"
         x-transition:leave-end="opacity-0"
         @click="close()"
         class="fixed inset-0 bg-black/80 backdrop-blur-md"></div>

    <!-- Modal Card -->
    <div x-show="open"
         x-transition:enter="ease-out duration-300"
         x-transition:enter-start="opacity-0 scale-95 translate-y-4"
         x-transition:enter-end="opacity-100 scale-100 translate-y-0"
         x-transition:leave="ease-in duration-200"
         x-transition:leave-start="opacity-100 scale-100 translate-y-0"
         x-transition:leave-end="opacity-0 scale-95 translate-y-4"
         class="relative w-full max-w-xl bg-gradient-to-b from-[#181818] to-[#101010] border border-[#D4AF37]/30 rounded-3xl shadow-2xl p-6 sm:p-8 z-10 max-h-[90vh] overflow-y-auto">
        
        <!-- Close Button -->
        <button type="button" 
                @click="close()"
                class="absolute top-5 right-5 p-2 rounded-full text-gray-400 hover:text-white hover:bg-white/10 transition-colors"
                aria-label="Close dialog">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>

        <!-- Header -->
        <div class="mb-6">
            <span class="kicker text-[11px] mb-1">ICONIC CONSULTATION</span>
            <h3 class="text-2xl sm:text-3xl font-serif font-bold text-white tracking-wide">
                Book a Design Session
            </h3>
            <p class="text-xs sm:text-sm text-gray-400 mt-1">
                Tell us about your space. Santosh Kumar Maharana & our design team will connect within 24 hours.
            </p>
        </div>

        <!-- Form -->
        <form action="<?= Url::to(['/inquiry/submit']) ?>" method="post" class="lux-form flex flex-col gap-4">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->csrfToken) ?>
            <input type="hidden" name="UserQuery[query_source]" :value="source">
            <input type="hidden" name="UserQuery[selected_hero_style]" :value="heroStyle">
            <input type="hidden" name="UserQuery[property_type]" :value="propertyType">
            <input type="hidden" name="UserQuery[carpet_area_sqft]" :value="carpetArea">
            <input type="hidden" name="UserQuery[finish_tier]" :value="finishTier">
            <input type="hidden" name="UserQuery[estimated_budget]" :value="estimatedBudget">

            <!-- Honeypot -->
            <input type="text" name="UserQuery[website]" class="hp-field" tabindex="-1" autocomplete="off">

            <!-- Context Badge (when triggered from hero style or service) -->
            <div x-show="heroStyle || service" class="p-3 rounded-xl bg-[#D4AF37]/10 border border-[#D4AF37]/25 text-xs text-amber-200 flex items-center gap-2">
                <span class="text-[#D4AF37] text-sm">✦</span>
                <span x-text="heroStyle ? 'Selected Style: ' + heroStyle : 'Selected Service: ' + service"></span>
            </div>

            <!-- Name & Phone -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="lux-label">Your Name *</label>
                    <input type="text" name="UserQuery[full_name]" required placeholder="e.g. Ananya Mohapatra" class="field-input">
                </div>
                <div>
                    <label class="lux-label">Phone / WhatsApp *</label>
                    <input type="tel" name="UserQuery[phone]" required placeholder="+91 98765 43210" class="field-input">
                </div>
            </div>

            <!-- Email & Location -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div>
                    <label class="lux-label">Email Address</label>
                    <input type="email" name="UserQuery[email]" placeholder="you@domain.com" class="field-input">
                </div>
                <div>
                    <label class="lux-label">Project Location</label>
                    <input type="text" name="UserQuery[preferred_location]" :value="preferredLocation" placeholder="e.g. Patia, Bhubaneswar" class="field-input">
                </div>
            </div>

            <!-- Service Selection -->
            <div>
                <label class="lux-label">Primary Service</label>
                <select name="UserQuery[selected_service]" x-model="service" class="field-input">
                    <option value="">Select a Service</option>
                    <?php foreach ($serviceList as $title): ?>
                        <option value="<?= Html::encode($title) ?>"><?= Html::encode($title) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Message -->
            <div>
                <label class="lux-label">Project Scope & Notes</label>
                <textarea name="UserQuery[message]" x-model="message" rows="3" placeholder="Tell us about the property, timeline, or design style you love..." class="field-input"></textarea>
            </div>

            <!-- Submit Button -->
            <div class="pt-2">
                <button type="submit" class="btn-gold w-full py-3.5 text-center text-sm font-bold tracking-widest">
                    <span>Submit & Request Site Visit</span>
                    <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                    </svg>
                </button>
            </div>

            <p class="text-[11px] text-center text-gray-400 mt-1">
                🔒 Your details are kept strictly private. Zero spam policy.
            </p>
        </form>
    </div>
</div>
