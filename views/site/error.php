<?php

/** @var yii\web\View $this */
/** @var string $name */
/** @var string $message */
/** @var Exception $exception */

use yii\helpers\Html;
use yii\helpers\Url;

$this->title = $name;
?>
<section class="min-h-[70vh] flex items-center justify-center py-20 px-4 text-center">
    <div class="max-w-xl mx-auto glass rounded-3xl p-10 sm:p-14 border border-[#D4AF37]/30 shadow-2xl relative">
        <div class="corner-marks"></div>
        
        <span class="text-6xl sm:text-7xl font-serif font-black text-gold-gradient block mb-4">
            <?= $exception->statusCode ?? 404 ?>
        </span>
        
        <h1 class="text-2xl sm:text-3xl font-serif font-bold text-white mb-4">
            <?= Html::encode($name) ?>
        </h1>
        
        <p class="text-sm text-gray-400 font-light leading-relaxed mb-8">
            <?= nl2br(Html::encode($message)) ?>
        </p>

        <div class="flex items-center justify-center gap-4">
            <a href="<?= Url::to(['/site/index']) ?>" class="btn-gold btn-sm">
                <span>Return to Home</span>
            </a>
            <a href="<?= Url::to(['/site/contact']) ?>" class="btn-ghost btn-sm">
                <span>Contact Studio</span>
            </a>
        </div>
    </div>
</section>
