<?php

/** @var yii\web\View $this */
/** @var app\models\CartItem[] $items */
/** @var float $total */
/** @var app\models\SiteSettings $settings */
/** @var int $cartCount */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<section class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-16 md:py-24">

    <!-- Header -->
    <div class="flex items-end justify-between gap-4 mb-12">
        <div>
            <span class="kicker text-[11px] mb-2">ICONIC SHOP</span>
            <h1 class="text-3xl sm:text-4xl font-serif font-black text-white">Your <span class="text-gold-gradient italic">Cart</span></h1>
        </div>
        <a href="<?= Url::to(['/product/index']) ?>" class="btn-ghost btn-sm">
            ← Continue Shopping
        </a>
    </div>

    <?php if (empty($items)): ?>
        <!-- Empty Cart -->
        <div class="text-center py-24 flex flex-col items-center gap-5">
            <div class="w-20 h-20 rounded-3xl bg-[#1A1A1A] border border-white/10 flex items-center justify-center text-3xl text-[#D4AF37]">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
            </div>
            <h2 class="text-xl font-serif text-white">Your cart is empty</h2>
            <p class="text-sm text-gray-400">Browse our curated collection and add pieces that speak to your aesthetic.</p>
            <a href="<?= Url::to(['/product/index']) ?>" class="btn-gold px-8 py-3">Explore the Collection</a>
        </div>

    <?php else: ?>
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-10">

            <!-- Cart Items -->
            <div class="lg:col-span-8 flex flex-col gap-4">
                <?php foreach ($items as $item): ?>
                    <?php if (!$item->product) continue; ?>
                    <?php $p = $item->product; ?>
                    <div class="card-lux p-5 flex items-start gap-5 relative" id="cart-row-<?= $p->id ?>">

                        <!-- Product image -->
                        <a href="<?= Url::to(['/product/view', 'slug' => $p->slug]) ?>"
                           class="w-20 h-20 sm:w-24 sm:h-24 rounded-xl overflow-hidden flex-shrink-0 bg-[#111]">
                            <?php if ($p->featured_image): ?>
                                <img src="<?= Html::encode($p->getImageUrl()) ?>"
                                     alt="<?= Html::encode($p->name) ?>"
                                     class="w-full h-full object-cover">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-[#D4AF37]/30 text-2xl">✦</div>
                            <?php endif; ?>
                        </a>

                        <div class="flex-1 min-w-0">
                            <span class="text-[10px] text-[#D4AF37] uppercase font-bold tracking-widest"><?= Html::encode($p->category) ?></span>
                            <h3 class="text-base font-serif font-bold text-white mt-0.5 leading-snug">
                                <a href="<?= Url::to(['/product/view', 'slug' => $p->slug]) ?>" class="hover:text-[#D4AF37] transition-colors">
                                    <?= Html::encode($p->name) ?>
                                </a>
                            </h3>
                            <?php if ($p->sku): ?>
                                <p class="text-[10px] text-gray-600 font-mono mt-0.5">SKU: <?= Html::encode($p->sku) ?></p>
                            <?php endif; ?>

                            <div class="flex items-center justify-between mt-4 gap-3 flex-wrap">
                                <!-- Qty stepper -->
                                <div class="flex items-center border border-white/10 rounded-xl overflow-hidden">
                                    <button type="button"
                                            onclick="updateQty(<?= $p->id ?>, <?= max(0, $item->quantity - 1) ?>)"
                                            class="w-9 h-9 text-gray-400 hover:text-white hover:bg-white/5 transition-colors flex items-center justify-center">−</button>
                                    <span class="w-8 text-center text-sm font-bold text-white" id="qty-<?= $p->id ?>"><?= $item->quantity ?></span>
                                    <button type="button"
                                            onclick="updateQty(<?= $p->id ?>, <?= $item->quantity + 1 ?>)"
                                            class="w-9 h-9 text-gray-400 hover:text-white hover:bg-white/5 transition-colors flex items-center justify-center">+</button>
                                </div>

                                <!-- Line total -->
                                <span class="text-base font-bold text-white" id="line-total-<?= $p->id ?>">
                                    ₹<?= number_format($p->price * $item->quantity, 0, '.', ',') ?>
                                </span>

                                <!-- Remove -->
                                <button type="button"
                                        onclick="removeItem(<?= $p->id ?>)"
                                        class="text-gray-600 hover:text-red-400 transition-colors"
                                        aria-label="Remove <?= Html::encode($p->name) ?>">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>

            <!-- Order Summary -->
            <div class="lg:col-span-4">
                <div class="glass rounded-3xl p-7 border border-white/10 sticky top-28 flex flex-col gap-5">
                    <h2 class="text-sm font-bold uppercase tracking-[0.2em] text-white">Order Summary</h2>

                    <div class="flex flex-col gap-3 text-sm border-b border-white/5 pb-5">
                        <div class="flex justify-between text-gray-400">
                            <span>Subtotal (<?= $cartCount ?> item<?= $cartCount !== 1 ? 's' : '' ?>)</span>
                            <span id="cart-subtotal">₹<?= number_format($total, 0, '.', ',') ?></span>
                        </div>
                        <div class="flex justify-between text-gray-400">
                            <span>Shipping</span>
                            <span class="text-emerald-400 text-xs font-semibold">Calculated at checkout</span>
                        </div>
                    </div>

                    <div class="flex justify-between items-baseline">
                        <span class="text-sm font-bold text-white uppercase tracking-wider">Total</span>
                        <span class="text-2xl font-black text-white" id="cart-total">₹<?= number_format($total, 0, '.', ',') ?></span>
                    </div>

                    <!-- Checkout via WhatsApp -->
                    <a href="<?= $settings->whatsappUrl('Hello, I would like to place an order from the ICONIC INTERIORS shop. My cart total is ₹' . number_format($total, 0, '.', ',') . '. Please assist with checkout and delivery.') ?>"
                       target="_blank" rel="noopener noreferrer"
                       class="btn-gold text-center py-4 font-bold uppercase tracking-widest text-xs flex items-center justify-center gap-2">
                        <svg class="w-4 h-4 fill-current" viewBox="0 0 24 24">
                            <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                        </svg>
                        <span>Checkout via WhatsApp</span>
                    </a>

                    <button type="button"
                            @click="$dispatch('open-inquiry-modal', { source: 'Cart Checkout', message: 'Hello, I would like to place an order. My cart total is ₹<?= number_format($total, 0, '.', ',') ?>.' })"
                            class="btn-ghost text-center py-3 text-xs uppercase tracking-widest font-bold">
                        Request Callback Instead
                    </button>

                    <!-- Trust badges -->
                    <div class="pt-4 border-t border-white/5 flex flex-col gap-2 text-xs text-gray-500">
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                            </svg>
                            Authenticity guaranteed on all pieces
                        </div>
                        <div class="flex items-center gap-2">
                            <svg class="w-3.5 h-3.5 text-[#D4AF37] shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"/>
                            </svg>
                            White-glove delivery pan-India
                        </div>
                    </div>
                </div>
            </div>
        </div>
    <?php endif; ?>
</section>

<?php
$updateUrl = Url::to(['/product/update-cart']);
$removeUrl = Url::to(['/product/remove-from-cart']);
$productPrices = [];
foreach ($items as $item) {
    if ($item->product) {
        $productPrices[$item->product->id] = (float) $item->product->price;
    }
}
$pricesJson = json_encode($productPrices);

$this->registerJs(<<<JS
const PRODUCT_PRICES = {$pricesJson};

function updateQty(productId, newQty) {
    fetch('$updateUrl', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId + '&qty=' + newQty + '&_csrf=' + (document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '')
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            if (newQty <= 0) {
                const row = document.getElementById('cart-row-' + productId);
                if (row) row.remove();
            } else {
                const qtyEl = document.getElementById('qty-' + productId);
                if (qtyEl) qtyEl.textContent = newQty;
                const ltEl = document.getElementById('line-total-' + productId);
                if (ltEl) ltEl.textContent = '₹' + Math.round(PRODUCT_PRICES[productId] * newQty).toLocaleString('en-IN');
            }
            updateCartBadge(data.cart_count);
            location.reload(); // reload to update totals
        }
    });
}

function removeItem(productId) {
    fetch('$removeUrl', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId + '&_csrf=' + (document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '')
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const row = document.getElementById('cart-row-' + productId);
            if (row) {
                row.style.opacity = '0';
                row.style.transition = 'opacity 0.3s';
                setTimeout(() => { row.remove(); location.reload(); }, 300);
            }
            updateCartBadge(data.cart_count);
        }
    });
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        badge.textContent = count;
        if (count > 0) badge.classList.remove('hidden');
        else badge.classList.add('hidden');
    }
}
JS);
?>
