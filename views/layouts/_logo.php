<?php
/** @var string|null $class */
/** @var bool $showTagline */
/** @var string $size 'sm' | 'md' | 'lg' | 'xl' */
/** @var string $variant 'compact' | 'full' */

use yii\helpers\Html;
use yii\helpers\Url;

$class = $class ?? '';
$showTagline = $showTagline ?? true;
$size = $size ?? 'md';
$variant = $variant ?? 'compact';

$iconSizes = [
    'sm' => 'w-9 h-9',
    'md' => 'w-11 h-11',
    'lg' => 'w-14 h-14',
    'xl' => 'w-20 h-20',
];
$titleSizes = [
    'sm' => 'text-base tracking-[0.22em]',
    'md' => 'text-xl tracking-[0.26em]',
    'lg' => 'text-2xl md:text-3xl tracking-[0.28em]',
    'xl' => 'text-3xl md:text-4xl tracking-[0.3em]',
];
$taglineSizes = [
    'sm' => 'text-[9px] tracking-[0.28em]',
    'md' => 'text-[10px] tracking-[0.34em]',
    'lg' => 'text-xs tracking-[0.38em]',
    'xl' => 'text-xs md:text-sm tracking-[0.42em]',
];
?>
<a href="<?= Url::to(['/site/index']) ?>" class="group flex items-center gap-3.5 select-none <?= Html::encode($class) ?>" title="ICONIC INTERIORS">
    <!-- Luxury 3D Gold Brand Emblem -->
    <div class="relative shrink-0 <?= $iconSizes[$size] ?? 'w-11 h-11' ?> rounded-xl bg-gradient-to-br from-[#2a2a2a] via-[#161616] to-[#0a0a0a] p-[1px] shadow-lg shadow-black/80 group-hover:shadow-[0_0_24px_rgba(212,175,55,0.4)] transition-all duration-500">
        <div class="w-full h-full rounded-[11px] bg-[#0A0A0A] flex items-center justify-center border border-[#D4AF37]/30 group-hover:border-[#D4AF37]/60 relative overflow-hidden transition-colors duration-500">
            <!-- Subtle Gold Sheen Ambient on hover -->
            <div class="absolute -top-10 -right-10 w-20 h-20 bg-[#D4AF37]/15 rounded-full blur-xl pointer-events-none group-hover:bg-[#D4AF37]/30 transition-all duration-700"></div>
            
            <img src="<?= Yii::getAlias('@web/images/logo-mark.png') ?>" 
                 alt="ICONIC INTERIORS Emblem" 
                 class="w-full h-full object-cover object-center relative z-10 transition-transform duration-500 group-hover:scale-105"
                 loading="eager" />
        </div>
    </div>

    <!-- Brand Typography: Dual-Tone "N" in ICONIC + Tagline -->
    <div class="flex flex-col">
        <span class="font-serif font-black uppercase text-white <?= $titleSizes[$size] ?? 'text-xl' ?> leading-tight flex items-baseline">
            <span>ICO</span><span class="dual-n font-black">N</span><span>IC</span>
            <span class="ml-1.5 font-light text-[#D4AF37] opacity-90">INTERIORS</span>
        </span>
        <?php if ($showTagline): ?>
            <span class="font-sans uppercase text-[#C5A059] font-medium <?= $taglineSizes[$size] ?? 'text-[10px]' ?> mt-0.5 opacity-90 group-hover:text-white transition-colors duration-300">
                DESIGN • CREATE • INSPIRE
            </span>
        <?php endif; ?>
    </div>
</a>

