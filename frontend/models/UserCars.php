<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "user_cars".
 *
 * @property int $id
 * @property int $user_id
 * @property int $position
 * @property string $car_name
 * @property int $year
 * @property int $mfa_id
 * @property int $mod_id
 * @property int $type_id
 */
class UserCars extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user_cars';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [[ 'car_name'], 'safe'],
            [['user_id', 'position', 'year', 'mfa_id', 'mod_id', 'type_id'], 'integer'],
            [['car_name'], 'string', 'max' => 200],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'position' => 'Position',
            'car_name' => 'Car Name',
            'year' => 'Year',
            'mfa_id' => 'Mfa ID',
            'mod_id' => 'Mod ID',
            'type_id' => 'Type ID',
        ];
    }



}
