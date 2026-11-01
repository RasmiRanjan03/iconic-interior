<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage|null $page */
/** @var app\models\SiteSettings $settings */
/** @var array $extra */

use app\components\Media;
use app\models\SiteSettings;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $page ? $page->title : 'About Us';
$this->params['meta_description'] = $page?->meta_description;

$philosophy = $extra['philosophy'] ?? [];
$founder = $extra['founder'] ?? [
    'name' => 'Santosh Kumar Maharana',
    'role' => 'Founder & Principal Designer',
    'bio' => 'With over a decade of hands-on experience across luxury residences and commercial fit-outs, Santosh leads every ICONIC project personally.',
    'quote' => 'A truly iconic interior is not decorated — it is composed, like music.',
];
$milestones = $extra['milestones'] ?? [];
$workflow = $extra['workflow'] ?? [];
$stats = $extra['stats'] ?? [];
?>

<!-- Hero Header Banner -->
<section class="relative py-20 md:py-32 overflow-hidden border-b border-white/5">
    <div class="absolute inset-0 z-0">
        <img src="<?= $page ? $page->getBannerUrl() : Media::url('uploads/seed/neoclassical_bedroom.jpg') ?>" 
             alt="About ICONIC INTERIORS" 
             class="w-full h-full object-cover opacity-25 filter brightness-50">
        <div class="absolute inset-0 bg-gradient-to-t from-[#0A0A0A] via-[#0A0A0A]/85 to-transparent"></div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10 text-center">
        <span class="kicker text-[11px] mb-3">WHO WE ARE</span>
        <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-black text-white tracking-wide max-w-4xl mx-auto">
            The Art of <span class="text-gold-gradient italic">Iconic Living</span>
        </h1>
        <p class="text-base sm:text-lg text-gray-300 mt-5 max-w-2xl mx-auto font-light leading-relaxed">
            <?= Html::encode($page ? $page->subtitle : 'A studio built on one belief: every space should tell the story of the people who live in it.') ?>
        </p>
    </div>
</section>

<!-- Story & Narrative -->
<section class="py-20 md:py-28 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-center">
            
            <div class="lg:col-span-6 flex flex-col gap-6 reveal">
                <span class="kicker text-[11px]">OUR ORIGIN</span>
                <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                    Crafting Spaces with <span class="text-gold-gradient italic">Purpose & Passion</span>
                </h2>
                <div class="prose-lux">
                    <?= $page && $page->content_body ? $page->content_body : '<p>ICONIC INTERIORS brings the precision of architecture and the soul of craftsmanship into every home and workplace we touch.</p>' ?>
                </div>

                <div class="pt-4 flex items-center gap-6">
                    <a href="<?= Url::to(['/portfolio/index']) ?>" class="btn-gold btn-sm">
                        <span>Explore Our Work</span>
                    </a>
                    <a href="<?= Url::to(['/site/contact']) ?>" class="btn-ghost btn-sm">
                        <span>Get In Touch</span>
                    </a>
                </div>
            </div>

            <div class="lg:col-span-6 reveal">
                <div class="relative gold-frame rounded-3xl overflow-hidden shadow-2xl">
                    <img src="<?= Media::url('uploads/seed/space_planning.jpg') ?>" 
                         alt="Architectural space planning and materials" 
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-gradient-to-t from-black/80 via-transparent to-transparent"></div>
                    <div class="absolute bottom-6 left-6 right-6 p-4 rounded-2xl bg-black/70 backdrop-blur-md border border-white/10 text-xs text-gray-300">
                        <span class="text-[#D4AF37] font-bold uppercase tracking-wider block mb-1">Architectural Discipline</span>
                        <span>Every millimetre planned in 3D before a single tool touches your site.</span>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>

<!-- Brand Philosophy: Design • Create • Inspire -->
<section class="py-20 bg-[#0E0E0E] border-y border-white/5 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="kicker text-[11px] mb-2">OUR CREED</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                DESIGN • CREATE • <span class="text-gold-gradient italic">INSPIRE</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($philosophy as $idx => $phi): ?>
                <div class="card-lux p-8 flex flex-col justify-between reveal">
                    <div>
                        <span class="font-serif text-3xl font-bold text-gold-gradient block mb-4">
                            0<?= $idx + 1 ?>
                        </span>
                        <h3 class="text-2xl font-serif font-bold text-white mb-3">
                            <?= Html::encode($phi['title']) ?>
                        </h3>
                        <p class="text-sm text-gray-400 font-light leading-relaxed">
                            <?= Html::encode($phi['text']) ?>
                        </p>
                    </div>
                    <div class="pt-6 mt-6 border-t border-white/5 flex items-center text-xs text-[#D4AF37] font-semibold tracking-wider uppercase">
                        <span>Core Pillar</span>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- Founder Profile: Santosh Kumar Maharana -->
