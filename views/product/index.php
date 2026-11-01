<?php

/** @var yii\web\View $this */
/** @var app\models\Product[] $products */
/** @var string[] $categories */
/** @var string|null $category */
/** @var string|null $sort */
/** @var app\models\SiteSettings $settings */
/** @var int $cartCount */

use yii\helpers\Html;
use yii\helpers\Url;
?>

<!-- =========================================================================
     SHOP HERO
     ========================================================================= -->
<section class="relative py-20 md:py-28 overflow-hidden border-b border-white/5">
    <!-- Gradient accent -->
    <div class="absolute inset-0 bg-gradient-to-b from-[#D4AF37]/5 via-transparent to-transparent pointer-events-none"></div>
    <div class="absolute top-0 left-1/2 -translate-x-1/2 w-[900px] h-[300px] bg-[#D4AF37]/8 blur-[120px] pointer-events-none"></div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
        <div class="flex flex-col md:flex-row md:items-end justify-between gap-6">
            <div class="reveal">
                <span class="kicker text-[11px] mb-3">ICONIC CURATED SHOP</span>
                <h1 class="text-4xl sm:text-5xl md:text-6xl font-serif font-black text-white tracking-tight leading-tight">
                    Bring the <span class="text-gold-gradient italic">Studio Home</span>
                </h1>
                <p class="text-sm sm:text-base text-gray-400 mt-4 max-w-xl font-light leading-relaxed">
                    Every piece in our curated collection is selected by our design team — handcrafted lighting, bespoke furniture, artisan decor, and investment-grade textiles.
                </p>
            </div>

            <!-- Cart pill -->
            <a href="<?= Url::to(['/product/cart']) ?>"
               id="shop-cart-btn"
               class="btn-ghost self-start md:self-auto flex items-center gap-2 relative">
                <svg class="w-4 h-4 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                </svg>
                <span>Your Cart</span>
                <span id="cart-badge"
                      class="<?= $cartCount > 0 ? '' : 'hidden' ?> absolute -top-2 -right-2 w-5 h-5 rounded-full bg-[#D4AF37] text-[#0A0A0A] text-[10px] font-black flex items-center justify-center">
                    <?= $cartCount ?>
                </span>
            </a>
        </div>
    </div>
</section>

<!-- =========================================================================
     FILTER BAR
     ========================================================================= -->
<section class="sticky top-20 z-30 bg-[#0A0A0A]/90 backdrop-blur-md border-b border-white/5 py-3">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex flex-col sm:flex-row items-start sm:items-center justify-between gap-3">

            <!-- Category tabs -->
            <div class="flex items-center gap-2 flex-wrap">
                <a href="<?= Url::to(['/product/index']) ?>"
                   class="filter-tab <?= !$category ? 'is-active' : '' ?>">All</a>
                <?php foreach ($categories as $cat): ?>
                    <a href="<?= Url::to(['/product/index', 'category' => $cat]) ?>"
                       class="filter-tab <?= $category === $cat ? 'is-active' : '' ?>">
                        <?= Html::encode($cat) ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Sort -->
            <div class="flex items-center gap-2">
                <span class="text-xs text-gray-500 uppercase tracking-wider font-medium">Sort:</span>
                <select id="sort-select"
                        onchange="window.location = this.value"
                        class="bg-transparent border border-white/10 rounded-lg px-3 py-1.5 text-xs text-gray-300 focus:outline-none focus:border-[#D4AF37]/50 cursor-pointer appearance-none pr-7">
                    <option value="<?= Url::to(['/product/index', 'category' => $category]) ?>"
                            <?= !$sort ? 'selected' : '' ?>>Featured</option>
                    <option value="<?= Url::to(['/product/index', 'category' => $category, 'sort' => 'price_asc']) ?>"
                            <?= $sort === 'price_asc' ? 'selected' : '' ?>>Price: Low → High</option>
                    <option value="<?= Url::to(['/product/index', 'category' => $category, 'sort' => 'price_desc']) ?>"
                            <?= $sort === 'price_desc' ? 'selected' : '' ?>>Price: High → Low</option>
                    <option value="<?= Url::to(['/product/index', 'category' => $category, 'sort' => 'new']) ?>"
                            <?= $sort === 'new' ? 'selected' : '' ?>>Newest</option>
                </select>
            </div>
        </div>
    </div>
</section>

<!-- =========================================================================
     PRODUCT GRID
     ========================================================================= -->
