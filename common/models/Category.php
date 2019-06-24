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
            [['images'], 'safe'],
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


    public function getFirstImage()
    {

        return $this->getAllImages()[0] ?? '';
    }

    public function getAllImages()
    {
        $images = [];
        if (!empty($this->images)) {
            foreach (explode(';', $this->images) as $img) {
                $images[] = '/images/1C_images/' . $img;
            }
        }
        return $images;
    }



    public static function getMainLinks()
    {
        $links = '';

        foreach (self::$_forMain as $code){
            $model = self::find()->where(['like','code',$code])->one();
            if ($model){
            $image = $model->getFirstImage() ? Html::img($model->getFirstImage(),['class'=>'cat-img']).'&nbsp;' : '';
            $links.= Html::a($image.$model->name,'/category/'.$code,['class'=>'btn']);
            }
        }
        return $links;
    }

}
