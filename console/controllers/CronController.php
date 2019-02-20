<?php

namespace console\controllers;


use common\models\Category;
use common\models\Product;
use yii\console\Controller;
use yii\helpers\Url;
use yii\db\Query;



class CronController extends Controller
{
    private static $item;


    /**
     * @see XML в SQL
     * @command  yii cron/xml-to-sql
     */

    public function actionXmlToSql()
    {


        $filename=Url::to('@backend/1C_files/opora.xml');
        $log = fopen(Url::to('@backend/logs/' . date('Y-m-d') . '.txt'), "a");
        $text_log = "______ 1C  XML ______  ";
        $text_log .= "( " . date("Y-m-d H:i:s") . " )";
        fputs($log, $text_log . "\n");

        if(!file_exists($filename)) {
            fputs($log, 'XML-фаил не обнаружен'."\n\n");
            fclose($log);
            exit();
        }

        fputs($log, 'Идёт загрузка XML-фаила ...'."\n");
        $xml = new \DOMDocument();
        $xml->loadXML(file_get_contents($filename));


        $data = $xml->getElementsByTagName('EXPORT')->item(0);
        $items = $data->getElementsByTagName('item');
        $cats = $data->getElementsByTagName('cat');


        foreach ($cats as $cat){
            self::$item = $cat;

            $code = self::getByTagName('Code');
            $category = Category::findOne(['code' => $code]);

            if (!$category){
                $category = new Category();
                $category->code = (string)$code;
            }

            $category->parent_code = (string)self::getByTagName('ParentCode');

            $category->name = (string)self::getByTagName('Name');

            $category->save();

        }

        $new_count = 0;
        $renew_count= 0 ;
        $deactivated_count=0;
        $active_records=[];

        foreach ($items as $item) {

            self::$item = $item;

            $code = self::getByTagName('Code');
            $active_records[]=$code;
            $product = Product::findOne(['code' => $code]);


            if (!$product){
                $product = new Product();
                $product->code = (string)$code;
                $new_count++;
            } else {
                $renew_count++;
            }

            $product->active=1;

            $product->name = (string)self::getByTagName('Name');

            if(!empty($descr = self::getByTagName('Descr')))
            $product->article = (string)self::getByTagName('Article') ?? null;

            $category=(string)self::getByTagName('ParentCode');

            $product->category = (int)Category::findOne(['code'=>$category])->id;

            $product->price = (float)str_replace(',','.',self::getByTagName('Price'));
            $product->currency = self::getByTagName('Сurrency') == 'грн' ? 1 : 2;

            $product->remains = (int)self::getByTagName('Remains');
            $product->unit = (string)self::getByTagName('Unit');

            $images= $item->getElementsByTagName('img_name');

            if($images->length) {
                $img_string = '';
                foreach ($images as $image) {
                    $img_string .= $image->nodeValue . ';';
                }
                $product->images = (string)trim($img_string,';');

            }

            if(!empty($descr = self::getByTagName('Descr')))
            $product->description = (string)$descr;

            if(!$product->save()) var_dump($product->errors);

        }


        foreach (Product::find()->active()->all() as $product){
            if(!in_array($product->code,$active_records)){
                $product->active=0;
                $product->save();
                $deactivated_count++;
            }
        }


        $report = 'Записей в XML-файле: ' . $items->length."\n";
        $report .='Добавлено новых продуктов: ' . $new_count."\n";
        $report .='Обновлено продуктов: ' . $renew_count."\n";
        $report .='Деактивировано продуктов: ' . $deactivated_count."\n";
        echo $report."\n";

        fputs($log, "Загрузка закончена ( " . date("Y-m-d H:i:s") . " )\n" . $report);

        if(rename($filename, Url::to('@backend/logs/files/' . date('Y-m-d_H-i-s') . '.xml')))
        fputs($log, "XML-фаил перемещён.\n\n");

        fclose($log);
    }




    protected static function getByTagName($tag){
        return trim(self::$item->getElementsByTagName($tag)->item(0)->nodeValue);
    }

}