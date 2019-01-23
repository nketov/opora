<?php

namespace common\models;

use yii\web\UploadedFile;
use Yii;
use yii\helpers\Url;

class ActionsContent extends \yii\db\ActiveRecord
{

    public $image;

    public static function tableName()
    {
        return 'actions_content';
    }


    public function rules()
    {
        return [
            [['text','header','content','image_name'], 'string'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg,jpeg', 'checkExtensionByMimeType' => false],
        ];
    }


    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'header' => 'Заголовок',
            'text' => 'Текст',
            'content' => 'Содержание',
            'image' => 'Изображение',

        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->image = UploadedFile::getInstance($this, 'image');
            if($this->image) {
                $this->image_name = 'action_' . $this->id . '.' . $this->image->extension;
                $this->image->saveAs(Url::to('@frontend/web/images/actions/') . $this->image_name);
            }
            $this->save();
            return true;
        } else {
            return false;
        }
    }
}
