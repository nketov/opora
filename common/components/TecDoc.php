<?php

namespace common\components;


use yii\base\Component;
use yii\db\Query;
use yii\helpers\Url;

class TecDoc extends Component
{

    public static function getModelInfo($mod_mfa_id)
    {

        /* Вывод списка моделей по заданной марке автомобиля (MFA_ID) */

        /*	MOD_ID - Номер модели */

        /*	MOD_CDS_TEXT - Название модели */

        /*	MOD_PCON_START - YYYYMM: Год/месяц начала выпуска модели */

        /*	MOD_PCON_END - YYYYMM: Год/месяц окончания выпуска модели (NULL - неограничен) */


        $rows = (new Query())
            ->select(['MOD_ID', 'TEX_TEXT AS MOD_CDS_TEXT', 'MOD_PCON_START', 'MOD_PCON_END'])
            ->from('MODELS')
            ->innerJoin('COUNTRY_DESIGNATIONS', 'CDS_ID = MOD_CDS_ID')
            ->innerJoin('DES_TEXTS', 'TEX_ID = CDS_TEX_ID ')
            ->where(['MOD_MFA_ID' => $mod_mfa_id, 'CDS_LNG_ID' => '16'])
//            ->andWhere(['>', 'MOD_PCON_START', 200000])
//            ->andWhere(['>', 'MOD_PCON_END', 201100])
            ->orderBy('MOD_CDS_TEXT')
            ->all();


        return $rows;

    }


    public static function getModels()
    {
        $rows = (new Query())
            ->select(['MFA_ID', 'MFA_BRAND'])
            ->from('MANUFACTURERS')
            ->orderBy('MFA_BRAND')
            ->all();

        return $rows;
    }

    public static function getModelName($mfa_id)
    {
        return (new Query())
            ->select(['MFA_BRAND'])
            ->from('MANUFACTURERS')
            ->orderBy('MFA_BRAND')
            ->where(['MFA_ID' => $mfa_id,])
            ->scalar();
    }

    public static function getTypes($mod_id)
    {
        /* Вывод списка типов автомобилей по заданной модели (MOD_ID) */

        /* TYP_ID - Номер типа автомобиля */

        /* MFA_BRAND - Марка автомобиля */

        /* MOD_CDS_TEXT - Модель автомобиля */

        /* TYP_CDS_TEXT - Название типа автомобиля */

        /* TYP_PCON_START - YYYYMM: Год/месяц начала выпуска типа */

        /* TYP_PCON_END - YYYYMM: Год/месяц окончания выпуска типа (NULL - неограничен) */

        /* TYP_CCM - Объём двигателя (куб.см) */

        /* TYP_KW_FROM - Мощность двигателя (кВт): ОТ */

        /* TYP_KW_UPTO - Мощность двигателя (кВт): ДО (NULL - неограничен) */

        /* TYP_HP_FROM - Мощность двигателя (л.с.): ОТ */

        /* TYP_HP_UPTO - Мощность двигателя (л.с.): ДО (NULL - неограничен) */

        /* TYP_CYLINDERS - Количество цилиндров */

        /* ENG_CODE - Код двигателя */

        /* TYP_ENGINE_DES_TEXT - Тип двигателя */

        /* TYP_FUEL_DES_TEXT - Тип топлива */

        /* TYP_BODY_DES_TEXT - Вид сборки */

        /* TYP_AXLE_DES_TEXT - Конструкция оси (для грузовых)*/

        /* TYP_MAX_WEIGHT - Тоннаж (для грузовых)*/

        return \Yii::$app->db->createCommand('SELECT	TYP_ID,	MFA_BRAND,	DES_TEXTS7.TEX_TEXT AS MOD_CDS_TEXT,	DES_TEXTS.TEX_TEXT AS TYP_CDS_TEXT,	TYP_PCON_START,	TYP_PCON_END,	TYP_CCM,	TYP_KW_FROM,	TYP_KW_UPTO,	TYP_HP_FROM,	TYP_HP_UPTO,	TYP_CYLINDERS,	ENGINES.ENG_CODE,	DES_TEXTS2.TEX_TEXT AS TYP_ENGINE_DES_TEXT,	DES_TEXTS3.TEX_TEXT AS TYP_FUEL_DES_TEXT,	IFNULL(DES_TEXTS4.TEX_TEXT, DES_TEXTS5.TEX_TEXT) AS TYP_BODY_DES_TEXT,	DES_TEXTS6.TEX_TEXT AS TYP_AXLE_DES_TEXT,	TYP_MAX_WEIGHT
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
WHERE	TYP_MOD_ID = '.$mod_id.'
ORDER BY	MFA_BRAND,	MOD_CDS_TEXT,	TYP_CDS_TEXT,	TYP_PCON_START,	TYP_CCM
LIMIT	100;')->queryAll();

    }


}