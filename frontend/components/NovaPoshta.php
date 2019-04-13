<?php

namespace frontend\components;


use yii\helpers\ArrayHelper;

class NovaPoshta extends NovaPoshtaApi2
{
    public function __construct()
    {
        return parent::__construct('6dc9d20fe5e12052805da112e8bc0b9f');
    }

    public function getCitiesByArea($areaRef)
    {
        return array_filter($this->getCities()['data'],
            function ($city) use ($areaRef) {
                return $city['Area'] == $areaRef;
            });
    }

    public function getAreasListRu()
    {
        $areas_refs = ArrayHelper::getColumn($this->getAreas()['data'], 'Ref');
        $listRu = [];
        foreach ($areas_refs as $ref) {
            $listRu[$ref] = $this->getAreaNameRu($ref);
        }
        return $listRu;
    }

    public function getAreaNameRu($ref)
    {
        return $this->getArea('', $ref)['data'][0]['AreaRu'];
    }

    public function getCityNameRu($ref)
    {
        $name = '';
        foreach ($this->getCities()['data'] as $city) {
            if ($city['Ref'] == $ref) {
                $name = $city['DescriptionRu'];
                break;
            }
        }
        return $name;
    }

    public function getWarehouseNameRu($cityRef,$ref)
    {
        $name = '';
        foreach ($this->getWarehouses($cityRef)['data'] as $wh) {
            if ($wh['Ref'] == $ref) {
                $name = $wh['DescriptionRu'];
                break;
            }
        }
        return $name;
    }



}