<?php

/** @var yii\web\View $this */
/** @var string $content */

use app\assets\AppAsset;
use app\models\SiteSettings;
use app\models\UserQuery;
use yii\helpers\Html;
use yii\helpers\Url;

AppAsset::register($this);
$settings = SiteSettings::get();
$unreadCount = UserQuery::unreadCount();
$currentRoute = Yii::$app->controller ? (string) Yii::$app->controller->route : '';

$nav = [
    ['label' => 'Dashboard', 'url' => ['/admin/default/index'], 'icon' => 'M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6', 'badge' => null],
    ['label' => 'User Queries & Leads', 'url' => ['/admin/user-query/index'], 'icon' => 'M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4', 'badge' => $unreadCount],
    ['label' => 'Hero Style Customizer', 'url' => ['/admin/hero-style/index'], 'icon' => 'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z', 'badge' => null],
    ['label' => 'Services Manager', 'url' => ['/admin/service/index'], 'icon' => 'M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10', 'badge' => null],
    ['label' => 'Portfolio Manager', 'url' => ['/admin/project/index'], 'icon' => 'M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4', 'badge' => null],
    ['label' => 'Pages & SEO', 'url' => ['/admin/cms-page/index'], 'icon' => 'M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z', 'badge' => null],
    ['label' => 'Site Settings', 'url' => ['/admin/settings/index'], 'icon' => 'M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z', 'badge' => null],
];
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="en" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?> | ICONIC Executive CMS</title>
    <?= Html::csrfMetaTags() ?>

    <!-- Brand Favicons -->
    <link rel="icon" type="image/x-icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="64x64" href="<?= Yii::getAlias('@web/images/favicon.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web/images/apple-touch-icon.png') ?>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700;800&family=Plus+Jakarta+Sans:wght@300;400;500;600;700&family=JetBrains+Mono:wght@400;600&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.14.8/dist/cdn.min.js"></script>

    <style>
        body { background: #0A0A0A; color: #F9FAFB; font-family: 'Plus Jakarta Sans', sans-serif; }
        .lux-card { background: linear-gradient(170deg, #161616 0%, #0f0f0f 100%); border: 1px solid rgba(255,255,255,.07); border-radius: 1.25rem; }
        .table-lux { width: 100%; border-collapse: separate; border-spacing: 0; }
        .table-lux th { background: #141414; color: #9CA3AF; font-size: 0.65rem; text-transform: uppercase; letter-spacing: 0.2em; font-weight: 700; padding: 0.85rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.08); text-align: left; }
        .table-lux td { padding: 0.95rem 1rem; border-bottom: 1px solid rgba(255,255,255,0.04); font-size: 0.82rem; }
        .table-lux tr:hover td { background: rgba(212,175,55,0.03); }
        .gold-border { border-color: rgba(212,175,55,0.3); }
    </style>
    <?php $this->head() ?>
</head>
<body class="h-full bg-[#0A0A0A] flex overflow-hidden">
<?php $this->beginBody() ?>

    <!-- Executive Dark Sidebar -->
    <aside class="w-64 lg:w-72 bg-[#0F0F0F] border-r border-white/5 flex flex-col justify-between shrink-0 h-full z-30">
        <div>
            <!-- Monogram Header -->
            <div class="p-5 border-b border-white/5 flex items-center justify-between">
                <a href="<?= Url::to(['/admin/default/index']) ?>" class="group flex items-center gap-3">
                    <div class="w-10 h-10 rounded-xl bg-[#141414] border border-[#D4AF37]/35 p-0.5 shadow-md shadow-black overflow-hidden group-hover:border-[#D4AF37] transition-all">
                        <img src="<?= Yii::getAlias('@web/images/logo-mark.png') ?>" alt="ICONIC Mark" class="w-full h-full object-cover">
                    </div>
                    <div class="flex flex-col">
                        <span class="text-sm font-serif font-bold uppercase tracking-[0.2em] text-white group-hover:text-[#D4AF37] transition-colors">ICONIC</span>
                        <span class="text-[9px] text-[#C5A059] uppercase tracking-[0.26em] font-semibold">Executive CMS</span>
                    </div>
                </a>
            </div>

            <!-- Navigation Links -->
            <nav class="p-4 space-y-1.5 overflow-y-auto">
                <?php foreach ($nav as $item): ?>
                    <?php 
                    $isActive = (str_starts_with($currentRoute, trim($item['url'][0], '/'))); 
                    ?>
                    <a href="<?= Url::to($item['url']) ?>" 
                       class="flex items-center justify-between px-3.5 py-2.5 rounded-xl text-xs font-semibold uppercase tracking-wider transition-all <?= $isActive ? 'bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30 shadow-md shadow-black' : 'text-gray-400 hover:text-white hover:bg-white/5' ?>">
                        <div class="flex items-center gap-3">
                            <svg class="w-4 h-4 <?= $isActive ? 'text-[#D4AF37]' : 'text-gray-400' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.8" d="<?= $item['icon'] ?>"/>
                            </svg>
                            <span><?= $item['label'] ?></span>
                        </div>
                        <?php if ($item['badge'] && $item['badge'] > 0): ?>
                            <span class="px-2 py-0.5 rounded-full text-[10px] font-bold bg-[#D4AF37] text-black font-mono">
                                <?= $item['badge'] ?>
                            </span>
                        <?php endif; ?>
                    </a>
                <?php endforeach; ?>
            </nav>
        </div>

        <!-- Sidebar Footer -->
        <div class="p-4 border-t border-white/5 flex flex-col gap-3">
            <a href="<?= Url::to(['/site/index']) ?>" target="_blank" 
               class="flex items-center justify-center gap-2 py-2 px-3 rounded-lg border border-white/10 text-xs font-medium text-gray-300 hover:text-[#D4AF37] hover:border-[#D4AF37]/40 transition-colors">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                </svg>
                <span>View Public Website</span>
            </a>

            <div class="flex items-center justify-between pt-2 text-xs text-gray-400">
                <div class="flex items-center gap-2">
                    <span class="w-2 h-2 rounded-full bg-emerald-500"></span>
                    <span class="text-white font-medium"><?= Html::encode(Yii::$app->user->identity->username ?? 'admin') ?></span>
                </div>
                <a href="<?= Url::to(['/admin/default/logout']) ?>" 
                   data-method="post" 
                   class="text-red-400 hover:text-red-300 text-[11px] font-semibold uppercase tracking-wider">
                    Sign Out
                </a>
            </div>
        </div>
    </aside>

    <!-- Main Workspace Area -->
    <div class="flex-1 flex flex-col h-full overflow-hidden">
        
        <!-- Top Navbar -->
        <header class="h-16 bg-[#0E0E0E] border-b border-white/5 px-6 flex items-center justify-between shrink-0">
            <div class="flex items-center gap-3">
                <h1 class="text-base font-serif font-bold text-white tracking-wide">
                    <?= Html::encode($this->title) ?>
                </h1>
            </div>

            <div class="flex items-center gap-4 text-xs">
                <?php if ($unreadCount > 0): ?>
                    <a href="<?= Url::to(['/admin/user-query/index', 'UserQuerySearch[is_read]' => 0]) ?>" 
                       class="px-3 py-1 rounded-full bg-[#D4AF37]/15 border border-[#D4AF37]/40 text-[#D4AF37] font-semibold flex items-center gap-1.5">
                        <span class="w-2 h-2 rounded-full bg-[#D4AF37] animate-ping"></span>
                        <span><?= $unreadCount ?> New Leads Pending</span>
                    </a>
                <?php endif; ?>

                <span class="text-gray-400">Owner: <strong class="text-white font-serif"><?= Html::encode($settings->owner_name) ?></strong></span>
            </div>
        </header>

        <!-- Flash Messages in Admin -->
        <?php foreach (['success', 'error', 'info'] as $type): ?>
            <?php if (Yii::$app->session->hasFlash($type)): ?>
                <div class="mx-6 mt-4 p-4 rounded-xl text-xs font-semibold flex items-center justify-between <?= $type === 'success' ? 'bg-emerald-950/80 border border-emerald-500/40 text-emerald-200' : 'bg-red-950/80 border border-red-500/40 text-red-200' ?>">
                    <span><?= Yii::$app->session->getFlash($type) ?></span>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>

        <!-- Scrollable Page Content -->
        <main class="flex-1 overflow-y-auto p-6 md:p-8">
            <?= $content ?>
        </main>
    </div>

<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
