<?php

namespace backend\components;

use common\models\Product;
use PhpOffice\PhpSpreadsheet\Reader\Csv;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\base\Widget;
use yii\helpers\Url;

class ShopUploader extends Widget
{

    public $shop;
    public $extension;
    public $markup = 0;
    private $sheetData;

    private $new_count = 0;
    private $updated_count = 0;
    private $activated_count = 0;
    private $deactivated_count = 0;


    public function init()
    {
        parent::init();
        ini_set('memory_limit', '4096M');
        ini_set('max_execution_time', '36000');
        \Yii::$app->db->createCommand('SET SESSION wait_timeout = 36000;')->execute();

        $inputFileName = Url::to('@backend/web/uploads/prices/') . Product::shopName($this->shop) . '.' . $this->extension;

        switch ($this->extension) {
            case 'xlsx':
                $reader = new Xlsx();
                break;
            case 'xls':
                $reader = new Xls();
                break;
            case 'csv':
                $reader = new Csv();
                $reader->setInputEncoding('Windows-1251');
                break;
        }


        $reader->setReadDataOnly(true);
        $reader->setReadFilter($this->getFilter());


        try {
            $spreadsheet = $reader->load($inputFileName);

        } catch (Exception $e) {
            exit('Error loading file: ' . $e->getMessage());
        }

        $this->sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);


    }

    public function run()
    {
        switch ($this->shop) {
            case Product::VLAD_SHOP:
                $this->uploadVlad();
                break;
//            case Product::EGLO_SHOP:
//                $this->uploadEglo();
//                break;
//            case Product::FREYA_SHOP:
//                $this->uploadFreya();
//                break;
//            case Product::MAYTONI_SHOP:
//                $this->uploadMaytoni();
//                break;
//            case Product::ARTGLASS_SHOP:
//                $this->uploadArtglass();
//                break;
        }


        return $this->render('upload-report.php',
            [
                'shopName' => Product::shopName($this->shop),
                'updated_count' => $this->updated_count,
                'new_count' => $this->new_count,
                'deactivated_count' => $this->deactivated_count,
                'activated_count' => $this->activated_count,
            ]);

    }

    private function uploadVlad()
    {
        $rows = [];
        foreach ($this->sheetData as $row) {
            if (!empty($row['A'])) {
                $markup = 30;

                if (stripos(mb_strtolower($row['H']), 'масло') !== false || stripos(mb_strtolower($row['H']), 'аккумулятор') !== false) {
                    $markup = 20;
                } elseif ((float)str_replace(',', '.', $row['E']) < 1) {
                    $markup = 50;
                }

                $rows[(string)trim($row['A'])] = [
                    'name' => (string)(trim($row['H']) . ' ' . trim($row['C'])),
                    'article' => (string)trim($row['D']),
                    'remains' => (int)trim($row['G']),
                    'price' => (float)str_replace(',', '.', $row['E']) * 30 * (1 + $markup / 100)
                ];
            }
        }


        $products = Product::find()->shop(Product::VLAD_SHOP)->all();

        foreach ($products as $product) {
            if (array_key_exists($product->code, $rows)) {
                if ($product->active) {
                    $this->updated_count++;
                } else {
                    $this->activated_count++;
                    $product->active = 1;
                }

                $product->name = (string)$rows[$product->code]['name'];
                $product->shop = Product::VLAD_SHOP;
                $product->article = (string)$rows[$product->code]['article'];
                $product->remains = (int)$rows[$product->code]['remains'];
                $product->price = (float)$rows[$product->code]['price'];
            } else {
                if ($product->active) {
                    $product->active = 0;
                    $this->deactivated_count++;
                }
            }

            if ($product->save()) {
                unset($rows[$product->code]);
            } else {
                var_dump($product->errors);
                var_dump($product);
                exit();
            }
        }

        foreach ($rows as $code => $row) {
            $newProduct = new Product();
            $newProduct->code = (string)$code;
            $newProduct->name = (string)$row['name'];
            $newProduct->shop = Product::VLAD_SHOP;
            $newProduct->article = (string)$row['article'];
            $newProduct->remains = (int)$row['remains'];
            $newProduct->price = (float)$row['price'];

            if ($newProduct->save()) {
                $this->new_count++;
            } else {
                var_dump($newProduct->errors);
                var_dump($newProduct);
                exit();
            }
        }
    }


    private function getFilter()

    {
        switch ($this->shop) {
            case Product::VLAD_SHOP:
                return new XlsFilter(2, ['A', 'C', 'D', 'E', 'G', 'H']);
                break;

//            case Product::EGLO_SHOP:
//                return new XlsFilter(2, ['A', 'B', 'C']);
//                break;
//
//            case Product::FREYA_SHOP:
//                return new XlsFilter(5, ['A', 'B', 'C', 'D', 'E', 'H', 'I', 'J', 'K', 'L', 'M']);
//                break;
//
//            case Product::MAYTONI_SHOP:
//                return new XlsFilter(6, ['A', 'B', 'C', 'D', 'H', 'I', 'J', 'K', 'L', 'M']);
//                break;
//
//            case Product::ARTGLASS_SHOP:
//                return new XlsFilter(2, ['A', 'B', 'C']);
//                break;
        }

    }


}