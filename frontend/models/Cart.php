<?php

namespace frontend\models;

use common\components\TecDoc;
use common\models\Product;
use yii\base\Model;

class Cart extends Model
{

    public function addCart($data, $qty = 1)
    {

        $id = $data['id'];

        if (isset($_SESSION['cart'][$id])) {
            $_SESSION['cart'][$id]['qty'] += $qty;
        } else {
            $_SESSION['cart'][$id] = [
                'qty' => $qty];
        }


        $_SESSION['cart'][$id]['name'] = $data['name'];
        $_SESSION['cart'][$id]['image'] = $data['image'];
        $_SESSION['cart'][$id]['price'] = $data['price'];
        $_SESSION['cart'][$id]['remains'] = $data['remains'];
        $_SESSION['cart'][$id]['unit'] = $data['unit'];


        if (!empty($car = TecDoc::getCookieCar())) {
            $car_text = $car['car_name'];
            if ($car['year']) {
                $car_text .= ', ' . $car['year'] . ' г.в.';
            }
            if ($car['vin']) {
                $car_text .= ', VIN-код: ' . $car['vin'];
            }
            $_SESSION['cart'][$id]['car'] = $car_text;
        }


        $_SESSION['cart.qty'] = $qty + ($_SESSION['cart.qty'] ?? 0);
        $_SESSION['cart.sum'] = $qty * $data['price'] + ($_SESSION['cart.sum'] ?? 0);
        return true;
    }

    public function resetCart()
    {
        $_SESSION['cart'] = [];
        $_SESSION['cart.qty'] = 0;
        $_SESSION['cart.sum'] = 0;
        return true;
    }

    public function deleteCart($id)
    {
        if ($product = Product::findOne($id)) {
            $_SESSION['cart.qty'] -= $_SESSION['cart'][$id]['qty'];
            $_SESSION['cart.sum'] -= $_SESSION['cart'][$id]['qty'] * $product->getDiscountPrice();
            unset($_SESSION['cart'][$id]);
        }
        return true;
    }

    public function getCart()
    {
        return $_SESSION['cart'] ?? 0;
    }


    public function getSumm()
    {
        return $_SESSION['cart.sum'] ?? 0;
    }

    public function getPositionQuantity()
    {
        return $_SESSION['cart'] ?? 0;
    }

    public function getProductsQuantity()
    {
        return $_SESSION['cart.qty'] ?? 0;
    }


}


