<?php

/** @var yii\web\View $this */
/** @var array $kpis */
/** @var app\models\UserQuery[] $recentQueries */

use app\models\UserQuery;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Executive Dashboard';
?>

<div class="space-y-8">
    
    <!-- Top Welcome Banner -->
    <div class="lux-card p-6 sm:p-8 flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4 border border-[#D4AF37]/30">
        <div>
            <span class="text-[10px] uppercase font-bold tracking-[0.25em] text-[#D4AF37] block mb-1">COMMAND CONSOLE</span>
            <h2 class="text-2xl sm:text-3xl font-serif font-bold text-white">
                ICONIC INTERIORS <span class="text-gold-gradient font-black">CMS</span>
            </h2>
            <p class="text-xs text-gray-400 mt-1">
                Real-time lead inquiries, live style customizer presets, and active project catalog.
            </p>
        </div>
        <div class="flex items-center gap-3">
            <a href="<?= Url::to(['/admin/user-query/index']) ?>" class="btn-gold btn-sm">
                <span>View All Leads</span>
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"/>
                </svg>
            </a>
        </div>
    </div>

    <!-- Summary KPI Cards -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4">
        
        <!-- Total Leads -->
        <div class="lux-card p-5 border border-white/5 flex flex-col justify-between">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Total Leads</span>
            <div class="my-2 flex items-baseline justify-between">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['total_queries'] ?></span>
                <span class="text-xs text-[#D4AF37] font-mono">100% logged</span>
            </div>
            <a href="<?= Url::to(['/admin/user-query/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors">
                Browse inquiries →
            </a>
        </div>

        <!-- Unread Leads -->
        <div class="lux-card p-5 border <?= $kpis['unread_queries'] > 0 ? 'border-[#D4AF37]/40 bg-[#D4AF37]/5' : 'border-white/5' ?> flex flex-col justify-between">
            <span class="text-[10px] uppercase font-bold tracking-wider <?= $kpis['unread_queries'] > 0 ? 'text-[#D4AF37]' : 'text-gray-400' ?>">
                New / Unread Leads
            </span>
            <div class="my-2 flex items-baseline justify-between">
                <span class="text-3xl font-serif font-black <?= $kpis['unread_queries'] > 0 ? 'text-[#D4AF37]' : 'text-white' ?>">
                    <?= $kpis['unread_queries'] ?>
                </span>
                <?php if ($kpis['unread_queries'] > 0): ?>
                    <span class="w-2.5 h-2.5 rounded-full bg-[#D4AF37] animate-ping"></span>
                <?php endif; ?>
            </div>
            <a href="<?= Url::to(['/admin/user-query/index', 'UserQuerySearch[is_read]' => 0]) ?>" class="text-[11px] text-[#D4AF37] font-semibold hover:underline">
                View new submissions →
            </a>
        </div>

        <!-- Active Services -->
        <div class="lux-card p-5 border border-white/5 flex flex-col justify-between">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Active Services</span>
            <div class="my-2">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['active_services'] ?></span>
            </div>
            <a href="<?= Url::to(['/admin/service/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors">
                Manage services →
            </a>
        </div>

        <!-- Published Projects -->
        <div class="lux-card p-5 border border-white/5 flex flex-col justify-between">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Portfolio Projects</span>
            <div class="my-2">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['portfolio_projects'] ?></span>
            </div>
            <a href="<?= Url::to(['/admin/project/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors">
                Manage portfolio →
            </a>
        </div>

        <!-- Hero Customizer Presets -->
        <div class="lux-card p-5 border border-white/5 flex flex-col justify-between">
            <span class="text-[10px] uppercase font-bold tracking-wider text-gray-400">Hero Presets</span>
            <div class="my-2">
                <span class="text-3xl font-serif font-black text-white"><?= $kpis['hero_styles'] ?></span>
            </div>
            <a href="<?= Url::to(['/admin/hero-style/index']) ?>" class="text-[11px] text-gray-400 hover:text-[#D4AF37] transition-colors">
                Configure styles →
            </a>
        </div>

    </div>

    <!-- Recent Queries Table -->
    <div class="lux-card p-6 border border-white/5 space-y-4">
        
        <div class="flex items-center justify-between">
            <div>
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">LEAD PIPELINE</span>
                <h3 class="text-lg font-serif font-bold text-white">Recent Client Inquiries</h3>
            </div>
            <a href="<?= Url::to(['/admin/user-query/index']) ?>" class="text-xs text-[#D4AF37] hover:underline font-semibold">
                View All Inquiries →
            </a>
        </div>

        <?php if (empty($recentQueries)): ?>
            <p class="text-xs text-gray-400 py-8 text-center">No leads recorded yet. Test by submitting an inquiry from the website.</p>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="table-lux">
                    <thead>
                        <tr>
                            <th>Lead</th>
                            <th>Contact</th>
                            <th>Source</th>
                            <th>Service / Style</th>
                            <th>Status</th>
                            <th>Received</th>
                            <th class="text-right">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($recentQueries as $q): ?>
                            <tr class="<?= $q->is_read === 0 ? 'bg-[#D4AF37]/[0.02]' : '' ?>">
                                <td>
                                    <div class="flex items-center gap-2">
                                        <?php if ($q->is_read === 0): ?>
                                            <span class="w-2 h-2 rounded-full bg-[#D4AF37] shrink-0"></span>
                                        <?php endif; ?>
                                        <a href="<?= Url::to(['/admin/user-query/view', 'id' => $q->id]) ?>" class="font-bold text-white hover:text-[#D4AF37] transition-colors">
                                            <?= Html::encode($q->full_name) ?>
                                        </a>
                                    </div>
                                    <?php if ($q->property_type || $q->carpet_area_sqft): ?>
                                        <span class="text-[10px] text-gray-400 font-mono block">
                                            <?= Html::encode($q->property_type) ?> <?= $q->carpet_area_sqft ? '· ' . $q->carpet_area_sqft . ' sqft' : '' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="font-mono text-xs text-gray-200 block"><?= Html::encode($q->phone) ?></span>
                                    <span class="text-[11px] text-gray-400 block"><?= Html::encode($q->email ?: '—') ?></span>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-white/5 text-gray-300">
                                        <?= Html::encode($q->query_source) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-xs text-white block"><?= Html::encode($q->selected_service ?: ($q->selected_hero_style ?: '—')) ?></span>
                                    <?php if ($q->estimated_budget): ?>
                                        <span class="text-[10px] text-[#D4AF37] font-mono block"><?= Html::encode($q->estimated_budget) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase <?= $q->status === 'New' ? 'bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/40' : ($q->status === 'Converted' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/10 text-gray-300') ?>">
                                        <?= Html::encode($q->status) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-xs text-gray-400 font-mono">
                                        <?= Yii::$app->formatter->asDatetime($q->created_at, 'php:d M, h:i A') ?>
                                    </span>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <!-- 1-Click WhatsApp Reply -->
                                        <a href="<?= $q->getWhatsappReplyUrl() ?>" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="p-1.5 rounded-lg bg-[#25D366]/15 text-emerald-400 hover:bg-[#25D366]/30 transition-colors"
                                           title="Reply via WhatsApp">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </a>

                                        <!-- View Details -->
                                        <a href="<?= Url::to(['/admin/user-query/view', 'id' => $q->id]) ?>" 
                                           class="px-2.5 py-1 rounded bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 text-xs font-semibold transition-colors">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

    </div>

</div>
