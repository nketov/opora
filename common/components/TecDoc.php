<?php

namespace common\components;


use common\models\Product;
use yii\base\Component;
use yii\db\Query;
use yii\helpers\ArrayHelper;
use yii\helpers\Url;

class TecDoc extends Component
{


    public static function getManufacturers($year = null)
    {


        $data = \Yii::$app->cache->getOrSet('manufacturers',
            function () use ($year) {
                $rows = (new Query())
                    ->select(['MFA_ID', 'MFA_BRAND'])
                    ->from('MANUFACTURERS')
                    ->where(['active' => 1,])
                    ->orderBy('MFA_BRAND');

                if ($year) {
                    $rows->andWhere(['or',
                        ['<=', 'MF_START', $year . '00'],
                        ['is', 'MF_START', null]
                    ])
                        ->andWhere(['or',
                            ['>=', 'MF_END', $year . '12'],
                            ['is', 'MF_END', null]
                        ]);
                }

                return $rows->all();
            });

        return $data;
    }


    public static function getBrandName($mfa_id)
    {
        return (new Query())
            ->select(['MFA_BRAND'])
            ->from('MANUFACTURERS')
            ->orderBy('MFA_BRAND')
            ->where(['MFA_ID' => $mfa_id,])
            ->scalar();
    }


    public static function getModels($mfa_id, $year = null)
    {

        /* Вывод списка моделей по заданной марке автомобиля (MFA_ID) */

        /*	MOD_ID - Номер модели */

        /*	MOD_CDS_TEXT - Название модели */

        /*	MOD_PCON_START - YYYYMM: Год/месяц начала выпуска модели */

        /*	MOD_PCON_END - YYYYMM: Год/месяц окончания выпуска модели (NULL - неограничен) */


        $data = \Yii::$app->cache->getOrSet('mfa_' . $mfa_id . '_year_' . $year,
            function () use ($mfa_id, $year) {
                $rows = (new Query())
                    ->select(['MOD_ID', 'TEX_TEXT AS MOD_CDS_TEXT', 'MOD_PCON_START', 'MOD_PCON_END'])
                    ->from('MODELS')
                    ->innerJoin('COUNTRY_DESIGNATIONS', 'CDS_ID = MOD_CDS_ID')
                    ->innerJoin('DES_TEXTS', 'TEX_ID = CDS_TEX_ID ')
                    ->where(['MOD_MFA_ID' => $mfa_id, 'CDS_LNG_ID' => '16']);

                if ($year) {
                    $rows->andWhere(['or',
                        ['<=', 'MOD_PCON_START', $year . '00'],
                        ['is', 'MOD_PCON_START', null]
                    ])
                        ->andWhere(['or',
                            ['>=', 'MOD_PCON_END', $year . '12'],
                            ['is', 'MOD_PCON_END', null]
                        ]);
                }


                return $rows->orderBy('MOD_CDS_TEXT')->all();
            });

        return $data;

    }


