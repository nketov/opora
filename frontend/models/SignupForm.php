<?php

namespace frontend\models;

use DOMDocument;
use yii\base\Model;
use common\models\User;
use yii\helpers\Url;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $phone;


    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255],
            ['email', 'unique', 'targetClass' => '\common\models\User', 'message' => 'Пользователь с таким почтовым ящиком уже зарегистрирован.'],
            ['phone', 'required'],
            ['phone', 'string', 'min' => 9, 'message' => 'Неверный номер'],
            ['password', 'required'],
            ['password', 'string', 'min' => 6],
        ];
    }

    public function attributeLabels()
    {
        return [

            'email' => 'E-mail',
            'password' => 'Пароль',
            'phone' => 'Телефон'
        ];
    }


    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }

        $user = new User();
        $user->email = $this->email;
        $user->phone = $this->phone;
        $user->setPassword($this->password);
        $user->generateAuthKey();

        if ($user->save()) {
            $xml = new DOMDocument('1.0', 'windows-1251');
            $xml_user = $xml->appendChild($xml->createElement('User'));
            $xml_id = $xml_user->appendChild($xml->createElement('Id'));
            $xml_id->appendChild($xml->createTextNode($user->id));
            $xml_email = $xml_user->appendChild($xml->createElement('Email'));
            $xml_email->appendChild($xml->createTextNode($user->email));
            $xml_phone = $xml_user->appendChild($xml->createElement('Phone'));
            $xml_phone->appendChild($xml->createTextNode('0' . $user->phone));

            $xml->formatOutput = true;
            $content = $xml->saveXML();
            $xml->save(Url::to('@backend/1C_files/users/new_user_'.$user->id .'_' .time() . '.xml'));
            $xml->save(Url::to('@backend/logs/users/new_user_'.$user->id .'_' .time() . '.xml'));
            return $user;
        }
        return null;
    }
}
