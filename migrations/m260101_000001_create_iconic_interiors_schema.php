<?php

use yii\db\Migration;

/**
 * ICONIC INTERIORS — complete schema + seed data.
 *
 * Tables: user, site_settings, cms_pages, hero_styles, services, projects,
 *         testimonials, user_queries
 *
 * Run: php yii migrate
 */
class m260101_000001_create_iconic_interiors_schema extends Migration
{
    private string $tableOptions = 'CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE=InnoDB';

    public function safeUp()
    {
        $this->createSchema();
        $this->seed();
    }

    public function safeDown()
    {
        $this->dropForeignKey('fk_projects_service', '{{%projects}}');
        foreach (['user_queries', 'testimonials', 'projects', 'services', 'hero_styles', 'cms_pages', 'site_settings', 'user'] as $t) {
            $this->dropTable('{{%' . $t . '}}');
        }
    }

    /* ------------------------------------------------------------------ *
     *  SCHEMA
     * ------------------------------------------------------------------ */
    private function createSchema(): void
    {
        $o = $this->tableOptions;

        // 1. Admin users
        $this->createTable('{{%user}}', [
            'id' => $this->primaryKey(),
            'username' => $this->string(64)->notNull()->unique(),
            'auth_key' => $this->string(32)->notNull(),
            'password_hash' => $this->string(255)->notNull(),
            'email' => $this->string(191)->notNull()->unique(),
            'status' => $this->smallInteger()->notNull()->defaultValue(10),
            'created_at' => $this->integer()->notNull(),
            'updated_at' => $this->integer()->notNull(),
        ], $o);

        // 2. Global site settings (single row)
        $this->createTable('{{%site_settings}}', [
            'id' => $this->primaryKey(),
            'company_name' => $this->string(120)->notNull(),
            'tagline' => $this->string(160),
            'owner_name' => $this->string(120),
            'phone_primary' => $this->string(20),
            'phone_secondary' => $this->string(20),
            'whatsapp_number' => $this->string(20),
            'email' => $this->string(191),
            'address' => $this->string(500),
            'working_hours' => $this->string(160),
            'map_embed_url' => $this->string(1000),
            'facebook_url' => $this->string(255),
            'instagram_url' => $this->string(255),
            'footer_text' => $this->string(500),
            'meta_title' => $this->string(160),
            'meta_description' => $this->string(320),
            // Cost estimator base rates (INR per sq.ft.)
            'rate_essential' => $this->integer()->notNull()->defaultValue(1450),
            'rate_luxury_gold' => $this->integer()->notNull()->defaultValue(2400),
            'rate_bespoke_signature' => $this->integer()->notNull()->defaultValue(3800),
        ], $o);

        // 3. Individual page content & SEO
        $this->createTable('{{%cms_pages}}', [
            'id' => $this->primaryKey(),
            'slug' => $this->string(64)->notNull()->unique(),
            'title' => $this->string(200)->notNull(),
            'subtitle' => $this->string(400),
            'hero_banner_image' => $this->string(255),
            'content_body' => 'MEDIUMTEXT NULL',
            'extra_json' => $this->text(),
            'meta_title' => $this->string(160),
            'meta_description' => $this->string(320),
            'status' => $this->tinyInteger()->notNull()->defaultValue(1),
            'updated_at' => $this->dateTime(),
        ], $o);

        // 4. Hero style customizer presets (one row = style x room)
        $this->createTable('{{%hero_styles}}', [
            'id' => $this->primaryKey(),
            'style_name' => $this->string(120)->notNull(),
            'slug' => $this->string(120)->notNull(),
            'room_category' => $this->string(60)->notNull(),
            'tagline' => $this->string(200),
            'description' => $this->text(),
            'after_image' => $this->string(255),
            'before_image' => $this->string(255),
            'color_palette_json' => $this->text(),
            'materials_json' => $this->text(),
            'lighting_mode_default' => $this->string(20)->notNull()->defaultValue('warm'),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'is_active' => $this->tinyInteger()->notNull()->defaultValue(1),
        ], $o);
        $this->createIndex('idx_hero_styles_slug_room', '{{%hero_styles}}', ['slug', 'room_category'], true);
        $this->createIndex('idx_hero_styles_active_sort', '{{%hero_styles}}', ['is_active', 'sort_order']);

        // 5. Services
        $this->createTable('{{%services}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(160)->notNull(),
            'slug' => $this->string(160)->notNull()->unique(),
            'icon_svg_or_class' => $this->text(),
            'short_description' => $this->string(500),
            'full_description' => 'MEDIUMTEXT NULL',
            'featured_image' => $this->string(255),
            'deliverables_json' => $this->text(),
            'material_tiers_json' => $this->text(),
            'gallery_images_json' => $this->text(),
            'starting_price' => $this->decimal(12, 2),
            'meta_title' => $this->string(160),
            'meta_description' => $this->string(320),
            'sort_order' => $this->integer()->notNull()->defaultValue(0),
            'is_active' => $this->tinyInteger()->notNull()->defaultValue(1),
        ], $o);

