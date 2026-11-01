<?php

use yii\db\Migration;

/**
 * Creates the products + cart_items tables for the ICONIC INTERIORS shop section.
 *
 * Run: php yii migrate
 */
class m260101_000002_create_products_table extends Migration
{
    private string $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

    public function safeUp()
    {
        $this->createTable('{{%products}}', [
            'id'               => $this->primaryKey(),
            'name'             => $this->string(200)->notNull(),
            'slug'             => $this->string(200)->notNull()->unique(),
            'category'         => $this->string(80)->notNull()->defaultValue('Decor'),
            'short_description'=> $this->string(500),
            'full_description' => 'MEDIUMTEXT NULL',
            'price'            => $this->decimal(12, 2)->notNull(),
            'original_price'   => $this->decimal(12, 2)->null(),
            'sku'              => $this->string(80),
            'stock'            => $this->integer()->notNull()->defaultValue(0),
            'is_in_stock'      => $this->tinyInteger()->notNull()->defaultValue(1),
            'featured_image'   => $this->string(255),
            'gallery_json'     => $this->text(),
            'specs_json'       => $this->text(),
            'tags_json'        => $this->text(),
            'is_featured'      => $this->tinyInteger()->notNull()->defaultValue(0),
            'is_active'        => $this->tinyInteger()->notNull()->defaultValue(1),
            'sort_order'       => $this->integer()->notNull()->defaultValue(0),
            'meta_title'       => $this->string(160),
            'meta_description' => $this->string(320),
            'created_at'       => $this->dateTime(),
            'updated_at'       => $this->dateTime(),
        ], $this->tableOptions);

        $this->createIndex('idx_products_slug',     '{{%products}}', 'slug', true);
        $this->createIndex('idx_products_category', '{{%products}}', 'category');
        $this->createIndex('idx_products_active',   '{{%products}}', ['is_active', 'sort_order']);

        $this->createTable('{{%cart_items}}', [
            'id'         => $this->primaryKey(),
            'session_id' => $this->string(128)->notNull(),
            'product_id' => $this->integer()->notNull(),
            'quantity'   => $this->integer()->notNull()->defaultValue(1),
            'added_at'   => $this->dateTime(),
        ], $this->tableOptions);

        $this->addForeignKey('fk_cart_product', '{{%cart_items}}', 'product_id', '{{%products}}', 'id', 'CASCADE', 'CASCADE');
        $this->createIndex('idx_cart_session', '{{%cart_items}}', 'session_id');

        $this->seed();
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_cart_product', '{{%cart_items}}');
        $this->dropTable('{{%cart_items}}');
        $this->dropTable('{{%products}}');
    }

