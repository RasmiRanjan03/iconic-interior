<?php

/** @var yii\web\View $this */
/** @var app\models\Project $project */
/** @var app\models\SiteSettings $settings */
/** @var app\models\Project[] $moreProjects */

use app\components\Media;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $project->title . ' — Case Study';
$this->params['meta_description'] = $project->summary;
$gallery = $project->getGallery();
?>

<!-- Header -->
<section class="relative py-16 md:py-24 border-b border-white/5 bg-[#0C0C0C]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <a href="<?= Url::to(['/portfolio/index']) ?>" class="inline-flex items-center gap-2 text-xs uppercase tracking-widest text-gray-400 hover:text-[#D4AF37] transition-colors mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
            <span>Back to Portfolio</span>
        </a>

        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-end">
            <div class="lg:col-span-8">
                <div class="flex items-center gap-3 mb-3">
                    <span class="px-3 py-1 rounded-full text-[10px] uppercase font-bold tracking-widest bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">
                        <?= Html::encode($project->category) ?>
                    </span>
                    <?php if ($project->completion_year): ?>
                        <span class="text-xs text-gray-400 font-mono"><?= $project->completion_year ?></span>
                    <?php endif; ?>
                </div>
                <h1 class="text-3xl sm:text-5xl font-serif font-black text-white tracking-wide">
                    <?= Html::encode($project->title) ?>
                </h1>
                <p class="text-base text-gray-300 mt-4 max-w-2xl font-light leading-relaxed">
                    <?= Html::encode($project->summary) ?>
                </p>
            </div>

            <!-- Project Metrics Strip -->
            <div class="lg:col-span-4 glass rounded-2xl p-5 border border-white/10 grid grid-cols-2 gap-4 text-xs">
                <div>
                    <span class="text-gray-400 uppercase tracking-wider block text-[10px]">Location</span>
                    <span class="font-bold text-white"><?= Html::encode($project->location ?: 'Bhubaneswar') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase tracking-wider block text-[10px]">Carpet Area</span>
                    <span class="font-bold text-white"><?= $project->carpet_area ? number_format($project->carpet_area) . ' sq.ft.' : 'N/A' ?></span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase tracking-wider block text-[10px]">Budget Tier</span>
                    <span class="font-bold text-[#D4AF37]"><?= Html::encode($project->budget_tier ?: 'Luxury') ?></span>
                </div>
                <div>
                    <span class="text-gray-400 uppercase tracking-wider block text-[10px]">Delivery Time</span>
                    <span class="font-bold text-white"><?= Html::encode($project->completion_time ?: 'On Schedule') ?></span>
                </div>
            </div>
        </div>

    </div>
</section>

<!-- Before / After Interactive Comparison Stage -->
<?php if ($project->before_image): ?>
<section class="py-12 md:py-20 relative bg-[#090909]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="mb-6 flex items-center justify-between">
            <div>
                <span class="kicker text-[11px] mb-1">TRANSFORMATION</span>
                <h2 class="text-2xl font-serif font-bold text-white">Before & Handover Comparison</h2>
            </div>
            <span class="text-xs text-gray-400 hidden sm:inline">Drag the split handle to reveal the transformation</span>
        </div>

        <div class="stage aspect-[16/9] w-full gold-frame shadow-2xl relative select-none"
             x-data="{ pos: 50 }">
            
            <!-- Before Image -->
            <img src="<?= Media::url($project->before_image) ?>" 
                 alt="Before Renovation Shell" 
                 class="stage-img before-layer z-0">

            <!-- After Cover Image -->
            <img src="<?= $project->getCoverUrl() ?>" 
                 alt="<?= Html::encode($project->title) ?>" 
                 class="stage-img after-layer z-10 compare-after"
                 :style="'--pos: ' + pos + '%'">

            <!-- Handle -->
            <div class="compare-handle" :style="'--pos: ' + pos + '%'">
                <span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l-3 3 3 3m8-6l3 3-3 3"/>
                    </svg>
                </span>
            </div>

            <input type="range" min="0" max="100" x-model="pos" class="compare-range" aria-label="Before/After Slider">

            <div class="compare-label left-4 text-gray-300">Raw Site</div>
            <div class="compare-label right-4 text-[#D4AF37] border-[#D4AF37]/40">ICONIC Delivery</div>
        </div>

    </div>
</section>
<?php endif; ?>

<!-- Case Study Narrative: Client Brief & Challenge & Solution -->
<section class="py-16 md:py-24 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-12">
            
            <!-- Requirements -->
            <div class="card-lux p-8 border border-white/10">
                <span class="kicker text-[11px] mb-2">THE BRIEF</span>
                <h3 class="text-2xl font-serif font-bold text-white mb-4">Client Requirements</h3>
                <p class="text-sm text-gray-300 font-light leading-relaxed">
                    <?= nl2br(Html::encode($project->client_requirements ?: $project->summary)) ?>
                </p>
                <?php if ($project->client_name): ?>
                    <div class="pt-6 mt-6 border-t border-white/5 text-xs text-gray-400">
                        Commissioned by: <strong class="text-white"><?= Html::encode($project->client_name) ?></strong>
                    </div>
                <?php endif; ?>
            </div>

            <!-- Challenge & Solution -->
            <div class="card-lux p-8 border border-white/10">
                <span class="kicker text-[11px] mb-2">OUR METHOD</span>
                <h3 class="text-2xl font-serif font-bold text-white mb-4">Design Challenge & Execution</h3>
                <p class="text-sm text-gray-300 font-light leading-relaxed">
                    <?= nl2br(Html::encode($project->challenge_and_solution ?: 'Our team executed space planning, 3D modelling, custom modular joinery, and complete site finishing on a turnkey basis.')) ?>
                </p>
                <div class="pt-6 mt-6 border-t border-white/5 flex items-center justify-between text-xs">
                    <span class="text-gray-400">Principal Lead:</span>
                    <span class="text-[#D4AF37] font-semibold"><?= Html::encode($settings->owner_name) ?></span>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Gallery Showcase -->
<?php if (!empty($gallery)): ?>
<section class="py-16 bg-[#0B0B0B] border-t border-white/5 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="mb-10">
            <span class="kicker text-[11px] mb-1">VISUAL ARCHIVE</span>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-white">Project Gallery</h2>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <?php foreach ($gallery as $img): ?>
                <div class="rounded-2xl overflow-hidden aspect-[16/10] gold-frame group">
                    <img src="<?= Media::url($img) ?>" 
                         alt="<?= Html::encode($project->title) ?> image"
                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Bottom CTA -->
<section class="py-16 border-t border-white/5 text-center">
    <div class="max-w-3xl mx-auto px-4">
        <h3 class="text-2xl sm:text-3xl font-serif font-bold text-white mb-4">
            Inspired by <?= Html::encode($project->title) ?>?
        </h3>
        <p class="text-sm text-gray-300 mb-8 font-light leading-relaxed">
            Let us design a home or workplace tailored to your lifestyle and architectural aspirations.
        </p>
        <div class="flex items-center justify-center gap-4">
            <button type="button" 
                    @click="$dispatch('open-inquiry-modal', { source: 'Project Case Study', service: '<?= Html::encode($project->service?->title ?: 'Residential Interiors') ?>', message: 'I was impressed by the <?= Html::encode($project->title) ?> project and would like to consult on my space.' })"
                    class="btn-gold btn-sm">
                <span>Book Similar Look</span>
            </button>
            <a href="<?= $settings->whatsappUrl("Hello Santosh Kumar Maharana, I loved your {$project->title} case study and want to discuss my home.") ?>" 
               target="_blank" rel="noopener noreferrer"
               class="btn-ghost btn-sm">
                <span>WhatsApp Us</span>
            </a>
        </div>
    </div>
</section>