<section class="py-16 md:py-24">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

        <?php if (empty($products)): ?>
            <div class="text-center py-24 flex flex-col items-center gap-4">
                <div class="w-16 h-16 rounded-2xl bg-[#1C1C1C] border border-white/10 flex items-center justify-center text-[#D4AF37] text-2xl">✦</div>
                <p class="text-gray-400 text-base">No products found in this category.</p>
                <a href="<?= Url::to(['/product/index']) ?>" class="btn-gold btn-sm">View All Products</a>
            </div>
        <?php else: ?>
            <!-- Product count -->
            <p class="text-xs text-gray-500 uppercase tracking-wider mb-10">
                Showing <?= count($products) ?> <?= $category ? Html::encode($category) . ' ' : '' ?>piece<?= count($products) !== 1 ? 's' : '' ?>
            </p>

            <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-8">
                <?php foreach ($products as $product): ?>
                <div class="product-card group card-lux overflow-hidden flex flex-col reveal"
                     id="product-<?= $product->id ?>">

                    <!-- Image -->
                    <a href="<?= Url::to(['/product/view', 'slug' => $product->slug]) ?>" class="block relative overflow-hidden">
                        <div class="aspect-[4/3] overflow-hidden bg-[#111] relative">
                            <?php if ($product->featured_image): ?>
                                <img src="<?= Html::encode($product->getImageUrl()) ?>"
                                     alt="<?= Html::encode($product->name) ?>"
                                     class="w-full h-full object-cover transition-transform duration-700 group-hover:scale-108"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="w-full h-full flex items-center justify-center text-[#D4AF37]/30 text-5xl font-serif">✦</div>
                            <?php endif; ?>

                            <!-- Overlay -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-500"></div>

                            <!-- Quick badges -->
                            <div class="absolute top-3 left-3 flex flex-col gap-1.5">
                                <?php if ($product->getDiscountPercent() > 0): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-black uppercase tracking-wider bg-[#D4AF37] text-[#0A0A0A]">
                                        −<?= $product->getDiscountPercent() ?>%
                                    </span>
                                <?php endif; ?>
                                <?php if (!$product->is_in_stock): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-white/10 backdrop-blur text-gray-300">
                                        Sold Out
                                    </span>
                                <?php elseif ($product->stock <= 3): ?>
                                    <span class="px-2.5 py-1 rounded-full text-[10px] font-bold uppercase tracking-wider bg-red-900/80 backdrop-blur text-red-200">
                                        Only <?= $product->stock ?> left
                                    </span>
                                <?php endif; ?>
                            </div>

                            <!-- Quick view on hover -->
                            <div class="absolute bottom-3 inset-x-3 opacity-0 group-hover:opacity-100 transition-all duration-300 translate-y-2 group-hover:translate-y-0">
                                <a href="<?= Url::to(['/product/view', 'slug' => $product->slug]) ?>"
                                   class="btn-ghost btn-sm w-full text-center justify-center text-xs">
                                    Quick View
                                </a>
                            </div>
                        </div>
                    </a>

                    <!-- Info -->
                    <div class="p-5 flex flex-col flex-1">
                        <!-- Category + SKU -->
                        <div class="flex items-center justify-between mb-2">
                            <span class="text-[10px] uppercase font-bold tracking-[0.2em] text-[#D4AF37]">
                                <?= Html::encode($product->category) ?>
                            </span>
                            <?php if ($product->sku): ?>
                                <span class="text-[10px] text-gray-600 font-mono"><?= Html::encode($product->sku) ?></span>
                            <?php endif; ?>
                        </div>

                        <!-- Name -->
                        <h2 class="text-base sm:text-lg font-serif font-bold text-white group-hover:text-[#D4AF37] transition-colors mb-2 leading-snug">
                            <a href="<?= Url::to(['/product/view', 'slug' => $product->slug]) ?>">
                                <?= Html::encode($product->name) ?>
                            </a>
                        </h2>

                        <!-- Description -->
                        <p class="text-xs text-gray-400 font-light leading-relaxed line-clamp-2 mb-4 flex-1">
                            <?= Html::encode($product->short_description) ?>
                        </p>

                        <!-- Tags -->
                        <?php $tags = array_slice($product->getTags(), 0, 3); ?>
                        <?php if (!empty($tags)): ?>
                        <div class="flex flex-wrap gap-1 mb-4">
                            <?php foreach ($tags as $tag): ?>
                                <span class="px-2 py-0.5 rounded-full text-[10px] bg-white/5 border border-white/8 text-gray-400">
                                    <?= Html::encode($tag) ?>
                                </span>
                            <?php endforeach; ?>
                        </div>
                        <?php endif; ?>

                        <!-- Price + CTA -->
                        <div class="flex items-center justify-between pt-4 border-t border-white/5 mt-auto">
                            <div class="flex flex-col">
                                <span class="text-lg font-bold text-white"><?= $product->getPriceFormatted() ?></span>
                                <?php if ($product->getOriginalPriceFormatted()): ?>
                                    <span class="text-xs text-gray-500 line-through"><?= $product->getOriginalPriceFormatted() ?></span>
                                <?php endif; ?>
                            </div>

                            <?php if ($product->is_in_stock): ?>
                                <button type="button"
                                        onclick="addToCart(<?= $product->id ?>)"
                                        id="add-to-cart-<?= $product->id ?>"
                                        class="btn-gold btn-sm add-to-cart-btn"
                                        data-product-id="<?= $product->id ?>">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/>
                                    </svg>
                                    <span>Add to Cart</span>
                                </button>
                            <?php else: ?>
                                <span class="text-xs text-gray-500 font-medium">Sold Out</span>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</section>

<!-- =========================================================================
     TRUST STRIP
     ========================================================================= -->
<section class="py-16 border-t border-white/5 bg-[#0B0B0B]">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8 text-center">
            <?php $perks = [
                ['icon' => 'M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4', 'title' => 'White-Glove Delivery', 'desc' => 'Pan-India doorstep installation'],
                ['icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z', 'title' => 'Authenticity Guarantee', 'desc' => 'Certificates of origin on every piece'],
                ['icon' => 'M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z', 'title' => 'Secure Checkout', 'desc' => 'Razorpay & bank transfer accepted'],
                ['icon' => 'M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z', 'title' => 'Expert Consultation', 'desc' => '1-on-1 design advice via WhatsApp'],
            ]; ?>
            <?php foreach ($perks as $perk): ?>
            <div class="flex flex-col items-center gap-3 reveal">
                <div class="w-12 h-12 rounded-2xl bg-[#1A1A1A] border border-[#D4AF37]/20 flex items-center justify-center">
                    <svg class="w-5 h-5 text-[#D4AF37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="<?= $perk['icon'] ?>"/>
                    </svg>
                </div>
                <div>
                    <p class="text-sm font-semibold text-white"><?= $perk['title'] ?></p>
                    <p class="text-xs text-gray-500 mt-0.5"><?= $perk['desc'] ?></p>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>

<!-- =========================================================================
     TOAST NOTIFICATION
     ========================================================================= -->
<div id="cart-toast"
     class="fixed bottom-24 left-1/2 -translate-x-1/2 z-[200] flex items-center gap-3 px-5 py-3.5 rounded-2xl bg-[#111] border border-[#D4AF37]/40 shadow-2xl backdrop-blur-md text-sm font-medium text-white opacity-0 pointer-events-none transition-all duration-400 -translate-y-4"
     aria-live="polite">
    <span class="text-[#D4AF37] text-lg">✓</span>
    <span id="cart-toast-msg">Added to cart!</span>
    <a href="<?= Url::to(['/product/cart']) ?>" class="text-[#D4AF37] underline text-xs font-bold ml-2">View Cart</a>
</div>

<?php
$addUrl = \yii\helpers\Url::to(['/product/add-to-cart']);
$js = <<<JS
// Add to cart AJAX
function addToCart(productId, qty = 1) {
    const btn = document.getElementById('add-to-cart-' + productId);
    if (btn) {
        btn.disabled = true;
        btn.innerHTML = '<svg class="w-3.5 h-3.5 animate-spin" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8v8z"></path></svg><span>Adding...</span>';
    }
    fetch('{$addUrl}', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
            'X-CSRF-Token': document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : ''
        },
        body: 'product_id=' + productId + '&qty=' + qty + '&_csrf=' + (document.querySelector('meta[name=csrf-token]') ? document.querySelector('meta[name=csrf-token]').content : '')
    })
    .then(r => r.json())
    .then(data => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z"/></svg><span>Add to Cart</span>';
        }
        if (data.success) {
            showCartToast(data.message || 'Added to cart!');
            updateCartBadge(data.cart_count);
        }
    })
    .catch(() => {
        if (btn) {
            btn.disabled = false;
            btn.innerHTML = '<span>Add to Cart</span>';
        }
    });
}

