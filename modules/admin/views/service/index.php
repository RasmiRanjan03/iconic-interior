<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\components\Media;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Services Manager';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">Signature Services Catalog</h2>
            <p class="text-xs text-gray-400 mt-0.5">Manage the 6 core disciplines, pricing starting points, deliverables, and gallery images.</p>
        </div>
        <a href="<?= Url::to(['create']) ?>" class="btn-gold btn-sm">
            <span>+ Add New Service</span>
        </a>
    </div>

    <div class="lux-card border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-lux">
                <thead>
                    <tr>
                        <th>Image</th>
                        <th>Title & Slug</th>
                        <th>Starting Price</th>
                        <th>Deliverables</th>
                        <th>Sort</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider->getModels() as $m): ?>
                        <tr>
                            <td class="w-20">
                                <div class="w-16 aspect-[16/10] rounded-lg overflow-hidden border border-white/10">
                                    <img src="<?= Media::url($m->featured_image) ?>" alt="" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td>
                                <span class="font-bold text-white block"><?= Html::encode($m->title) ?></span>
                                <span class="text-xs text-gray-400 font-mono">/services/<?= Html::encode($m->slug) ?></span>
                            </td>
                            <td class="text-xs font-mono font-semibold text-[#D4AF37]">
                                <?= $m->getPriceLabel() ?>
                            </td>
                            <td class="text-xs text-gray-300">
                                <?= count($m->getDeliverables()) ?> points
                            </td>
                            <td class="font-mono text-xs text-gray-400">
                                <?= $m->sort_order ?>
                            </td>
                            <td>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $m->is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-gray-700 text-gray-400' ?>">
                                    <?= $m->is_active ? 'Published' : 'Draft' ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <a href="<?= Url::to(['/service/view', 'slug' => $m->slug]) ?>" target="_blank" class="px-2.5 py-1 rounded bg-white/5 hover:bg-white/10 text-gray-300 transition-colors">
                                        View
                                    </a>
                                    <a href="<?= Url::to(['update', 'id' => $m->id]) ?>" class="px-2.5 py-1 rounded bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 transition-colors">
                                        Edit
                                    </a>
                                    <?= Html::a('Delete', ['delete', 'id' => $m->id], [
                                        'class' => 'px-2.5 py-1 rounded bg-red-950/30 hover:bg-red-900/50 text-red-400 transition-colors',
                                        'data' => [
                                            'confirm' => 'Delete this service?',
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
