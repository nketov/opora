<?php

namespace frontend\models;

use Yii;
use yii\base\Model;
use yii\helpers\Url;

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

        $xml = new \DOMDocument('1.0', 'windows-1251');
        $xml_contact = $xml->appendChild($xml->createElement('Contact'));
        $xml_name = $xml_contact->appendChild($xml->createElement('Name'));
        $xml_name->appendChild($xml->createTextNode($this->name));
        $xml_email= $xml_contact->appendChild($xml->createElement('Email'));
        $xml_email->appendChild($xml->createTextNode($this->email));
        $xml_phone= $xml_contact->appendChild($xml->createElement('Phone'));
        $xml_phone->appendChild($xml->createTextNode('0'.$string));
        $xml_text= $xml_contact->appendChild($xml->createElement('Text'));
        $xml_text->appendChild($xml->createTextNode($this->body));


        $xml->formatOutput = true;
        $content = $xml->saveXML();
        $xml->save(Url::to('@backend/1C_files/contacts/contact_'.time().'.xml'));

        $text = '<p><b>Вопрос  от  '. $this->name.'</b></p>';
        $text .= '<p>E-mail : '. $this->email.'</p>';
        $text .= '<p>Телефон : '. $this->phone.'</p>';
        $text .= '<p>Вопрос : '. $this->body.'</p>';

        Yii::$app->mailer->compose()
            ->setTo(['ketovnv@gmail.com','mail@opora.dn.ua'])
            ->setFrom(['mail@opora.dn.ua' => 'Opora'])
            ->setSubject('Вопрос от '. $this->name)
            ->setHtmlBody($text)
            ->send();

        return true;
    }
}
