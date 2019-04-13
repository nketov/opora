<?php

namespace frontend\models;

use frontend\components\NovaPoshta;
use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class OrderForm extends Model
{

    const SCENARIO_NOVA_POSHTA = 'nova_poshta';

    const DELIVERY_NOVA_POSHTA = 1;
    const DELIVERY_COURIER = 2;
    const DELIVERY_SELF = 3;

    public $NP;

    public $phone;
    public $FIO;
    public $delivery;
    public $region_id;
    public $city_id;
    public $warehouse_id;
    public $courier_address;

    private static $_deliveryName = [
        '' => 'Выберите способ',
        self::DELIVERY_NOVA_POSHTA => 'Новая почта',
        self::DELIVERY_COURIER => 'Курьером по городу',
        self::DELIVERY_SELF => 'Самовывоз',
    ];

    /**
     * OrderForm constructor.
     */
    public function __construct()
    {
        $this->NP = new NovaPoshta();
        return parent::__construct();
    }


    public static function deliveryName($type)
    {
        return self::$_deliveryName[$type];
    }

    public static function deliveryNamesList()
    {
        return self::$_deliveryName;
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['region_id', 'city_id', 'warehouse_id'], 'required',
                'when' => function ($model) {
                    return $model->delivery == self::DELIVERY_NOVA_POSHTA;
                },'whenClient' => "function (attribute, value) {
                    return $('#orderform-delivery').val() == '" . self::DELIVERY_NOVA_POSHTA. "';
                    }"
            ],
            [['courier_address'], 'required',
                'when' => function ($model) {
                    return $model->delivery == self::DELIVERY_COURIER;
                }, 'whenClient' => "function (attribute, value) {
                    return $('#orderform-delivery').val() == '" . self::DELIVERY_COURIER . "';
                    }"
            ],
            [['courier_address'], 'string', 'max' => 200],
            [['FIO'], 'string', 'max' => 150],
            ['phone', 'string', 'length' => 9, 'message' => 'Неверный номер'],
            [['phone', 'delivery','FIO'], 'required']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'region_id' => 'Область',
            'city_id' => 'Город',
            'warehouse_id' => 'Отделение',
            'phone' => 'Телефон',
            'FIO' => 'Ф.И.О.',
            'delivery' => 'Способ доставки'
        ];
    }


}