function showCartToast(msg) {
    const toast = document.getElementById('cart-toast');
    const msgEl = document.getElementById('cart-toast-msg');
    if (toast && msgEl) {
        msgEl.textContent = msg;
        toast.classList.remove('opacity-0', '-translate-y-4', 'pointer-events-none');
        toast.classList.add('opacity-100', 'translate-y-0', 'pointer-events-auto');
        setTimeout(() => {
            toast.classList.add('opacity-0', '-translate-y-4', 'pointer-events-none');
            toast.classList.remove('opacity-100', 'translate-y-0', 'pointer-events-auto');
        }, 3500);
    }
}

function updateCartBadge(count) {
    const badge = document.getElementById('cart-badge');
    if (badge) {
        badge.textContent = count;
        if (count > 0) badge.classList.remove('hidden');
        else badge.classList.add('hidden');
    }
}
JS;

$this->registerJs($js);
?>

<style>
.filter-tab {
    display: inline-flex;
    align-items: center;
    padding: 0.3rem 1rem;
    border-radius: 9999px;
    font-size: 0.7rem;
    font-weight: 700;
    text-transform: uppercase;
    letter-spacing: 0.15em;
    border: 1px solid rgba(255,255,255,0.08);
    color: #9ca3af;
    transition: all 0.2s;
    cursor: pointer;
    text-decoration: none;
}
.filter-tab:hover {
    border-color: rgba(212,175,55,0.4);
    color: #D4AF37;
    background: rgba(212,175,55,0.06);
}
.filter-tab.is-active {
    background: #D4AF37;
    color: #0A0A0A;
    border-color: #D4AF37;
}
.group-hover\:scale-108:hover {
    transform: scale(1.08);
}
</style>
