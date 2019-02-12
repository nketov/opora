<?php

namespace frontend\controllers;

use common\components\TecDoc;
use yii\data\ArrayDataProvider;

class TecdocController extends \yii\web\Controller
{
    public function actionIndex()
    {

        $brandsProvider = new ArrayDataProvider([
            'allModels' => TecDoc::getBrands(),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', compact('brandsProvider'));
    }


    public function actionModels($mfa_id)
    {

        $modelsProvider = new ArrayDataProvider([
            'allModels' => TecDoc::getModels($mfa_id),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('models', compact('modelsProvider','mfa_id'));
    }

    public function actionTypes($mod_id)
    {

        $typesProvider = new ArrayDataProvider([
            'allModels' => TecDoc::getTypes($mod_id),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('types', compact('typesProvider','mod_id'));
    }



}
