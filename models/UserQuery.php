<?php

namespace app\models;

use Yii;
use app\components\Estimator;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;

/**
 * User lead / inquiry / estimator submission.
 *
 * @property int $id
 * @property string $full_name
 * @property string $phone
 * @property string|null $email
 * @property string $query_source
 * @property string|null $selected_service
 * @property string|null $selected_hero_style
 * @property string|null $property_type
 * @property int|null $carpet_area_sqft
 * @property string|null $finish_tier
 * @property string|null $estimated_budget
 * @property string|null $preferred_location
 * @property string|null $message
 * @property string $status
 * @property string|null $admin_notes
 * @property int $is_read
 * @property string|null $ip_address
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class UserQuery extends ActiveRecord
{
    public const SOURCE_CONTACT = 'Contact Form';
    public const SOURCE_HERO = 'Hero Style Customizer';
    public const SOURCE_SERVICE = 'Service Page';
    public const SOURCE_ESTIMATOR = 'Cost Estimator';

    public const SOURCES = [self::SOURCE_CONTACT, self::SOURCE_HERO, self::SOURCE_SERVICE, self::SOURCE_ESTIMATOR];

    public const STATUSES = ['New', 'Contacted', 'Site Visit Scheduled', 'Quotation Sent', 'Converted', 'Closed'];

    public const SCENARIO_CONTACT = 'contact';
    public const SCENARIO_HERO = 'hero';
    public const SCENARIO_SERVICE = 'service';
    public const SCENARIO_ESTIMATOR = 'estimator';
    public const SCENARIO_ADMIN = 'admin';

    /** @var string Honeypot — must stay empty (bots fill every field). */
    public $website;

    public static function tableName(): string
    {
        return '{{%user_queries}}';
    }

    public function behaviors(): array
    {
        return [[
            'class' => TimestampBehavior::class,
            'value' => new Expression('NOW()'),
        ]];
    }

    public function scenarios(): array
    {
        $contact = ['full_name', 'phone', 'email', 'website', 'message'];
        return [
            self::SCENARIO_DEFAULT => array_merge($contact, ['selected_service', 'preferred_location']),
            self::SCENARIO_CONTACT => array_merge($contact, ['selected_service', 'property_type', 'estimated_budget', 'preferred_location']),
            self::SCENARIO_HERO => array_merge($contact, ['selected_hero_style', 'selected_service', 'preferred_location']),
            self::SCENARIO_SERVICE => array_merge($contact, ['selected_service', 'property_type', 'estimated_budget', 'preferred_location']),
            self::SCENARIO_ESTIMATOR => array_merge($contact, ['property_type', 'carpet_area_sqft', 'finish_tier', 'preferred_location']),
            self::SCENARIO_ADMIN => ['status', 'admin_notes'],
        ];
    }

    public function rules(): array
    {
        return [
            [['full_name', 'phone', 'email', 'selected_service', 'selected_hero_style', 'preferred_location', 'message', 'estimated_budget', 'admin_notes'], 'trim'],
            [['full_name', 'phone'], 'required'],
            ['full_name', 'string', 'min' => 2, 'max' => 160],
            ['phone', 'match', 'pattern' => '/^\+?[0-9][0-9\s\-]{8,18}$/', 'message' => 'Please enter a valid phone number.'],
            ['email', 'email'],
            ['email', 'string', 'max' => 191],
            [['selected_service', 'selected_hero_style', 'preferred_location'], 'string', 'max' => 160],
            ['estimated_budget', 'string', 'max' => 120],
            ['message', 'string', 'max' => 3000],
            ['website', 'compare', 'compareValue' => '', 'message' => 'Spam detected.'],

            ['selected_hero_style', 'required', 'on' => self::SCENARIO_HERO],
            ['selected_service', 'required', 'on' => self::SCENARIO_SERVICE],

            [['property_type', 'carpet_area_sqft', 'finish_tier'], 'required', 'on' => self::SCENARIO_ESTIMATOR],
            ['property_type', 'in', 'range' => array_keys(Yii::$app->params['propertyTypes'])],
            ['carpet_area_sqft', 'integer', 'min' => 100, 'max' => 100000],
            ['finish_tier', 'in', 'range' => Estimator::TIERS],

            ['status', 'required', 'on' => self::SCENARIO_ADMIN],
            ['status', 'in', 'range' => self::STATUSES],
            ['admin_notes', 'string', 'max' => 10000],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'id' => 'Lead #',
            'full_name' => 'Full Name',
            'phone' => 'Phone / WhatsApp',
            'email' => 'Email',
            'query_source' => 'Source',
            'selected_service' => 'Service',
            'selected_hero_style' => 'Hero Design Style',
            'property_type' => 'Property Type',
            'carpet_area_sqft' => 'Carpet Area (sq.ft.)',
            'finish_tier' => 'Finish Tier',
            'estimated_budget' => 'Estimated Budget',
            'preferred_location' => 'Project Location',
            'message' => 'Message',
            'status' => 'Lead Status',
            'admin_notes' => 'Internal Notes',
            'is_read' => 'Read',
            'ip_address' => 'IP Address',
            'created_at' => 'Received',
            'updated_at' => 'Last Updated',
        ];
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($insert) {
            if (Yii::$app->request instanceof \yii\web\Request) {
                $this->ip_address = Yii::$app->request->userIP;
            }
            $this->status = $this->status ?: 'New';
            $this->is_read = 0;
        }
        return true;
    }

    /* ---------- presentation helpers ---------- */

    public static function unreadCount(): int
    {
        return (int) static::find()->where(['is_read' => 0])->count();
    }

    public function getPhoneDigits(): string
    {
        $d = preg_replace('/\D+/', '', (string) $this->phone);
        if (strlen($d) === 10) {
            $d = '91' . $d;
        } elseif (strlen($d) === 11 && $d[0] === '0') {
            $d = '91' . substr($d, 1);
        }
        return $d;
    }

    /** Pre-filled WhatsApp reply to the client. */
    public function getWhatsappReplyUrl(): string
    {
        $s = SiteSettings::get();
        $topic = $this->selected_hero_style
            ? 'the “' . $this->selected_hero_style . '” look'
            : ($this->selected_service ?: 'your interior project');
        $lines = [
            'Hello ' . $this->full_name . ',',
            '',
            'Greetings from ' . $s->company_name . '! This is ' . ($s->owner_name ?: 'our design team') . '.',
            'Thank you for your enquiry about ' . $topic . '.',
        ];
        if ($this->property_type || $this->carpet_area_sqft) {
            $lines[] = 'Property: ' . trim(($this->property_type ?? '') . ($this->carpet_area_sqft ? ' · ' . $this->carpet_area_sqft . ' sq.ft.' : ''), ' ·');
        }
        if ($this->estimated_budget) {
            $lines[] = 'Indicative budget: ' . $this->estimated_budget . ($this->finish_tier ? ' (' . $this->finish_tier . ')' : '');
        }
        $lines[] = '';
        $lines[] = 'When would be a convenient time for a quick call or a site visit?';

        return 'https://wa.me/' . $this->getPhoneDigits() . '?text=' . rawurlencode(implode("\n", $lines));
    }

    public static function statusColor(string $status): string
    {
        return [
            'New' => 'gold',
            'Contacted' => 'sky',
            'Site Visit Scheduled' => 'violet',
            'Quotation Sent' => 'amber',
            'Converted' => 'emerald',
            'Closed' => 'zinc',
        ][$status] ?? 'zinc';
    }

    public static function sourceIcon(string $source): string
    {
        return [
            self::SOURCE_CONTACT => '✉',
            self::SOURCE_HERO => '✦',
            self::SOURCE_SERVICE => '◆',
            self::SOURCE_ESTIMATOR => '₹',
        ][$source] ?? '•';
    }
}
