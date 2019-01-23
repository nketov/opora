<?php
namespace app\models;

use yii\base\Model;
use yii\helpers\Url;
use yii\web\UploadedFile;

class ImageUploadForm extends Model
{
    /**
     * @var UploadedFile
     */
    public $image;
    public $product;
    public $key;

    public function rules()
    {
        return [
            [['product'], 'integer'],
            [['key'], 'string'],
            [['image'], 'file', 'skipOnEmpty' => true, 'extensions' => 'png, jpg, jpeg, gif', 'checkExtensionByMimeType' => false],
        ];
    }


    public function upload()
    {
        if ($this->validate()) {
            $this->image->saveAs(Url::to('@frontend/web/images/products/p_') .$this->product.'_'. $this->key.'.' . $this->image->extension);
            return true;
        } else {
            return false;
        }

    }
}