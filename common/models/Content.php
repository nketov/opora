<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "content".
 *
 * @property int $id
 * @property string $email
 * @property string $phone
 */
class Content extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['address', 'keywords','description','title','phones_header','phone_footer', 'phone_footer_icon' ], 'string'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'address' => 'Адрес',
            'title' => 'Название',
            'phones_header' => 'Телефоны вверху',
            'phone_footer' => 'Телефон внизу',
            'phone_footer_icon' => 'Иконка телефона внизу',
        ];
    }
}