        // 6. Portfolio projects
        $this->createTable('{{%projects}}', [
            'id' => $this->primaryKey(),
            'title' => $this->string(200)->notNull(),
            'slug' => $this->string(200)->notNull()->unique(),
            'service_id' => $this->integer()->null(),
            'category' => $this->string(40)->notNull()->defaultValue('Residential'),
            'client_name' => $this->string(160),
            'location' => $this->string(160),
            'carpet_area' => $this->integer(),
            'budget_tier' => $this->string(60),
            'completion_year' => $this->smallInteger(),
            'completion_time' => $this->string(60),
            'cover_image' => $this->string(255),
            'before_image' => $this->string(255),
            'gallery_images_json' => $this->text(),
            'summary' => $this->text(),
            'client_requirements' => $this->text(),
            'challenge_and_solution' => $this->text(),
            'is_featured' => $this->tinyInteger()->notNull()->defaultValue(0),
            'is_active' => $this->tinyInteger()->notNull()->defaultValue(1),
            'created_at' => $this->dateTime(),
        ], $o);
        $this->addForeignKey('fk_projects_service', '{{%projects}}', 'service_id', '{{%services}}', 'id', 'SET NULL', 'CASCADE');
        $this->createIndex('idx_projects_category', '{{%projects}}', 'category');

        // 7. Testimonials
        $this->createTable('{{%testimonials}}', [
            'id' => $this->primaryKey(),
            'client_name' => $this->string(160)->notNull(),
            'client_role' => $this->string(160),
            'project_type' => $this->string(120),
            'rating' => $this->tinyInteger()->notNull()->defaultValue(5),
            'review_text' => $this->text()->notNull(),
            'client_photo' => $this->string(255),
            'is_active' => $this->tinyInteger()->notNull()->defaultValue(1),
        ], $o);