<section class="py-20 md:py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass rounded-3xl p-8 sm:p-14 border border-[#D4AF37]/30 shadow-2xl relative overflow-hidden reveal">
            <div class="corner-marks"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-10 items-center">
                <!-- Founder Photo / Monogram Portrait -->
                <div class="lg:col-span-5 flex flex-col items-center">
                    <div class="relative w-64 h-64 sm:w-80 sm:h-80 rounded-3xl overflow-hidden gold-frame shadow-2xl">
                        <img src="<?= Media::url('uploads/seed/custom_furniture.jpg') ?>" 
                             alt="Founder Santosh Kumar Maharana Atelier" 
                             class="w-full h-full object-cover">
                        <div class="absolute inset-0 bg-gradient-to-t from-black/90 via-black/30 to-transparent"></div>
                        <div class="absolute bottom-4 left-4 right-4 text-center">
                            <span class="text-xs font-serif font-bold text-white block"><?= Html::encode($founder['name']) ?></span>
                            <span class="text-[10px] uppercase tracking-widest text-[#D4AF37]"><?= Html::encode($founder['role']) ?></span>
                        </div>
                    </div>
                </div>

                <!-- Founder Bio & Quote -->
                <div class="lg:col-span-7 flex flex-col gap-6">
                    <span class="kicker text-[11px]">THE VISIONARY</span>
                    <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                        <?= Html::encode($founder['name']) ?>
                    </h2>
                    <span class="text-xs uppercase tracking-[0.2em] text-[#D4AF37] font-semibold -mt-3">
                        <?= Html::encode($founder['role']) ?>
                    </span>

                    <blockquote class="p-4 rounded-2xl bg-white/[0.02] border-l-2 border-[#D4AF37] italic font-serif text-base sm:text-lg text-gray-200">
                        “<?= Html::encode($founder['quote']) ?>”
                    </blockquote>

                    <p class="text-sm text-gray-300 leading-relaxed font-light">
                        <?= Html::encode($founder['bio']) ?>
                    </p>

                    <!-- Contact with Founder -->
                    <div class="pt-4 flex flex-wrap items-center gap-4">
                        <a href="<?= $settings->whatsappUrl('Hello Santosh Kumar Maharana, I would like to schedule a direct discussion regarding my project.') ?>" 
                           target="_blank" rel="noopener noreferrer"
                           class="btn-gold btn-sm">
                            <span>Direct WhatsApp with Santosh</span>
                        </a>
                        <a href="<?= SiteSettings::telHref($settings->phone_primary) ?>" class="btn-ghost btn-sm">
                            <span>Call <?= $settings->phone_primary ?></span>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Workflow Timeline (6 Steps) -->
<section class="py-20 bg-[#080808] border-t border-white/5 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="kicker text-[11px] mb-2">OUR BLUEPRINT</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                How Your Dream Space <span class="text-gold-gradient italic">Comes to Life</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($workflow as $wf): ?>
                <div class="p-6 rounded-2xl bg-white/[0.015] border border-white/5 hover:border-[#D4AF37]/40 transition-all duration-300 flex flex-col justify-between reveal">
                    <div>
                        <div class="flex items-center justify-between mb-4">
                            <span class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#222] to-[#111] border border-[#D4AF37]/40 flex items-center justify-center font-serif text-sm font-bold text-[#D4AF37]">
                                <?= Html::encode($wf['step']) ?>
                            </span>
                            <span class="text-[10px] uppercase tracking-widest text-gray-300">Phase</span>
                        </div>
                        <h3 class="text-lg font-serif font-bold text-white mb-2">
                            <?= Html::encode($wf['title']) ?>
                        </h3>
                        <p class="text-xs text-gray-400 font-light leading-relaxed">
                            <?= Html::encode($wf['text']) ?>
                        </p>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- Company Milestones -->
<?php if (!empty($milestones)): ?>
<section class="py-20 md:py-28 relative">
    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="text-center mb-16 reveal">
            <span class="kicker text-[11px] mb-2">MILESTONES</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                The Journey of <span class="text-gold-gradient italic">Excellence</span>
            </h2>
        </div>

        <div class="relative border-l border-[#D4AF37]/30 ml-4 sm:ml-32 pl-6 sm:pl-10 space-y-12">
            <?php foreach ($milestones as $ms): ?>
                <div class="relative reveal">
                    <!-- Dot -->
                    <span class="absolute -left-[31px] sm:-left-[47px] top-1.5 w-4 h-4 rounded-full bg-[#D4AF37] border-4 border-[#0A0A0A] shadow-md shadow-[#D4AF37]/40"></span>
                    
                    <span class="sm:absolute sm:-left-32 sm:text-right font-mono text-sm font-bold text-[#D4AF37] block sm:w-20 mb-1 sm:mb-0">
                        <?= Html::encode($ms['year']) ?>
                    </span>
                    <h3 class="text-lg font-serif font-bold text-white">
                        <?= Html::encode($ms['title']) ?>
                    </h3>
                    <p class="text-xs text-gray-400 mt-1 font-light leading-relaxed">
                        <?= Html::encode($ms['text']) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>
