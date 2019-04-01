<?php

namespace common\models;

use yii\web\UploadedFile;
use Yii;
use yii\helpers\Url;

/**
 * This is the model class for table "post".
 *
 * @property int $id
 * @property string $title
 * @property string $text
 * @property int $user_id
 * @property string $time
 */
class Post extends \yii\db\ActiveRecord
{

    public $image;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }


    public function getUser()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['title', 'text', 'user_id'], 'required'],
            [['text', 'image_name'], 'string'],
            [['user_id','type'], 'integer'],
            [['time'], 'safe'],
            [['title'], 'string', 'max' => 150],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg,jpeg', 'checkExtensionByMimeType' => false],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'title' => 'Название',
            'text' => 'Содержание',
            'user_id' => 'Пользователь',
            'time' => 'Время создания',
            'image' => 'Изображение',
            'type' => 'Тип объявления',

        ];
    }

    public static function getTypes()
    {
        return [
            0 => 'Продажа',
            1 => 'Покупка',
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->image = UploadedFile::getInstance($this, 'image');
            if ($this->image) {
                $this->image_name = 'post' . $this->id . '.' . $this->image->extension;
                $this->image->saveAs(Url::to('@frontend/web/images/posts/') . $this->image_name);
            }
            $this->save();
            return true;
        } else {
            return false;
        }
    }
}