        // 8. User queries / leads
        $this->createTable('{{%user_queries}}', [
            'id' => $this->primaryKey(),
            'full_name' => $this->string(160)->notNull(),
            'phone' => $this->string(20)->notNull(),
            'email' => $this->string(191)->null(),
            'query_source' => "ENUM('Contact Form','Hero Style Customizer','Service Page','Cost Estimator') NOT NULL DEFAULT 'Contact Form'",
            'selected_service' => $this->string(160),
            'selected_hero_style' => $this->string(160)->null(),
            'property_type' => $this->string(60)->null(),
            'carpet_area_sqft' => $this->integer()->null(),
            'finish_tier' => $this->string(60)->null(),
            'estimated_budget' => $this->string(120)->null(),
            'preferred_location' => $this->string(160)->null(),
            'message' => $this->text(),
            'status' => "ENUM('New','Contacted','Site Visit Scheduled','Quotation Sent','Converted','Closed') NOT NULL DEFAULT 'New'",
            'admin_notes' => $this->text(),
            'is_read' => $this->tinyInteger()->notNull()->defaultValue(0),
            'ip_address' => $this->string(45),
            'created_at' => $this->dateTime(),
            'updated_at' => $this->dateTime(),
        ], $o);
        $this->createIndex('idx_uq_status', '{{%user_queries}}', 'status');
        $this->createIndex('idx_uq_source', '{{%user_queries}}', 'query_source');
        $this->createIndex('idx_uq_is_read', '{{%user_queries}}', 'is_read');
        $this->createIndex('idx_uq_created', '{{%user_queries}}', 'created_at');
    }

    /* ------------------------------------------------------------------ *
     *  SEED DATA
     * ------------------------------------------------------------------ */
    private function seed(): void
    {
        $now = date('Y-m-d H:i:s');
        $j = static fn($v) => json_encode($v, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
        $img = static fn($n) => 'uploads/seed/' . $n . '.jpg';

        // --- Admin user: admin / Iconic@2026 ---
        $this->insert('{{%user}}', [
            'username' => 'admin',
            'auth_key' => Yii::$app->security->generateRandomString(32),
            'password_hash' => Yii::$app->security->generatePasswordHash('Iconic@2026'),
            'email' => 'iconicinterior2026@gmail.com',
            'status' => 10,
            'created_at' => time(),
            'updated_at' => time(),
        ]);

        // --- Site settings ---
        $this->insert('{{%site_settings}}', [
            'company_name' => 'ICONIC INTERIORS',
            'tagline' => 'DESIGN • CREATE • INSPIRE',
            'owner_name' => 'Santosh Kumar Maharana',
            'phone_primary' => '+91 9778646212',
            'phone_secondary' => '+91 9337080037',
            'whatsapp_number' => '919778646212',
            'email' => 'iconicinterior2026@gmail.com',
            'address' => 'Bhubaneswar, Odisha, India',
            'working_hours' => 'Mon – Sat · 10:00 AM – 7:30 PM',
            'map_embed_url' => 'https://www.google.com/maps?q=Bhubaneswar,Odisha&output=embed',
            'facebook_url' => 'https://facebook.com/',
            'instagram_url' => 'https://instagram.com/',
            'footer_text' => 'Luxury residential & commercial interiors, crafted end-to-end with precision, passion and timeless taste.',
            'meta_title' => 'ICONIC INTERIORS — Luxury Interior Design Studio',
            'meta_description' => 'ICONIC INTERIORS designs and builds luxury homes, modular kitchens, bedrooms, offices and custom furniture. Design • Create • Inspire.',
            'rate_essential' => 1450,
            'rate_luxury_gold' => 2400,
            'rate_bespoke_signature' => 3800,
        ]);

        // --- CMS pages ---
        $pages = [
            [
                'slug' => 'home',
                'title' => 'Interiors That Define Your Legacy',
                'subtitle' => 'Explore signature looks in real time — switch styles, rooms and lighting, then book the look you love.',
                'hero_banner_image' => $img('royal_noir_living'),
                'content_body' => '<p>From the first sketch to the final styling touch, ICONIC INTERIORS delivers turnkey luxury interiors with a single point of accountability.</p>',
                'extra_json' => $j([
                    'stats' => [
                        ['value' => '250+', 'label' => 'Spaces Delivered'],
                        ['value' => '12+', 'label' => 'Years of Craft'],
                        ['value' => '45', 'label' => 'Day Kitchen Delivery'],
                        ['value' => '10 Yr', 'label' => 'Warranty on Modular'],
                    ],
                    'cta_title' => 'Ready to design something iconic?',
                    'cta_text' => 'Book a complimentary design consultation with Santosh Kumar Maharana and our senior design team.',
                ]),
                'meta_title' => 'ICONIC INTERIORS — Luxury Interior Designers | Design • Create • Inspire',
                'meta_description' => 'Luxury residential and commercial interior design, modular kitchens, bedrooms and custom furniture. Explore our live style customizer.',
            ],
            [
                'slug' => 'about',
                'title' => 'The Art of Iconic Living',
                'subtitle' => 'A studio built on one belief: every space should tell the story of the people who live in it.',
                'hero_banner_image' => $img('neoclassical_bedroom'),
                'content_body' => '<p>ICONIC INTERIORS was founded with a simple promise — to bring the precision of architecture and the soul of craftsmanship into every home and workplace we touch. What began as a small design atelier has grown into a full-service studio delivering residences, penthouses, villas, corporate offices and hospitality spaces.</p><p>Every project is handled end-to-end by our in-house team of designers, site engineers and master craftsmen. We design in 3D, specify every material, manufacture modular furniture in controlled conditions and install with obsessive attention to detail — so that what you approve is exactly what you live in.</p>',
                'extra_json' => $j([
                    'philosophy' => [
                        ['title' => 'Design', 'text' => 'Space planning, 3D visualisation and material storytelling tailored to how you live and work.'],
                        ['title' => 'Create', 'text' => 'Precision manufacturing, certified materials and master craftsmen executing every detail on site.'],
                        ['title' => 'Inspire', 'text' => 'Spaces that elevate everyday rituals and leave a lasting impression on everyone who walks in.'],
                    ],
                    'founder' => [
                        'name' => 'Santosh Kumar Maharana',
                        'role' => 'Founder & Principal Designer',
                        'bio' => 'With over a decade of hands-on experience across luxury residences and commercial fit-outs, Santosh leads every ICONIC project personally — from the first site visit to the final handover. His approach blends architectural discipline with a deep love for materials, light and proportion.',
                        'quote' => 'A truly iconic interior is not decorated — it is composed, like music.',
                    ],
                    'stats' => [
                        ['value' => '250+', 'label' => 'Projects Delivered'],
                        ['value' => '12+', 'label' => 'Years Experience'],
                        ['value' => '40+', 'label' => 'Craftsmen & Experts'],
                        ['value' => '98%', 'label' => 'Client Referral Rate'],
                    ],
                    'milestones' => [
                        ['year' => '2014', 'title' => 'The First Commission', 'text' => 'Santosh delivers his first turnkey residence, setting the standard for everything that followed.'],
                        ['year' => '2018', 'title' => 'Modular Manufacturing', 'text' => 'In-house modular kitchen & wardrobe production with precision CNC and edge-banding.'],
                        ['year' => '2022', 'title' => 'Commercial Division', 'text' => 'Expansion into corporate offices, showrooms and hospitality interiors.'],
                        ['year' => '2026', 'title' => 'ICONIC INTERIORS', 'text' => 'Relaunch as ICONIC INTERIORS — a luxury design-build studio. Design • Create • Inspire.'],
                    ],
                    'workflow' => [
                        ['step' => '01', 'title' => 'Consultation & Site Visit', 'text' => 'We understand your lifestyle, requirements and budget, and measure the site precisely.'],
                        ['step' => '02', 'title' => 'Concept & Space Planning', 'text' => 'Layouts, mood boards and material palettes to lock the design direction.'],
                        ['step' => '03', 'title' => '3D Visualisation', 'text' => 'Photorealistic renders of every room so you see the result before we build.'],
                        ['step' => '04', 'title' => 'Transparent Quotation', 'text' => 'Itemised BOQ with brands, finishes and timelines — no hidden costs.'],
                        ['step' => '05', 'title' => 'Production & Execution', 'text' => 'Factory-finished modular units and supervised on-site civil, electrical and finishing works.'],
                        ['step' => '06', 'title' => 'Styling & Handover', 'text' => 'Deep cleaning, décor styling and a walkthrough handover with warranty documentation.'],
                    ],
                ]),
                'meta_title' => 'About ICONIC INTERIORS — Founder Santosh Kumar Maharana',
                'meta_description' => 'Meet ICONIC INTERIORS, the luxury design-build studio led by Santosh Kumar Maharana. Our story, philosophy, milestones and process.',
            ],
            [
                'slug' => 'services',
                'title' => 'Signature Services',
                'subtitle' => 'Six disciplines. One studio. Complete accountability from concept to keys.',
                'hero_banner_image' => $img('commercial_lobby'),
                'content_body' => '',
                'extra_json' => $j([]),
                'meta_title' => 'Interior Design Services — ICONIC INTERIORS',
                'meta_description' => 'Residential interiors, commercial interiors, modular kitchens, bedroom designs, custom furniture and space planning by ICONIC INTERIORS.',
            ],
            [
                'slug' => 'portfolio',
                'title' => 'The Portfolio',
                'subtitle' => 'A curated selection of homes, kitchens, bedrooms and workplaces we have brought to life.',
                'hero_banner_image' => $img('emerald_deco_lounge'),
                'content_body' => '',
                'extra_json' => $j([]),
                'meta_title' => 'Interior Design Portfolio — ICONIC INTERIORS',
                'meta_description' => 'Browse luxury residential, commercial, kitchen and bedroom interior projects completed by ICONIC INTERIORS.',
            ],
            [
                'slug' => 'estimator',
                'title' => 'Interior Cost Estimator',
                'subtitle' => 'Get an instant, itemised budget range for your home or office in under 60 seconds.',
                'hero_banner_image' => $img('royal_noir_kitchen'),
                'content_body' => '<p>Estimates are indicative and based on typical scope for the selected property type. A precise, itemised quotation is shared after a site visit and design finalisation.</p>',
                'extra_json' => $j([
                    'breakdown' => [
                        ['label' => 'Modular Kitchen & Wardrobes', 'pct' => 34],
                        ['label' => 'Living, Dining & Loose Furniture', 'pct' => 22],
                        ['label' => 'False Ceiling & Lighting', 'pct' => 14],
                        ['label' => 'Wall Finishes, Panelling & Paint', 'pct' => 12],
                        ['label' => 'Flooring, Décor & Soft Furnishings', 'pct' => 10],
                        ['label' => 'Design, Supervision & Project Mgmt.', 'pct' => 8],
                    ],
                ]),
                'meta_title' => 'Interior Design Cost Calculator — ICONIC INTERIORS',
                'meta_description' => 'Calculate the interior design cost for your 1BHK, 2BHK, 3BHK, villa or office instantly with the ICONIC INTERIORS estimator.',
            ],
            [
                'slug' => 'contact',
                'title' => 'Begin Your Iconic Journey',
                'subtitle' => 'Book a complimentary consultation. We respond to every enquiry within 24 hours.',
                'hero_banner_image' => $img('industrial_office'),
                'content_body' => '<p>Visit our studio, call us, or drop a WhatsApp message — Santosh Kumar Maharana and the ICONIC team would love to hear about your space.</p>',
                'extra_json' => $j([]),
                'meta_title' => 'Contact ICONIC INTERIORS — Book a Design Consultation',
                'meta_description' => 'Call +91 9778646212 / +91 9337080037 or email iconicinterior2026@gmail.com to book your luxury interior design consultation.',
            ],
        ];
        foreach ($pages as $p) {
            $p['status'] = 1;
            $p['updated_at'] = $now;
            $this->insert('{{%cms_pages}}', $p);
        }

        // --- Hero styles (style x room) ---
        $styles = [
            ['Royal Noir & Gold', 'royal-noir-gold', 'Living Lounge', 'Midnight drama, gilded edges.',
                'Book-matched black marble, fluted noir panelling and sculptural brass light — an after-dark lounge built for unforgettable evenings.',
                'royal_noir_living', 'before_living_shell', ['#0A0A0A', '#1C1C1C', '#D4AF37', '#F5F0E6', '#8A6E2F'],
                ['Flooring' => 'Nero Marquina marble, book-matched with gold veining', 'Wall Finish' => 'Matte black fluted panels, PU lacquer', 'Lighting' => '2700K cove lighting + crystal & brass ring chandelier', 'Hardware' => 'Brushed gold PVD-coated fittings', 'Furniture' => 'Curved bouclé sofa, marble & brass coffee table'], 'warm', 10],
            ['Royal Noir & Gold', 'royal-noir-gold', 'Modular Kitchen', 'A chef\'s stage in black & gold.',
                'Handleless matte-black modules, a waterfall granite island and faceted brass pendants deliver a kitchen that doubles as a statement bar.',
                'royal_noir_kitchen', 'before_room_shell', ['#0A0A0A', '#2B2B2B', '#C5A059', '#D4AF37', '#5C4A2A'],
                ['Flooring' => 'Smoked oak engineered wood', 'Wall Finish' => 'Charcoal herringbone stone backsplash', 'Lighting' => 'Geometric brass pendants + under-cabinet LED', 'Hardware' => 'Gold profile handles, Blum soft-close', 'Countertop' => 'Black Cosmic granite waterfall island'], 'noir', 11],
            ['Royal Noir & Gold', 'royal-noir-gold', 'Executive Office', 'Boardroom-grade presence.',
                'Backlit onyx, fluted noir walls and gold linear light compose a reception and office suite that commands respect at first glance.',
                'commercial_lobby', 'before_living_shell', ['#0A0A0A', '#3A3A3A', '#D4AF37', '#E8D9B5', '#7A6A55'],
                ['Flooring' => 'Grey terrazzo with brass inlay strips', 'Wall Finish' => 'Black fluted panelling + brushed brass feature', 'Lighting' => 'Suspended gold linear sculpture', 'Hardware' => 'Satin brass', 'Furniture' => 'Backlit onyx reception desk, velvet lounge'], 'warm', 12],
            ['Modern Minimalist Luxe', 'modern-minimalist-luxe', 'Master Bedroom', 'Quiet luxury, perfectly edited.',
                'Travertine, linen and light oak in a calm, clutter-free composition — warmth without excess, punctuated by slim brass pendants.',
                'modern_minimal_bedroom', 'before_room_shell', ['#F5F1EA', '#D8CFC0', '#B8A58A', '#C5A059', '#6B6259'],
                ['Flooring' => 'Light oak wide-plank engineered wood', 'Wall Finish' => 'Natural travertine cladding', 'Lighting' => 'Slim brass drop pendants + concealed downlights', 'Hardware' => 'Satin brass', 'Furniture' => 'Low upholstered linen platform bed'], 'daylight', 20],
            ['Modern Minimalist Luxe', 'modern-minimalist-luxe', 'Modular Kitchen', 'Clean lines, warm touch.',
                'A white quartz waterfall island with oak and ivory handleless modules — a serene kitchen designed for light and flow.',
                'minimal_kitchen', 'before_room_shell', ['#FFFFFF', '#EDE7DD', '#C8A97E', '#C5A059', '#8C8C8C'],
                ['Flooring' => 'Polished micro-concrete', 'Wall Finish' => 'Hand-glazed ivory zellige tiles', 'Lighting' => 'Linear brass pendant + natural daylight', 'Hardware' => 'Push-to-open, Hettich', 'Countertop' => 'Calacatta-look quartz waterfall'], 'daylight', 21],
            ['Emerald & Brass Art Deco', 'emerald-brass-art-deco', 'Living Lounge', 'Jazz-age glamour, reimagined.',
                'Lacquered emerald walls with gold inlay, fan sconces and a sunburst marble floor — an Art Deco salon made for entertaining.',
                'emerald_deco_lounge', 'before_living_shell', ['#0B3D2E', '#14532D', '#C5A059', '#F2EBDD', '#111111'],
                ['Flooring' => 'Black & Statuario sunburst marble inlay', 'Wall Finish' => 'Emerald lacquer panels with brass inlay', 'Lighting' => 'Fluted glass fan sconces & Deco chandelier', 'Hardware' => 'Polished brass', 'Furniture' => 'Curved emerald velvet sofa, fluted brass consoles'], 'noir', 30],
            ['Neoclassical Ivory & Gold', 'neoclassical-ivory-gold', 'Master Bedroom', 'Palatial calm in ivory and gilt.',
                'Classical mouldings, a tufted ivory headboard and gilded mirrors beneath a crystal chandelier — timeless romance for the master suite.',
                'neoclassical_bedroom', 'before_room_shell', ['#FBF8F1', '#EFE6D2', '#D4AF37', '#B08D57', '#8B6F47'],
                ['Flooring' => 'Herringbone oak parquet', 'Wall Finish' => 'Ivory boiserie panelling with gilded mouldings', 'Lighting' => 'Crystal candle chandelier + table lamps', 'Hardware' => 'Antique gold', 'Furniture' => 'Tufted headboard bed, gilded console & mirror'], 'daylight', 40],
            ['Industrial Penthouse', 'industrial-penthouse', 'Executive Office', 'Raw edge, refined finish.',
                'Board-formed concrete, black steel glazing and live-edge walnut — a penthouse office with skyline views and serious character.',
                'industrial_office', 'before_living_shell', ['#2E2E2E', '#5A5A5A', '#8B5A2B', '#C5A059', '#D9D4CC'],
                ['Flooring' => 'Sealed polished concrete + jute rug', 'Wall Finish' => 'Exposed concrete & black steel-framed glass', 'Lighting' => 'Edison filament pendants, 2200K', 'Hardware' => 'Blackened steel', 'Furniture' => 'Live-edge walnut desk, cognac leather chair'], 'warm', 50],
            ['Biophilic Zen Luxury', 'biophilic-zen-luxury', 'Living Lounge', 'Nature, brought indoors.',
                'An indoor olive tree, living moss wall and teak beams open onto a courtyard garden — a restorative sanctuary for slow living.',
                'biophilic_zen_living', 'before_living_shell', ['#2F4F2F', '#6B8E23', '#D8CFC0', '#A47551', '#C5A059'],
                ['Flooring' => 'Honed limestone with teak inlay bands', 'Wall Finish' => 'Preserved moss wall + lime plaster', 'Lighting' => 'Washi paper pendant + natural daylight', 'Hardware' => 'Brushed bronze', 'Furniture' => 'Low linen sofa, rattan lounge chairs'], 'daylight', 60],
        ];
        foreach ($styles as $s) {
            [$name, $slug, $room, $tag, $desc, $after, $before, $palette, $materials, $light, $sort] = $s;
            $this->insert('{{%hero_styles}}', [
                'style_name' => $name,
                'slug' => $slug,
                'room_category' => $room,
                'tagline' => $tag,
                'description' => $desc,
                'after_image' => $img($after),
                'before_image' => $img($before),
                'color_palette_json' => $j($palette),
                'materials_json' => $j($materials),
                'lighting_mode_default' => $light,
                'sort_order' => $sort,
                'is_active' => 1,
            ]);
        }

        // --- Services (6 core) ---
        $tiers = static fn(string $e, string $l, string $b) => [
            ['name' => 'Essential', 'text' => $e],
            ['name' => 'Luxury Gold', 'text' => $l],
            ['name' => 'Bespoke Signature', 'text' => $b],
        ];
        $icon = static fn(string $path) => '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.4" stroke-linecap="round" stroke-linejoin="round">' . $path . '</svg>';

        $services = [
            [
                'Residential Interiors', 'residential-interiors',
                $icon('<path d="M3 11l9-7 9 7"/><path d="M5 10v10h14V10"/><path d="M10 20v-6h4v6"/>'),
                'Turnkey luxury interiors for apartments, penthouses and villas — designed around the way you live.',
                '<p>Your home is the most personal space you will ever own. Our residential practice delivers complete, turnkey interiors — from space planning and 3D visualisation to civil works, modular furniture, lighting, décor and final styling.</p><p>Every residence is composed around a clear design narrative, a curated material palette and meticulous detailing, executed by our in-house craftsmen under one accountable project lead.</p>',
                'royal_noir_living',
                ['Detailed space planning & furniture layouts', 'Photorealistic 3D renders for every room', 'Itemised BOQ with brands & finishes', 'Modular kitchen & wardrobes', 'False ceiling, lighting & electrical design', 'Décor, soft furnishing & final styling'],
                $tiers('BWP plywood, laminate finishes, branded hardware and clean contemporary detailing.', 'Acrylic & PU finishes, veneer accents, profile lighting, premium Hettich/Blum fittings.', 'Imported stones, custom brass inlays, bespoke furniture and designer lighting curated per room.'),
                ['royal_noir_living', 'emerald_deco_lounge', 'biophilic_zen_living'], 450000, 10,
            ],
            [
                'Commercial Interiors', 'commercial-interiors',
                $icon('<rect x="4" y="3" width="16" height="18" rx="1"/><path d="M9 7h1M14 7h1M9 11h1M14 11h1M9 15h1M14 15h1"/><path d="M10 21v-3h4v3"/>'),
                'Offices, showrooms, clinics and hospitality spaces that express your brand and boost productivity.',
                '<p>We design workplaces and commercial environments that work as hard as you do. From corporate headquarters and executive cabins to retail showrooms, clinics and cafés, we balance brand expression, ergonomics and operational efficiency.</p><p>Our commercial team manages fast-track timelines, MEP coordination and phased execution to minimise business downtime.</p>',
                'commercial_lobby',
                ['Brand-led concept design', 'Workstation & seating planning', 'Reception, cabins & conference rooms', 'Acoustic & lighting design', 'MEP & HVAC coordination', 'Fast-track phased execution'],
                $tiers('Efficient modular workstations, gypsum ceilings and durable laminates.', 'Acoustic panelling, glass partitions, feature reception & designer lighting.', 'Signature brand experience — onyx, metal inlays, custom joinery and art curation.'),
                ['commercial_lobby', 'industrial_office'], 800000, 20,
            ],
            [
                'Modular Kitchens', 'modular-kitchens',
                $icon('<rect x="3" y="4" width="18" height="16" rx="1"/><path d="M3 10h18"/><path d="M8 7h.01M12 7h.01"/><path d="M9 14h6"/>'),
                'Precision-engineered modular kitchens with premium hardware — delivered and installed in 45 days.',
                '<p>The kitchen is the heart of the home — and the hardest-working room in it. Our modular kitchens are factory-manufactured on precision CNC machinery, finished in a controlled environment and installed by specialist teams.</p><p>Choose from L-shaped, U-shaped, parallel, island and peninsula layouts with acrylic, PU, glass, veneer or laminate shutters and world-class hardware.</p>',
                'royal_noir_kitchen',
                ['Ergonomic work-triangle layout', 'BWR / BWP plywood carcass', 'Soft-close Hettich / Blum hardware', 'Quartz, granite or marble countertops', 'Tall units, pantry & corner solutions', 'Chimney, hob & appliance integration'],
                $tiers('Laminate shutters, BWR ply carcass, standard soft-close channels.', 'Acrylic / PU shutters, profile handles, quartz tops & under-cabinet lighting.', 'Fenix / veneer / lacquered glass, waterfall islands, gold-PVD hardware & smart storage.'),
                ['royal_noir_kitchen', 'minimal_kitchen'], 185000, 30,
            ],
            [
                'Bedroom Designs', 'bedroom-designs',
                $icon('<path d="M3 18V8"/><path d="M21 18v-5a3 3 0 0 0-3-3H10v8"/><path d="M3 14h18"/><circle cx="6.5" cy="11" r="1.5"/>'),
                'Restful master suites, kids\' rooms and guest bedrooms with bespoke wardrobes and layered lighting.',
                '<p>A bedroom should feel like a private retreat. We design master suites, kids\' rooms and guest bedrooms with tailored headboards, walk-in or sliding wardrobes, dressers, study nooks and layered, dimmable lighting.</p><p>Fabrics, textures and colour are chosen to calm the senses — so every evening feels like checking into your favourite hotel.</p>',
                'neoclassical_bedroom',
                ['Upholstered headboard & bed design', 'Sliding / hinged / walk-in wardrobes', 'Dresser & study units', 'Layered mood lighting', 'Curtains, rugs & soft furnishing', 'Acoustic & blackout solutions'],
                $tiers('Laminate wardrobes, simple headboard panel, warm white lighting.', 'Fabric & leather headboards, lacquered glass wardrobes, profile lights.', 'Walk-in dressing suites, boiserie panelling, silk & velvet upholstery.'),
                ['neoclassical_bedroom', 'modern_minimal_bedroom'], 225000, 40,
            ],
            [
                'Custom Furniture', 'custom-furniture',
                $icon('<path d="M5 11V7a2 2 0 0 1 2-2h10a2 2 0 0 1 2 2v4"/><path d="M3 11h18v5H3z"/><path d="M5 16v3M19 16v3"/>'),
                'Handcrafted sofas, consoles, beds, dining tables and statement pieces made to your exact size and finish.',
                '<p>When the perfect piece does not exist, we make it. Our custom furniture atelier crafts sofas, beds, consoles, dining tables, TV units and statement pieces to your exact dimensions, materials and finish.</p><p>Solid woods, veneers, metal, marble, leather and premium fabrics are combined with traditional joinery and modern engineering.</p>',
                'custom_furniture',
                ['Concept sketches & shop drawings', 'Solid wood, veneer & metal fabrication', 'Upholstery in premium fabrics & leather', 'Marble, onyx & stone tops', 'Brass & PVD metal detailing', 'White-glove delivery & installation'],
                $tiers('Engineered wood with laminate or duco finishes.', 'Veneer, fabric & leather upholstery, brass accents.', 'Solid hardwood, hand-finished lacquer, bespoke metalwork & stone.'),
                ['custom_furniture'], 35000, 50,
            ],
            [
                'Space Planning', 'space-planning',
                $icon('<path d="M3 3h18v18H3z"/><path d="M3 9h8v12"/><path d="M11 13h10"/><path d="M15 3v6"/>'),
                'Intelligent layouts, furniture planning and circulation studies that make every square foot count.',
                '<p>Great interiors begin with great planning. Our space planning service studies how you move, gather, work and rest — then translates that into intelligent layouts, furniture plans and circulation that unlock the full potential of every square foot.</p><p>Ideal before purchase, renovation or full interior execution, with Vastu-friendly options on request.</p>',
                'space_planning',
                ['Site measurement & as-built drawings', 'Multiple layout options', 'Furniture & circulation planning', 'Electrical & lighting layouts', 'Vastu-compliant planning (optional)', 'Working drawings for execution'],
                $tiers('2D layout options with furniture plan.', '2D + 3D massing, electrical & ceiling layouts.', 'Full working drawing set with 3D walkthrough.'),
                ['space_planning', 'modern_minimal_bedroom'], 15000, 60,
            ],
        ];
        foreach ($services as $s) {
            [$title, $slug, $svg, $short, $full, $feat, $deliv, $tierArr, $gallery, $price, $sort] = $s;
            $this->insert('{{%services}}', [
                'title' => $title,
                'slug' => $slug,
                'icon_svg_or_class' => $svg,
                'short_description' => $short,
                'full_description' => $full,
                'featured_image' => $img($feat),
                'deliverables_json' => $j($deliv),
                'material_tiers_json' => $j($tierArr),
                'gallery_images_json' => $j(array_map($img, $gallery)),
                'starting_price' => $price,
                'meta_title' => $title . ' — ICONIC INTERIORS',
                'meta_description' => $short,
                'sort_order' => $sort,
                'is_active' => 1,
            ]);
        }
        $sid = fn(string $slug) => (int) (new \yii\db\Query())->select('id')->from('{{%services}}')->where(['slug' => $slug])->scalar($this->db);

        // --- Projects ---
        $projects = [
            ['The Obsidian Penthouse', 'the-obsidian-penthouse', 'residential-interiors', 'Residential', 'Mr. & Mrs. Mohanty', 'Patia, Bhubaneswar', 3200, 'Bespoke Signature', 2025, '120 Days', 'royal_noir_living', 'before_living_shell', ['royal_noir_living', 'royal_noir_kitchen', 'neoclassical_bedroom'],
                'A sky-high penthouse transformed into a noir-and-gold sanctuary with book-matched marble, fluted panelling and bespoke brass lighting.',
                'A 4BHK penthouse for a family who entertain often. They wanted a dramatic, hotel-grade living lounge, a show kitchen that doubles as a bar, and calm, classic bedrooms.',
                'Floor-to-ceiling glazing caused heat and glare. We introduced motorised sheers, a deep cove ceiling for indirect light and dark, low-reflectance finishes that turn the skyline into the hero after sunset.', 1],
            ['Emerald Deco Residence', 'emerald-deco-residence', 'residential-interiors', 'Residential', 'The Patnaik Family', 'Cuttack, Odisha', 2400, 'Luxury Gold', 2025, '95 Days', 'emerald_deco_lounge', 'before_living_shell', ['emerald_deco_lounge', 'custom_furniture'],
                'A jazz-age salon with emerald lacquer, brass inlays and a sunburst marble floor.',
                'The clients asked for a bold, colourful home that felt glamorous yet livable, with a dedicated bar lounge for weekend gatherings.',
                'Deep colour can feel heavy. We balanced the emerald with reflective brass, mirrored roundels and a high-contrast marble floor to bounce light back into the room.', 1],
            ['Noir Culinary Studio', 'noir-culinary-studio', 'modular-kitchens', 'Kitchens', 'Dr. A. Sahoo', 'Jaydev Vihar, Bhubaneswar', 260, 'Luxury Gold', 2026, '45 Days', 'royal_noir_kitchen', 'before_room_shell', ['royal_noir_kitchen', 'minimal_kitchen'],
                'A matte-black modular kitchen with a waterfall granite island, wine storage and gold detailing.',
                'An open kitchen that could host guests, with integrated appliances, wine storage and a breakfast island for four.',
                'Black kitchens show every fingerprint. We specified anti-fingerprint matte laminates, gold-PVD profiles and warm under-cabinet LEDs to keep the space luxurious and practical.', 1],
            ['Ivory Palace Suite', 'ivory-palace-suite', 'bedroom-designs', 'Bedrooms', 'Mrs. S. Das', 'Puri, Odisha', 420, 'Bespoke Signature', 2025, '60 Days', 'neoclassical_bedroom', 'before_room_shell', ['neoclassical_bedroom', 'modern_minimal_bedroom'],
                'A neoclassical master suite with boiserie panelling, gilded mirrors and a crystal chandelier.',
                'A romantic, palatial master bedroom with ample wardrobe storage concealed behind the panelling.',
                'We integrated full-height wardrobes into the classical wall panelling so storage disappears completely, keeping the room serene and symmetrical.', 1],
            ['Loft 21 Executive Office', 'loft-21-executive-office', 'commercial-interiors', 'Commercial', 'Loft 21 Ventures', 'Infocity, Bhubaneswar', 1800, 'Luxury Gold', 2026, '70 Days', 'industrial_office', 'before_living_shell', ['industrial_office', 'commercial_lobby'],
                'An industrial penthouse office with black steel glazing, exposed concrete and a live-edge walnut desk.',
                'A founder\'s office and lounge that felt creative and premium, with glass-partitioned meeting rooms.',
                'Exposed services were cleaned, painted and celebrated; acoustic panels were hidden behind the bookshelves to tame echo from the hard surfaces.', 1],
            ['Aurum Corporate Lobby', 'aurum-corporate-lobby', 'commercial-interiors', 'Commercial', 'Aurum Group', 'Rourkela, Odisha', 2600, 'Bespoke Signature', 2025, '85 Days', 'commercial_lobby', 'before_living_shell', ['commercial_lobby', 'industrial_office'],
                'A corporate reception with a backlit onyx desk, fluted noir wall and suspended gold light sculpture.',
                'A first impression that reflects the group\'s premium positioning, plus a client lounge and two meeting rooms.',
                'The onyx desk was CNC-cut in sections and backlit with diffused LED panels for even glow without hotspots.', 0],
            ['Serene Courtyard Villa', 'serene-courtyard-villa', 'residential-interiors', 'Residential', 'Mr. R. Mishra', 'Sambalpur, Odisha', 3800, 'Bespoke Signature', 2024, '140 Days', 'biophilic_zen_living', 'before_living_shell', ['biophilic_zen_living', 'modern_minimal_bedroom'],
                'A biophilic villa with an indoor olive tree, preserved moss wall and courtyard-facing living.',
                'A calm, nature-led home for a retired couple, with seamless indoor-outdoor living.',
                'We used preserved moss (zero maintenance) and drought-tolerant indoor species with automated drip irrigation to keep the greenery effortless.', 0],
            ['Linen & Light Suite', 'linen-and-light-suite', 'bedroom-designs', 'Bedrooms', 'Ms. P. Rath', 'Bhubaneswar, Odisha', 380, 'Luxury Gold', 2026, '40 Days', 'modern_minimal_bedroom', 'before_room_shell', ['modern_minimal_bedroom'],
                'A quiet-luxury bedroom in travertine, linen and light oak.',
                'A minimalist, hotel-style bedroom with hidden storage and lots of daylight.',
                'Travertine-look large-format panels delivered a stone feel at a fraction of the weight, with concealed handle-less wardrobes.', 0],
            ['Daylight Kitchen Residence', 'daylight-kitchen-residence', 'modular-kitchens', 'Kitchens', 'Mr. K. Behera', 'Cuttack, Odisha', 220, 'Essential', 2026, '35 Days', 'minimal_kitchen', 'before_room_shell', ['minimal_kitchen'],
                'A bright modular kitchen with a quartz waterfall island and oak handleless cabinetry.',
                'An open kitchen and dining for a young family with lots of storage and easy maintenance.',
                'We converted a closed kitchen into an open plan by relocating plumbing lines and adding a waterfall island as the new social hub.', 0],
        ];
        $year = (int) date('Y');
        foreach ($projects as $i => $p) {
            [$title, $slug, $svc, $cat, $client, $loc, $area, $tier, $yr, $time, $cover, $before, $gallery, $summary, $req, $sol, $feat] = $p;
            $this->insert('{{%projects}}', [
                'title' => $title,
                'slug' => $slug,
                'service_id' => $sid($svc) ?: null,
                'category' => $cat,
                'client_name' => $client,
                'location' => $loc,
                'carpet_area' => $area,
                'budget_tier' => $tier,
                'completion_year' => min($yr, $year),
                'completion_time' => $time,
                'cover_image' => $img($cover),
                'before_image' => $img($before),
                'gallery_images_json' => $j(array_map($img, $gallery)),
                'summary' => $summary,
                'client_requirements' => $req,
                'challenge_and_solution' => $sol,
                'is_featured' => $feat,
                'is_active' => 1,
                'created_at' => date('Y-m-d H:i:s', time() - $i * 86400),
            ]);
        }

        // --- Testimonials ---
        $this->batchInsert('{{%testimonials}}', ['client_name', 'client_role', 'project_type', 'rating', 'review_text', 'is_active'], [
            ['Rakesh Mohanty', 'Homeowner', '4BHK Penthouse', 5, 'Santosh and his team understood exactly what we wanted. The 3D renders matched the final result to the last detail — our living room genuinely feels like a five-star lounge.', 1],
            ['Dr. Anjali Sahoo', 'Physician', 'Modular Kitchen', 5, 'Delivered in 45 days as promised. The finish quality, the soft-close hardware and the gold detailing are outstanding. Our friends cannot stop talking about the kitchen.', 1],
            ['Vikram Patnaik', 'Director, Loft 21 Ventures', 'Commercial Office', 5, 'Professional from day one — clear BOQ, zero surprises and a site team that respected our working hours. Our office now reflects the brand we are building.', 1],
        ]);
    }
}
