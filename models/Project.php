<?php

namespace app\models;

use Yii;
use app\components\JsonFieldsTrait;
use app\components\Media;
use yii\behaviors\AttributeBehavior;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\db\Expression;
use yii\web\UploadedFile;

/**
 * Portfolio project / case study.
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property int|null $service_id
 * @property string $category
 * @property string|null $client_name
 * @property string|null $location
 * @property int|null $carpet_area
 * @property string|null $budget_tier
 * @property int|null $completion_year
 * @property string|null $completion_time
 * @property string|null $cover_image
 * @property string|null $before_image
 * @property string|null $gallery_images_json
 * @property string|null $summary
 * @property string|null $client_requirements
 * @property string|null $challenge_and_solution
 * @property int $is_featured
 * @property int $is_active
 * @property string|null $created_at
 *
 * @property Service|null $service
 */
class Project extends ActiveRecord
{
    use JsonFieldsTrait;

    /** @var UploadedFile|null */
    public $coverImageFile;
    /** @var UploadedFile|null */
    public $beforeImageFile;
    /** @var UploadedFile[] */
    public $galleryFiles = [];
    /** @var string[] */
    public $removeGallery = [];

    public static function tableName(): string
    {
        return '{{%projects}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class' => SluggableBehavior::class,
                'attribute' => 'title',
                'ensureUnique' => true,
                'immutable' => true,
            ],
            [
                'class' => AttributeBehavior::class,
                'attributes' => [ActiveRecord::EVENT_BEFORE_INSERT => 'created_at'],
                'value' => new Expression('NOW()'),
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['title', 'category'], 'required'],
            [['title'], 'string', 'max' => 200],
            ['category', 'in', 'range' => Yii::$app->params['projectCategories']],
            ['service_id', 'integer'],
            ['service_id', 'exist', 'targetClass' => Service::class, 'targetAttribute' => 'id', 'skipOnEmpty' => true],
            [['client_name', 'location'], 'string', 'max' => 160],
            [['carpet_area'], 'integer', 'min' => 1, 'max' => 1000000],
            [['completion_year'], 'integer', 'min' => 1990, 'max' => 2100],
            [['budget_tier', 'completion_time'], 'string', 'max' => 60],
            [['summary', 'client_requirements', 'challenge_and_solution'], 'string', 'max' => 5000],
            [['is_featured', 'is_active'], 'boolean'],
            ['removeGallery', 'each', 'rule' => ['string']],
            [['coverImageFile', 'beforeImageFile'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024],
            ['galleryFiles', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024, 'maxFiles' => 20],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'service_id' => 'Service',
            'carpet_area' => 'Carpet Area (sq.ft.)',
            'challenge_and_solution' => 'Challenge & Solution',
            'coverImageFile' => 'Cover / After Image',
            'beforeImageFile' => 'Before Image',
            'galleryFiles' => 'Add Gallery Images',
            'is_featured' => 'Featured on Home',
        ];
    }

    public function getService()
    {
        return $this->hasOne(Service::class, ['id' => 'service_id']);
    }

    public function getGallery(): array
    {
        return $this->jsonArray('gallery_images_json');
    }

    public function getCoverUrl(): string
    {
        return Media::url($this->cover_image);
    }

    public function saveWithUploads(): bool
    {
        $this->coverImageFile = UploadedFile::getInstance($this, 'coverImageFile');
        $this->beforeImageFile = UploadedFile::getInstance($this, 'beforeImageFile');
        $this->galleryFiles = UploadedFile::getInstances($this, 'galleryFiles');
        if (!$this->validate()) {
            return false;
        }
        if ($p = Media::store($this->coverImageFile, 'projects')) {
            Media::delete($this->getOldAttribute('cover_image'));
            $this->cover_image = $p;
        }
        if ($p = Media::store($this->beforeImageFile, 'projects')) {
            Media::delete($this->getOldAttribute('before_image'));
            $this->before_image = $p;
        }
        $remove = array_values(array_intersect((array) $this->removeGallery, $this->getGallery()));
        foreach ($remove as $r) {
            Media::delete($r);
        }
        $gallery = array_values(array_diff($this->getGallery(), $remove));
        $gallery = array_merge($gallery, Media::storeMany($this->galleryFiles, 'projects'));
        $this->gallery_images_json = self::encodeJson($gallery);

        return $this->save(false);
    }

    public static function active()
    {
        return static::find()->where(['is_active' => 1])->orderBy(['is_featured' => SORT_DESC, 'completion_year' => SORT_DESC, 'id' => SORT_DESC]);
    }
}
