<?php

namespace app\models;

use app\components\Media;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;

/**
 * @property int $id
 * @property string $client_name
 * @property string|null $client_role
 * @property string|null $project_type
 * @property int $rating
 * @property string $review_text
 * @property string|null $client_photo
 * @property int $is_active
 */
class Testimonial extends ActiveRecord
{
    /** @var UploadedFile|null */
    public $photoFile;

    public static function tableName(): string
    {
        return '{{%testimonials}}';
    }

    public function rules(): array
    {
        return [
            [['client_name', 'review_text'], 'required'],
            [['client_name', 'client_role'], 'string', 'max' => 160],
            [['project_type'], 'string', 'max' => 120],
            [['review_text'], 'string', 'max' => 2000],
            ['rating', 'integer', 'min' => 1, 'max' => 5],
            ['is_active', 'boolean'],
            ['photoFile', 'image', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, webp', 'maxSize' => 4 * 1024 * 1024],
        ];
    }

    public function attributeLabels(): array
    {
        return ['photoFile' => 'Client Photo'];
    }

    public function getInitials(): string
    {
        $parts = preg_split('/\s+/', trim($this->client_name));
        return strtoupper(mb_substr($parts[0] ?? '', 0, 1) . mb_substr(end($parts) ?: '', 0, 1));
    }

    public function getPhotoUrl(): ?string
    {
        return $this->client_photo ? Media::url($this->client_photo) : null;
    }

    public function saveWithUploads(): bool
    {
        $this->photoFile = UploadedFile::getInstance($this, 'photoFile');
        if (!$this->validate()) {
            return false;
        }
        if ($p = Media::store($this->photoFile, 'testimonials')) {
            Media::delete($this->getOldAttribute('client_photo'));
            $this->client_photo = $p;
        }
        return $this->save(false);
    }
}
