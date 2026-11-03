<?php

/** @var yii\web\View $this */
/** @var app\models\CmsPage|null $page */
/** @var app\models\SiteSettings $settings */
/** @var app\models\HeroStyle[] $heroPresets */
/** @var array $heroPresetsJson */
/** @var app\models\Service[] $services */
/** @var app\models\Project[] $projects */
/** @var app\models\Testimonial[] $testimonials */

use app\components\Estimator;
use app\components\Media;
use yii\helpers\Html;
use yii\helpers\Json;
use yii\helpers\Url;

$this->title = $page ? $page->title : 'Luxury Interiors & Bespoke Architecture';
$this->params['meta_description'] = $page?->meta_description;
$stats = $page ? ($page->getExtra()['stats'] ?? []) : [];
$seqBaseUrl = Yii::getAlias('@web') . '/images/apartment-sequence';
?>

<!-- ═══ Full-Screen Animated Preloader (above everything, including header) ═══ -->
<div id="pre-hero-loader" class="phl">
    <!-- Floating gold particles -->
    <div class="phl-particles">
        <span class="phl-dot" style="--x:12%;--y:18%;--d:6s;--dl:0s"></span>
        <span class="phl-dot" style="--x:82%;--y:25%;--d:8s;--dl:1s"></span>
        <span class="phl-dot" style="--x:35%;--y:72%;--d:7s;--dl:0.5s"></span>
        <span class="phl-dot" style="--x:68%;--y:80%;--d:9s;--dl:2s"></span>
        <span class="phl-dot" style="--x:50%;--y:40%;--d:5s;--dl:1.5s"></span>
        <span class="phl-dot" style="--x:20%;--y:55%;--d:7.5s;--dl:0.8s"></span>
        <span class="phl-dot" style="--x:90%;--y:60%;--d:6.5s;--dl:2.5s"></span>
        <span class="phl-dot" style="--x:45%;--y:10%;--d:8.5s;--dl:1.2s"></span>
    </div>

    <!-- Radial glow backdrop -->
    <div class="phl-glow"></div>

    <!-- Centre content -->
    <div class="phl-center">
        <!-- Diamond icon -->
        <div class="phl-icon">
            <svg width="54" height="54" viewBox="0 0 54 54" fill="none">
                <rect x="27" y="3" width="34" height="34" rx="3" transform="rotate(45 27 3)"
                      stroke="url(#ico-grad)" stroke-width="1.2" fill="none"/>
                <rect x="27" y="10" width="24" height="24" rx="2" transform="rotate(45 27 10)"
                      stroke="url(#ico-grad)" stroke-width="0.7" fill="rgba(212,175,55,0.04)"/>
                <circle cx="27" cy="27" r="3.5" fill="#D4AF37"/>
                <circle cx="27" cy="27" r="6" stroke="#D4AF37" stroke-width="0.5" fill="none" opacity="0.35"/>
                <defs>
                    <linearGradient id="ico-grad" x1="0%" y1="0%" x2="100%" y2="100%">
                        <stop offset="0%" stop-color="#FBF5B7"/>
                        <stop offset="50%" stop-color="#D4AF37"/>
                        <stop offset="100%" stop-color="#AA771C"/>
                    </linearGradient>
                </defs>
            </svg>
        </div>

        <!-- Brand wordmark -->
        <h2 class="phl-title">
            <span class="phl-word">ICONIC</span>
            <span class="phl-word phl-word--2">INTERIORS</span>
        </h2>

        <!-- Animated gold underline -->
        <div class="phl-line"></div>

        <!-- Tagline -->
        <p class="phl-tagline">Crafting Timeless Spaces</p>
    </div>

    <!-- Corner accents -->
    <span class="phl-corner phl-corner--tl"></span>
    <span class="phl-corner phl-corner--tr"></span>
    <span class="phl-corner phl-corner--bl"></span>
    <span class="phl-corner phl-corner--br"></span>
</div>

<!-- =========================================================================
     FULL-SCREEN SCROLL-SCRUBBING IMAGE SEQUENCE SECTION (#pre-hero-scroll)
     ========================================================================= -->
<section id="pre-hero-scroll">
    <div class="pre-hero-sticky">
        <canvas id="pre-hero-canvas"></canvas>
        <div class="pre-hero-overlay"></div>

        <div class="pre-hero-content-wrap">
            <!-- Phase 1: 0% - 28% -->
            <div class="pre-hero-caption" id="ph-caption-1">
                <span class="pre-hero-badge">Curated Spatial Revelation</span>
                <h2 class="pre-hero-title">The Art of <span class="pre-hero-gold">Living</span></h2>
                <p class="pre-hero-sub">Scroll to initiate spatial transcendence</p>
            </div>

            <!-- Phase 2: 35% - 64% -->
            <div class="pre-hero-caption" id="ph-caption-2">
                <span class="pre-hero-badge">Master Craftsmanship</span>
                <h2 class="pre-hero-title">Form in <span class="pre-hero-gold">Perfection</span></h2>
                <p class="pre-hero-sub">Every contour sculpted with timeless architectural precision</p>
            </div>

            <!-- Phase 3: 70% - 94% -->
            <div class="pre-hero-caption" id="ph-caption-3">
                <span class="pre-hero-badge">Iconic Interiors</span>
                <h2 class="pre-hero-title">Welcome to <span class="pre-hero-gold">Grandeur</span></h2>
                <p class="pre-hero-sub">Step inside our bespoke sanctuary below</p>
            </div>
        </div>

        <!-- HUD: Progress, Frame Counter & Scroll Indicator -->
        <div class="pre-hero-hud">
            <div class="pre-hero-progress-track">
                <div class="pre-hero-progress-bar" id="pre-hero-bar"></div>
            </div>
            <div class="pre-hero-counter">
                <span id="pre-hero-frame-num">001</span> / 239
            </div>
            <button type="button" class="pre-hero-scroll-hint" id="pre-hero-scroll-btn">
                <span>Scroll to Unveil</span>
                <svg viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path d="M7 13l5 5 5-5M7 6l5 5 5-5" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"/>
                </svg>
            </button>
        </div>
    </div>
</section>

<style>
/* =========================================================================
   SCOPED STYLES: #pre-hero-scroll ONLY
   Strictly scoped to prevent any global interference
   ========================================================================= */
#pre-hero-scroll {
    position: relative;
    width: 100%;
    height: 450vh;
    background-color: #050505;
    margin: 0;
    padding: 0;
    box-sizing: border-box;
    z-index: 20;
}

#pre-hero-scroll *,
#pre-hero-scroll *::before,
#pre-hero-scroll *::after {
    box-sizing: border-box;
}

