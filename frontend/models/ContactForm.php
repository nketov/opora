<?php

namespace frontend\models;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class ContactForm extends Model
{
    public $name;
    public $email;
    public $phone;
    public $body;
    public $verifyCode;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [

            [['name', 'email', 'phone', 'body'], 'required'],
//            ['phone', 'string', 'length' => 9],
            ['email', 'email'],
            ['verifyCode', 'captcha'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Имя',
            'email' => 'E-mail',
            'phone' => 'Телефон',
            'body' => 'Вопрос',
            'verifyCode' => 'Проверочный код',

        ];
    }

    /**
     * Sends an email to the specified email address using the information collected by this model.
     *
     * @param string $email the target email address
     * @return bool whether the email was sent
     */
    public function sendEmail()
    {

        $string = $this->phone;
        $this->phone = '+38 (0'.$string[0].$string[1].') '.$string[2].$string[3].$string[4].' '.$string[5].$string[6].' '.$string[7].$string[8];

        $text = '<p><b>Вопрос  от '. $this->name.'</b></p>';
        $text .= '<p>E-mail : '. $this->email.'</p>';
        $text .= '<p>Телефон : '. $this->phone.'</p>';
        $text .= '<p>Вопрос : '. $this->body.'</p>';

        mail('ketovnv@gmail.com', 'Вопрос  от '. $this->name , $text ,"Content-type:text/html;charset=UTF-8");

        return true;
    }
}
