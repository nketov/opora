<?php

namespace common\models;

use frontend\components\NovaPoshta;
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
    public $NP;

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'post';
    }


    public function __construct()
    {
        $this->NP = new NovaPoshta();
        return parent::__construct();
    }

    private static $_categoryName = [
        '0'=> 'Не указана',
        '1' => 'Автосвет',
        '2' => 'Аккумуляторы',
        '3' => 'Газобаллонное оборудование (ГБО)',
        '4' => 'Двигатель',
        '5' => 'Фильтры',
        '6' => 'Кузовные детали',
        '7' => 'Подвеска',
        '8' => 'Рулевое управление',
        '9' => 'Салон',
        '10' => 'Стёкла',
        '11' => 'Топливная и выхлопная системы',
        '12' => 'Тормозная система',
        '13' => 'Трансмиссия и привод',
        '14' => 'Электрооборудование'
    ];


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
            [['price'], 'number'],
            [['title', 'text', 'user_id'], 'required'],
            [['text', 'image_name'], 'string'],
            [['user_id','type','new','category'], 'integer'],
            [['time', 'region_id', 'city_id'], 'safe'],
            [['title','article'], 'string', 'max' => 150],
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
            'new' => 'Состояние',
            'article' => 'Артикул',
            'price'=> 'Цена,грн',
            'status' => 'Статус',
            'category' => 'Категория',
            'city_id' => 'Город',
            'region_id' => 'Область',
        ];
    }

    public static function categoryNamesList()
    {
        return self::$_categoryName;
    }

    public static function categoryName($category)
    {
        return self::$_categoryName[$category];
    }

    public static function getTypes()
    {
        return [
            0 => 'Продажа',
            1 => 'Покупка',
        ];
    }

    public static function getNews()
    {
        return [
            0 => 'Новое',
            1 => 'Б/у',
        ];
    }

    public static function getStatuses()
    {
        return [
            0 => 'Ожидание',
            1 => 'Активно',
            2 => 'Отклонёно'
        ];
    }


    public function upload()
    {
        if ($this->validate()) {
            $this->save();
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
