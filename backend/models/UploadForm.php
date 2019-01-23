<?php

namespace app\models;

use common\models\Product;
use yii\base\Model;
use yii\helpers\Url;
use yii\web\UploadedFile;

class UploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $excelFile;
    public $shop;
    public $markup=0;

    public function rules()
    {
        return [
            [['excelFile'], 'file', 'skipOnEmpty' => false, 'extensions' => 'xls,xlsx', 'checkExtensionByMimeType' => false],
            [['markup'], 'number'],
            [['shop'], 'required']
        ];
    }

    public function attributeLabels()
    {
        return [
            'shop' => 'Магазин',
            'excelFile' => 'Фаил Excel',
            'markup' => 'Наценка, %'
        ];
    }

    public function upload()
    {
        if ($this->validate()) {
            $this->excelFile->saveAs('uploads/prices/'.Product::shopName($this->shop) . '.'. $this->excelFile->extension, false);
            return true;
        } else {
            return false;
        }
    }
}