<?php

namespace app\models;

use app\components\JsonFieldsTrait;
use app\components\Media;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Product model for ICONIC INTERIORS shop section.
 *
 * @property int         $id
 * @property string      $name
 * @property string      $slug
 * @property string      $category
 * @property string|null $short_description
 * @property string|null $full_description
 * @property float       $price
 * @property float|null  $original_price
 * @property string|null $sku
 * @property int         $stock
 * @property int         $is_in_stock
 * @property string|null $featured_image
 * @property string|null $gallery_json
 * @property string|null $specs_json
 * @property string|null $tags_json
 * @property int         $is_featured
 * @property int         $is_active
 * @property int         $sort_order
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property string|null $created_at
 * @property string|null $updated_at
 */
class Product extends ActiveRecord
{
    use JsonFieldsTrait;

    /** All supported categories */
    public const CATEGORIES = ['Lighting', 'Furniture', 'Decor', 'Textiles', 'Wall Art'];

    /** @var UploadedFile|null */
    public $featuredImageFile;
    /** @var UploadedFile[] */
    public $galleryFiles = [];
    /** @var string[] gallery paths to remove */
    public $removeGallery = [];

    public static function tableName(): string
    {
        return '{{%products}}';
    }

    public function behaviors(): array
    {
        return [
            [
                'class'       => SluggableBehavior::class,
                'attribute'   => 'name',
                'ensureUnique'=> true,
                'immutable'   => true,
            ],
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'price', 'category'], 'required'],
            ['name', 'string', 'max' => 200],
            ['category', 'in', 'range' => self::CATEGORIES],
            ['short_description', 'string', 'max' => 500],
            ['full_description', 'string'],
            ['price', 'number', 'min' => 0],
            ['original_price', 'number', 'min' => 0],
            ['sku', 'string', 'max' => 80],
            ['stock', 'integer', 'min' => 0],
            [['is_in_stock', 'is_featured', 'is_active'], 'boolean'],
            ['sort_order', 'integer'],
            ['meta_title', 'string', 'max' => 160],
            ['meta_description', 'string', 'max' => 320],
            ['removeGallery', 'each', 'rule' => ['string']],
            ['featuredImageFile', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024],
            ['galleryFiles', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024, 'maxFiles' => 12],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'name'              => 'Product Name',
            'price'             => 'Price (INR)',
            'original_price'    => 'Original / MRP Price (INR) — leave blank if no discount',
            'is_in_stock'       => 'In Stock',
            'is_featured'       => 'Featured on Shop',
            'is_active'         => 'Published / Active',
            'featuredImageFile' => 'Featured Image',
            'galleryFiles'      => 'Add Gallery Images',
        ];
    }

    /* ------------------------------------------------------------------ *
     *  JSON helpers
     * ------------------------------------------------------------------ */
    public function getSpecs(): array
    {
        return $this->jsonAssoc('specs_json');
    }

    public function getTags(): array
    {
        return $this->jsonArray('tags_json');
    }

    public function getGallery(): array
    {
        return $this->jsonArray('gallery_json');
    }

    /* ------------------------------------------------------------------ *
     *  Formatting
     * ------------------------------------------------------------------ */
    public function getImageUrl(): string
    {
        return Media::url($this->featured_image);
    }

    public function getPriceFormatted(): string
    {
        return '&#8377;' . number_format($this->price, 0, '.', ',');
    }

    public function getOriginalPriceFormatted(): string
    {
        return $this->original_price ? '&#8377;' . number_format($this->original_price, 0, '.', ',') : '';
    }

    public function getDiscountPercent(): int
    {
        if (!$this->original_price || $this->original_price <= $this->price) {
            return 0;
        }
        return (int) round(($this->original_price - $this->price) / $this->original_price * 100);
    }

    /* ------------------------------------------------------------------ *
     *  Scopes
     * ------------------------------------------------------------------ */
    public static function active()
    {
        return static::find()->where(['is_active' => 1])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }

    public static function featured()
    {
        return static::find()->where(['is_active' => 1, 'is_featured' => 1])->orderBy(['sort_order' => SORT_ASC]);
    }

    public static function byCategory(string $cat)
    {
        return static::find()->where(['is_active' => 1, 'category' => $cat])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }

    /** @return string[] category => category */
    public static function categoryList(): array
    {
        return array_combine(self::CATEGORIES, self::CATEGORIES);
    }

    /* ------------------------------------------------------------------ *
     *  Save with uploads
     * ------------------------------------------------------------------ */
    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        $this->updated_at = date('Y-m-d H:i:s');
        if ($insert) {
            $this->created_at = $this->updated_at;
        }
        $this->is_in_stock = ($this->stock > 0) ? 1 : 0;
        return true;
    }

    public function saveWithUploads(): bool
    {
        $this->featuredImageFile = UploadedFile::getInstance($this, 'featuredImageFile');
        $this->galleryFiles = UploadedFile::getInstances($this, 'galleryFiles');
        if (!$this->validate()) {
            return false;
        }
        if ($p = Media::store($this->featuredImageFile, 'products')) {
            Media::delete($this->getOldAttribute('featured_image'));
            $this->featured_image = $p;
        }
        $gallery = $this->getGallery();
        $remove  = array_values(array_intersect((array) $this->removeGallery, $gallery));
        foreach ($remove as $r) {
            Media::delete($r);
        }
        $gallery = array_values(array_diff($gallery, $remove));
        $gallery = array_merge($gallery, Media::storeMany($this->galleryFiles, 'products'));
        $this->gallery_json = self::encodeJson($gallery);

        return $this->save(false);
    }
}
