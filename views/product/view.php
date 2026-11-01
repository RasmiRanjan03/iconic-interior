<?php

/** @var yii\web\View $this */
/** @var app\models\Product $product */
/** @var app\models\Product[] $related */
/** @var app\models\SiteSettings $settings */
/** @var int $cartCount */

use yii\helpers\Html;
use yii\helpers\Url;

$specs   = $product->getSpecs();
$tags    = $product->getTags();
$gallery = $product->getGallery();
$disc    = $product->getDiscountPercent();
?>

<!-- =========================================================================
     PRODUCT DETAIL — Breadcrumb
     ========================================================================= -->
<nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-5 flex items-center gap-2 text-xs text-gray-500" aria-label="Breadcrumb">
    <a href="<?= Url::to(['/site/index']) ?>" class="hover:text-[#D4AF37] transition-colors">Home</a>
    <span>›</span>
    <a href="<?= Url::to(['/product/index']) ?>" class="hover:text-[#D4AF37] transition-colors">Shop</a>
    <span>›</span>
    <a href="<?= Url::to(['/product/index', 'category' => $product->category]) ?>" class="hover:text-[#D4AF37] transition-colors"><?= Html::encode($product->category) ?></a>
    <span>›</span>
    <span class="text-gray-300"><?= Html::encode($product->name) ?></span>
</nav>

<!-- =========================================================================
     PRODUCT DETAIL — Main
     ========================================================================= -->
