<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\components\Media;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Portfolio Manager';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">Portfolio Case Studies</h2>
            <p class="text-xs text-gray-400 mt-0.5">Manage projects, before/after photographs, client briefs, and gallery albums.</p>
        </div>
        <a href="<?= Url::to(['create']) ?>" class="btn-gold btn-sm">
            <span>+ Add New Project</span>
        </a>
    </div>

    <div class="lux-card border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-lux">
                <thead>
                    <tr>
                        <th>Cover</th>
                        <th>Project & Category</th>
                        <th>Location & Area</th>
                        <th>Tier</th>
                        <th>Featured</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider->getModels() as $m): ?>
                        <tr>
                            <td class="w-20">
                                <div class="w-16 aspect-[16/10] rounded-lg overflow-hidden border border-white/10">
                                    <img src="<?= $m->getCoverUrl() ?>" alt="" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td>
                                <span class="font-bold text-white block"><?= Html::encode($m->title) ?></span>
                                <span class="text-xs text-[#D4AF37] font-mono"><?= Html::encode($m->category) ?></span>
                            </td>
                            <td class="text-xs">
                                <span class="text-white block"><?= Html::encode($m->location ?: '—') ?></span>
                                <span class="text-gray-400 font-mono"><?= $m->carpet_area ? number_format($m->carpet_area) . ' sq.ft.' : '—' ?></span>
                            </td>
                            <td class="text-xs font-semibold text-gray-300">
                                <?= Html::encode($m->budget_tier ?: '—') ?>
                            </td>
                            <td>
                                <?php if ($m->is_featured): ?>
                                    <span class="px-2 py-0.5 rounded text-[10px] font-bold uppercase tracking-wider bg-[#D4AF37]/20 text-[#D4AF37]">
                                        ★ Featured
                                    </span>
                                <?php else: ?>
                                    <span class="text-gray-400 text-xs">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $m->is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-gray-700 text-gray-400' ?>">
                                    <?= $m->is_active ? 'Active' : 'Draft' ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <a href="<?= Url::to(['/portfolio/view', 'slug' => $m->slug]) ?>" target="_blank" class="px-2.5 py-1 rounded bg-white/5 hover:bg-white/10 text-gray-300 transition-colors">
                                        View
                                    </a>
                                    <a href="<?= Url::to(['update', 'id' => $m->id]) ?>" class="px-2.5 py-1 rounded bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 transition-colors">
                                        Edit
                                    </a>
                                    <?= Html::a('Delete', ['delete', 'id' => $m->id], [
                                        'class' => 'px-2.5 py-1 rounded bg-red-950/30 hover:bg-red-900/50 text-red-400 transition-colors',
                                        'data' => [
                                            'confirm' => 'Delete this portfolio project?',
                                            'method' => 'post',
                                        ],
                                    ]) ?>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
