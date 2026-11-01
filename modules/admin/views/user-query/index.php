<?php

/** @var yii\web\View $this */
/** @var app\models\UserQuerySearch $searchModel */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\models\Service;
use app\models\UserQuery;
use yii\grid\GridView;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'User Queries & Lead Pipeline';
$serviceList = Service::titleList();
?>

<div class="space-y-6">
    
    <!-- Top Bar with CSV Export -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">All Client Inquiries & Leads</h2>
            <p class="text-xs text-gray-400 mt-0.5">Filter, track conversion status, review user inputs, and forward to WhatsApp.</p>
        </div>

        <div class="flex items-center gap-3">
            <a href="<?= Url::to(array_merge(['export-csv'], Yii::$app->request->queryParams)) ?>" 
               class="px-4 py-2 rounded-xl bg-white/5 hover:bg-white/10 border border-white/10 text-xs font-semibold text-gray-200 hover:text-white transition-colors flex items-center gap-2">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
                <span>Export Filtered CSV</span>
            </a>
        </div>
    </div>

    <!-- Quick Status Filter Pills -->
    <div class="flex items-center gap-2 flex-wrap">
        <a href="<?= Url::to(['index']) ?>" 
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors <?= empty($searchModel->status) && $searchModel->is_read === null ? 'bg-[#D4AF37] text-black font-bold' : 'bg-white/5 text-gray-400 hover:text-white' ?>">
            All (<?= UserQuery::find()->count() ?>)
        </a>
        <a href="<?= Url::to(['index', 'UserQuerySearch[is_read]' => 0]) ?>" 
           class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors <?= $searchModel->is_read === '0' || $searchModel->is_read === 0 ? 'bg-[#D4AF37] text-black font-bold' : 'bg-white/5 text-amber-300 hover:text-amber-200' ?>">
            Unread (<?= UserQuery::find()->where(['is_read' => 0])->count() ?>)
        </a>
        <?php foreach (UserQuery::STATUSES as $st): ?>
            <a href="<?= Url::to(['index', 'UserQuerySearch[status]' => $st]) ?>" 
               class="px-3 py-1.5 rounded-lg text-xs font-semibold transition-colors <?= $searchModel->status === $st ? 'bg-[#D4AF37] text-black font-bold' : 'bg-white/5 text-gray-400 hover:text-white' ?>">
                <?= $st ?> (<?= UserQuery::find()->where(['status' => $st])->count() ?>)
            </a>
        <?php endforeach; ?>
    </div>

    <!-- Search / Filter Form -->
    <div class="lux-card p-5 border border-white/5">
        <form method="get" action="<?= Url::to(['index']) ?>" class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3 text-xs">
            
            <div>
                <label class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block mb-1">Search Keywords</label>
                <input type="text" name="UserQuerySearch[q]" value="<?= Html::encode($searchModel->q) ?>" placeholder="Name, phone, email, location..." class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white outline-none focus:border-[#D4AF37]">
            </div>

            <div>
                <label class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block mb-1">Source</label>
                <select name="UserQuerySearch[query_source]" class="w-full bg-[#181818] border border-white/10 rounded-lg px-3 py-2 text-white outline-none focus:border-[#D4AF37]">
                    <option value="">All Sources</option>
                    <?php foreach (UserQuery::SOURCES as $s): ?>
                        <option value="<?= $s ?>" <?= $searchModel->query_source === $s ? 'selected' : '' ?>><?= $s ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block mb-1">Service</label>
                <select name="UserQuerySearch[selected_service]" class="w-full bg-[#181818] border border-white/10 rounded-lg px-3 py-2 text-white outline-none focus:border-[#D4AF37]">
                    <option value="">All Services</option>
                    <?php foreach ($serviceList as $title): ?>
                        <option value="<?= Html::encode($title) ?>" <?= $searchModel->selected_service === $title ? 'selected' : '' ?>><?= Html::encode($title) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div>
                <label class="text-[10px] uppercase font-bold tracking-wider text-gray-400 block mb-1">From Date</label>
                <input type="date" name="UserQuerySearch[date_from]" value="<?= Html::encode($searchModel->date_from) ?>" class="w-full bg-white/5 border border-white/10 rounded-lg px-3 py-2 text-white outline-none focus:border-[#D4AF37]">
            </div>

            <div class="flex items-end gap-2">
                <button type="submit" class="btn-gold py-2 px-4 text-xs font-bold w-full text-center">
                    Filter
                </button>
                <a href="<?= Url::to(['index']) ?>" class="p-2 rounded-lg bg-white/5 hover:bg-white/10 text-gray-300" title="Reset">
                    ↺
                </a>
            </div>

        </form>
    </div>

    <!-- Leads GridView Container -->
    <div class="lux-card border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-lux">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client Details</th>
                        <th>Source</th>
                        <th>Property / Requirement</th>
                        <th>Budget</th>
                        <th>Status</th>
                        <th>Date</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $models = $dataProvider->getModels(); ?>
                    <?php if (empty($models)): ?>
                        <tr>
                            <td colspan="8" class="text-center py-10 text-gray-400 text-xs">
                                No user inquiries matching the active criteria.
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($models as $m): ?>
                            <tr class="<?= $m->is_read === 0 ? 'bg-[#D4AF37]/[0.03]' : '' ?>">
                                <td class="font-mono text-gray-400 text-xs">
                                    #<?= $m->id ?>
                                </td>
                                <td>
                                    <div class="flex items-center gap-2">
                                        <?php if ($m->is_read === 0): ?>
                                            <span class="w-2 h-2 rounded-full bg-[#D4AF37] shrink-0" title="Unread Lead"></span>
                                        <?php endif; ?>
                                        <a href="<?= Url::to(['view', 'id' => $m->id]) ?>" class="font-bold text-white hover:text-[#D4AF37] transition-colors">
                                            <?= Html::encode($m->full_name) ?>
                                        </a>
                                    </div>
                                    <div class="text-[11px] text-gray-400 mt-0.5">
                                        <span class="font-mono text-gray-300"><?= Html::encode($m->phone) ?></span>
                                        <?php if ($m->preferred_location): ?>
                                            <span>· <?= Html::encode($m->preferred_location) ?></span>
                                        <?php endif; ?>
                                    </div>
                                </td>
                                <td>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold tracking-wider uppercase bg-white/5 text-gray-300">
                                        <?= Html::encode($m->query_source) ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="text-xs text-white block">
                                        <?= Html::encode($m->selected_service ?: ($m->selected_hero_style ?: 'General Consultation')) ?>
                                    </span>
                                    <?php if ($m->property_type || $m->carpet_area_sqft): ?>
                                        <span class="text-[11px] text-gray-400 font-mono block">
                                            <?= Html::encode($m->property_type) ?> <?= $m->carpet_area_sqft ? '· ' . $m->carpet_area_sqft . ' sqft' : '' ?>
                                        </span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="font-mono text-xs font-semibold text-[#D4AF37]">
                                        <?= Html::encode($m->estimated_budget ?: '—') ?>
                                    </span>
                                    <?php if ($m->finish_tier): ?>
                                        <span class="text-[10px] text-gray-400 block"><?= Html::encode($m->finish_tier) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold tracking-wider uppercase <?= $m->status === 'New' ? 'bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/40' : ($m->status === 'Converted' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/10 text-gray-300') ?>">
                                        <?= Html::encode($m->status) ?>
                                    </span>
                                </td>
                                <td class="text-xs font-mono text-gray-400">
                                    <?= Yii::$app->formatter->asDatetime($m->created_at, 'php:d M Y, h:i A') ?>
                                </td>
                                <td class="text-right">
                                    <div class="flex items-center justify-end gap-2">
                                        <a href="<?= $m->getWhatsappReplyUrl() ?>" 
                                           target="_blank" rel="noopener noreferrer"
                                           class="p-1.5 rounded-lg bg-[#25D366]/15 text-emerald-400 hover:bg-[#25D366]/30 transition-colors"
                                           title="Reply via WhatsApp">
                                            <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                                                <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                                            </svg>
                                        </a>
                                        <a href="<?= Url::to(['view', 'id' => $m->id]) ?>" 
                                           class="px-2.5 py-1 rounded bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 text-xs font-semibold transition-colors">
                                            View
                                        </a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-4 border-t border-white/5 flex items-center justify-between text-xs text-gray-400">
            <span>Showing <?= count($models) ?> of <?= $dataProvider->getTotalCount() ?> records</span>
            <div>
                <?= \yii\widgets\LinkPager::widget([
                    'pagination' => $dataProvider->pagination,
                    'options' => ['class' => 'flex items-center gap-1.5'],
                    'linkOptions' => ['class' => 'px-3 py-1 rounded bg-white/5 text-gray-300 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37]'],
                    'disabledPageCssClass' => 'opacity-30 pointer-events-none',
                    'activePageCssClass' => 'bg-[#D4AF37] text-black font-bold',
                ]) ?>
            </div>
        </div>
    </div>

</div>
