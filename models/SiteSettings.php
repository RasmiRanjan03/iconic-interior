<?php

namespace app\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * Global CMS configuration (single row).
 *
 * @property int $id
 * @property string $company_name
 * @property string|null $tagline
 * @property string|null $owner_name
 * @property string|null $phone_primary
 * @property string|null $phone_secondary
 * @property string|null $whatsapp_number
 * @property string|null $email
 * @property string|null $address
 * @property string|null $working_hours
 * @property string|null $map_embed_url
 * @property string|null $facebook_url
 * @property string|null $instagram_url
 * @property string|null $footer_text
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int $rate_essential
 * @property int $rate_luxury_gold
 * @property int $rate_bespoke_signature
 */
class SiteSettings extends ActiveRecord
{
    private static ?self $_instance = null;

    public static function tableName(): string
    {
        return '{{%site_settings}}';
    }

    /**
     * Returns the single settings row (memoised per request).
     */
    public static function get(): self
    {
        if (self::$_instance === null) {
            self::$_instance = static::find()->orderBy(['id' => SORT_ASC])->one() ?? new static([
                'company_name' => 'ICONIC INTERIORS',
                'tagline' => 'DESIGN • CREATE • INSPIRE',
                'rate_essential' => 1450,
                'rate_luxury_gold' => 2400,
                'rate_bespoke_signature' => 3800,
            ]);
        }
        return self::$_instance;
    }

    public function rules(): array
    {
        return [
            [['company_name', 'phone_primary', 'email'], 'required'],
            [['company_name', 'owner_name'], 'string', 'max' => 120],
            [['tagline', 'meta_title', 'working_hours'], 'string', 'max' => 160],
            [['phone_primary', 'phone_secondary', 'whatsapp_number'], 'string', 'max' => 20],
            [['phone_primary', 'phone_secondary'], 'match', 'pattern' => '/^\+?[0-9 \-]{7,20}$/'],
            ['whatsapp_number', 'match', 'pattern' => '/^[0-9]{10,15}$/', 'message' => 'Digits only incl. country code, e.g. 919778646212'],
            ['email', 'email'],
            [['address', 'footer_text'], 'string', 'max' => 500],
            ['map_embed_url', 'url'],
            ['map_embed_url', 'string', 'max' => 1000],
            [['facebook_url', 'instagram_url'], 'url'],
            ['meta_description', 'string', 'max' => 320],
            [['rate_essential', 'rate_luxury_gold', 'rate_bespoke_signature'], 'required'],
            [['rate_essential', 'rate_luxury_gold', 'rate_bespoke_signature'], 'integer', 'min' => 100, 'max' => 100000],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'rate_essential' => 'Essential (₹ / sq.ft.)',
            'rate_luxury_gold' => 'Luxury Gold (₹ / sq.ft.)',
            'rate_bespoke_signature' => 'Bespoke Signature (₹ / sq.ft.)',
            'whatsapp_number' => 'WhatsApp Number (digits with country code)',
            'map_embed_url' => 'Google Map Embed URL',
        ];
    }

    /** Digits-only WhatsApp number. */
    public function getWhatsappDigits(): string
    {
        $n = preg_replace('/\D+/', '', (string) ($this->whatsapp_number ?: $this->phone_primary));
        return strlen($n) === 10 ? '91' . $n : $n;
    }

    public function whatsappUrl(string $text = ''): string
    {
        return 'https://wa.me/' . $this->getWhatsappDigits() . ($text !== '' ? '?text=' . rawurlencode($text) : '');
    }

    public static function telHref(?string $phone): string
    {
        return 'tel:' . preg_replace('/[^0-9+]/', '', (string) $phone);
    }

    /** Estimator rates keyed by tier name. */
    public function getEstimatorRates(): array
    {
        return [
            'Essential' => (int) $this->rate_essential,
            'Luxury Gold' => (int) $this->rate_luxury_gold,
            'Bespoke Signature' => (int) $this->rate_bespoke_signature,
        ];
    }

    public function afterSave($insert, $changedAttributes)
    {
        parent::afterSave($insert, $changedAttributes);
        self::$_instance = null;
        Yii::$app->cache?->delete('site_settings');
    }
}