#pre-hero-scroll .pre-hero-sticky {
    position: sticky;
    top: 0;
    left: 0;
    width: 100%;
    height: 100vh;
    height: 100dvh;
    overflow: hidden;
    background: #050505;
    display: flex;
    align-items: center;
    justify-content: center;
}

#pre-hero-scroll #pre-hero-canvas {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    display: block;
    z-index: 1;
}

#pre-hero-scroll .pre-hero-overlay {
    position: absolute;
    inset: 0;
    background: radial-gradient(circle at center, rgba(5,5,5,0.1) 0%, rgba(5,5,5,0.6) 80%, rgba(5,5,5,0.92) 100%),
                linear-gradient(to bottom, rgba(5,5,5,0.5) 0%, transparent 20%, transparent 80%, rgba(5,5,5,0.9) 100%);
    pointer-events: none;
    z-index: 2;
}

#pre-hero-scroll .pre-hero-content-wrap {
    position: absolute;
    inset: 0;
    display: flex;
    align-items: center;
    justify-content: center;
    pointer-events: none;
    z-index: 3;
    padding: 2rem;
}

#pre-hero-scroll .pre-hero-caption {
    position: absolute;
    max-width: 820px;
    width: 90%;
    text-align: center;
    opacity: 0;
    transform: translateY(28px) scale(0.97);
    transition: opacity 0.5s cubic-bezier(0.16, 1, 0.3, 1), transform 0.5s cubic-bezier(0.16, 1, 0.3, 1);
    will-change: opacity, transform;
    padding: 2.25rem 2.5rem;
    isolation: isolate;
}

/* Scrim stays behind the caption content while softening bright frames. */
#pre-hero-scroll .pre-hero-caption::before {
    content: '';
    position: absolute;
    inset: -2rem -3rem;
    z-index: -1;

    filter: blur(14px);
    pointer-events: none;
}

#pre-hero-scroll .pre-hero-caption.is-active {
    opacity: 1;
    transform: translateY(0) scale(1);
}

#pre-hero-scroll .pre-hero-badge {
    display: inline-flex;
    align-items: center;
    gap: 0.5rem;
    padding: 0.35rem 1rem;
    border-radius: 9999px;
    background: rgba(10, 10, 10, 0.8);
    border: 1px solid rgba(212, 175, 55, 0.55);
    color: #F2CF63;
    font-size: 0.72rem;
    font-weight: 700;
    letter-spacing: 0.25em;
    text-transform: uppercase;
    margin-bottom: 1.25rem;
    backdrop-filter: blur(8px);
}

#pre-hero-scroll .pre-hero-badge::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background-color: #D4AF37;
    box-shadow: 0 0 8px #D4AF37;
}

#pre-hero-scroll .pre-hero-title {
    font-family: ui-serif, Georgia, Cambria, "Times New Roman", Times, serif;
    font-size: clamp(2.2rem, 5.2vw, 4.4rem);
    font-weight: 900;
    line-height: 1.15;
    letter-spacing: -0.02em;
    color: #FFFFFF;
    margin: 0 0 1rem 0;
    text-wrap: balance;
    text-shadow: 0 2px 6px rgba(0,0,0,0.9), 0 6px 28px rgba(0,0,0,0.75);
}

#pre-hero-scroll .pre-hero-gold {
    display: inline-block;
    padding: 0 0.12em 0.05em 0;
    background: linear-gradient(135deg, #FBF5B7 0%, #E8C547 50%, #FFB52E 100%);
    -webkit-background-clip: text;
    background-clip: text;
    -webkit-text-fill-color: transparent;
    color: transparent;
    text-shadow: none;
    font-style: italic;
    filter: drop-shadow(0 2px 4px rgba(0,0,0,0.85)) drop-shadow(0 0 18px rgba(0,0,0,0.5));
}

#pre-hero-scroll .pre-hero-sub {
    font-size: clamp(0.95rem, 1.8vw, 1.2rem);
    color: #FFFFFF;
    font-weight: 500;
    line-height: 1.6;
    margin: 0;
    text-wrap: balance;
    text-shadow: 0 1px 4px rgba(0,0,0,0.95), 0 3px 18px rgba(0,0,0,0.8);
}

#pre-hero-scroll .pre-hero-hud {
    position: absolute;
    bottom: 2rem;
    left: 0;
    right: 0;
    padding: 0 2.5rem;
    display: flex;
    align-items: center;
    justify-content: space-between;
    z-index: 4;
    pointer-events: none;
}

#pre-hero-scroll .pre-hero-progress-track {
    width: 140px;
    height: 2px;
    background: rgba(255, 255, 255, 0.15);
    border-radius: 2px;
    overflow: hidden;
}

#pre-hero-scroll .pre-hero-progress-bar {
    width: 0%;
    height: 100%;
    background: linear-gradient(90deg, #D4AF37, #FBF5B7);
    transition: width 0.08s ease-out;
}

#pre-hero-scroll .pre-hero-counter {
    font-family: monospace;
    font-size: 0.75rem;
    letter-spacing: 0.15em;
    color: #9CA3AF;
}

#pre-hero-scroll .pre-hero-counter span {
    color: #D4AF37;
    font-weight: 700;
}

#pre-hero-scroll .pre-hero-scroll-hint {
    pointer-events: auto;
    cursor: pointer;
    display: flex;
    align-items: center;
    gap: 0.6rem;
    font-size: 0.7rem;
    font-weight: 700;
    letter-spacing: 0.2em;
    text-transform: uppercase;
    color: #D4AF37;
    padding: 0.5rem 1.1rem;
    border-radius: 9999px;
    background: rgba(10, 10, 10, 0.65);
    border: 1px solid rgba(212, 175, 55, 0.3);
    backdrop-filter: blur(8px);
    transition: all 0.3s ease;
}

#pre-hero-scroll .pre-hero-scroll-hint:hover {
    background: rgba(212, 175, 55, 0.15);
    border-color: #D4AF37;
    transform: translateY(2px);
}

#pre-hero-scroll .pre-hero-scroll-hint svg {
    width: 14px;
    height: 14px;
    animation: preHeroBounce 1.8s infinite ease-in-out;
}

/* ══════════════════════════════════════════════════════════════════════
   FULL-SCREEN ANIMATED PRELOADER — ICONIC INTERIORS
   ══════════════════════════════════════════════════════════════════════ */
