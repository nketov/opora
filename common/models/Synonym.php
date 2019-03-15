<?php

namespace common\models;

use Yii;
use yii\helpers\ArrayHelper;

/**
 * This is the model class for table "synonym".
 *
 * @property int $id
 * @property string $first
 * @property string $second
 */
class Synonym extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'synonym';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['first', 'second'], 'required'],
            [['first', 'second'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'first' => 'Первый',
            'second' => 'Второй',
        ];
    }


    public static function getSynonyms($text)
    {
        $result=[];

        foreach (ArrayHelper::getColumn(
            self::find()->where(['like', 'first', trim($text)])->all(),
            'second') as $second){
            $result[]=$second;
        }

        foreach (ArrayHelper::getColumn(
            self::find()->where(['like', 'second', trim($text)])->all(),
            'first') as $first){
            $result[]=$first;
        }

        return $result;
    }


}