    private function seed(): void
    {
        $now = date('Y-m-d H:i:s');
        $j   = static fn($v) => json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

        $products = [
            [
                'name'              => 'Aurelius Pendant Cluster',
                'slug'              => 'aurelius-pendant-cluster',
                'category'          => 'Lighting',
                'short_description' => 'Hand-blown amber glass globes suspended on matte black stems. A trio cluster that transforms any ceiling into art.',
                'full_description'  => 'The Aurelius Pendant Cluster is the centrepiece of any luxury living or dining space. Three hand-blown amber borosilicate glass globes of graduated sizes are suspended from individually adjustable matte-black powder-coated stems. Compatible with LED E27 bulbs up to 40W. Canopy plate finished in antique brass.',
                'price'             => 28500.00,
                'original_price'    => 34000.00,
                'sku'               => 'LT-APL-001',
                'stock'             => 12,
                'is_featured'       => 1,
                'specs_json'        => $j(['Material' => 'Hand-blown borosilicate glass + matte brass', 'Shade Diameter' => '18 / 22 / 26 cm', 'Drop Height' => 'Adjustable up to 200 cm', 'Bulb Type' => 'E27 LED (40W max)', 'Finish' => 'Antique Brass + Amber Glass']),
                'tags_json'         => $j(['Pendant Light', 'Cluster', 'Amber Glass', 'Handcrafted', 'Bestseller']),
                'sort_order'        => 1,
            ],
            [
                'name'              => 'Noir Arc Floor Lamp',
                'slug'              => 'noir-arc-floor-lamp',
                'category'          => 'Lighting',
                'short_description' => 'A bold arched floor lamp in matte obsidian with a handspun brass shade. Sculptural by day, warm by night.',
                'full_description'  => 'The Noir Arc Floor Lamp features a heavy nero marquina marble base, a slender matte-black steel arc, and a hand-spun solid-brass bell shade. Integrated dimmer on cord. Height: 180 cm. Shade diameter: 40 cm.',
                'price'             => 42000.00,
                'original_price'    => null,
                'sku'               => 'LT-NAF-002',
                'stock'             => 7,
                'is_featured'       => 1,
                'specs_json'        => $j(['Base' => 'Nero Marquina Marble', 'Arm' => 'Matte Black Steel', 'Shade' => 'Hand-spun Solid Brass', 'Height' => '180 cm', 'Shade Diameter' => '40 cm', 'Control' => 'Inline Dimmer Switch']),
                'tags_json'         => $j(['Floor Lamp', 'Arc', 'Marble Base', 'Brass', 'Statement Piece']),
                'sort_order'        => 2,
            ],
            [
                'name'              => 'Empress Velvet Accent Chair',
                'slug'              => 'empress-velvet-accent-chair',
                'category'          => 'Furniture',
                'short_description' => 'Deep forest-green velvet on solid teak legs with gold sabots. Regal comfort for reading nooks and drawing rooms.',
                'full_description'  => 'Upholstered in 100% silk-velvet in deep forest green, resting on a hand-carved solid teak frame with 24K-gold-plated brass sabots. High-resilience foam cushioning with a 7-year warranty. Available in Emerald, Cognac, and Midnight Blue on request.',
                'price'             => 68000.00,
                'original_price'    => 78000.00,
                'sku'               => 'FN-EVC-001',
                'stock'             => 5,
                'is_featured'       => 1,
                'specs_json'        => $j(['Upholstery' => '100% Silk Velvet — Forest Green', 'Frame' => 'Solid Teak, Hand-carved', 'Feet' => '24K Gold-plated Brass Sabots', 'Seat Height' => '46 cm', 'Overall Height' => '82 cm']),
                'tags_json'         => $j(['Accent Chair', 'Velvet', 'Teak', 'Gold', 'Bestseller']),
                'sort_order'        => 3,
            ],
            [
                'name'              => 'Atlas Dining Table — Marble & Steel',
                'slug'              => 'atlas-dining-table-marble-steel',
                'category'          => 'Furniture',
                'short_description' => 'A 6-seater oval Calacatta marble top floating on a brushed-steel X-base. The table that commands every dinner conversation.',
                'full_description'  => 'The Atlas Dining Table merges Italian Calacatta Oro marble with a precision-engineered brushed stainless steel X-frame base. The oval top measures 210 x 105 cm, comfortably seating six. Sealed with nano-glass coating for stain resistance. White-glove delivery available.',
                'price'             => 185000.00,
                'original_price'    => null,
                'sku'               => 'FN-ADT-002',
                'stock'             => 3,
                'is_featured'       => 1,
                'specs_json'        => $j(['Top Material' => 'Calacatta Oro Marble (20mm)', 'Base' => 'Brushed Stainless Steel X-frame', 'Dimensions' => '210 x 105 x 76 cm', 'Seating' => '6 persons', 'Delivery' => 'White-glove service']),
                'tags_json'         => $j(['Dining Table', 'Marble', 'Steel', 'Luxury', '6 Seater']),
                'sort_order'        => 4,
            ],
            [
                'name'              => 'Gilded Lotus Vase — Large',
                'slug'              => 'gilded-lotus-vase-large',
                'category'          => 'Decor',
                'short_description' => 'Hand-thrown stoneware with 22K gold gilded lotus relief. A statement floor vase inspired by temple architecture.',
                'full_description'  => 'Hand-thrown by master potters from Khurja with a deep-relief lotus motif brushed with 22K gold leaf. At 65 cm tall, this floor vase commands any corner, hallway, or beside a fireplace. Decorative only. Each piece is unique.',
                'price'             => 18500.00,
                'original_price'    => 22000.00,
                'sku'               => 'DC-GLV-001',
                'stock'             => 20,
                'is_featured'       => 1,
                'specs_json'        => $j(['Material' => 'Hand-thrown Stoneware', 'Finish' => '22K Gold Leaf + Matte Charcoal', 'Height' => '65 cm', 'Diameter' => '28 cm', 'Type' => 'Decorative', 'Origin' => 'Khurja, UP']),
                'tags_json'         => $j(['Vase', 'Gold', 'Handcrafted', 'Floor Vase', 'Statement']),
                'sort_order'        => 5,
            ],
            [
                'name'              => 'Obsidian Tray Set — Onyx & Brass',
                'slug'              => 'obsidian-tray-set-onyx-brass',
                'category'          => 'Decor',
                'short_description' => 'A curated set of two display trays in natural black onyx, rimmed with solid brass. Elegance, organized.',
                'full_description'  => 'Set of two nested trays in natural black onyx with solid cast brass rims. Sizes: 40x30 cm and 28x20 cm. Sealed with food-safe lacquer. Perfect for coffee tables, vanities, or consoles. Gift-boxed.',
                'price'             => 12800.00,
                'original_price'    => null,
                'sku'               => 'DC-OTB-002',
                'stock'             => 15,
                'is_featured'       => 0,
                'specs_json'        => $j(['Material' => 'Natural Black Onyx + Solid Brass Rim', 'Sizes' => '40x30 cm and 28x20 cm (set of 2)', 'Finish' => 'Hand-polished Brass + Food-safe Lacquer', 'Weight' => '2.4 kg + 1.2 kg']),
                'tags_json'         => $j(['Tray', 'Onyx', 'Brass', 'Set', 'Coffee Table']),
                'sort_order'        => 6,
            ],
            [
                'name'              => 'Cashmere Throw — Midnight Dusk',
                'slug'              => 'cashmere-throw-midnight-dusk',
                'category'          => 'Textiles',
                'short_description' => 'Pure Grade-A Cashmere in a midnight navy-to-charcoal gradient. Draped over a sofa, it transforms the entire room.',
                'full_description'  => 'Woven from Grade-A Pashmina cashmere sourced from the Changra goats of Ladakh. The gradient dye is achieved through hand-dip-dyeing in small batches. Dimensions: 180 x 130 cm. Weight: 450g. Dry-clean recommended. Presented in a hand-stitched muslin bag.',
                'price'             => 22000.00,
                'original_price'    => 26500.00,
                'sku'               => 'TX-CTM-001',
                'stock'             => 30,
                'is_featured'       => 1,
                'specs_json'        => $j(['Material' => '100% Grade-A Pashmina Cashmere', 'Dimensions' => '180 x 130 cm', 'Weight' => '450 g', 'Dye' => 'Hand dip-dyed gradient', 'Care' => 'Dry-clean only', 'Origin' => 'Ladakh / Kashmir']),
                'tags_json'         => $j(['Throw', 'Cashmere', 'Pashmina', 'Luxury', 'Bedroom']),
                'sort_order'        => 7,
            ],
            [
                'name'              => 'Handknotted Silk Rug — Persian Medallion',
                'slug'              => 'handknotted-silk-rug-persian-medallion',
                'category'          => 'Textiles',
                'short_description' => 'A 200-knot-per-inch handknotted silk rug with a classic Persian medallion in burgundy, ivory, and gold.',
                'full_description'  => 'Crafted by master weavers in Bhadohi using 100% pure mulberry silk on cotton foundation. Takes 6 months to complete. 200 knots per square inch. 8 x 10 ft. Authentication certificate of origin included.',
                'price'             => 320000.00,
                'original_price'    => null,
                'sku'               => 'TX-HSR-002',
                'stock'             => 2,
                'is_featured'       => 1,
                'specs_json'        => $j(['Material' => '100% Mulberry Silk on Cotton', 'Size' => '8 x 10 ft (244 x 305 cm)', 'Knot Density' => '200 KPSI', 'Pile Height' => '5 mm', 'Origin' => 'Bhadohi, UP — Certified']),
                'tags_json'         => $j(['Rug', 'Silk', 'Persian', 'Handknotted', 'Heirloom']),
                'sort_order'        => 8,
            ],
            [
                'name'              => 'Abstract Gold Leaf Canvas — Epoch Series',
                'slug'              => 'abstract-gold-leaf-canvas-epoch-series',
                'category'          => 'Wall Art',
                'short_description' => 'Large-format abstract expressionist painting on linen canvas with genuine 24K gold leaf accents. Edition of 10.',
                'full_description'  => '120 x 90 cm work on Belgian linen with mineral pigments and 24K gold leaf. Numbered and signed. Floated in a custom hand-crafted ebonized oak frame (included). Certificate of authenticity included.',
                'price'             => 55000.00,
                'original_price'    => 65000.00,
                'sku'               => 'WA-AGL-001',
                'stock'             => 4,
                'is_featured'       => 1,
                'specs_json'        => $j(['Medium' => 'Mineral Pigments + 24K Gold Leaf on Belgian Linen', 'Dimensions' => '120 x 90 cm', 'Edition' => 'Limited — 10 pieces', 'Frame' => 'Ebonized Oak (included)', 'Certificate' => 'Authenticity included']),
                'tags_json'         => $j(['Wall Art', 'Painting', 'Gold Leaf', 'Abstract', 'Limited Edition']),
                'sort_order'        => 9,
            ],
        ];

        foreach ($products as $data) {
            $this->insert('{{%products}}', array_merge($data, [
                'is_in_stock' => ($data['stock'] > 0) ? 1 : 0,
                'created_at'  => $now,
                'updated_at'  => $now,
            ]));
        }
    }
}
