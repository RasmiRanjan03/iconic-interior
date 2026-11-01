<?php

/** @var yii\web\View $this */
/** @var app\models\UserQuery $model */

use app\models\SiteSettings;
use app\models\UserQuery;
use yii\helpers\Html;
use yii\helpers\Url;
use yii\widgets\ActiveForm;

$this->title = "Lead #{$model->id} — " . Html::encode($model->full_name);
$settings = SiteSettings::get();
?>

<div class="space-y-6 max-w-5xl">
    
    <!-- Top Action Bar -->
    <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-4">
        <div>
            <a href="<?= Url::to(['index']) ?>" class="inline-flex items-center gap-1.5 text-xs text-gray-400 hover:text-[#D4AF37] mb-2 transition-colors">
                <span>← Back to All Leads</span>
            </a>
            <h2 class="text-2xl font-serif font-bold text-white flex items-center gap-3">
                <span><?= Html::encode($model->full_name) ?></span>
                <span class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider <?= $model->status === 'New' ? 'bg-[#D4AF37]/20 text-[#D4AF37] border border-[#D4AF37]/40' : ($model->status === 'Converted' ? 'bg-emerald-500/20 text-emerald-300' : 'bg-white/10 text-gray-300') ?>">
                    <?= Html::encode($model->status) ?>
                </span>
            </h2>
        </div>

        <div class="flex items-center gap-3">
            <!-- 1-Click WhatsApp Client Reply -->
            <a href="<?= $model->getWhatsappReplyUrl() ?>" 
               target="_blank" rel="noopener noreferrer"
               class="px-4 py-2.5 rounded-xl bg-[#25D366] hover:bg-[#20ba59] text-black font-bold text-xs uppercase tracking-wider flex items-center gap-2 transition-all shadow-lg shadow-[#25D366]/20">
                <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>Reply on WhatsApp</span>
            </a>

            <!-- Delete Action -->
            <?= Html::a('Delete Lead', ['delete', 'id' => $model->id], [
                'class' => 'px-3 py-2 rounded-xl bg-red-950/40 border border-red-500/30 text-red-300 hover:text-red-100 hover:bg-red-900/50 text-xs font-semibold transition-colors',
                'data' => [
                    'confirm' => 'Are you sure you want to delete this client query permanently?',
                    'method' => 'post',
                ],
            ]) ?>
        </div>
    </div>

    <!-- Main Content Details Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 items-start">
        
        <!-- Left: Full Client Submission Data (7 cols) -->
        <div class="lg:col-span-7 flex flex-col gap-6">
            
            <!-- Contact Details Card -->
            <div class="lux-card p-6 border border-white/5 space-y-4">
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">CLIENT CONTACT DETAILS</span>
                
                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Full Name</span>
                        <span class="text-sm font-bold text-white"><?= Html::encode($model->full_name) ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Phone / WhatsApp</span>
                        <a href="tel:<?= Html::encode($model->phone) ?>" class="text-sm font-mono font-bold text-[#D4AF37] hover:underline">
                            <?= Html::encode($model->phone) ?>
                        </a>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Email</span>
                        <span class="text-white"><?= Html::encode($model->email ?: 'Not provided') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Project Location</span>
                        <span class="text-white"><?= Html::encode($model->preferred_location ?: 'Not specified') ?></span>
                    </div>
                </div>
            </div>

            <!-- Inquiry Scope & Specifications Card -->
            <div class="lux-card p-6 border border-white/5 space-y-4">
                <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">PROJECT SCOPE & SELECTIONS</span>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 text-xs">
                    <div>
                        <span class="text-gray-400 block mb-0.5">Query Source</span>
                        <span class="px-2 py-0.5 rounded text-[11px] font-bold tracking-wider uppercase bg-white/10 text-white inline-block">
                            <?= Html::encode($model->query_source) ?>
                        </span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Selected Discipline</span>
                        <span class="font-bold text-white"><?= Html::encode($model->selected_service ?: '—') ?></span>
                    </div>
                    <?php if ($model->selected_hero_style): ?>
                        <div class="sm:col-span-2">
                            <span class="text-gray-400 block mb-0.5">Hero Style Customizer Preset</span>
                            <span class="font-bold text-[#D4AF37]"><?= Html::encode($model->selected_hero_style) ?></span>
                        </div>
                    <?php endif; ?>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Property Type</span>
                        <span class="text-white"><?= Html::encode($model->property_type ?: '—') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Carpet Area</span>
                        <span class="font-mono text-white"><?= $model->carpet_area_sqft ? number_format($model->carpet_area_sqft) . ' sq.ft.' : '—' ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Finish Tier</span>
                        <span class="text-white"><?= Html::encode($model->finish_tier ?: '—') ?></span>
                    </div>
                    <div>
                        <span class="text-gray-400 block mb-0.5">Estimated Budget Range</span>
                        <span class="font-mono font-bold text-[#D4AF37] text-sm"><?= Html::encode($model->estimated_budget ?: '—') ?></span>
                    </div>
                </div>

                <!-- Client Message -->
                <div class="pt-4 border-t border-white/5">
                    <span class="text-gray-400 text-xs block mb-1">Client Message / Notes</span>
                    <div class="p-4 rounded-xl bg-white/[0.02] border border-white/5 text-sm text-gray-200 leading-relaxed font-light">
                        <?= nl2br(Html::encode($model->message ?: 'No additional message provided.')) ?>
                    </div>
                </div>
            </div>

            <!-- Metadata info -->
            <div class="p-4 rounded-xl bg-white/[0.015] border border-white/5 flex items-center justify-between text-[11px] text-gray-400 font-mono">
                <span>Received: <?= Yii::$app->formatter->asDatetime($model->created_at) ?></span>
                <span>IP: <?= Html::encode($model->ip_address ?: 'Unknown') ?></span>
            </div>

        </div>

        <!-- Right: Admin Status & Internal Notes (5 cols) -->
        <div class="lg:col-span-5 flex flex-col gap-6">
            
            <div class="lux-card p-6 border border-[#D4AF37]/30 shadow-xl space-y-6">
                <div>
                    <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">LEAD MANAGEMENT</span>
                    <h3 class="text-lg font-serif font-bold text-white mt-1">Status & Santosh's Notes</h3>
                    <p class="text-xs text-gray-400 mt-0.5">Update stage and log internal site visit details.</p>
                </div>

                <?php $form = ActiveForm::begin([
                    'id' => 'lead-update-form',
                    'options' => ['class' => 'space-y-4'],
                ]); ?>

                <div>
                    <label class="block text-xs uppercase font-bold tracking-wider text-gray-400 mb-2">Change Lead Status</label>
                    <?= $form->field($model, 'status', ['template' => '{input}{error}'])->dropDownList(
                        array_combine(UserQuery::STATUSES, UserQuery::STATUSES),
                        ['class' => 'w-full bg-[#181818] border border-white/10 rounded-xl px-3.5 py-2.5 text-white font-semibold outline-none focus:border-[#D4AF37]']
                    ) ?>
                </div>

                <div>
                    <label class="block text-xs uppercase font-bold tracking-wider text-gray-400 mb-2">Internal Notes (Santosh Kumar Maharana & Team)</label>
                    <?= $form->field($model, 'admin_notes', ['template' => '{input}{error}'])->textarea([
                        'rows' => 6,
                        'placeholder' => 'Enter site visit feedback, architectural scope adjustments, client budget constraints, or quotation numbers...',
                        'class' => 'w-full bg-[#181818] border border-white/10 rounded-xl p-3.5 text-xs text-white outline-none focus:border-[#D4AF37] font-light leading-relaxed',
                    ]) ?>
                </div>

                <div class="pt-2">
                    <button type="submit" class="btn-gold w-full py-3 text-xs uppercase font-bold tracking-widest text-center">
                        Save Status & Notes
                    </button>
                </div>

                <?php ActiveForm::end(); ?>
            </div>

            <!-- Quick WhatsApp Action Summary Box -->
            <div class="p-5 rounded-2xl bg-[#25D366]/10 border border-[#25D366]/30 text-xs flex flex-col gap-2.5">
                <span class="font-bold uppercase tracking-wider text-emerald-400 flex items-center gap-1.5">
                    <span>✦</span>
                    <span>Direct Concierge Link</span>
                </span>
                <p class="text-gray-300 font-light leading-relaxed">
                    Clicking the WhatsApp button opens a pre-composed message directly addressed to <strong class="text-white"><?= Html::encode($model->full_name) ?></strong> with their chosen scope (<?= Html::encode($model->selected_service ?: $model->selected_hero_style ?: 'Interiors') ?>).
                </p>
                <a href="<?= $model->getWhatsappReplyUrl() ?>" 
                   target="_blank" rel="noopener noreferrer"
                   class="text-emerald-400 hover:text-emerald-300 font-bold hover:underline inline-flex items-center gap-1 mt-1">
                    <span>Launch WhatsApp Web / App →</span>
                </a>
            </div>

        </div>

    </div>

</div>
