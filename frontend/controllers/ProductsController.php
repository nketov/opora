<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\ProductTextSearch;
use frontend\models\Cart;
use frontend\models\UnregisteredUser;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;

/**
 * ProductsController implements the CRUD actions for Product model.
 */
class ProductsController extends Controller
{


    public function actionCategory()
    {
        $searchModel = new ProductSearch();
        $searchModel->setAttribute('active', 1);
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);

        return $this->render('category', compact('products', 'searchModel', 'dataProvider'));

    }

    public function actionTextSearch()
    {
        $searchModel = new ProductTextSearch();
        $dataProvider = $searchModel->search(Yii::$app->request->queryParams);
        return $this->render('text_search', compact( 'searchModel', 'dataProvider'));

    }



    public function actionView($id)
    {
        return $this->render('view', [
            'model' => $this->findModel($id),
        ]);
    }


    public function actionAjaxGetSession()
    {
        if (Yii::$app->request->isAjax) {
            \ Yii::$app->response->format = Response::FORMAT_JSON;
            $cart = new Cart;
            return $cart->getCart();
        }
    }


    public function actionAddCart()
    {
        $data = Yii::$app->request->post('data');
        $qty = Yii::$app->request->post('qty');

        if (empty($this->findModel($data['id']))) return false;

        $session = Yii::$app->session;
        $session->open();
        $cart = new Cart;
        $cart->addCart($data, $qty);

        return true;
    }

    public function actionResetCart()
    {
        $cart = new Cart;
        $cart->resetCart();
        $this->redirect(Url::to(['/']));
    }

    public function actionDeleteCart($id)
    {
        $cart = new Cart;
        $cart->deleteCart($id);
    }

    public function actionOrder()
    {


        $cart = new Cart();
        $order = new Order();

        if (Yii::$app->user->isGuest) {
            $order->user_id = 0;
            $phone = $_REQUEST['UnregisteredUser']['phone'];
            $shop_text = '<p><b>Незарегистрированный пользователь</b> сделал заказ. Содержание заказа : </p>';
        } else {
            $order->user_id = Yii::$app->user->id;
            $phone = Yii::$app->user->identity->phone;
            $shop_text = '<p>Пользователь  <b>' . Yii::$app->user->identity->email . '</b> сделал заказ. Содержание заказа : </p>';
        }



        $order->summ = $cart->getSumm();
        $products = $cart->getCart();
        $order_content = '';
        $count = 1;
        foreach ($products as $id => $array) {
            $product = $this->findModel($id);
            $order_content .= '<p>' . $count . '. ' . $product->name . ' (' . $product->code . ') ' . $array['qty'] . ' шт.  - ' . round($product->getDiscountPrice() * $array['qty'], 2) . ' грн</p>';
            $count++;
        }


        $order_content .= '<p><b> Всего: ' . round($cart->getSumm(), 2) . ' грн</b></p>';

        $string = $phone;
        $phone = '+38 (0'.$string[0].$string[1].') '.$string[2].$string[3].$string[4].' '.$string[5].$string[6].' '.$string[7].$string[8];

         $order_content .= '<br> Телефон: <b>' .$phone.'</b>';
         $shop_text .= $order_content;

        $order->order_content = $order_content;
        $order->save();

        if (!Yii::$app->user->isGuest) {
            $user_text = '<p>Вы сделали заказ на сайте <b>opora.dn.ua</b>. Содержание заказа : </p>' . $order_content;
            mail(Yii::$app->user->identity->email, 'Заказ № ' . $order->id, $user_text, "Content-type:text/html;charset=UTF-8");
        }

        mail('ketovnv@gmail.com', 'Заказ № ' . $order->id, $shop_text, "Content-type:text/html;charset=UTF-8");

        $cart->resetCart();
        Yii::$app->session->setFlash('success', 'Ваш заказ отправлен!');
        $this->redirect(Url::to(['/']));

    }


    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
