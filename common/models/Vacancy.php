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
class Vacancy extends \yii\db\ActiveRecord
{


    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'vacancy';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['header','content'], 'string'],

        ];
    }

    public function attributeLabels()
    {
        return [
            'header' => 'Вакансия',
            'content' => 'Содержание'
        ];
    }

}
