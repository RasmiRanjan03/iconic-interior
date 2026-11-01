<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage|null $page */
/** @var app\models\SiteSettings $settings */
/** @var string[] $categories */
/** @var app\models\Project[] $projects */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $page ? $page->title : 'Curated Portfolio of Luxury Interiors';
$this->params['meta_description'] = $page?->meta_description;
?>

<!-- Header -->
<section class="relative py-16 md:py-24 border-b border-white/5 bg-[#0C0C0C] overflow-hidden text-center">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <span class="kicker text-[11px] mb-3">BUILT MASTERPIECES</span>
        <h1 class="text-3xl sm:text-5xl font-serif font-black text-white tracking-wide">
            The Iconic <span class="text-gold-gradient italic">Portfolio</span>
        </h1>
        <p class="text-sm sm:text-base text-gray-300 mt-4 max-w-xl mx-auto font-light leading-relaxed">
            <?= Html::encode($page ? $page->subtitle : 'A curated selection of luxury homes, modular kitchens, bedrooms and workplaces brought to life.') ?>
        </p>
    </div>
</section>

<!-- Filterable Portfolio Gallery (Alpine.js) -->
<section class="py-16 md:py-28 relative"
         x-data="{ currentFilter: 'all' }">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <!-- Category Filter Pills -->
        <div class="flex items-center justify-center flex-wrap gap-2 mb-12">
            <button type="button" 
                    @click="currentFilter = 'all'"
                    class="chip"
                    :class="currentFilter === 'all' ? 'is-active' : ''">
                All Projects
            </button>
            <?php foreach ($categories as $cat): ?>
                <button type="button" 
                        @click="currentFilter = '<?= Html::encode($cat) ?>'"
                        class="chip"
                        :class="currentFilter === '<?= Html::encode($cat) ?>' ? 'is-active' : ''">
                    <?= Html::encode($cat) ?>
                </button>
            <?php endforeach; ?>
        </div>

        <!-- Project Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($projects as $proj): ?>
                <div class="card-lux group overflow-hidden flex flex-col justify-between filter-item"
                     x-show="currentFilter === 'all' || currentFilter === '<?= Html::encode($proj->category) ?>'"
                     x-transition:enter="transition ease-out duration-300"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100">
                    
                    <a href="<?= Url::to(['/portfolio/view', 'slug' => $proj->slug]) ?>" class="block">
                        <div class="aspect-[16/10] overflow-hidden relative rounded-t-[1.4rem]">
                            <img src="<?= $proj->getCoverUrl() ?>" 
                                 alt="<?= Html::encode($proj->title) ?>"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105">
                            
                            <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                            
                            <span class="absolute top-4 left-4 px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-widest bg-black/75 backdrop-blur-md text-[#D4AF37] border border-[#D4AF37]/30">
                                <?= Html::encode($proj->category) ?>
                            </span>

                            <div class="absolute bottom-4 left-4 right-4 flex items-center justify-between text-xs text-gray-300">
                                <span><?= Html::encode($proj->location) ?></span>
                                <?php if ($proj->carpet_area): ?>
                                    <span class="font-mono"><?= number_format($proj->carpet_area) ?> sq.ft.</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </a>

                    <div class="p-6 flex flex-col justify-between flex-1">
                        <div>
                            <h2 class="text-xl font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-2">
                                <a href="<?= Url::to(['/portfolio/view', 'slug' => $proj->slug]) ?>">
                                    <?= Html::encode($proj->title) ?>
                                </a>
                            </h2>
                            <p class="text-xs text-gray-400 font-light line-clamp-2 leading-relaxed">
                                <?= Html::encode($proj->summary) ?>
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between text-xs">
                            <span class="text-[#D4AF37] font-semibold"><?= Html::encode($proj->budget_tier) ?></span>
                            <a href="<?= Url::to(['/portfolio/view', 'slug' => $proj->slug]) ?>" class="text-gray-300 hover:text-white flex items-center gap-1 font-medium">
                                <span>Case Study</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                                </svg>
                            </a>
                        </div>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
