<?php

namespace app\models;

use app\components\Estimator;
use app\components\JsonFieldsTrait;
use app\components\Media;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Service (6 core + future additions).
 *
 * @property int $id
 * @property string $title
 * @property string $slug
 * @property string|null $icon_svg_or_class
 * @property string|null $short_description
 * @property string|null $full_description
 * @property string|null $featured_image
 * @property string|null $deliverables_json
 * @property string|null $material_tiers_json
 * @property string|null $gallery_images_json
 * @property string|null $starting_price
 * @property string|null $meta_title
 * @property string|null $meta_description
 * @property int $sort_order
 * @property int $is_active
 *
 * @property Project[] $projects
 */
class Service extends ActiveRecord
{
    use JsonFieldsTrait;

    /** @var UploadedFile|null */
    public $featuredImageFile;
    /** @var UploadedFile[] */
    public $galleryFiles = [];
    /** @var string[] gallery paths to remove */
    public $removeGallery = [];
    /** @var string one deliverable per line */
    public $deliverablesInput;
    /** @var string "Tier: description" lines */
    public $tiersInput;

    public static function tableName(): string
    {
        return '{{%services}}';
    }

    public function behaviors(): array
    {
        return [[
            'class' => SluggableBehavior::class,
            'attribute' => 'title',
            'ensureUnique' => true,
            'immutable' => true,
        ]];
    }

    public function rules(): array
    {
        return [
            [['title', 'short_description'], 'required'],
            [['title'], 'string', 'max' => 160],
            ['slug', 'match', 'pattern' => '/^[a-z0-9\-]+$/', 'skipOnEmpty' => true],
            [['short_description'], 'string', 'max' => 500],
            [['full_description', 'icon_svg_or_class'], 'string'],
            [['starting_price'], 'number', 'min' => 0],
            [['meta_title'], 'string', 'max' => 160],
            [['meta_description'], 'string', 'max' => 320],
            [['sort_order'], 'integer'],
            [['is_active'], 'boolean'],
            [['deliverablesInput', 'tiersInput'], 'string'],
            ['removeGallery', 'each', 'rule' => ['string']],
            ['featuredImageFile', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024],
            ['galleryFiles', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024, 'maxFiles' => 12],
        ];
    }

    public function attributeLabels(): array
    {
        return [
            'icon_svg_or_class' => 'Icon (inline SVG markup)',
            'starting_price' => 'Starting Price (₹)',
            'deliverablesInput' => 'Deliverables (one per line)',
            'tiersInput' => 'Material Tiers (one per line — “Luxury Gold: Acrylic shutters…”)',
            'featuredImageFile' => 'Featured Image',
            'galleryFiles' => 'Add Gallery Images',
        ];
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->deliverablesInput = implode("\n", $this->getDeliverables());
        $map = [];
        foreach ($this->getMaterialTiers() as $t) {
            $map[$t['name'] ?? ''] = $t['text'] ?? '';
        }
        $this->tiersInput = self::mapToLines($map);
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->deliverablesInput !== null) {
            $this->deliverables_json = self::encodeJson(self::linesToArray($this->deliverablesInput));
        }
        if ($this->tiersInput !== null) {
            $tiers = [];
            foreach (self::linesToMap($this->tiersInput) as $name => $text) {
                $tiers[] = ['name' => $name, 'text' => $text];
            }
            $this->material_tiers_json = self::encodeJson($tiers);
        }
        return true;
    }

    public function getProjects()
    {
        return $this->hasMany(Project::class, ['service_id' => 'id']);
    }

    public function getDeliverables(): array
    {
        return $this->jsonArray('deliverables_json');
    }

    public function getMaterialTiers(): array
    {
        return $this->jsonArray('material_tiers_json');
    }

    public function getGallery(): array
    {
        return $this->jsonArray('gallery_images_json');
    }

    public function getImageUrl(): string
    {
        return Media::url($this->featured_image);
    }

    public function getPriceLabel(): string
    {
        return $this->starting_price ? Estimator::inr((float) $this->starting_price) . ' onwards' : 'On request';
    }

    public function saveWithUploads(): bool
    {
        $this->featuredImageFile = UploadedFile::getInstance($this, 'featuredImageFile');
        $this->galleryFiles = UploadedFile::getInstances($this, 'galleryFiles');
        if (!$this->validate()) {
            return false;
        }
        if ($p = Media::store($this->featuredImageFile, 'services')) {
            Media::delete($this->getOldAttribute('featured_image'));
            $this->featured_image = $p;
        }
        $gallery = $this->getGallery();
        $remove = array_values(array_intersect((array) $this->removeGallery, $gallery));
        foreach ($remove as $r) {
            Media::delete($r);
        }
        $gallery = array_values(array_diff($gallery, $remove));
        $gallery = array_merge($gallery, Media::storeMany($this->galleryFiles, 'services'));
        $this->gallery_images_json = self::encodeJson($gallery);

        return $this->save(false);
    }

    public static function active()
    {
        return static::find()->where(['is_active' => 1])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }

    /** @return array<string,string> title => title (for dropdowns) */
    public static function titleList(): array
    {
        $titles = static::active()->select('title')->column();
        return array_combine($titles, $titles) ?: [];
    }
}
