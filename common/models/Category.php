<?php

namespace common\models;

use Yii;
use yii\helpers\Html;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $code
 * @property string $name
 * @property string $parent_code
 */
class Category extends \yii\db\ActiveRecord
{


    private static $_forMain    = [
        '000002868', '000002741', '000003846', '000003534', '000003222', '000003221', '000002020', '000002482, 000003053, 000003664'
    ];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['code', 'name'], 'required'],
            [['code', 'parent_code'], 'string', 'max' => 50],
            [['name'], 'string', 'max' => 100],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'code' => 'Code',
            'name' => 'Name',
            'parent_code' => 'Parent Code',
        ];
    }

    public static function getMainLinks()
    {
        $links = '';

        foreach (self::$_forMain as $code){
            $model = self::find()->where(['like','code',$code])->one();
            if ($model)
            $links.= Html::a($model->name,'/category/'.$code,['class'=>'btn']);
        }
        return $links;
    }

}
