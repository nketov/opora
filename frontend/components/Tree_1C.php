<?php

namespace frontend\components;


use common\models\Category;
use yii\base\BaseObject;


class Tree_1C extends BaseObject
{

    private $_category_arr = [];
    private $_tree = [];
    private $_render ='';

    public function init()
    {
        $all_cats = Category::find()->all();
        foreach ($all_cats as $cat) {
            $this->_category_arr[$cat['parent_code']][] = $cat;
        }
    }

    public function renderTree(){
    $this->_render = '';
    $this->outTree('', 0);
    return $this->_render;
}

    public function getChildesIds($parent_code){
        $this->_tree = [];
        $this->_tree[] = Category::find()->where(['like','code', $parent_code])->one()->id;
        $this->setTree($parent_code);
        return $this->_tree;
    }


    public function getCategories(){
        return $this->_category_arr['000000318'];
    }

    public function getSubCategories($parent_code){
        return $this->_category_arr[$parent_code] ?? null;
    }


    private function outTree($parent_id, $level)
    {
        if (isset($this->_category_arr[$parent_id])) {
            foreach ($this->_category_arr[$parent_id] as $value) {

                $this->_render .= "<div style='
                margin-left:" . ($level * 50) . "px; 
                font-size:" . (30 - $level * 2) . "px;
                color: rgb(" . (255 - $level * 25) . "," . (0 + $level * 35) . "," . (100 + $level * 25) . ");
                '>" . $value->name ." " .$value->code."</div>";
                $level++;
                $this->outTree($value->code, $level);
                $level--;
            }
        }
    }

    private function setTree($parent_id)
    {
        if (isset($this->_category_arr[$parent_id])) {
            foreach ($this->_category_arr[$parent_id] as $value) {
                $this->_tree[] = $value->id;
                $this->setTree($value->code);
            }
        }
    }

}