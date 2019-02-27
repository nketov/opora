<?php

namespace common\models;

/**
 * This is the ActiveQuery class for [[Product]].
 *
 * @see Product
 */
class ProductQuery extends \yii\db\ActiveQuery
{
    /*public function active()
    {
        return $this->andWhere('[[status]]=1');
    }*/

    /**
     * {@inheritdoc}
     * @return Product[]|array
     */
    public function all($db = null)
    {
        return parent::all($db);
    }

    /**
     * {@inheritdoc}
     * @return Product|array|null
     */
    public function one($db = null)
    {
        return parent::one($db);
    }


    //Example

//    public function big($threshold = 100)
//    {
//        return $this->andWhere(['>', 'subtotal', $threshold]);
//    }



    public function active()
    {
        return $this->andWhere(['active' => 1]);
    }

    public function hasArticle()
    {
        return $this->andWhere(['is not','article', null])
//            ->andWhere(['!=','article', 0])
            ;
    }


}
