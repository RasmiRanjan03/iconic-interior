<?php

use app\models\SiteSettings;
use yii\helpers\Url;

$settings = SiteSettings::get();
$currentRoute = Yii::$app->controller ? (string) Yii::$app->controller->route : '';
$isHome = ($currentRoute === 'site/index');

$navItems = [
    ['label' => 'Home', 'url' => ['/site/index'], 'active' => $isHome],
    ['label' => 'About', 'url' => ['/site/about'], 'active' => ($currentRoute === 'site/about')],
    ['label' => 'Services', 'url' => ['/service/index'], 'active' => (str_starts_with($currentRoute, 'service/'))],
    ['label' => 'Portfolio', 'url' => ['/portfolio/index'], 'active' => (str_starts_with($currentRoute, 'portfolio/'))],
    ['label' => 'Shop', 'url' => ['/product/index'], 'active' => (str_starts_with($currentRoute, 'product/'))],
    ['label' => 'Cost Estimator', 'url' => ['/estimator/index'], 'active' => ($currentRoute === 'estimator/index')],
    ['label' => 'Contact', 'url' => ['/site/contact'], 'active' => ($currentRoute === 'site/contact')],
];
?>

<header class="site-header fixed top-0 left-0 right-0 z-50 transition-all duration-300 border-b border-white/5 py-3 md:py-4 px-4 sm:px-6 lg:px-8 bg-[#0A0A0A]/80 backdrop-blur-md"
        x-data="{ mobileOpen: false }">
    <div class="max-w-7xl mx-auto flex items-center justify-between gap-6">
        <!-- Logo -->
        <?= $this->render('_logo', ['size' => 'md', 'showTagline' => true]) ?>

        <!-- Desktop Navigation -->
        <nav class="hidden lg:flex items-center gap-7 xl:gap-9">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= Url::to($item['url']) ?>" 
                   class="nav-link <?= $item['active'] ? 'active text-[#D4AF37]' : '' ?>">
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </nav>

        <!-- Right Quick Actions -->
        <div class="hidden sm:flex items-center gap-4">
            <!-- Phone pill -->
            <a href="<?= SiteSettings::telHref($settings->phone_primary) ?>" 
               class="hidden xl:inline-flex items-center gap-2 text-xs font-semibold tracking-wider text-gray-300 hover:text-[#D4AF37] transition-colors py-2 px-3.5 rounded-full border border-white/10 hover:border-[#D4AF37]/50 bg-white/[0.02]">
                <svg class="w-3.5 h-3.5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span><?= $settings->phone_primary ?></span>
            </a>

            <!-- Book Consultation CTA Button -->
            <button type="button"
                    @click="$dispatch('open-inquiry-modal', { source: 'Header Consultation Button', message: 'Hello, I would like to book an interior consultation with ICONIC INTERIORS.' })"
                    class="btn-gold btn-sm">
                <span>Book Consultation</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </button>
        </div>

        <!-- Mobile Menu Trigger -->
        <button type="button" 
                @click="mobileOpen = !mobileOpen"
                class="lg:hidden p-2 rounded-lg border border-white/10 text-gray-300 hover:text-[#D4AF37] focus:outline-none"
                aria-label="Toggle navigation menu">
            <svg x-show="!mobileOpen" class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16m-7 6h7"/>
            </svg>
            <svg x-show="mobileOpen" x-cloak class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
            </svg>
        </button>
    </div>

    <!-- Mobile Navigation Drawer -->
    <div x-show="mobileOpen" 
         x-cloak
         x-transition:enter="transition ease-out duration-300"
         x-transition:enter-start="opacity-0 -translate-y-4"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-200"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-4"
         class="lg:hidden absolute top-full left-0 right-0 bg-[#0E0E0E] border-b border-white/10 shadow-2xl p-6 flex flex-col gap-4">
        
        <div class="flex flex-col gap-2">
            <?php foreach ($navItems as $item): ?>
                <a href="<?= Url::to($item['url']) ?>" 
                   @click="mobileOpen = false"
                   class="px-4 py-3 rounded-lg text-sm font-semibold tracking-wider uppercase transition-colors <?= $item['active'] ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30' : 'text-gray-300 hover:bg-white/5 hover:text-white' ?>">
                    <?= $item['label'] ?>
                </a>
            <?php endforeach; ?>
        </div>

        <div class="pt-4 border-t border-white/10 flex flex-col gap-3">
            <a href="<?= SiteSettings::telHref($settings->phone_primary) ?>" 
               class="flex items-center justify-center gap-2 py-3 rounded-lg border border-white/10 text-sm font-medium text-gray-200">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                </svg>
                <span>Call <?= $settings->phone_primary ?></span>
            </a>
            
            <button type="button"
                    @click="mobileOpen = false; $dispatch('open-inquiry-modal', { source: 'Mobile Menu CTA', message: 'Hello, I would like to book an interior consultation with ICONIC INTERIORS.' })"
                    class="btn-gold w-full text-center py-3">
                Book Consultation
            </button>
        </div>
    </div>
</header>
