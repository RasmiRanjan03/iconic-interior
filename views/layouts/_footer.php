<?php

use app\models\Service;
use app\models\SiteSettings;
use yii\helpers\Html;
use yii\helpers\Url;

$settings = SiteSettings::get();
$services = Service::active()->limit(6)->all();
?>

<footer class="relative bg-[#080808] border-t border-white/5 pt-16 md:pt-24 pb-12 overflow-hidden text-gray-400">
    <!-- Ambient Gold Glow -->
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-3/4 h-24 bg-gradient-to-b from-[#D4AF37]/5 to-transparent blur-2xl pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-12 gap-12 lg:gap-8 mb-16">
            <!-- Brand Column (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-6">
                <?= $this->render('_logo', ['size' => 'lg', 'showTagline' => true]) ?>
                <p class="text-sm leading-relaxed text-gray-400 mt-2 max-w-sm">
                    <?= Html::encode($settings->footer_text) ?>
                </p>
                <div class="pt-2 flex flex-col gap-1 text-xs text-gray-400">
                    <span class="text-gray-300 font-semibold uppercase tracking-wider text-[11px] text-[#D4AF37]">Design Studio Led By</span>
                    <span class="text-white text-sm font-serif font-bold"><?= Html::encode($settings->owner_name) ?></span>
                    <span class="text-gray-400">Principal Designer & Founder</span>
                </div>
            </div>

            <!-- Quick Links (2 cols) -->
            <div class="lg:col-span-2">
                <h4 class="text-xs font-bold tracking-[0.24em] uppercase text-white mb-6 border-b border-[#D4AF37]/20 pb-2 inline-block">
                    Explore
                </h4>
                <ul class="flex flex-col gap-3 text-sm">
                    <li><a href="<?= Url::to(['/site/index']) ?>" class="hover:text-[#D4AF37] transition-colors">Home Showcase</a></li>
                    <li><a href="<?= Url::to(['/site/about']) ?>" class="hover:text-[#D4AF37] transition-colors">Our Philosophy</a></li>
                    <li><a href="<?= Url::to(['/service/index']) ?>" class="hover:text-[#D4AF37] transition-colors">Signature Services</a></li>
                    <li><a href="<?= Url::to(['/portfolio/index']) ?>" class="hover:text-[#D4AF37] transition-colors">Portfolio Gallery</a></li>
                    <li><a href="<?= Url::to(['/estimator/index']) ?>" class="hover:text-[#D4AF37] transition-colors">Cost Estimator</a></li>
                    <li><a href="<?= Url::to(['/site/contact']) ?>" class="hover:text-[#D4AF37] transition-colors">Consultation</a></li>
                </ul>
            </div>

            <!-- Signature Services (3 cols) -->
            <div class="lg:col-span-3">
                <h4 class="text-xs font-bold tracking-[0.24em] uppercase text-white mb-6 border-b border-[#D4AF37]/20 pb-2 inline-block">
                    Services
                </h4>
                <ul class="flex flex-col gap-3 text-sm">
                    <?php foreach ($services as $svc): ?>
                        <li>
                            <a href="<?= Url::to(['/service/view', 'slug' => $svc->slug]) ?>" class="hover:text-[#D4AF37] transition-colors flex items-center gap-2">
                                <span class="text-[#D4AF37] text-xs">◆</span>
                                <span><?= Html::encode($svc->title) ?></span>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <!-- Contact & Studio Info (3 cols) -->
            <div class="lg:col-span-3 flex flex-col gap-4">
                <h4 class="text-xs font-bold tracking-[0.24em] uppercase text-white mb-2 border-b border-[#D4AF37]/20 pb-2 inline-block">
                    Studio & Reach
                </h4>
                
                <div class="text-sm flex flex-col gap-2.5">
                    <div class="flex items-start gap-3">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                        </svg>
                        <span><?= Html::encode($settings->address) ?></span>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <div class="flex flex-col">
                            <a href="<?= SiteSettings::telHref($settings->phone_primary) ?>" class="text-white hover:text-[#D4AF37] font-semibold transition-colors">
                                <?= Html::encode($settings->phone_primary) ?>
                            </a>
                            <?php if ($settings->phone_secondary): ?>
                                <a href="<?= SiteSettings::telHref($settings->phone_secondary) ?>" class="text-gray-300 hover:text-[#D4AF37] text-xs transition-colors">
                                    <?= Html::encode($settings->phone_secondary) ?>
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        <a href="mailto:<?= Html::encode($settings->email) ?>" class="hover:text-[#D4AF37] transition-colors text-xs break-all">
                            <?= Html::encode($settings->email) ?>
                        </a>
                    </div>

                    <div class="flex items-center gap-3">
                        <svg class="w-4 h-4 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <span class="text-xs"><?= Html::encode($settings->working_hours) ?></span>
                    </div>
                </div>

                <!-- Direct WhatsApp button -->
                <div class="pt-2">
                    <a href="<?= $settings->whatsappUrl('Hello Santosh Kumar Maharana & team, I would like to enquire about interior design services.') ?>" 
                       target="_blank" rel="noopener noreferrer"
                       class="inline-flex items-center justify-center gap-2.5 px-4 py-2.5 rounded-xl bg-[#25D366]/15 hover:bg-[#25D366]/25 border border-[#25D366]/40 text-emerald-400 text-xs font-semibold tracking-wider uppercase transition-all duration-300 w-full text-center">
                        <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Chat on WhatsApp</span>
                    </a>
                </div>
            </div>
        </div>

        <!-- Bottom Copyright & Admin link -->
        <div class="pt-8 border-t border-white/5 flex flex-col sm:flex-row items-center justify-between gap-4 text-xs text-gray-400">
            <div>
                © <?= date('Y') ?> <strong class="text-gray-300 font-semibold">ICONIC INTERIORS</strong>. All rights reserved. 
                <span class="hidden sm:inline">|</span> 
                <span class="text-[#D4AF37]/90 font-serif font-medium">DESIGN • CREATE • INSPIRE</span>
            </div>
            <div class="flex items-center gap-6">
                <a href="<?= Url::to(['/admin/default/index']) ?>" class="hover:text-[#D4AF37] transition-colors flex items-center gap-1.5 opacity-70 hover:opacity-100">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <span>Admin CMS</span>
                </a>
                <span>Founder: <?= Html::encode($settings->owner_name) ?></span>
            </div>
        </div>
    </div>
</footer>