.phl {
    position: fixed;
    inset: 0;
    background: #040408;
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 99999;
    transition: opacity 1.1s cubic-bezier(0.16,1,0.3,1),
                visibility 1.1s cubic-bezier(0.16,1,0.3,1);
    overflow: hidden;
}
.phl.is-hidden {
    opacity: 0;
    visibility: hidden;
    pointer-events: none;
}

/* ── Floating gold particles ──────────────────────────────────────── */
.phl-particles { position: absolute; inset: 0; pointer-events: none; }
.phl-dot {
    position: absolute;
    left: var(--x); top: var(--y);
    width: 4px; height: 4px;
    border-radius: 50%;
    background: #D4AF37;
    opacity: 0;
    filter: blur(0.5px);
    animation: phlFloat var(--d) ease-in-out var(--dl) infinite;
}
@keyframes phlFloat {
    0%   { opacity: 0; transform: translateY(0)   scale(0.6); }
    15%  { opacity: 0.7; }
    50%  { opacity: 0.35; transform: translateY(-40px) scale(1); }
    85%  { opacity: 0.6; }
    100% { opacity: 0; transform: translateY(-80px) scale(0.4); }
}

/* ── Radial glow ──────────────────────────────────────────────────── */
.phl-glow {
    position: absolute;
    width: 600px; height: 600px;
    top: 50%; left: 50%;
    transform: translate(-50%,-50%);
    border-radius: 50%;
    background: radial-gradient(circle,
        rgba(212,175,55,0.10) 0%,
        rgba(212,175,55,0.04) 35%,
        transparent 70%);
    animation: phlGlowPulse 4s ease-in-out infinite alternate;
    pointer-events: none;
}
@keyframes phlGlowPulse {
    from { opacity: 0.6; transform: translate(-50%,-50%) scale(0.85); }
    to   { opacity: 1;   transform: translate(-50%,-50%) scale(1.15); }
}

/* ── Centre block ─────────────────────────────────────────────────── */
.phl-center {
    position: relative;
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0;
    z-index: 2;
}

/* Diamond icon */
.phl-icon {
    margin-bottom: 1.6rem;
    animation: phlIconIn 1s 0.2s cubic-bezier(0.16,1,0.3,1) both;
}
.phl-icon svg {
    animation: phlSpin 18s linear infinite;
    filter: drop-shadow(0 0 18px rgba(212,175,55,0.35));
}
@keyframes phlIconIn {
    from { opacity: 0; transform: scale(0.5) rotate(-90deg); }
    to   { opacity: 1; transform: scale(1)   rotate(0deg);   }
}
@keyframes phlSpin {
    to { transform: rotate(360deg); }
}

/* Brand title */
.phl-title {
    display: flex;
    flex-direction: column;
    align-items: center;
    gap: 0.15rem;
    margin: 0;
    line-height: 1.1;
}
.phl-word {
    display: block;
    font-family: ui-serif, Georgia, Cambria, 'Times New Roman', serif;
    font-weight: 900;
    text-transform: uppercase;
    letter-spacing: 0.35em;
    color: transparent;
    background: linear-gradient(135deg, #FBF5B7 0%, #D4AF37 40%, #AA771C 100%);
    -webkit-background-clip: text;
    -webkit-text-fill-color: transparent;
    background-clip: text;
    font-size: clamp(2.2rem, 6vw, 4rem);
    animation: phlWordIn 0.9s 0.5s cubic-bezier(0.16,1,0.3,1) both;
    filter: drop-shadow(0 2px 20px rgba(212,175,55,0.3));
}
.phl-word--2 {
    font-size: clamp(1rem, 2.8vw, 1.6rem);
    letter-spacing: 0.55em;
    font-weight: 600;
    animation-delay: 0.75s;
    filter: drop-shadow(0 1px 12px rgba(212,175,55,0.2));
}
@keyframes phlWordIn {
    from { opacity: 0; transform: translateY(30px) scale(0.92); letter-spacing: 0.8em; }
    to   { opacity: 1; transform: translateY(0)    scale(1);    }
}

/* Gold underline */
.phl-line {
    width: 0;
    height: 1.5px;
    margin-top: 1rem;
    background: linear-gradient(90deg, transparent, #D4AF37, #FBF5B7, #D4AF37, transparent);
    animation: phlLineGrow 1s 1.1s cubic-bezier(0.16,1,0.3,1) forwards;
    border-radius: 9999px;
    box-shadow: 0 0 12px rgba(212,175,55,0.4);
}
@keyframes phlLineGrow {
    to { width: min(280px, 60vw); }
}

/* Tagline */
.phl-tagline {
    margin: 1.2rem 0 0;
    font-size: 0.72rem;
    font-weight: 500;
    letter-spacing: 0.3em;
    text-transform: uppercase;
    color: rgba(212,175,55,0.6);
    animation: phlTagIn 0.8s 1.5s cubic-bezier(0.16,1,0.3,1) both,
               phlTagPulse 3s 2.3s ease-in-out infinite;
}
@keyframes phlTagIn {
    from { opacity: 0; transform: translateY(14px); }
    to   { opacity: 1; transform: translateY(0);    }
}
@keyframes phlTagPulse {
    0%, 100% { opacity: 1;   }
    50%      { opacity: 0.45; }
}

/* ── Corner accents ───────────────────────────────────────────────── */
.phl-corner {
    position: absolute;
    width: 40px; height: 40px;
    pointer-events: none;
    opacity: 0;
    animation: phlCornerIn 0.7s 1.3s cubic-bezier(0.16,1,0.3,1) forwards;
}
.phl-corner::before,
.phl-corner::after {
    content: '';
    position: absolute;
    background: rgba(212,175,55,0.25);
}
.phl-corner::before { width: 100%; height: 1px; }
.phl-corner::after  { width: 1px;  height: 100%; }
.phl-corner--tl { top: 2.5rem; left: 2.5rem; }
.phl-corner--tl::before { top: 0; left: 0; }
.phl-corner--tl::after  { top: 0; left: 0; }
.phl-corner--tr { top: 2.5rem; right: 2.5rem; }
.phl-corner--tr::before { top: 0; right: 0; }
.phl-corner--tr::after  { top: 0; right: 0; }
.phl-corner--bl { bottom: 2.5rem; left: 2.5rem; }
.phl-corner--bl::before { bottom: 0; left: 0; }
.phl-corner--bl::after  { bottom: 0; left: 0; }
.phl-corner--br { bottom: 2.5rem; right: 2.5rem; }
.phl-corner--br::before { bottom: 0; right: 0; }
.phl-corner--br::after  { bottom: 0; right: 0; }
@keyframes phlCornerIn {
    from { opacity: 0; }
    to   { opacity: 1; }
}

/* Header fade-in after preloader dismisses */
@keyframes phlHeaderFadeIn {
    from { opacity: 0; transform: translateY(-100%); }
    to   { opacity: 1; transform: translateY(0); }
}

@media (max-width: 640px) {
    .phl-corner { width: 24px; height: 24px; }
    .phl-corner--tl, .phl-corner--tr { top: 1.2rem; }
    .phl-corner--bl, .phl-corner--br { bottom: 1.2rem; }
    .phl-corner--tl, .phl-corner--bl { left: 1.2rem; }
    .phl-corner--tr, .phl-corner--br { right: 1.2rem; }
    .phl-glow { width: 340px; height: 340px; }
}

@keyframes preHeroSpin {
    to { transform: rotate(360deg); }
}

@keyframes preHeroBounce {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(4px); }
}

@media (max-width: 640px) {
    #pre-hero-scroll .pre-hero-hud {
        padding: 0 1.25rem;
        bottom: 1.25rem;
    }
    #pre-hero-scroll .pre-hero-progress-track {
        width: 70px;
    }
    #pre-hero-scroll .pre-hero-counter {
        display: none;
    }
}
</style>

