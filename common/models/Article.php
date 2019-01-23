<?php

namespace common\models;

use yii\helpers\Url;
use yii\web\UploadedFile;
use Yii;

/**
 * This is the model class for table "article".
 *
 * @property int $id
 * @property string $header
 * @property string $content
 * @property string $image_name
 */
class Article extends \yii\db\ActiveRecord
{

    public $image;
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'article';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['header','content','image_name'], 'string'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg,jpeg', 'checkExtensionByMimeType' => false],
        ];
    }

    public function attributeLabels()
    {
        return [
            'header' => 'Заголовок',
            'content' => 'Содержание',
            'image' => 'Изображение',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->image = UploadedFile::getInstance($this, 'image');
            if($this->image) {
                $this->image_name = 'article_' . $this->id . '.' . $this->image->extension;
                $this->image->saveAs(Url::to('@frontend/web/images/articles/') . $this->image_name);
            }
            $this->save();
            return true;
        } else {
            return false;
        }
    }

}
