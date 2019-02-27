<?php

namespace frontend\controllers;

use common\components\TecDoc;
use common\models\Product;
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
        $SQL = \Yii::$app->db->createCommand('SELECT
                STR_ID, TEX_TEXT AS STR_DES_TEXT, STR_ID_PARENT, STR_LEVEL, STR_SORT, STR_NODE_NR, 
                IF(EXISTS(SELECT * FROM SEARCH_TREE AS SEARCH_TREE2 WHERE SEARCH_TREE2.STR_ID_PARENT <=> SEARCH_TREE.STR_ID LIMIT 1), 1, 0) AS DESCENDANTS
            FROM SEARCH_TREE
                INNER JOIN DESIGNATIONS ON DES_ID = STR_DES_ID
                INNER JOIN DES_TEXTS ON TEX_ID = DES_TEX_ID
            WHERE
                DES_LNG_ID = 16 AND
                EXISTS (
                    SELECT * FROM LINK_GA_STR
                        INNER JOIN LINK_LA_TYP ON LAT_TYP_ID =' . $type_id . ' AND LAT_GA_ID = LGS_GA_ID
                        INNER JOIN LINK_ART ON LA_ID = LAT_LA_ID
                    WHERE
                        LGS_STR_ID = STR_ID
                    LIMIT 1)
            ');
        //ORDER BY STR_DES_TEXT');


        foreach ($SQL->queryAll() as $arPRes) {

            if (empty($arPRes['STR_ID_PARENT'])) {
                //if($arPRes['STR_LEVEL'] == 1) {
                $emptyArray[] = $arPRes;
                $menu[] = $arPRes;
                $menu[sizeof($menu) - 1]['child'] = array();
                $menu_index[$arPRes['STR_ID']] = &$menu[sizeof($menu) - 1];
            } else {
                $menu_index[$arPRes['STR_ID_PARENT']]['child'][] = $arPRes;
                $menu_index[$arPRes['STR_ID']] = &$menu_index[$arPRes['STR_ID_PARENT']]['child'][sizeof($menu_index[$arPRes['STR_ID_PARENT']]['child']) - 1];
            }
        }

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

        $article = 'B20002PR';
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



        foreach ($SQL->queryAll() as $article){
            $exist = false;
            foreach ($products as $product) {
                if ($product['article'] == $article['ART_ARTICLE_NR']) $exist = true;
            }

            $text= $article['SUP_BRAND'].' <b>'.$article['ART_ARTICLE_NR'].'</b><span style="font-size: .7em">  ID: '.$article['ART_ID'].'</span><br>';
            if ($exist){
                echo '<div style="color:green">'.$text.'</div>';
            }else{
                echo  $text;
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
                if ( preg_replace('/[^a-zA-Z0-9]/ui', '', $product['article']) == $article['NUMBER']) $exist = true;
            }


            $kind = $article['ARL_KIND'] == 3 ? 'Оригинал' : 'Неоригинал';
            $text = $article['BRAND'] . ' <b>' . $article['NUMBER'] . '</b>  <i>' . $kind . '</i><br>';
            if ($exist){
                echo '<div style="color:green">'.$text.'</div>';
            }else{
                echo  $text;
            }

        }

    }


}
