<?php

/** @var yii\web\View $this */
/** @var yii\data\ActiveDataProvider $dataProvider */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = 'Individual Pages & SEO Manager';
?>

<div class="space-y-6">
    <div>
        <h2 class="text-xl font-serif font-bold text-white">Pages & SEO Metadata</h2>
        <p class="text-xs text-gray-400 mt-0.5">Edit headings, hero banners, rich content, milestones, and meta tags for all public site routes.</p>
    </div>

    <div class="lux-card border border-white/5 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="table-lux">
                <thead>
                    <tr>
                        <th>Page</th>
                        <th>Route URL</th>
                        <th>Heading</th>
                        <th>Last Modified</th>
                        <th class="text-right">Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($dataProvider->getModels() as $m): ?>
                        <tr>
                            <td>
                                <span class="font-bold text-white block uppercase tracking-wider text-xs">
                                    <?= Html::encode($m->slug) ?>
                                </span>
                            </td>
                            <td>
                                <a href="<?= Url::to([$m->slug === 'home' ? '/site/index' : ($m->slug === 'about' ? '/site/about' : ($m->slug === 'contact' ? '/site/contact' : ($m->slug === 'estimator' ? '/estimator/index' : ($m->slug === 'services' ? '/service/index' : '/portfolio/index'))))]) ?>" 
                                   target="_blank"
                                   class="font-mono text-xs text-[#D4AF37] hover:underline">
                                    /<?= $m->slug === 'home' ? '' : Html::encode($m->slug) ?>
                                </a>
                            </td>
                            <td class="text-xs text-gray-200">
                                <?= Html::encode($m->title) ?>
                            </td>
                            <td class="text-xs font-mono text-gray-400">
                                <?= $m->updated_at ? Yii::$app->formatter->asDatetime($m->updated_at) : 'Initial' ?>
                            </td>
                            <td class="text-right">
                                <a href="<?= Url::to(['update', 'id' => $m->id]) ?>" class="px-3 py-1 rounded bg-white/5 hover:bg-[#D4AF37]/20 hover:text-[#D4AF37] text-gray-300 text-xs font-semibold transition-colors">
                                    Edit Content & SEO
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
