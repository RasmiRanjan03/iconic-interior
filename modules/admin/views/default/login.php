<?php

/** @var yii\web\View $this */
/** @var app\models\LoginForm $model */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Admin Authentication';
?>
<!DOCTYPE html>
<html lang="en" class="dark h-full">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= Html::encode($this->title) ?> | ICONIC INTERIORS</title>

    <!-- Brand Favicons -->
    <link rel="icon" type="image/x-icon" href="<?= Yii::getAlias('@web/favicon.ico') ?>">
    <link rel="icon" type="image/png" sizes="32x32" href="<?= Yii::getAlias('@web/favicon-32x32.png') ?>">
    <link rel="icon" type="image/png" sizes="64x64" href="<?= Yii::getAlias('@web/images/favicon.png') ?>">
    <link rel="apple-touch-icon" sizes="180x180" href="<?= Yii::getAlias('@web/images/apple-touch-icon.png') ?>">
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@700;800&family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        body { background: #0A0A0A; color: #F9FAFB; font-family: 'Plus Jakarta Sans', sans-serif; }
        .glass { background: linear-gradient(160deg, rgba(24,24,24,.85), rgba(14,14,14,.75)); backdrop-filter: blur(20px); border: 1px solid rgba(212,175,55,.25); }
        .lux-input { width: 100%; background: rgba(255,255,255,.04); border: 1px solid rgba(255,255,255,.1); color: #fff; border-radius: .75rem; padding: .85rem 1rem; font-size: .88rem; outline: none; transition: all .3s; }
        .lux-input:focus { border-color: #D4AF37; box-shadow: 0 0 0 4px rgba(212,175,55,.15); background: rgba(255,255,255,.06); }
        .btn-gold { background: linear-gradient(135deg, #F6E7A8 0%, #D4AF37 30%, #B8892B 55%, #F1D68C 80%, #C5A059 100%); color: #141008; font-weight: 700; border-radius: 999px; transition: all .3s; }
        .btn-gold:hover { transform: translateY(-2px); box-shadow: 0 14px 30px -10px rgba(212,175,55,.7); }
    </style>
</head>
<body class="h-full flex items-center justify-center p-4 relative overflow-hidden">
    
    <!-- Ambient Gold Glow -->
    <div class="absolute w-[600px] h-[600px] bg-[#D4AF37]/10 rounded-full blur-[140px] pointer-events-none -top-40 -left-40"></div>
    <div class="absolute w-[500px] h-[500px] bg-[#D4AF37]/5 rounded-full blur-[120px] pointer-events-none -bottom-40 -right-40"></div>

    <div class="w-full max-w-md glass rounded-3xl p-8 sm:p-10 shadow-2xl relative z-10">
        
        <!-- Monogram / Branding -->
        <div class="text-center mb-8">
            <div class="w-20 h-20 rounded-2xl bg-[#121212] border border-[#D4AF37]/40 mx-auto p-1 shadow-2xl shadow-black mb-4 relative overflow-hidden group">
                <div class="absolute -top-6 -right-6 w-14 h-14 bg-[#D4AF37]/20 rounded-full blur-lg pointer-events-none"></div>
                <img src="<?= Yii::getAlias('@web/images/logo-mark.png') ?>" alt="ICONIC INTERIORS" class="w-full h-full object-cover rounded-xl">
            </div>
            <span class="text-[10px] uppercase font-bold tracking-[0.3em] text-[#D4AF37] block">EXECUTIVE CMS</span>
            <h1 class="text-2xl font-serif font-black tracking-wider text-white mt-1">ICONIC INTERIORS</h1>
            <p class="text-xs text-gray-400 mt-1">Principal Designer & CMS Access</p>
        </div>

        <?php $form = ActiveForm::begin([
            'id' => 'admin-login-form',
            'options' => ['class' => 'space-y-5'],
        ]); ?>

        <div>
            <label class="block text-[11px] uppercase font-bold tracking-wider text-gray-400 mb-2">Username</label>
            <?= $form->field($model, 'username', ['template' => '{input}{error}'])->textInput(['placeholder' => 'admin', 'class' => 'lux-input', 'autofocus' => true]) ?>
        </div>

        <div>
            <label class="block text-[11px] uppercase font-bold tracking-wider text-gray-400 mb-2">Password</label>
            <?= $form->field($model, 'password', ['template' => '{input}{error}'])->passwordInput(['placeholder' => '••••••••', 'class' => 'lux-input']) ?>
        </div>

        <div class="flex items-center justify-between text-xs text-gray-400">
            <label class="flex items-center gap-2 cursor-pointer">
                <?= Html::activeCheckbox($model, 'rememberMe', ['label' => false]) ?>
                <span>Stay signed in</span>
            </label>
            <span class="text-gray-400 text-[11px]">Default: admin / Iconic@2026</span>
        </div>

        <div class="pt-2">
            <button type="submit" class="btn-gold w-full py-3.5 text-xs uppercase tracking-widest text-center">
                Authorize Sign In
            </button>
        </div>

        <?php ActiveForm::end(); ?>

        <div class="mt-8 pt-6 border-t border-white/5 text-center text-xs text-gray-400">
            <a href="<?= \yii\helpers\Url::to(['/site/index']) ?>" class="hover:text-[#D4AF37] transition-colors">
                ← Return to Public Website
            </a>
        </div>

    </div>

</body>
</html>