<script>
(function() {
    const container = document.getElementById('pre-hero-scroll');
    if (!container) return;

    const canvas = document.getElementById('pre-hero-canvas');
    if (!canvas) return;

    const ctx = canvas.getContext('2d', { alpha: false });
    const loader      = document.getElementById('pre-hero-loader');
    const progressBar = document.getElementById('pre-hero-bar');
    const frameNum    = document.getElementById('pre-hero-frame-num');
    const scrollBtn   = document.getElementById('pre-hero-scroll-btn');

    const cap1 = document.getElementById('ph-caption-1');
    const cap2 = document.getElementById('ph-caption-2');
    const cap3 = document.getElementById('ph-caption-3');

    const TOTAL_FRAMES = 239;
    const baseUrl = <?= json_encode($seqBaseUrl) ?>;

    const images = new Array(TOTAL_FRAMES);
    const loaded = new Array(TOTAL_FRAMES).fill(false);

    let loadedCount   = 0;
    let sequenceReady = false;
    let currentFrame  = 0;
    let targetFrame   = 0;
    let isTicking     = false;
    let dpr = Math.min(window.devicePixelRatio || 1, 2);

    // Hide header behind preloader on load
    var siteHeader = document.querySelector('.site-header');
    if (siteHeader) siteHeader.style.display = 'none';

    // Lock scroll until sequence is ready
    document.documentElement.style.overflow = 'hidden';

    function getFrameUrl(idx) {
        return baseUrl + '/frame_' + String(idx).padStart(6, '0') + '.webp';
    }

    // ── Reveal sequence after all frames loaded ────────────────────────
    function revealSequence() {
        if (sequenceReady) return;
        sequenceReady = true;

        // Draw frame 0 on canvas first
        resizeCanvas();
        drawFrame(0);

        // Short pause so user sees 100% flash, then fade out loader
        setTimeout(function() {
            if (loader) loader.classList.add('is-hidden');
            // Show header and restore scroll after preloader fades out
            setTimeout(function() {
                document.documentElement.style.overflow = '';
                if (siteHeader) {
                    siteHeader.style.display = '';
                    siteHeader.style.animation = 'phlHeaderFadeIn 0.6s ease-out forwards';
                }
            }, 600);
        }, 520);
    }

    // ── Canvas helpers ─────────────────────────────────────────────────
    function resizeCanvas() {
        dpr = Math.min(window.devicePixelRatio || 1, 2);
        const w = window.innerWidth;
        const h = window.innerHeight;
        canvas.width  = Math.round(w * dpr);
        canvas.height = Math.round(h * dpr);
        canvas.style.width  = w + 'px';
        canvas.style.height = h + 'px';
        drawFrame(currentFrame);
    }

    window.addEventListener('resize', resizeCanvas, { passive: true });

    function drawFrame(frameIndex) {
        let img = images[frameIndex];
        if (!img || !loaded[frameIndex]) {
            for (let i = frameIndex - 1; i >= 0; i--) {
                if (images[i] && loaded[i]) { img = images[i]; break; }
            }
            if (!img || !img.complete) {
                for (let i = frameIndex + 1; i < TOTAL_FRAMES; i++) {
                    if (images[i] && loaded[i]) { img = images[i]; break; }
                }
            }
        }
        if (!img || !img.complete || img.naturalWidth === 0) return;

        const cw = canvas.width,  ch = canvas.height;
        const imgRatio    = img.naturalWidth / img.naturalHeight;
        const canvasRatio = cw / ch;
        let dw, dh, dx, dy;
        if (canvasRatio > imgRatio) {
            dw = cw; dh = cw / imgRatio; dx = 0; dy = (ch - dh) / 2;
        } else {
            dw = ch * imgRatio; dh = ch; dx = (cw - dw) / 2; dy = 0;
        }
        ctx.drawImage(img, dx, dy, dw, dh);
    }

    // ── Preload all frames silently ──────────────────────────────────────
    function preloadFrame(index) {
        if (images[index]) return;
        const img = new Image();
        img.onload = function() {
            loaded[index] = true;
            loadedCount++;
            if (loadedCount >= TOTAL_FRAMES) revealSequence();
        };
        img.onerror = function() {
            loadedCount++;
            if (loadedCount >= TOTAL_FRAMES) revealSequence();
        };
        img.src = getFrameUrl(index);
        images[index] = img;
    }

    // Kick off all 239 frames in staggered batches to avoid network storm
    (function kickoffPreload() {
        const BATCH = 20;  // concurrent requests per tick
        let start = 0;
        function loadBatch() {
            const end = Math.min(start + BATCH, TOTAL_FRAMES);
            for (let i = start; i < end; i++) preloadFrame(i);
            start = end;
            if (start < TOTAL_FRAMES) setTimeout(loadBatch, 60);
        }
        loadBatch();
    })();

    // ── Scroll scrubbing (active only after reveal) ────────────────────
    function onScroll() {
        if (!sequenceReady) return;

        const rect = container.getBoundingClientRect();
        const maxScroll = rect.height - window.innerHeight;
        let progress = 0;
        if (maxScroll > 0) {
            progress = Math.min(Math.max(-rect.top / maxScroll, 0), 1);
        }

        targetFrame = Math.min(Math.max(Math.floor(progress * (TOTAL_FRAMES - 1)), 0), TOTAL_FRAMES - 1);

        if (!isTicking) {
            isTicking = true;
            requestAnimationFrame(function() {
                if (currentFrame !== targetFrame) {
                    currentFrame = targetFrame;
                    drawFrame(currentFrame);
                }
                if (progressBar) progressBar.style.width = (progress * 100).toFixed(1) + '%';
                if (frameNum)    frameNum.textContent = String(currentFrame + 1).padStart(3, '0');

                const setCaption = function(el, on) {
                    el && (on ? el.classList.add('is-active') : el.classList.remove('is-active'));
                };
                setCaption(cap1, progress >= 0.02 && progress <= 0.28);
                setCaption(cap2, progress >= 0.35 && progress <= 0.64);
                setCaption(cap3, progress >= 0.70 && progress <= 0.94);

                isTicking = false;
            });
        }
    }

    window.addEventListener('scroll', onScroll, { passive: true });
    resizeCanvas();

    if (scrollBtn) {
        scrollBtn.addEventListener('click', function() {
            const nextSec = container.nextElementSibling;
            if (nextSec) nextSec.scrollIntoView({ behavior: 'smooth' });
        });
    }
})();
</script>

