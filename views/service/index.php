<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage|null $page */
/** @var app\models\SiteSettings $settings */
/** @var app\models\Service[] $services */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $page ? $page->title : 'Signature Interior Services';
$this->params['meta_description'] = $page?->meta_description;
?>

<!-- Header -->
<section class="relative py-16 md:py-24 border-b border-white/5 bg-[#0C0C0C] overflow-hidden text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="kicker text-[11px] mb-3">DISCIPLINES OF CRAFT</span>
        <h1 class="text-3xl sm:text-5xl font-serif font-black text-white tracking-wide">
            Signature Interior <span class="text-gold-gradient italic">Services</span>
        </h1>
        <p class="text-sm sm:text-base text-gray-300 mt-4 max-w-xl mx-auto font-light leading-relaxed">
            <?= Html::encode($page && $page->subtitle ? $page->subtitle : 'From concept to completion, we provide end-to-end interior design and execution services for homes, offices and commercial spaces.') ?>
        </p>
    </div>
</section>

<!-- Services Grid -->
<section class="py-20 md:py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $svc): ?>
                <div class="card-lux group p-8 flex flex-col justify-between overflow-hidden relative reveal">
                    
                    <div class="aspect-[16/10] rounded-2xl overflow-hidden mb-6 relative">
                        <img src="<?= $svc->getImageUrl() ?>" 
                             alt="<?= Html::encode($svc->title) ?>"
                             class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                        <div class="absolute bottom-3 left-3 w-10 h-10 rounded-xl bg-black/80 backdrop-blur-md border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] p-2">
                            <?= $svc->icon_svg_or_class ?: '◆' ?>
                        </div>
                    </div>

                    <div>
                        <h2 class="text-2xl font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-3">
                            <a href="<?= Url::to(['/service/view', 'slug' => $svc->slug]) ?>">
                                <?= Html::encode($svc->title) ?>
                            </a>
                        </h2>

                        <p class="text-sm text-gray-400 font-light leading-relaxed mb-6">
                            <?= Html::encode($svc->short_description) ?>
                        </p>

                        <!-- Deliverables sneak peek -->
                        <div class="space-y-1.5 mb-6 text-xs text-gray-300">
                            <?php foreach (array_slice($svc->getDeliverables(), 0, 3) as $del): ?>
                                <div class="flex items-center gap-2">
                                    <span class="text-[#D4AF37]">✓</span>
                                    <span class="line-clamp-1"><?= Html::encode($del) ?></span>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </div>

                    <!-- Bottom Bar -->
                    <div class="pt-6 border-t border-white/5 flex items-center justify-between">
                        <div>
                            <span class="text-[10px] uppercase tracking-wider text-gray-300 block">Pricing</span>
                            <span class="text-xs font-semibold text-[#D4AF37]"><?= $svc->getPriceLabel() ?></span>
                        </div>
                        <a href="<?= Url::to(['/service/view', 'slug' => $svc->slug]) ?>" class="btn-ghost btn-sm">
                            <span>Explore Details</span>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
