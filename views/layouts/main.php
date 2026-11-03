<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\SiteSettings;
use yii\helpers\Html;

AppAsset::register($this);
$settings = SiteSettings::get();
$pageTitle = $this->title ? Html::encode($this->title) . ' | ' . Html::encode($settings->company_name) : Html::encode($settings->meta_title ?: $settings->company_name);
$metaDesc = $this->params['meta_description'] ?? $settings->meta_description;
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>" class="dark h-full">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?= Html::encode($metaDesc) ?>">
    <?= Html::csrfMetaTags() ?>
    <title><?= $pageTitle ?></title>

    <!-- Brand Favicons & Icons -->
    <link rel="icon" type="image/x-icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="64x64" href="<?= Yii::getAlias('@web/images/favicon.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web/images/apple-touch-icon.png') ?>">

    <!-- Open Graph / Social Sharing -->
    <meta property="og:type" content="website">
    <meta property="og:title" content="<?= $pageTitle ?>">
    <meta property="og:description" content="<?= Html::encode($metaDesc) ?>">
    <meta property="og:image" content="<?= Yii::$app->request->hostInfo . Yii::getAlias('@web/images/logo.jpeg') ?>">

    <!-- Google Fonts: Luxury Serif & Sans -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cinzel:wght@500;600;700;800;900&family=Playfair+Display:ital,wght@0,400;0,600;0,700;0,900;1,400;1,600&family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Tailwind CSS (CDN) -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['"Plus Jakarta Sans"', 'sans-serif'],
                        serif: ['"Playfair Display"', 'Georgia', 'serif'],
                        cinzel: ['"Cinzel"', 'serif'],
                    },
                    colors: {
                        obsidian: '#0A0A0A',
                        charcoal: '#121212',
                        gold: {
                            DEFAULT: '#D4AF37',
                            soft: '#C5A059',
                            deep: '#8A6E2F',
                            light: '#F6E7A8',
                        },
                        platinum: '#E5E7EB',
                        ivory: '#F9FAFB',
                    }
                }
            }
        }
    </script>

    <!-- Alpine.js -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <?php $this->head() ?>
</head>
<body class="min-h-full bg-[#0A0A0A] text-[#F9FAFB] font-sans antialiased selection:bg-[#D4AF37]/30 selection:text-white grain flex flex-col">
<?php $this->beginBody() ?>

    <!-- Header Navigation -->
    <?= $this->render('_header') ?>

    <!-- Flash Notifications / Toasts -->
    <div class="fixed top-20 right-4 sm:right-6 z-[110] flex flex-col gap-3 pointer-events-none max-w-md w-full">
        <?php foreach (['success', 'error', 'info', 'warning'] as $type): ?>
            <?php if (Yii::$app->session->hasFlash($type)): ?>
                <div class="pointer-events-auto toast-enter flex items-start gap-3 p-4 rounded-2xl shadow-2xl border backdrop-blur-xl <?= $type === 'success' ? 'bg-emerald-950/90 border-emerald-500/40 text-emerald-100' : 'bg-red-950/90 border-red-500/40 text-red-100' ?>"
                     x-data="{ show: true }" x-show="show" x-init="setTimeout(() => show = false, 7000)">
                    <div class="shrink-0 mt-0.5">
                        <?php if ($type === 'success'): ?>
                            <svg class="w-5 h-5 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        <?php else: ?>
                            <svg class="w-5 h-5 text-red-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        <?php endif; ?>
                    </div>
                    <div class="flex-1 text-sm font-medium">
                        <?= Yii::$app->session->getFlash($type) ?>
                    </div>
                    <button type="button" @click="show = false" class="text-white/60 hover:text-white shrink-0">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>

    <!-- Main Content Area -->
    <main class="flex-grow <?= (Yii::$app->controller && Yii::$app->controller->route === 'site/index') ? '' : 'pt-20 md:pt-24' ?>">
        <?= $content ?>
    </main>

    <!-- Global Consultation Modal -->
    <?= $this->render('_inquiry_modal') ?>

    <!-- Floating WhatsApp Concierge Button -->
    <aside aria-label="WhatsApp Concierge" class="fixed bottom-6 right-6 z-40 flex items-center group">
        <a href="<?= $settings->whatsappUrl('Hello Santosh Kumar Maharana & team, I am exploring the ICONIC INTERIORS website and would love to discuss a project.') ?>"
           target="_blank" rel="noopener noreferrer"
           class="pulse-ring flex items-center gap-3 px-4 py-3 rounded-full bg-[#25D366] text-white shadow-2xl hover:bg-[#20ba59] transition-all duration-300 hover:scale-105"
           aria-label="Direct WhatsApp Concierge with Santosh Kumar Maharana">
            <svg class="w-6 h-6 fill-current" viewBox="0 0 24 24">
                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
            </svg>
            <span class="max-w-0 overflow-hidden whitespace-nowrap group-hover:max-w-xs transition-all duration-500 text-xs font-bold uppercase tracking-wider">
                Chat With Founder
            </span>
        </a>
    </aside>

    <!-- Footer -->
    <?= $this->render('_footer') ?>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