<section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 pb-20">
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-12 items-start">

        <!-- LEFT: Images -->
        <div class="lg:col-span-7" x-data="{ activeImg: 0, imgs: <?= Html::encode(json_encode(array_merge($product->featured_image ? [$product->getImageUrl()] : [], $gallery))) ?> }">

            <!-- Main image -->
            <div class="gold-frame aspect-[4/3] overflow-hidden rounded-2xl shadow-2xl relative bg-[#111]">
                <template x-for="(img, i) in imgs" :key="i">
                    <img :src="img"
                         :alt="'<?= Html::encode(addslashes($product->name)) ?> view ' + (i+1)"
                         x-show="activeImg === i"
                         x-transition:enter="transition ease-out duration-300"
                         x-transition:enter-start="opacity-0 scale-105"
                         x-transition:enter-end="opacity-100 scale-100"
                         class="w-full h-full object-cover absolute inset-0">
                </template>
                <?php if (!$product->featured_image && empty($gallery)): ?>
                    <div class="w-full h-full flex items-center justify-center text-[#D4AF37]/30 text-7xl font-serif">✦</div>
                <?php endif; ?>

                <!-- Badges -->
                <div class="absolute top-4 left-4 flex flex-col gap-2">
                    <?php if ($disc > 0): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-black uppercase tracking-wider bg-[#D4AF37] text-[#0A0A0A]">−<?= $disc ?>% OFF</span>
                    <?php endif; ?>
                    <?php if (!$product->is_in_stock): ?>
                        <span class="px-3 py-1 rounded-full text-xs font-bold bg-white/10 backdrop-blur text-gray-300">Sold Out</span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Thumbnail strip -->
            <?php if (count($gallery) > 0 || $product->featured_image): ?>
            <div class="flex items-center gap-3 mt-4 flex-wrap">
                <?php
                $allImgs = $product->featured_image ? array_merge([$product->getImageUrl()], $gallery) : $gallery;
                foreach ($allImgs as $i => $img): ?>
                <button type="button"
                        @click="activeImg = <?= $i ?>"
                        :class="activeImg === <?= $i ?> ? 'border-[#D4AF37] ring-1 ring-[#D4AF37]/50' : 'border-white/10 hover:border-[#D4AF37]/40'"
                        class="w-16 h-16 rounded-xl overflow-hidden border-2 transition-all flex-shrink-0">
                    <img src="<?= Html::encode($img) ?>" alt="Thumbnail <?= $i+1 ?>" class="w-full h-full object-cover">
                </button>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>

        <!-- RIGHT: Info -->
        <div class="lg:col-span-5 flex flex-col gap-6">

            <!-- Category + SKU -->
            <div class="flex items-center justify-between">
                <a href="<?= Url::to(['/product/index', 'category' => $product->category]) ?>"
                   class="text-[10px] uppercase font-bold tracking-[0.25em] text-[#D4AF37] hover:underline">
                    <?= Html::encode($product->category) ?>
                </a>
                <?php if ($product->sku): ?>
                    <span class="text-[10px] text-gray-600 font-mono">SKU: <?= Html::encode($product->sku) ?></span>
                <?php endif; ?>
            </div>

            <!-- Name -->
            <h1 class="text-3xl sm:text-4xl font-serif font-black text-white tracking-tight leading-tight">
                <?= Html::encode($product->name) ?>
            </h1>

            <!-- Price -->
            <div class="flex items-end gap-3">
                <span class="text-3xl font-bold text-white"><?= $product->getPriceFormatted() ?></span>
                <?php if ($product->getOriginalPriceFormatted()): ?>
                    <span class="text-lg text-gray-500 line-through mb-0.5"><?= $product->getOriginalPriceFormatted() ?></span>
                    <span class="px-2.5 py-0.5 rounded-full text-xs font-black bg-[#D4AF37]/15 text-[#D4AF37] border border-[#D4AF37]/30">Save <?= $disc ?>%</span>
                <?php endif; ?>
            </div>

            <!-- Short description -->
            <p class="text-sm text-gray-300 leading-relaxed font-light"><?= Html::encode($product->short_description) ?></p>

            <!-- Stock status -->
            <div class="flex items-center gap-2">
                <?php if ($product->is_in_stock): ?>
                    <span class="w-2 h-2 rounded-full bg-emerald-400 animate-pulse"></span>
                    <span class="text-xs text-emerald-400 font-semibold">In Stock<?= $product->stock <= 5 ? ' — only ' . $product->stock . ' left' : '' ?></span>
                <?php else: ?>
                    <span class="w-2 h-2 rounded-full bg-gray-500"></span>
                    <span class="text-xs text-gray-500 font-semibold">Out of Stock — Notify me</span>
                <?php endif; ?>
            </div>

            <!-- Quantity + Add to Cart -->
            <?php if ($product->is_in_stock): ?>
            <div class="flex items-center gap-3" x-data="{ qty: 1 }">
                <!-- Qty stepper -->
                <div class="flex items-center border border-white/10 rounded-xl overflow-hidden">
                    <button type="button" @click="qty = Math.max(1, qty - 1)"
                            class="w-10 h-11 text-gray-400 hover:text-white hover:bg-white/5 transition-colors flex items-center justify-center text-lg">−</button>
                    <span class="w-10 text-center text-sm font-bold text-white" x-text="qty"></span>
                    <button type="button" @click="qty = Math.min(<?= $product->stock ?>, qty + 1)"
                            class="w-10 h-11 text-gray-400 hover:text-white hover:bg-white/5 transition-colors flex items-center justify-center text-lg">+</button>
                </div>

                <!-- Add to cart -->
                <button type="button"
                        @click="addToCartWithQty(<?= $product->id ?>, qty)"
                        id="add-to-cart-<?= $product->id ?>"
                        class="btn-gold flex-1 text-center justify-center py-3">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                    </svg>
                    <span>Add to Cart</span>
                </button>
            </div>

            <!-- WhatsApp Order -->
            <a href="<?= $settings->whatsappUrl('Hello, I am interested in purchasing: ' . $product->name . ' (SKU: ' . $product->sku . ') from the ICONIC INTERIORS shop. Please assist.') ?>"
               target="_blank" rel="noopener noreferrer"
               class="btn-ghost text-center py-3 text-sm flex items-center justify-center gap-2">
                <svg class="w-4 h-4 fill-current text-[#25D366]" viewBox="0 0 24 24">
                    <path d="M.057 24l1.687-6.163c-1.041-1.804-1.588-3.849-1.587-5.946.003-6.556 5.338-11.891 11.893-11.891 3.181.001 6.167 1.24 8.413 3.488 2.245 2.248 3.481 5.236 3.48 8.414-.003 6.557-5.338 11.892-11.893 11.892-1.99-.001-3.951-.5-5.688-1.448l-6.305 1.654zm6.597-3.807c1.676.995 3.276 1.591 5.392 1.592 5.448 0 9.886-4.434 9.889-9.885.002-5.462-4.415-9.89-9.881-9.892-5.452 0-9.887 4.434-9.889 9.884-.001 2.225.651 3.891 1.746 5.634l-.999 3.648 3.742-.981zm11.387-5.464c-.074-.124-.272-.198-.57-.347-.297-.149-1.758-.868-2.031-.967-.272-.099-.47-.149-.669.149-.198.297-.768.967-.941 1.165-.173.198-.347.223-.644.074-.297-.149-1.255-.462-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.297-.347.446-.521.151-.172.2-.296.3-.495.099-.198.05-.372-.025-.521-.075-.148-.669-1.611-.916-2.206-.242-.579-.487-.501-.669-.51l-.57-.01c-.198 0-.52.074-.792.372s-1.04 1.016-1.04 2.479 1.065 2.876 1.213 3.074c.149.198 2.095 3.2 5.076 4.487.709.306 1.263.489 1.694.626.712.226 1.36.194 1.872.118.571-.085 1.758-.719 2.006-1.413.248-.695.248-1.29.173-1.414z"/>
                </svg>
                <span>Order via WhatsApp</span>
            </a>
            <?php else: ?>
                <a href="<?= $settings->whatsappUrl('Hello, I am interested in the ' . $product->name . ' which appears sold out. Please notify me when it is available.') ?>"
                   target="_blank" rel="noopener noreferrer"
                   class="btn-ghost text-center py-3 text-sm w-full">
                    Notify Me When Available
                </a>
            <?php endif; ?>

            <!-- Specs table -->
            <?php if (!empty($specs)): ?>
            <div class="glass rounded-2xl p-5 border border-white/8">
                <h3 class="text-xs font-bold uppercase tracking-[0.2em] text-gray-300 mb-4 flex items-center gap-2">
                    <span class="text-[#D4AF37]">✦</span> Specifications
                </h3>
                <dl class="flex flex-col gap-2.5">
                    <?php foreach ($specs as $key => $val): ?>
                    <div class="flex items-start justify-between gap-4 text-xs">
                        <dt class="text-gray-500 shrink-0 font-medium"><?= Html::encode($key) ?></dt>
                        <dd class="text-gray-200 text-right"><?= Html::encode($val) ?></dd>
                    </div>
                    <?php endforeach; ?>
                </dl>
            </div>
            <?php endif; ?>

            <!-- Tags -->
            <?php if (!empty($tags)): ?>
            <div class="flex flex-wrap gap-1.5">
                <?php foreach ($tags as $tag): ?>
                    <span class="px-2.5 py-1 rounded-full text-[10px] bg-white/5 border border-white/8 text-gray-400">
                        <?= Html::encode($tag) ?>
                    </span>
                <?php endforeach; ?>
            </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Full description -->
    <?php if ($product->full_description): ?>
    <div class="mt-16 pt-12 border-t border-white/5">
        <h2 class="text-2xl font-serif font-bold text-white mb-6">About This Piece</h2>
        <div class="prose prose-invert max-w-3xl text-sm text-gray-300 leading-relaxed font-light">
            <?= nl2br(Html::encode($product->full_description)) ?>
        </div>
    </div>
    <?php endif; ?>
</section>

<!-- =========================================================================
     RELATED PRODUCTS
     ========================================================================= -->
<?php if (!empty($related)): ?>
<section class="py-16 md:py-20 bg-[#0B0B0B] border-t border-white/5">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex items-end justify-between gap-6 mb-12">
            <div>
                <span class="kicker text-[11px] mb-2">YOU MAY ALSO LOVE</span>
                <h2 class="text-2xl sm:text-3xl font-serif font-bold text-white">Related <span class="text-gold-gradient italic">Pieces</span></h2>
            </div>
            <a href="<?= Url::to(['/product/index', 'category' => $product->category]) ?>" class="btn-ghost btn-sm">
                <span>View All <?= Html::encode($product->category) ?></span>
            </a>
        </div>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6">
            <?php foreach ($related as $rel): ?>
            <div class="card-lux group overflow-hidden flex flex-col">
                <a href="<?= Url::to(['/product/view', 'slug' => $rel->slug]) ?>" class="block">
                    <div class="aspect-square overflow-hidden bg-[#111] relative">
                        <?php if ($rel->featured_image): ?>
                            <img src="<?= Html::encode($rel->getImageUrl()) ?>"
                                 alt="<?= Html::encode($rel->name) ?>"
                                 class="w-full h-full object-cover transition-transform duration-500 group-hover:scale-105">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center text-[#D4AF37]/30 text-4xl">✦</div>
                        <?php endif; ?>
                    </div>
                </a>
                <div class="p-4 flex flex-col flex-1">
                    <span class="text-[10px] text-[#D4AF37] uppercase font-bold tracking-widest mb-1"><?= Html::encode($rel->category) ?></span>
                    <h3 class="text-sm font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-2 line-clamp-2">
                        <a href="<?= Url::to(['/product/view', 'slug' => $rel->slug]) ?>"><?= Html::encode($rel->name) ?></a>
                    </h3>
                    <div class="mt-auto flex items-center justify-between pt-3 border-t border-white/5">
                        <span class="text-sm font-bold text-white"><?= $rel->getPriceFormatted() ?></span>
                        <?php if ($rel->is_in_stock): ?>
                        <button type="button" onclick="addToCart(<?= $rel->id ?>)"
                                id="add-to-cart-<?= $rel->id ?>"
                                class="w-8 h-8 rounded-full border border-white/10 hover:border-[#D4AF37] flex items-center justify-center text-gray-400 hover:text-[#D4AF37] transition-all">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                        </button>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
<?php endif; ?>

<!-- Toast -->
<div id="cart-toast"
     class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[200] flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-[#111] border border-[#D4AF37]/40 shadow-2xl backdrop-blur-md text-sm font-medium text-white opacity-0 pointer-events-none transition-all duration-400 -translate-y-4">
    <span class="text-[#D4AF37] text-lg">✓</span>
    <span id="cart-toast-msg">Added to cart!</span>
    <a href="<?= Url::to(['/product/cart']) ?>" class="text-[#D4AF37] underline text-xs font-bold ml-2">View Cart</a>
</div>

<?php
$addUrl    = Url::to(['/product/add-to-cart']);
$cartUrl   = Url::to(['/product/cart']);
$this->registerJs(<<<JS
function addToCart(productId, qty) {
    qty = qty || 1;
    const btn = document.getElementById('add-to-cart-' + productId);
    if (btn) {
        btn.disabled = true;
    }
    fetch('$addUrl', {
        method: 'POST',
        headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
        body: 'product_id=' + productId + '&qty=' + qty + '&_csrf=' + (document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '')
    })
    .then(r => r.json())
    .then(data => {
        if (btn) btn.disabled = false;
        if (data.success) {
            showCartToast(data.message || 'Added to cart!');
            updateCartBadge(data.cart_count);
        }
    })
    .catch(() => { if (btn) btn.disabled = false; });
}

function addToCartWithQty(productId, qty) {
    addToCart(productId, qty);
}

function showCartToast(msg) {
    const toast = document.getElementById('cart-toast');
    const msgEl = document.getElementById('cart-toast-msg');
    if (!toast) return;
    if (msgEl) msgEl.textContent = msg;
    toast.classList.remove('opacity-0', '-translate-y-4', 'pointer-events-none');
    toast.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
    setTimeout(() => {
        toast.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
        toast.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
    }, 3500);
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