<!-- =========================================================================
     HERO STYLE CUSTOMIZER SECTION
     ========================================================================= -->
<section class="relative min-h-[92vh] flex flex-col justify-center pb-16 pt-6 overflow-hidden"
         x-data="heroCustomizer(<?= Html::encode(Json::encode($heroPresetsJson)) ?>)">
    
    <!-- Ambient Background Lighting Accent -->
    <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[500px] bg-gradient-to-b from-[#D4AF37]/15 via-transparent to-transparent blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 w-full relative z-10">
        
        <!-- Hero Header -->
        <div class="text-center max-w-3xl mx-auto mb-8 md:mb-12 reveal">
            <div class="inline-flex items-center gap-2 px-4 py-1.5 rounded-full border border-[#D4AF37]/30 bg-[#D4AF37]/5 text-xs font-semibold tracking-[0.25em] uppercase text-[#D4AF37] mb-4">
                <span class="w-1.5 h-1.5 rounded-full bg-[#D4AF37] animate-ping"></span>
                <span>Interactive Style Customizer</span>
            </div>
            <h1 class="text-3xl sm:text-5xl lg:text-6xl font-serif font-black tracking-tight text-white leading-tight">
                Interiors That Define <br>
                <span class="text-gold-gradient italic">Your Legacy</span>
            </h1>
            <p class="text-sm sm:text-base text-gray-300 mt-4 leading-relaxed font-light">
                Switch design presets, explore different room atmospheres, and toggle lighting moods in real time. Experience what your bespoke space looks like before it is built.
            </p>
        </div>

        <!-- Room Category Switcher Tabs -->
        <div class="flex items-center justify-center mb-6 reveal">
            <div class="segmented">
                <template x-for="room in availableRooms" :key="room">
                    <button type="button" 
                            @click="setRoom(room)"
                            :class="selectedRoom === room ? 'is-active' : ''"
                            x-text="room">
                    </button>
                </template>
            </div>
        </div>

        <!-- Main Showcase Stage (16:9 Cinema Container) -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start reveal">
            
            <!-- Left / Center Visual Stage (8 cols) -->
            <div class="lg:col-span-8 flex flex-col gap-4">
                
                <div class="stage aspect-[16/9] w-full gold-frame shadow-2xl relative select-none"
                     :data-light="lightingMode"
                     :class="isSwapping ? 'is-swapping' : ''">
                    
                    <!-- Before Layer (for comparison slider or toggle) -->
                    <img :src="currentPreset.before" 
                         alt="Unfinished Room Shell" 
                         class="stage-img before-layer z-0">

                    <!-- After / Finished Room Layer -->
                    <img :src="currentPreset.after" 
                         :alt="currentPreset.style" 
                         class="stage-img after-layer z-10"
                         :class="showBeforeAfter ? 'compare-after' : ''"
                         :style="showBeforeAfter ? '--pos: ' + comparePos + '%' : ''">

                    <!-- Interactive Before/After Split Line & Handle -->
                    <div x-show="showBeforeAfter" class="compare-handle" :style="'--pos: ' + comparePos + '%'">
                        <span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M8 9l-3 3 3 3m8-6l3 3-3 3"/>
                            </svg>
                        </span>
                    </div>

                    <!-- Range input slider for Before/After -->
                    <input x-show="showBeforeAfter" 
                           type="range" min="0" max="100" 
                           x-model="comparePos" 
                           class="compare-range" 
                           aria-label="Before and after split slider">

                    <!-- Labels for Comparison -->
                    <div x-show="showBeforeAfter" class="compare-label left-4 text-gray-300">
                        Raw Shell
                    </div>
                    <div x-show="showBeforeAfter" class="compare-label right-4 text-[#D4AF37] border-[#D4AF37]/40">
                        ICONIC Handover
                    </div>

                    <!-- Ambient Lighting Blends & Vignette Overlay -->
                    <div class="light-overlay z-20"></div>
                    <div class="vignette z-20"></div>

                    <!-- Bottom Bar Over Image (Style info & quick book) -->
                    <div class="absolute bottom-0 inset-x-0 p-4 sm:p-6 bg-gradient-to-t from-black/90 via-black/50 to-transparent z-30 flex flex-col sm:flex-row items-start sm:items-end justify-between gap-4">
                        <div>
                            <span class="text-[10px] sm:text-xs font-bold tracking-[0.22em] text-[#D4AF37] uppercase" x-text="currentPreset.room"></span>
                            <h2 class="text-xl sm:text-2xl font-serif font-bold text-white tracking-wide" x-text="currentPreset.style"></h2>
                            <p class="text-xs text-gray-300 max-w-md hidden sm:block mt-1 font-light" x-text="currentPreset.tagline"></p>
                        </div>
                        <div class="flex items-center gap-3 shrink-0">
                            <!-- Before/After Toggle -->
                            <button type="button" 
                                    @click="toggleBeforeAfter()" 
                                    class="btn-ghost btn-sm"
                                    :class="showBeforeAfter ? 'border-[#D4AF37] text-[#D4AF37] bg-[#D4AF37]/15' : ''">
                                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"/>
                                </svg>
                                <span x-text="showBeforeAfter ? 'Exit Split View' : 'Before / After'"></span>
                            </button>

                            <!-- Book This Look CTA -->
                            <button type="button" 
                                    @click="openBookModal()"
                                    class="btn-gold btn-sm">
                                <span>Book This Look</span>
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Lighting Mode Controller Bar -->
                <div class="glass rounded-2xl p-4 flex flex-wrap items-center justify-between gap-4 border border-white/5">
                    <div class="flex items-center gap-2 text-xs font-semibold uppercase tracking-wider text-gray-300">
                        <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 3v1m0 16v1m9-9h-1M4 12H3m15.364 6.364l-.707-.707M6.343 6.343l-.707-.707m12.728 0l-.707.707M6.343 17.657l-.707.707M16 12a4 4 0 11-8 0 4 4 0 018 0z"/>
                        </svg>
                        <span>Simulate Lighting Mood:</span>
                    </div>
                    <div class="segmented">
                        <button type="button" 
                                @click="setLighting('warm')"
                                :class="lightingMode === 'warm' ? 'is-active' : ''">
                            <span class="w-2.5 h-2.5 rounded-full bg-amber-400"></span>
                            <span>Warm Evening 2700K</span>
                        </button>
                        <button type="button" 
                                @click="setLighting('daylight')"
                                :class="lightingMode === 'daylight' ? 'is-active' : ''">
                            <span class="w-2.5 h-2.5 rounded-full bg-sky-200"></span>
                            <span>Natural Daylight</span>
                        </button>
                        <button type="button" 
                                @click="setLighting('noir')"
                                :class="lightingMode === 'noir' ? 'is-active' : ''">
                            <span class="w-2.5 h-2.5 rounded-full bg-yellow-600"></span>
                            <span>Noir Lounge Mood</span>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Right Controls: Style Presets & Material Specifications (4 cols) -->
            <div class="lg:col-span-4 flex flex-col gap-5">
                
                <!-- Presets Grid -->
                <div class="glass rounded-3xl p-6 border border-white/10 flex flex-col gap-4">
                    <div class="flex items-center justify-between">
                        <h3 class="text-xs font-bold tracking-[0.24em] uppercase text-white">
                            Signature Style Presets
                        </h3>
                        <span class="text-[10px] text-gray-400 uppercase tracking-widest font-mono">
                            <span x-text="presetsForRoom.length"></span> Looks
                        </span>
                    </div>

                    <div class="flex flex-col gap-2.5">
                        <template x-for="p in presetsForRoom" :key="p.id">
                            <button type="button" 
                                    @click="selectPreset(p.id)"
                                    class="preset flex items-center justify-between gap-3"
                                    :class="activePresetId === p.id ? 'is-active' : ''">
                                <div class="flex flex-col text-left">
                                    <span class="text-sm font-semibold text-white tracking-wide" x-text="p.style"></span>
                                    <span class="text-[11px] text-gray-400 line-clamp-1" x-text="p.tagline"></span>
                                </div>
                                <!-- Color Swatches preview -->
                                <div class="flex items-center -space-x-1 shrink-0">
                                    <template x-for="hex in (p.palette || []).slice(0, 3)" :key="hex">
                                        <span class="swatch" :style="'background-color: ' + hex"></span>
                                    </template>
                                </div>
                            </button>
                        </template>
                    </div>
                </div>

                <!-- Live Material Specifications & Palette Card -->
                <div class="glass rounded-3xl p-6 border border-white/10 flex flex-col gap-4">
                    <h3 class="text-xs font-bold tracking-[0.24em] uppercase text-white flex items-center gap-2">
                        <span class="text-[#D4AF37]">✦</span>
                        <span>Architectural Specs</span>
                    </h3>

                    <!-- Color Swatches -->
                    <div>
                        <span class="text-[10px] font-bold uppercase tracking-wider text-gray-400 block mb-2">Curated Palette Swatches</span>
                        <div class="flex items-center gap-2 flex-wrap">
                            <template x-for="hex in (currentPreset.palette || [])" :key="hex">
                                <div class="group/sw relative">
                                    <div class="swatch-lg cursor-pointer" :style="'background-color: ' + hex"></div>
                                    <span class="absolute -top-7 left-1/2 -translate-x-1/2 px-1.5 py-0.5 rounded bg-black/90 border border-white/20 text-[9px] font-mono text-white opacity-0 group-hover/sw:opacity-100 transition-opacity whitespace-nowrap pointer-events-none" x-text="hex"></span>
                                </div>
                            </template>
                        </div>
                    </div>

                    <!-- Material Details -->
                    <div class="pt-2 border-t border-white/5 flex flex-col gap-2.5 text-xs">
                        <template x-for="(spec, key) in (currentPreset.materials || {})" :key="key">
                            <div class="flex items-start justify-between gap-3">
                                <span class="text-gray-400 shrink-0 font-medium" x-text="key + ':'"></span>
                                <span class="text-white text-right font-light" x-text="spec"></span>
                            </div>
                        </template>
                    </div>
                </div>

            </div>

        </div>

    </div>
</section>

<!-- =========================================================================
     STATISTICS STRIP
     ========================================================================= -->
<?php if (!empty($stats)): ?>
<section class="py-12 border-y border-white/5 bg-[#0C0C0C] relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <?php foreach ($stats as $st): ?>
                <div class="flex flex-col items-center reveal">
                    <span class="text-3xl sm:text-5xl font-serif font-black text-gold-gradient tracking-tight">
                        <?= Html::encode($st['value']) ?>
                    </span>
                    <span class="text-xs uppercase tracking-[0.2em] text-gray-300 mt-2 font-medium">
                        <?= Html::encode($st['label']) ?>
                    </span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- =========================================================================
     FEATURED SERVICES OVERVIEW (6 Signature Services)
     ========================================================================= -->
<section class="py-20 md:py-32 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 reveal">
            <div>
                <span class="kicker text-[11px] mb-2">OUR SERVICES</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-white tracking-wide">
                    Complete Interior <span class="text-gold-gradient italic">Solutions</span>
                </h2>
            </div>
            <a href="<?= Url::to(['/service/index']) ?>" class="btn-ghost btn-sm self-start md:self-auto">
                <span>View All Services</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($services as $svc): ?>
                <div class="card-lux group p-7 flex flex-col justify-between overflow-hidden relative reveal">
                    
                    <!-- Hover Glow -->
                    <div class="absolute -top-24 -right-24 w-48 h-48 bg-[#D4AF37]/10 rounded-full blur-3xl pointer-events-none group-hover:bg-[#D4AF37]/20 transition-all duration-700"></div>

                    <div>
                        <!-- Icon -->
                        <div class="w-12 h-12 rounded-2xl bg-gradient-to-br from-[#1C1C1C] to-[#101010] border border-[#D4AF37]/30 flex items-center justify-center text-[#D4AF37] mb-6 p-2.5 shadow-md shadow-black/50 group-hover:scale-110 transition-transform duration-500">
                            <?= $svc->icon_svg_or_class ?: '◆' ?>
                        </div>

                        <!-- Title -->
                        <h3 class="text-xl font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-3">
                            <a href="<?= Url::to(['/service/view', 'slug' => $svc->slug]) ?>">
                                <?= Html::encode($svc->title) ?>
                            </a>
                        </h3>

                        <p class="text-sm text-gray-400 leading-relaxed font-light line-clamp-3">
                            <?= Html::encode($svc->short_description) ?>
                        </p>
                    </div>

                    <!-- Bottom Action & Starting Price -->
                    <div class="pt-6 mt-6 border-t border-white/5 flex items-center justify-between">
                        <div class="flex flex-col">
                            <span class="text-[10px] uppercase tracking-wider text-gray-300">Investment</span>
                            <span class="text-xs font-semibold text-[#D4AF37]"><?= $svc->getPriceLabel() ?></span>
                        </div>
                        <a href="<?= Url::to(['/service/view', 'slug' => $svc->slug]) ?>" 
                           class="w-9 h-9 rounded-full border border-white/10 group-hover:border-[#D4AF37] flex items-center justify-center text-gray-300 group-hover:text-[#D4AF37] transition-all">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                            </svg>
                        </a>
                    </div>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>

<!-- =========================================================================
     FEATURED PORTFOLIO GRID
     ========================================================================= -->
<section class="py-20 md:py-32 bg-[#0B0B0B] border-t border-white/5 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 reveal">
            <div>
                <span class="kicker text-[11px] mb-2">BUILT REALITY</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-white tracking-wide">
                    Curated <span class="text-gold-gradient italic">Portfolio</span>
                </h2>
            </div>
            <a href="<?= Url::to(['/portfolio/index']) ?>" class="btn-ghost btn-sm self-start md:self-auto">
                <span>View Full Portfolio</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($projects as $proj): ?>
                <div class="card-lux group overflow-hidden flex flex-col justify-between reveal">
                    
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
                            <h3 class="text-xl font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-2">
                                <a href="<?= Url::to(['/portfolio/view', 'slug' => $proj->slug]) ?>">
                                    <?= Html::encode($proj->title) ?>
                                </a>
                            </h3>
                            <p class="text-xs text-gray-400 font-light line-clamp-2 leading-relaxed">
                                <?= Html::encode($proj->summary) ?>
                            </p>
                        </div>

                        <div class="pt-4 mt-4 border-t border-white/5 flex items-center justify-between text-xs">
                            <span class="text-[#D4AF37] font-semibold"><?= Html::encode($proj->budget_tier) ?></span>
                            <a href="<?= Url::to(['/portfolio/view', 'slug' => $proj->slug]) ?>" class="text-gray-300 hover:text-white flex items-center gap-1 font-medium">
                                <span>Explore Case Study</span>
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

<!-- =========================================================================
     COST ESTIMATOR TEASER & BANNER
     ========================================================================= -->
<section class="py-20 relative overflow-hidden">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="glass rounded-3xl p-8 sm:p-14 border border-[#D4AF37]/30 shadow-2xl relative overflow-hidden reveal">
            <!-- Gilded corner accents -->
            <div class="corner-marks"></div>
            
            <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-center">
                <div class="lg:col-span-8 flex flex-col gap-4">
                    <span class="kicker text-[11px]">TRANSPARENT PRICING</span>
                    <h2 class="text-2xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                        Curious About Your <span class="text-gold-gradient italic">Interior Investment?</span>
                    </h2>
                    <p class="text-sm sm:text-base text-gray-300 leading-relaxed font-light max-w-2xl">
                        Use our dedicated interactive estimator to calculate a realistic cost range for your 1BHK, 2BHK, 3BHK, luxury villa or commercial office in seconds — with complete category breakdowns.
                    </p>
                </div>

                <div class="lg:col-span-4 flex flex-col sm:flex-row lg:flex-col gap-4">
                    <a href="<?= Url::to(['/estimator/index']) ?>" class="btn-gold text-center py-4 text-xs font-bold tracking-widest uppercase">
                        <span>Launch Cost Estimator</span>
                        <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                        </svg>
                    </a>
                    
                    <a href="<?= $settings->whatsappUrl('Hello Santosh Kumar Maharana, I would like to get a quick rough estimate for my property.') ?>" 
                       target="_blank" rel="noopener noreferrer"
                       class="btn-ghost text-center py-4 text-xs font-bold tracking-widest uppercase">
                        <span>WhatsApp Quick Estimate</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     FEATURED PRODUCTS SHOP SECTION
     ========================================================================= -->
<?php
use app\models\Product;
$featuredProducts = Product::featured()->limit(6)->all();
?>
<?php if (!empty($featuredProducts)): ?>
<section class="py-20 md:py-32 bg-[#090909] border-t border-white/5 relative overflow-hidden">
    <div class="absolute top-0 right-0 w-[500px] h-[500px] bg-[#D4AF37]/5 blur-[150px] pointer-events-none rounded-full"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Section header -->
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6 mb-16 reveal">
            <div>
                <span class="kicker text-[11px] mb-2">ICONIC CURATED SHOP</span>
                <h2 class="text-3xl sm:text-4xl md:text-5xl font-serif font-bold text-white tracking-wide">
                    Bring the Studio <span class="text-gold-gradient italic">Home</span>
                </h2>
                <p class="text-sm text-gray-400 mt-3 max-w-md font-light leading-relaxed">
                    Handcrafted lighting, bespoke furniture, artisan decor — curated by our designers, delivered to your door.
                </p>
            </div>
            <a href="<?= Url::to(['/product/index']) ?>" class="btn-ghost btn-sm self-start md:self-auto">
                <span>Shop All Pieces</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>

        <!-- Category pills -->
        <div class="flex items-center gap-2 flex-wrap mb-10 reveal">
            <?php foreach (Product::CATEGORIES as $cat): ?>
            <a href="<?= Url::to(['/product/index', 'category' => $cat]) ?>"
               class="inline-flex items-center px-4 py-1.5 rounded-full text-[10px] font-bold uppercase tracking-[0.18em] border border-white/8 text-gray-400 hover:text-[#D4AF37] hover:border-[#D4AF37]/40 hover:bg-[#D4AF37]/5 transition-all duration-200">
                <?= Html::encode($cat) ?>
            </a>
            <?php endforeach; ?>
        </div>

        <!-- Product grid -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
            <?php foreach ($featuredProducts as $prod): ?>
            <div class="group card-lux overflow-hidden flex flex-col reveal" id="hp-product-<?= $prod->id ?>">
                <!-- Image -->
                <a href="<?= Url::to(['/product/view', 'slug' => $prod->slug]) ?>" class="block relative overflow-hidden">
                    <div class="aspect-[4/3] overflow-hidden bg-[#111] relative">
                        <?php if ($prod->featured_image): ?>
                            <img src="<?= Html::encode($prod->getImageUrl()) ?>"
                                 alt="<?= Html::encode($prod->name) ?>"
                                 class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-105"
                                 loading="lazy">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-[#D4AF37]/30 text-5xl font-serif">✦</div>
                        <?php endif; ?>
                        <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>
                        <?php if ($prod->getDiscountPercent() > 0): ?>
                            <span class="absolute top-3 left-3 px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#D4AF37] text-[#0A0A0A]">
                                −<?= $prod->getDiscountPercent() ?>%
                            </span>
                        <?php endif; ?>
                        <div class="absolute bottom-3 inset-x-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                            <a href="<?= Url::to(['/product/view', 'slug' => $prod->slug]) ?>"
                               class="btn-ghost btn-sm w-full text-center justify-center text-xs">Quick View</a>
                        </div>
                    </div>
                </a>

                <!-- Info -->
                <div class="p-5 flex flex-col flex-1">
                    <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37] mb-1"><?= Html::encode($prod->category) ?></span>
                    <h3 class="text-base font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-2 leading-snug flex-1">
                        <a href="<?= Url::to(['/product/view', 'slug' => $prod->slug]) ?>"><?= Html::encode($prod->name) ?></a>
                    </h3>
                    <p class="text-xs text-gray-400 font-light leading-relaxed line-clamp-2 mb-4">
                        <?= Html::encode($prod->short_description) ?>
                    </p>
                    <div class="flex items-center justify-between pt-4 border-t border-white/5 mt-auto">
                        <div class="flex flex-col">
                            <span class="text-base font-bold text-white"><?= $prod->getPriceFormatted() ?></span>
                            <?php if ($prod->getOriginalPriceFormatted()): ?>
                                <span class="text-xs text-gray-600 line-through"><?= $prod->getOriginalPriceFormatted() ?></span>
                            <?php endif; ?>
                        </div>
                        <?php if ($prod->is_in_stock): ?>
                            <button type="button"
                                    onclick="addToCartHP(<?= $prod->id ?>)"
                                    id="hp-cart-btn-<?= $prod->id ?>"
                                    class="btn-gold btn-sm">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                </svg>
                                <span>Add to Cart</span>
                            </button>
                        <?php else: ?>
                            <span class="text-xs text-gray-500">Sold Out</span>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <!-- Bottom CTA -->
        <div class="mt-16 glass rounded-3xl p-8 border border-[#D4AF37]/20 flex flex-col sm:flex-row items-center justify-between gap-6 reveal">
            <div>
                <p class="text-xs uppercase tracking-widest text-[#D4AF37] font-bold mb-1">Bespoke Sourcing</p>
                <h3 class="text-xl font-serif font-bold text-white">Can't find what you're looking for?</h3>
                <p class="text-sm text-gray-400 font-light mt-1">Our team sources one-of-a-kind pieces on request. Just describe your vision.</p>
            </div>
            <a href="<?= $settings->whatsappUrl('Hello, I would like to request bespoke product sourcing from ICONIC INTERIORS.') ?>"
               target="_blank" rel="noopener noreferrer"
               class="btn-gold shrink-0 py-3 px-6 text-xs font-bold uppercase tracking-widest">
                Request Custom Piece
            </a>
        </div>
    </div>
</section>

<div id="hp-cart-toast"
     class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[200] flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-[#111] border border-[#D4AF37]/40 shadow-2xl backdrop-blur-md text-sm font-medium text-white opacity-0 pointer-events-none transition-all duration-400 -translate-y-4">
    <span class="text-[#D4AF37] text-lg">✓</span>
    <span>Added to cart!</span>
    <a href="<?= Url::to(['/product/cart']) ?>" class="text-[#D4AF37] underline text-xs font-bold ml-2">View Cart →</a>
</div>

<?php
$hpAddUrl = Url::to(['/product/add-to-cart']);
$this->registerJs(<<<JS
function addToCartHP(productId) {
    const btn = document.getElementById('hp-cart-btn-' + productId);
    if (btn) btn.disabled = true;
    fetch('$hpAddUrl', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId + '&qty=1&_csrf=' + (document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '')
    })
    .then(r => r.json())
    .then(data => {
        if (btn) btn.disabled = false;
        if (data.success) {
            const toast = document.getElementById('hp-cart-toast');
            if (toast) {
                toast.classList.remove('opacity-0', '-translate-y-4', 'pointer-events-none');
                toast.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
                setTimeout(() => {
                    toast.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
                    toast.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
                }, 3500);
            }
        }
    })
    .catch(() => { if (btn) btn.disabled = false; });
}
JS);
?>
<?php endif; ?>

<!-- =========================================================================
     CLIENT TESTIMONIALS
     ========================================================================= -->
<?php if (!empty($testimonials)): ?>
<section class="py-20 md:py-28 bg-[#090909] border-t border-white/5 relative">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        
        <div class="text-center max-w-2xl mx-auto mb-16 reveal">
            <span class="kicker text-[11px] mb-2">VOICES OF TRUST</span>
            <h2 class="text-3xl sm:text-4xl font-serif font-bold text-white tracking-wide">
                Words From Our <span class="text-gold-gradient italic">Patrons</span>
            </h2>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <?php foreach ($testimonials as $t): ?>
                <div class="card-lux p-8 flex flex-col justify-between reveal">
                    <div>
                        <!-- Stars -->
                        <div class="flex items-center gap-1 text-[#D4AF37] mb-5">
                            <?php for ($i = 0; $i < $t->rating; $i++): ?>
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                </svg>
                            <?php endfor; ?>
                        </div>

                        <p class="text-sm text-gray-300 leading-relaxed italic font-serif">
                            “<?= Html::encode($t->review_text) ?>”
                        </p>
                    </div>

                    <div class="pt-6 mt-6 border-t border-white/5 flex items-center gap-3">
                        <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#222] to-[#111] border border-[#D4AF37]/40 flex items-center justify-center font-serif text-sm font-bold text-[#D4AF37]">
                            <?= Html::encode($t->getInitials()) ?>
                        </div>
                        <div class="flex flex-col text-xs">
                            <span class="font-bold text-white"><?= Html::encode($t->client_name) ?></span>
                            <span class="text-gray-400"><?= Html::encode($t->project_type) ?> · <?= Html::encode($t->client_role) ?></span>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>