    public static function getTypes($mod_id, $year = null)
    {

        $data = \Yii::$app->cache->getOrSet('mod_' . $mod_id . '_year_' . $year,
            function () use ($mod_id, $year) {

                $year_sql = ' ';
                if ($year) {
                    $year_sql = ' AND (TYP_PCON_START <= ' . $year . '00 OR TYP_PCON_START IS NULL) 
            AND (TYP_PCON_END >= ' . $year . '12 OR TYP_PCON_END IS NULL)';
                }

                return \Yii::$app->db->createCommand('SELECT DISTINCT  	
TYP_ID,	MFA_BRAND,	DES_TEXTS7.TEX_TEXT AS MOD_CDS_TEXT,	DES_TEXTS.TEX_TEXT AS TYP_CDS_TEXT,	TYP_PCON_START,	TYP_PCON_END,	TYP_CCM,	TYP_KW_FROM,	TYP_KW_UPTO,	TYP_HP_FROM,	TYP_HP_UPTO,	TYP_CYLINDERS,	ENGINES.ENG_CODE,	DES_TEXTS2.TEX_TEXT AS TYP_ENGINE_DES_TEXT,	DES_TEXTS3.TEX_TEXT AS TYP_FUEL_DES_TEXT,	IFNULL(DES_TEXTS4.TEX_TEXT, DES_TEXTS5.TEX_TEXT) AS TYP_BODY_DES_TEXT,	DES_TEXTS6.TEX_TEXT AS TYP_AXLE_DES_TEXT,	TYP_MAX_WEIGHT
FROM	           TYPES
INNER JOIN MODELS ON MOD_ID = TYP_MOD_ID
INNER JOIN MANUFACTURERS ON MFA_ID = MOD_MFA_ID
INNER JOIN COUNTRY_DESIGNATIONS AS COUNTRY_DESIGNATIONS2 ON COUNTRY_DESIGNATIONS2.CDS_ID = MOD_CDS_ID AND COUNTRY_DESIGNATIONS2.CDS_LNG_ID = 16
INNER JOIN DES_TEXTS AS DES_TEXTS7 ON DES_TEXTS7.TEX_ID = COUNTRY_DESIGNATIONS2.CDS_TEX_ID
INNER JOIN COUNTRY_DESIGNATIONS ON COUNTRY_DESIGNATIONS.CDS_ID = TYP_CDS_ID AND COUNTRY_DESIGNATIONS.CDS_LNG_ID = 16
INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = COUNTRY_DESIGNATIONS.CDS_TEX_ID
LEFT JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = TYP_KV_ENGINE_DES_ID AND DESIGNATIONS.DES_LNG_ID = 16
LEFT JOIN DES_TEXTS AS DES_TEXTS2 ON DES_TEXTS2.TEX_ID = DESIGNATIONS.DES_TEX_ID
LEFT JOIN DESIGNATIONS AS DESIGNATIONS2 ON DESIGNATIONS2.DES_ID = TYP_KV_FUEL_DES_ID AND DESIGNATIONS2.DES_LNG_ID = 16
LEFT JOIN DES_TEXTS AS DES_TEXTS3 ON DES_TEXTS3.TEX_ID = DESIGNATIONS2.DES_TEX_ID
LEFT JOIN LINK_TYP_ENG ON LTE_TYP_ID = TYP_ID
LEFT JOIN ENGINES ON ENG_ID = LTE_ENG_ID
LEFT JOIN DESIGNATIONS AS DESIGNATIONS3 ON DESIGNATIONS3.DES_ID = TYP_KV_BODY_DES_ID AND DESIGNATIONS3.DES_LNG_ID = 16
LEFT JOIN DES_TEXTS AS DES_TEXTS4 ON DES_TEXTS4.TEX_ID = DESIGNATIONS3.DES_TEX_ID
LEFT JOIN DESIGNATIONS AS DESIGNATIONS4 ON DESIGNATIONS4.DES_ID = TYP_KV_MODEL_DES_ID AND DESIGNATIONS4.DES_LNG_ID = 16
LEFT JOIN DES_TEXTS AS DES_TEXTS5 ON DES_TEXTS5.TEX_ID = DESIGNATIONS4.DES_TEX_ID
LEFT JOIN DESIGNATIONS AS DESIGNATIONS5 ON DESIGNATIONS5.DES_ID = TYP_KV_AXLE_DES_ID AND DESIGNATIONS5.DES_LNG_ID = 16
LEFT JOIN DES_TEXTS AS DES_TEXTS6 ON DES_TEXTS6.TEX_ID = DESIGNATIONS5.DES_TEX_ID
WHERE	TYP_MOD_ID = ' . $mod_id . $year_sql . '
GROUP BY TYP_ID
ORDER BY	MFA_BRAND,	MOD_CDS_TEXT,	TYP_CDS_TEXT,	TYP_PCON_START,	TYP_CCM;')->queryAll();

            });
        return $data;

    }


    public static function getCategory($category, $type)
    {
        $data = \Yii::$app->cache->getOrSet('category_' . $category . '_type_' . $type,
            function () use ($category, $type) {
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


//        INNER JOIN product ON ARTICLES.ART_ARTICLE_NR = product.article
                $result = [];
                foreach ($SQL->queryAll() as $article) {

                    $lookup = self::getLookup($article['ART_ID']);
                    foreach ($lookup as $article) {
                        $result[] = $article;
                    }
                }

                return array_unique($result);
            });

        return $data;


    }


    public
    static function getLookup($number)
    {
        $data = \Yii::$app->cache->getOrSet('lookup_' . $number,
            function () use ($number) {
                $SQL = \Yii::$app->db->createCommand("
SELECT DISTINCT
IF (ART_LOOKUP.ARL_KIND IN (3, 4), BRANDS.BRA_BRAND, SUPPLIERS.SUP_BRAND) AS BRAND,
ART_LOOKUP.ARL_SEARCH_NUMBER AS NUMBER,
ART_LOOKUP.ARL_KIND,
ART_LOOKUP.ARL_ART_ID, 
DES_TEXTS.TEX_TEXT AS ART_COMPLETE_DES_TEXT,
ARTICLES.ART_ARTICLE_NR,
ART_LOOKUP.ARL_DISPLAY_NR
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
GROUP BY BRAND, NUMBER;
        ");

                $result = [];
                foreach ($SQL->queryAll() as $article) {
                    if ($article['ARL_DISPLAY_NR'])
                        $result[] = $article['ARL_DISPLAY_NR'];
                }
                return array_unique($result);
            });
        return $data;

    }


    public
    static function getImages($article)
    {

        $SQL = \Yii::$app->db->createCommand("
         SELECT
    CONCAT(GRA_TAB_NR, '/',GRA_GRD_ID, '.',
	IF(LOWER(DOC_EXTENSION)='jp2', 'jpg', LOWER(DOC_EXTENSION))) AS PATH
FROM
    	LINK_GRA_ART
       	INNER JOIN GRAPHICS ON GRA_ID = LGA_GRA_ID
       	INNER JOIN DOC_TYPES ON DOC_TYPE = GRA_DOC_TYPE
       	INNER JOIN ARTICLES ON ART_ID = LGA_ART_ID
       	WHERE
		ART_ARTICLE_NR = '" . $article . "'  AND
        (GRA_LNG_ID = 16 OR GRA_LNG_ID = 255) AND
        GRA_DOC_TYPE <> 2
ORDER BY  GRA_GRD_ID   
            
        ");

        return $SQL->queryAll();

    }


    public
    static function getInfo($article)
    {

        $SQL = \Yii::$app->db->createCommand("
        SELECT
    ART_ARTICLE_NR,
    SUP_BRAND,
    DES_TEXTS.TEX_TEXT AS ART_COMPLETE_DES_TEXT,
    DES_TEXTS2.TEX_TEXT AS ART_DES_TEXT,
    DES_TEXTS3.TEX_TEXT AS ART_STATUS_TEXT
FROM
               ARTICLES
    INNER JOIN DESIGNATIONS ON DESIGNATIONS.DES_ID = ART_COMPLETE_DES_ID
                           AND DESIGNATIONS.DES_LNG_ID = 16
    INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = DESIGNATIONS.DES_TEX_ID
     LEFT JOIN DESIGNATIONS AS DESIGNATIONS2 ON DESIGNATIONS2.DES_ID = ART_DES_ID
                                            AND DESIGNATIONS2.DES_LNG_ID = 16
     LEFT JOIN DES_TEXTS AS DES_TEXTS2 ON DES_TEXTS2.TEX_ID = DESIGNATIONS2.DES_TEX_ID
    INNER JOIN SUPPLIERS ON SUP_ID = ART_SUP_ID
    INNER JOIN ART_COUNTRY_SPECIFICS ON ACS_ART_ID = ART_ID
    INNER JOIN DESIGNATIONS AS DESIGNATIONS3 ON DESIGNATIONS3.DES_ID = ACS_KV_STATUS_DES_ID
                                            AND DESIGNATIONS3.DES_LNG_ID = 16
    INNER JOIN DES_TEXTS AS DES_TEXTS3 ON DES_TEXTS3.TEX_ID = DESIGNATIONS3.DES_TEX_ID
WHERE
    ART_ARTICLE_NR = '" . $article . "';");

        return $SQL->queryAll();

    }

    public
    static function getTreeArray($type_id)
    {

        $data = \Yii::$app->cache->getOrSet('tree_type_' . $type_id,
            function () use ($type_id) {
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

                $menu=[];
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

                return $menu;
            });
        return $data;

    }


    public
    static function setMfYears()
    {

//        foreach (self::getManufacturers() as $brand) {
//
//            $models = self::getModels($brand['MFA_ID']);

//            if (!$models) {
//                $active = 0;
//            } else {
//                $active = 1;
//            }
//
//            $SQL = \Yii::$app->db->createCommand("
//                UPDATE MANUFACTURERS SET active=".$active." WHERE MFA_ID=" . $brand['MFA_ID']);
//
//

//             if (stripos($brand['MFA_BRAND'],'MOTORCYCLES') !== false) {
////
//            $SQL = \Yii::$app->db->createCommand("
//                UPDATE MANUFACTURERS SET active=0 WHERE MFA_ID=" . $brand['MFA_ID']);
//            }


//            $SQL->execute();

//
//            $brand_start = $models[0]['MOD_PCON_START'];
//            $brand_end = $models[0]['MOD_PCON_END'];
//
//            foreach ($models as $model) {
//                if ($model['MOD_PCON_END'] == null) {
//                    $brand_end = null;
//                    break;
//                }
//                if ($model['MOD_PCON_END'] > $brand_end) $brand_end = $model['MOD_PCON_END'];
//
//
//            }
//
//            if ($brand_end) {
//
//            var_dump($brand);
//                $SQL = \Yii::$app->db->createCommand("
//                 UPDATE MANUFACTURERS SET MF_END=".$brand_end." WHERE MFA_ID=" . $brand['MFA_ID']);
//                $SQL->execute();
//            }
//            var_dump($models[0]);
//            var_dump($brand_start);

//        }

    }


    public static function getModelFullName($mod_id)
    {

        $row = \Yii::$app->db->createCommand(
            'SELECT MFA_BRAND,	DES_TEXTS7.TEX_TEXT AS MOD_CDS_TEXT
FROM	           TYPES
INNER JOIN MODELS ON MOD_ID = TYP_MOD_ID
INNER JOIN MANUFACTURERS ON MFA_ID = MOD_MFA_ID
INNER JOIN COUNTRY_DESIGNATIONS AS COUNTRY_DESIGNATIONS2 ON COUNTRY_DESIGNATIONS2.CDS_ID = MOD_CDS_ID AND COUNTRY_DESIGNATIONS2.CDS_LNG_ID = 16
INNER JOIN DES_TEXTS AS DES_TEXTS7 ON DES_TEXTS7.TEX_ID = COUNTRY_DESIGNATIONS2.CDS_TEX_ID
INNER JOIN COUNTRY_DESIGNATIONS ON COUNTRY_DESIGNATIONS.CDS_ID = TYP_CDS_ID AND COUNTRY_DESIGNATIONS.CDS_LNG_ID = 16
INNER JOIN DES_TEXTS ON DES_TEXTS.TEX_ID = COUNTRY_DESIGNATIONS.CDS_TEX_ID
WHERE	TYP_MOD_ID = ' . $mod_id . '
ORDER BY	MOD_CDS_TEXT;'
        )->queryOne();


        return $row['MFA_BRAND'] . ' ' . $row['MOD_CDS_TEXT'];
    }


}