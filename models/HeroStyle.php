<?php

namespace app\models;

use Yii;
use app\components\JsonFieldsTrait;
use app\components\Media;
use yii\behaviors\SluggableBehavior;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * Interactive Hero Customizer preset (one row = style × room).
 *
 * @property int $id
 * @property string $style_name
 * @property string $slug
 * @property string $room_category
 * @property string|null $tagline
 * @property string|null $description
 * @property string|null $after_image
 * @property string|null $before_image
 * @property string|null $color_palette_json
 * @property string|null $materials_json
 * @property string $lighting_mode_default
 * @property int $sort_order
 * @property int $is_active
 */
class HeroStyle extends ActiveRecord
{
    use JsonFieldsTrait;

    /** @var UploadedFile|null */
    public $afterImageFile;
    /** @var UploadedFile|null */
    public $beforeImageFile;
    /** @var string comma / newline separated hex codes */
    public $paletteInput;
    /** @var string "Key: Value" lines */
    public $materialsInput;

    public static function tableName(): string
    {
        return '{{%hero_styles}}';
    }

    public function behaviors(): array
    {
        return [[
            'class' => SluggableBehavior::class,
            'attribute' => 'style_name',
            'ensureUnique' => false, // slug identifies the style; uniqueness is per (slug, room)
            'immutable' => false,
        ]];
    }

    public function rules(): array
    {
        $rooms = Yii::$app->params['heroRooms'];
        return [
            [['style_name', 'room_category'], 'required'],
            [['style_name'], 'string', 'max' => 120],
            ['room_category', 'in', 'range' => $rooms],
            ['lighting_mode_default', 'in', 'range' => array_keys(Yii::$app->params['lightingModes'])],
            [['tagline'], 'string', 'max' => 200],
            [['description'], 'string', 'max' => 2000],
            [['sort_order'], 'integer'],
            [['is_active'], 'boolean'],
            [['paletteInput', 'materialsInput'], 'string'],
            ['paletteInput', 'validatePalette'],
            [['slug'], 'unique', 'targetAttribute' => ['slug', 'room_category'], 'message' => 'This style already has a preset for the selected room.'],
            [['afterImageFile', 'beforeImageFile'], 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 8 * 1024 * 1024],
        ];
    }

    public function validatePalette($attribute): void
    {
        foreach ($this->parsePalette() as $hex) {
            if (!preg_match('/^#[0-9A-Fa-f]{6}$/', $hex)) {
                $this->addError($attribute, "“{$hex}” is not a valid 6-digit hex colour (e.g. #D4AF37).");
            }
        }
    }

    public function attributeLabels(): array
    {
        return [
            'style_name' => 'Style Name',
            'room_category' => 'Room',
            'lighting_mode_default' => 'Default Lighting Mode',
            'paletteInput' => 'Colour Palette (hex codes, comma separated)',
            'materialsInput' => 'Material Specs (one per line — “Flooring: Nero Marquina marble”)',
            'afterImageFile' => 'After / Designed Image',
            'beforeImageFile' => 'Before Image',
        ];
    }

    private function parsePalette(): array
    {
        $parts = preg_split('/[\s,;]+/', (string) $this->paletteInput);
        return array_values(array_filter(array_map(static fn($h) => strtoupper(trim($h)), $parts)));
    }

    public function afterFind()
    {
        parent::afterFind();
        $this->paletteInput = implode(', ', $this->getPalette());
        $this->materialsInput = self::mapToLines($this->getMaterials());
    }

    public function beforeSave($insert)
    {
        if (!parent::beforeSave($insert)) {
            return false;
        }
        if ($this->paletteInput !== null) {
            $this->color_palette_json = self::encodeJson($this->parsePalette());
        }
        if ($this->materialsInput !== null) {
            $this->materials_json = self::encodeJson(self::linesToMap($this->materialsInput));
        }
        return true;
    }

    public function getPalette(): array
    {
        return $this->jsonArray('color_palette_json');
    }

    public function getMaterials(): array
    {
        return $this->jsonArray('materials_json');
    }

    public function saveWithUploads(): bool
    {
        $this->afterImageFile = UploadedFile::getInstance($this, 'afterImageFile');
        $this->beforeImageFile = UploadedFile::getInstance($this, 'beforeImageFile');
        if (!$this->validate()) {
            return false;
        }
        if ($p = Media::store($this->afterImageFile, 'hero')) {
            Media::delete($this->getOldAttribute('after_image'));
            $this->after_image = $p;
        }
        if ($p = Media::store($this->beforeImageFile, 'hero')) {
            Media::delete($this->getOldAttribute('before_image'));
            $this->before_image = $p;
        }
        return $this->save(false);
    }

    /** Front-end payload for the Alpine.js customizer. */
    public function toClientArray(): array
    {
        return [
            'id' => $this->id,
            'style' => $this->style_name,
            'slug' => $this->slug,
            'room' => $this->room_category,
            'tagline' => $this->tagline,
            'description' => $this->description,
            'after' => Media::url($this->after_image),
            'before' => Media::url($this->before_image, 'uploads/seed/before_living_shell.jpg'),
            'palette' => $this->getPalette(),
            'materials' => $this->getMaterials(),
            'lighting' => $this->lighting_mode_default,
        ];
    }

    public static function activeOrdered()
    {
        return static::find()->where(['is_active' => 1])->orderBy(['sort_order' => SORT_ASC, 'id' => SORT_ASC]);
    }
}
