<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use app\components\Media;
use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Hero Style Customizer Presets';
?>

<div class="space-y-6">
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <h2 class="text-xl font-serif font-bold text-white">Hero Design Presets</h2>
            <p class="text-xs text-gray-400 mt-0.5">Manage the interactive showcase presets, room combinations, before/after images, and color swatches.</p>
        </div>
        <a href="<?= Url::to(['create']) ?>" class="btn-gold btn-sm">
            <span>+ Add New Style Preset</span>
        </a>
    </div>

    <div class="lux-card border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-lux">
                <thead>
                    <tr>
                        <th>Preview</th>
                        <th>Style & Room</th>
                        <th>Default Lighting</th>
                        <th>Palette</th>
                        <th>Order</th>
                        <th>Status</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider->getModels() as $m): ?>
                        <tr>
                            <td class="w-20">
                                <div class="w-16 aspect-[16/9] rounded-lg overflow-hidden border border-white/10">
                                    <img src="<?= Media::url($m->after_image) ?>" alt="" class="w-full h-full object-cover">
                                </div>
                            </td>
                            <td>
                                <span class="font-bold text-white block"><?= Html::encode($m->style_name) ?></span>
                                <span class="text-xs text-[#D4AF37] block font-mono"><?= Html::encode($m->room_category) ?></span>
                            </td>
                            <td>
                                <span class="text-xs uppercase tracking-wider text-gray-300 font-medium">
                                    <?= Html::encode($m->lighting_mode_default) ?>
                                </span>
                            </td>
                            <td>
                                <div class="flex items-center -space-x-1">
                                    <?php foreach ($m->getPalette() as $hex): ?>
                                        <span class="w-4 h-4 rounded-full border border-black" style="background-color: <?= Html::encode($hex) ?>" title="<?= Html::encode($hex) ?>"></span>
                                    <?php endforeach; ?>
                                </div>
                            </td>
                            <td class="font-mono text-xs text-gray-400">
                                <?= $m->sort_order ?>
                            </td>
                            <td>
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-bold uppercase tracking-wider <?= $m->is_active ? 'bg-emerald-500/20 text-emerald-300' : 'bg-gray-700 text-gray-400' ?>">
                                    <?= $m->is_active ? 'Active' : 'Draft' ?>
                                </span>
                            </td>
                            <td class="text-right">
                                <div class="flex items-center justify-end gap-2 text-xs">
                                    <a href="<?= Url::to(['update', 'id' => $m->id]) ?>" class="px-2.5 py-1 rounded bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 transition-colors">
                                        Edit
                                    </a>
                                    <?= Html::a('Delete', ['delete', 'id' => $m->id], [
                                        'class' => 'px-2.5 py-1 rounded bg-red-950/30 hover:bg-red-900/50 text-red-400 transition-colors',
                                        'data' => [
                                            'confirm' => 'Delete this hero style preset?',
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
