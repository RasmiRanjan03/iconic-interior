<?php

namespace app\models;

use app\components\JsonFieldsTrait;
use app\components\Media;
use yii\behaviors\AttributeBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * Individual page content & SEO.
 *
 * @property int $id
 * @property string $slug
 * @property string $title
 * @property string|null $subtitle
 * @property string|null $hero_banner_image
 * @property string|null $content_body
 * @property string|null $extra_json
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int $status
 * @property string|null $updated_at
 */
class CmsPage extends ActiveRecord
{
    use JsonFieldsTrait;

    /** @var UploadedFile|null */
    public $heroBannerFile;

    private static array $_cache = [];

    public static function tableName(): string
    {
        return '{{%cms_pages}}';
    }

    public function behaviors(): array
    {
        return [[
            'class' => AttributeBehavior::class,
            'attributes' => [
                ActiveRecord::EVENT_BEFORE_INSERT => 'updated_at',
                ActiveRecord::EVENT_BEFORE_UPDATE => 'updated_at',
            ],
            'value' => new Expression('NOW()'),
        ]];
    }

    public function rules(): array
    {
        return [
            [['slug', 'title'], 'required'],
            ['slug', 'match', 'pattern' => '/^[a-z0-9\-]+$/'],
            ['slug', 'unique'],
            [['title'], 'string', 'max' => 200],
            [['subtitle'], 'string', 'max' => 400],
            [['meta_title'], 'string', 'max' => 160],
            [['meta_description'], 'string', 'max' => 320],
            [['content_body'], 'string'],
            ['extra_json', 'validateJson'],
            ['status', 'boolean'],
            ['heroBannerFile', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024],
        ];
    }

    public function validateJson($attribute): void
    {
        $v = trim((string) $this->$attribute);
        if ($v === '') {
            $this->$attribute = '{}';
            return;
        }
        json_decode($v);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $this->addError($attribute, 'Extra data must be valid JSON: ' . json_last_error_msg());
        }
    }

    public function attributeLabels(): array
    {
        return [
            'extra_json' => 'Structured Data (JSON — stats, milestones, workflow…)',
            'heroBannerFile' => 'Hero Banner Image',
            'content_body' => 'Body Content',
        ];
    }

    public static function findBySlug(string $slug): ?self
    {
        if (!array_key_exists($slug, self::$_cache)) {
            self::$_cache[$slug] = static::findOne(['slug' => $slug, 'status' => 1]);
        }
        return self::$_cache[$slug];
    }

    public function getExtra(): array
    {
        return $this->jsonArray('extra_json');
    }

    public function getBannerUrl(): string
    {
        return Media::url($this->hero_banner_image);
    }

    public function saveWithUploads(): bool
    {
        $this->heroBannerFile = UploadedFile::getInstance($this, 'heroBannerFile');
        if (!$this->validate()) {
            return false;
        }
        if ($path = Media::store($this->heroBannerFile, 'pages')) {
            Media::delete($this->getOldAttribute('hero_banner_image'));
            $this->hero_banner_image = $path;
        }
        return $this->save(false);
    }
}
