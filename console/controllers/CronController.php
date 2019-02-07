<?php

namespace console\controllers;


use common\models\Product;
use yii\console\Controller;
use yii\helpers\Url;


class CronController extends Controller
{
    private static $item;

    /**
     * @see Тестирование
     * @command php yii test/mail
     */


    public function actionXmlToSql()
    {


        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents(Url::to('@backend/1C_test/opora.xml')));
        $log = fopen(Url::to('@backend/1C_test/logs/' . date('Y-m-d') . '.txt'), "a");

        $text_log = "______ 1C  XML ______\n";
        $text_log .= " ( " . date("Y-m-d H:i:s") . " ) \n";

        fputs($log, $text_log . "\n");

        $data = $xml->getElementsByTagName('EXPORT')->item(0);
        $items = $data->getElementsByTagName('item');


        foreach ($items as $item) {

            self::$item = $item;

            $code = self::getByTagName('Code');
            $product = Product::findOne(['code' => $code]);

            if (!$product){
                $product = new Product();
                $product->code = (string)$code;
            }

            $product->name = (string)self::getByTagName('Name');

            $product->article = (string)self::getByTagName('Article') ?? null;
            $product->category = (string)self::getByTagName('ParentCode');

            $product->price = (float)str_replace(',','.',self::getByTagName('Price'));
            $product->currency = self::getByTagName('Сurrency') == 'грн' ? 1 : 2;

            $product->remains = (int)self::getByTagName('Remains');
            $product->unit = (string)self::getByTagName('Unit');

            $product->image_1 = (string)self::getByTagName('Img');

            $product->save();
        }

        echo 'Total: ' . $items->length;
        fputs($log, " ( " . date("Y-m-d H:i:s") . " )\n" . 'Total: ' . $items->length ."\n\n");

        fclose($log);
    }


    protected static function getByTagName($tag){
        return trim(self::$item->getElementsByTagName($tag)->item(0)->nodeValue);
    }

}