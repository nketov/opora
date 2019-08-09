<?php

namespace common\models;

use Yii;

/**
 * This is the model class for table "page_content".
 *
 * @property int $id
 * @property string $actions
 * @property string $delivery
 * @property int $payment
 * @property string $guarantee
 * @property string $agreements
 * @property string $vacancies
 */
class PageContent extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'page_content';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['actions', 'delivery', 'payment', 'guarantee', 'agreements', 'vacancies','convention'], 'required'],
            [['actions', 'delivery', 'guarantee', 'agreements', 'vacancies','payment','convention'], 'string']
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => '',
            'actions' => 'Страница "Акции"',
            'delivery' => 'Страница "Доставка"',
            'payment' => 'Страница "Доставка"',
            'guarantee' => 'Страница "Гарантии"',
            'agreements' => 'Страница "Договора"',
            'vacancies' => 'Страница "Вакансии"',
            'convention' => 'Страница "Соглашение"'
        ];
    }

    public static function getPages()
    {
        return self::findOne(1);
    }

}
