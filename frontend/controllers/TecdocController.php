<?php

namespace frontend\controllers;

use common\components\TecDoc;
use common\models\Product;
use common\models\TecdocSearch;
use yii\data\ArrayDataProvider;
use yii\helpers\ArrayHelper;
use yii\helpers\Html;
use yii\helpers\Json;

class TecdocController extends \yii\web\Controller
{
    public function actionIndex($year = null)
    {

        $brandsProvider = new ArrayDataProvider([
            'allModels' => TecDoc::getManufacturers($year),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('index', compact('brandsProvider'));
    }


    public function actionSearch()
    {

        $tecdocSearch = new TecdocSearch();
        $tree = null;

        if (isset($_COOKIE['car']) && !empty($car = unserialize($_COOKIE['car'], ["allowed_classes" => false]))) {
            $tecdocSearch->load($car, '');
            $tree = TecDoc::getTreeArray($car['type_id'])[0]['child'];
            $tecdocSearch['category'] = $_POST['category'] ?? 0;

        }
        $dataProvider= $tecdocSearch->search();

        return $this->render('test-search', compact('tecdocSearch', 'dataProvider', 'tree'));
    }


    public function actionModels($mfa_id, $year = null)
    {

        $modelsProvider = new ArrayDataProvider([
            'allModels' => TecDoc::getModels($mfa_id, $year),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);
        return $this->render('models', compact('modelsProvider', 'mfa_id', 'year'));
    }

    public function actionTypes($mod_id, $year = null)
    {

        $typesProvider = new ArrayDataProvider([
            'allModels' => TecDoc::getTypes($mod_id, $year),
            'pagination' => [
                'pageSize' => 10,
            ],
        ]);

        return $this->render('types', compact('typesProvider', 'mod_id'));
    }


    public function actionTestTree($type_id)
    {

        $menu = TecDoc::getTreeArray($type_id);

        function sort_p($a, $b)
        {
            return strcmp($a["STR_DES_TEXT"], $b["STR_DES_TEXT"]);
        }

        function GetCat($array)
        {
            asort($array);
            $result = '<ul>';
            foreach ($array as $key => $value) {
                $result .= '<li data-key=' . $value['STR_ID'] . '>' . $value['STR_DES_TEXT'] . ', id: ' . $value['STR_ID'] . ', sub:' . $value['STR_ID_PARENT'];
                if (isset($value['child']) && $value['child']) {
                    asort($value['child']);
                    $result .= GetCat($value['child']);
                }
                $result .= '</li>';
            }
            $result .= '</ul>';
            return $result;
        }

        $cats = '';
        if ($c10001 = $menu[0]['child']) {
            $cats = GetCat($c10001);
        }

        return $this->render('test-tree', compact('cats', 'type_id'));

    }


    public function actionAllImages()
    {

        $products = Product::find()->all();

        foreach ($products as $product) {
            if (!empty($article = $product->article)) {
                $product->tecdoc_images = TecDoc::getImages($article);
            }
        }
        return $this->render('all-images', compact('products'));
    }

    public function actionInfo($article)
    {


        foreach (TecDoc::getInfo($article) as $row) {
            foreach ($row as $key => $val) {
                echo $key . '   <b>' . $val . "</b><br>";
            }
            echo '<hr>';
            echo '<hr>';
        };

    }


    public function actionCarsForArticle()
    {

        $article = 'K015408XS';
        $SQL = \Yii::$app->db->createCommand("
SELECT 
TYPES.TYP_ID,    
DES_TEXTS.TEX_TEXT AS TEX_TEXT,
TYPES.TYP_MOD_ID 
FROM TYPES  
    INNER JOIN COUNTRY_DESIGNATIONS ON COUNTRY_DESIGNATIONS.CDS_ID = TYPES.TYP_MMT_CDS_ID 
    INNER JOIN DES_TEXTS ON COUNTRY_DESIGNATIONS.CDS_TEX_ID = DES_TEXTS.TEX_ID 
    INNER JOIN LINK_LA_TYP ON LINK_LA_TYP.LAT_TYP_ID = TYPES.TYP_ID   
    INNER JOIN LINK_ART ON LINK_ART.LA_ID = LINK_LA_TYP.LAT_LA_ID 
    INNER JOIN ARTICLES ON ART_ID = LINK_ART.LA_ART_ID
WHERE COUNTRY_DESIGNATIONS.CDS_LNG_ID = 16 AND ART_ARTICLE_NR = '" . $article . "'
        ");

        return var_dump($SQL->queryAll());

    }

    public function actionCategory($category, $type)
    {

        $TYP_ID = '20861';
        $STR_ID = '10570';
        $SQL = \Yii::$app->db->createCommand("
SELECT 
     *
     FROM 
     LINK_GA_STR 
     INNER JOIN LINK_LA_TYP ON LAT_TYP_ID = " . $type . " AND 
     LAT_GA_ID = LGS_GA_ID 
     INNER JOIN LINK_ART ON LA_ID = LAT_LA_ID 
     INNER JOIN SUPPLIERS ON LINK_LA_TYP.LAT_SUP_ID=SUPPLIERS.SUP_ID 
     INNER JOIN ARTICLES ON ART_ID = LINK_ART.LA_ART_ID
     WHERE 
     LGS_STR_ID = " . $category . " 
        ");

//        var_dump($SQL->queryAll());

        $products = Product::find()->select('article')->active()->hasArticle()->asArray()->all();


        foreach ($SQL->queryAll() as $article) {
            $exist = false;
            foreach ($products as $product) {
                if ($product['article'] == $article['ART_ARTICLE_NR']) $exist = true;
            }

            $text = $article['SUP_BRAND'] . ' <b>' . $article['ART_ARTICLE_NR'] . '</b><span style="font-size: .7em">  ID: ' . $article['ART_ID'] . '</span><br>';
            if ($exist) {
                echo '<div style="color:green">' . $text . '</div>';
            } else {
                echo $text;
            }

        }

        exit;
    }

    public function actionLookup($number)
    {


        $SQL = \Yii::$app->db->createCommand("
SELECT DISTINCT
IF (ART_LOOKUP.ARL_KIND IN (3, 4), BRANDS.BRA_BRAND, SUPPLIERS.SUP_BRAND) AS BRAND,
ART_LOOKUP.ARL_SEARCH_NUMBER AS NUMBER,
ART_LOOKUP.ARL_KIND,
ART_LOOKUP.ARL_ART_ID, 
DES_TEXTS.TEX_TEXT AS ART_COMPLETE_DES_TEXT
FROM ART_LOOKUP
LEFT JOIN BRANDS ON BRANDS.BRA_ID = ART_LOOKUP.ARL_BRA_ID
INNER JOIN ARTICLES ON ARTICLES.ART_ID = ART_LOOKUP.ARL_ART_ID
INNER JOIN SUPPLIERS ON SUPPLIERS.SUP_ID = ARTICLES.ART_SUP_ID
INNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = ARTICLES.ART_COMPLETE_DES_ID
INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID
WHERE
ART_LOOKUP.ARL_ART_ID = '" . $number . "' AND
ART_LOOKUP.ARL_KIND IN (1,2,3, 4) AND
DESIGNATIONS.DES_LNG_ID = 16
GROUP BY BRAND, NUMBER ;
        ");


//

        $products = Product::find()->select('article')->active()->hasArticle()->asArray()->all();


        foreach ($SQL->queryAll() as $article) {

            $exist = false;
            foreach ($products as $product) {
                if (preg_replace('/[^a-zA-Z0-9]/ui', '', $product['article']) == $article['NUMBER']) $exist = true;
            }


            $kind = $article['ARL_KIND'] == 3 ? 'Оригинал' : 'Неоригинал';
            $text = $article['BRAND'] . ' <b>' . $article['NUMBER'] . '</b>  <i>' . $kind . '</i><br>';
            if ($exist) {
                echo '<div style="color:green">' . $text . '</div>';
            } else {
                echo $text;
            }

        }

    }

//    ---------------------DROPDOWNs-----------------


    public function actionMfaDropDown()
    {

        $data = $_POST['depdrop_all_params'];

        $list = TecDoc::getManufacturers($data['td_year']);
        $out = [];
        foreach ($list as $item) {
            $out[] = ['id' => $item['MFA_ID'], 'name' => $item['MFA_BRAND']];
        }

        return Json::encode(['output' => $out, 'selected' => $_GET['mfa_id']]);
    }


    public function actionModelDropDown()
    {

        $data = $_POST['depdrop_all_params'];

        $list = TecDoc::getModels($data['td_mfa_id'], $data['td_year']);
        $out = [];
        foreach ($list as $item) {
            $out[] = ['id' => $item['MOD_ID'], 'name' => $item['MOD_CDS_TEXT']];
        }

        return Json::encode(['output' => $out, 'selected' => $_GET['mod_id']]);
    }


    public function actionTypeDropDown()
    {

        $data = $_POST['depdrop_all_params'];
        $out = [];
        if ((int)$data['td_mod_id']) {
            $list = TecDoc::getTypes($data['td_mod_id'], $data['td_year']);
            foreach ($list as $item) {
                $out[] = ['id' => $item['TYP_ID'],
                    'name' => $item['MOD_CDS_TEXT'] . ' ' . $item['TYP_CDS_TEXT'] . ', ' . $item['TYP_HP_FROM'] . ' л.с.'];
            }
        }
        return Json::encode(['output' => $out, 'selected' => $_GET['type_id']]);
    }


    public function actionLevel2DropDown()
    {

        $data = $_POST['depdrop_all_params'];
        $out = [];
        if ((int)$data['tree_level_1']) {
            $car = unserialize($_COOKIE['car'], ["allowed_classes" => false]);
            $tree = TecDoc::getTreeArray($car['type_id'])[0]['child'];

            foreach ($tree as $level_1) {
                if ($level_1['STR_ID'] == $data['tree_level_1']) {
                    if (isset($level_1['child']))
                    foreach ($level_1['child'] as $level_2) {

                        $out[] = ['id' => $level_2['STR_ID'],
                            'name' => $level_2['STR_DES_TEXT']];
                    }
                }
            }
        }
        return Json::encode(['output' => $out]);
    }

    public function actionLevel3DropDown()
    {

        $data = $_POST['depdrop_all_params'];
        $out = [];
        if ((int)$data['tree_level_2']) {
            $car = unserialize($_COOKIE['car'], ["allowed_classes" => false]);
            $tree = TecDoc::getTreeArray($car['type_id'])[0]['child'];

            foreach ($tree as $level_1) {
                foreach ($level_1['child'] as $level_2) {
                    if ($level_2['STR_ID'] == $data['tree_level_2']) {
                        if (isset($level_2['child']))
                            foreach ($level_2['child'] as $level_3) {

                                $out[] = ['id' => $level_3['STR_ID'],
                                    'name' => $level_3['STR_DES_TEXT']];
                            }
                    }
                }
            }
        }
        return Json::encode(['output' => $out]);
    }

    public
    function actionAddCar()
    {

        unset($_COOKIE['car']);
        setcookie('car', null, -1, '/');
        $form = $_POST['TecdocSearch'];

        setcookie("car", serialize($form), time() + 3600 * 24 * 100, '/');

        $car_text = $form['car_name'];
        if ($form['year']) {
            $car_text .= ', ' . $form['year'] . ' г.в.';
        }
        return Html::a($car_text, '/car');
    }


    public
    function actionSetYears()
    {
//       TecDoc::setMfYears();
    }


}
