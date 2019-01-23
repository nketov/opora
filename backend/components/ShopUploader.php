<?php

namespace backend\components;

use common\models\Product;
use PhpOffice\PhpSpreadsheet\Reader\Xls;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;
use yii\base\Widget;
use yii\helpers\Url;

class ShopUploader extends Widget
{

    public $shop;
    public $extension;
    public $markup=0;
    private $sheetData;

    private $new_count = 0;
    private $updated_count = 0;
    private $activated_count = 0;
    private $deactivated_count = 0;


    public function init()
    {
        parent::init();
        ini_set('memory_limit', '256M');
        ini_set('max_execution_time', '300');



        $inputFileName = Url::to('@backend/web/uploads/prices/') .Product::shopName($this->shop) . '.'.$this->extension;

        if($this->extension == 'xlsx'){
            $reader = new Xlsx();
        } else{
            $reader = new Xls();
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
            case Product::SVET_SHOP:
                $this->uploadSvet();
                break;
            case Product::EGLO_SHOP:
                $this->uploadEglo();
                break;
            case Product::FREYA_SHOP:
                $this->uploadFreya();
                break;
            case Product::MAYTONI_SHOP:
                $this->uploadMaytoni();
                break;
            case Product::ARTGLASS_SHOP:
                $this->uploadArtglass();
                break;
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

    private function uploadSvet()
    {
        $rows = [];
        foreach ($this->sheetData as $row) {
            if (!empty($row['H']) && !empty($row['A'])) {
                $rows[(string)trim($row['H'])] = [
                    'name' => (string)trim($row['A']),
                    'second_code' => (string)trim($row['E']),
                    'price' => (float)trim($row['I'])*(1+$this->markup/100)
                ];
            }
        }

        $products = Product::find()->shop(Product::SVET_SHOP)->all();

        foreach ($products as $product) {
            if (array_key_exists($product->code, $rows)) {
                if ($product->active) {
                    $this->updated_count++;
                } else {
                    $this->activated_count++;
                    $product->active = 1;
                }

                $product->name = (string)$rows[$product->code]['name'];
                $product->shop = Product::SVET_SHOP;
                $product->second_code = (string)$rows[$product->code]['second_code'];
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
            $newProduct->shop = Product::SVET_SHOP;
            $newProduct->second_code = (string)$row['second_code'];
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

    private function uploadEglo()
    {

        $rows = [];
        foreach ($this->sheetData as $row) {
            if (!empty($row['A']) && !empty($row['B'])) {
                $rows[(string)trim($row['A'])] = [
                    'name' => (string)trim($row['B']),
                    'price' => (float)trim($row['C'])*(1+$this->markup/100)
                ];
            }
        }

        $products = Product::find()->shop(Product::EGLO_SHOP)->all();

        foreach ($products as $product) {
            if (array_key_exists($product->code, $rows)) {
                if ($product->active) {
                    $this->updated_count++;
                } else {
                    $this->activated_count++;
                    $product->active = 1;
                }

                $product->name = (string)$rows[$product->code]['name'];
                $product->shop = Product::EGLO_SHOP;
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
            $newProduct->shop = Product::EGLO_SHOP;
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

    private function uploadFreya()
    {

        $rows = [];

        foreach ($this->sheetData as $row) {
            if (!empty($row['A']) && !empty($row['B']) && !empty($row['C'])) {
                $rows[(string)trim($row['A'])] = [
                    'name' => (string)(trim($row['B']) . " " . trim($row['C']) . " " . trim($row['D'])),
                    'price' => (float)trim($row['H'])*(1+$this->markup/100),
                    'height' => (int)trim($row['I']),
                    'diametr' => (int)trim($row['J']),
                    'width' => (int)trim($row['K']),
                    'depth' => (int)trim($row['L']),
                    'lamps' => (string)trim($row['M']),
                ];
            }
        }

        $products = Product::find()->shop(Product::FREYA_SHOP)->all();

        foreach ($products as $product) {
            if (array_key_exists($product->code, $rows)) {
                if ($product->active) {
                    $this->updated_count++;
                } else {
                    $this->activated_count++;
                    $product->active = 1;
                }

                $product->name = (string)$rows[$product->code]['name'];
                $product->shop = Product::FREYA_SHOP;
                $product->price = (float)$rows[$product->code]['price'];
                $product->height = (int)$rows[$product->code]['height'];
                $product->diametr = (int)$rows[$product->code]['diametr'];
                $product->width = (int)$rows[$product->code]['width'];
                $product->depth = (int)$rows[$product->code]['depth'];
                $product->lamps = (string)$rows[$product->code]['lamps'];

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
            $newProduct->shop = Product::FREYA_SHOP;
            $newProduct->price = (float)$row['price'];
            $newProduct->height = (int)$row['height'];
            $newProduct->diametr = (int)$row['diametr'];
            $newProduct->width = (int)$row['width'];
            $newProduct->depth = (int)$row['depth'];
            $newProduct->lamps = (string)$row['lamps'];


            if ($newProduct->save()) {
                $this->new_count++;
            } else {
                var_dump($newProduct->errors);
                var_dump($newProduct);
                exit();
            }
        }
    }

    private function uploadMaytoni()
    {

        $rows = [];
        foreach ($this->sheetData as $row) {
            if (!empty($row['A']) && !empty($row['B']) && !empty($row['C'])) {
                $rows[(string)trim($row['A'])] = [
                    'name' => (string)(trim($row['B']) . " " . trim($row['C'])),
                    'price' => (float)trim($row['H'])*(1+$this->markup/100),
                    'height' => (int)trim($row['I']),
                    'diametr' => (int)trim($row['J']),
                    'width' => (int)trim($row['K']),
                    'depth' => (int)trim($row['L']),
                    'lamps' => (string)trim($row['M']),
                ];
            }
        }

        $products = Product::find()->shop(Product::MAYTONI_SHOP)->all();

        foreach ($products as $product) {
            if (array_key_exists($product->code, $rows)) {
                if ($product->active) {
                    $this->updated_count++;
                } else {
                    $this->activated_count++;
                    $product->active = 1;
                }

                $product->name = (string)$rows[$product->code]['name'];
                $product->shop = Product::MAYTONI_SHOP;
                $product->price = (float)$rows[$product->code]['price'];
                $product->height = (int)$rows[$product->code]['height'];
                $product->diametr = (int)$rows[$product->code]['diametr'];
                $product->width = (int)$rows[$product->code]['width'];
                $product->depth = (int)$rows[$product->code]['depth'];
                $product->lamps = (string)$rows[$product->code]['lamps'];
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
            $newProduct->shop = Product::MAYTONI_SHOP;
            $newProduct->price = (float)$row['price'];
            $newProduct->height = (int)$row['height'];
            $newProduct->diametr = (int)$row['diametr'];
            $newProduct->width = (int)$row['width'];
            $newProduct->depth = (int)$row['depth'];
            $newProduct->lamps = (string)$row['lamps'];


            if ($newProduct->save()) {
                $this->new_count++;
            } else {
                var_dump($newProduct->errors);
                var_dump($newProduct);
                exit();
            }
        }

    }


    private function uploadArtglass()
    {

        $rows = [];
        foreach ($this->sheetData as $row) {
            if (!empty($row['A'])) {
                $rows[(string)trim($row['A'])] = [
                    'name' => (string)trim($row['A']),
                    'price' => (float)trim($row['C'])*(1+$this->markup/100),
                    'size' => (string)trim($row['B'])
                ];
            }
        }

        $products = Product::find()->shop(Product::ARTGLASS_SHOP)->all();

        foreach ($products as $product) {
            if (array_key_exists($product->code, $rows)) {
                if ($product->active) {
                    $this->updated_count++;
                } else {
                    $this->activated_count++;
                    $product->active = 1;
                }

                $product->name = (string)$rows[$product->code]['name'];
                $product->shop = Product::ARTGLASS_SHOP;
                $product->price = (float)$rows[$product->code]['price'];
                $product->size = (string)$rows[$product->code]['size'];
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
            $newProduct->shop = Product::ARTGLASS_SHOP;
            $newProduct->price = (float)$row['price'];
            $newProduct->size = (string)$row['size'];

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
            case Product::SVET_SHOP:
                return new XlsFilter(9, ['A', 'E', 'H','I']);
                break;

            case Product::EGLO_SHOP:
                return new XlsFilter(2, ['A', 'B', 'C']);
                break;

            case Product::FREYA_SHOP:
                return new XlsFilter(5, ['A', 'B', 'C', 'D', 'E', 'H', 'I', 'J', 'K', 'L', 'M']);
                break;

            case Product::MAYTONI_SHOP:
                return new XlsFilter(6, ['A', 'B', 'C', 'D', 'H', 'I', 'J', 'K', 'L', 'M']);
                break;

            case Product::ARTGLASS_SHOP:
                return new XlsFilter(2, ['A', 'B', 'C']);
                break;
        }

    }


}