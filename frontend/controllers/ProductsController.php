<?php

namespace frontend\controllers;

use common\models\Order;
use common\models\ProductTextSearch;
use common\models\TecdocSearch;
use frontend\models\Cart;
use frontend\models\UnregisteredUser;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use common\models\Product;
use common\models\ProductSearch;
use yii\helpers\Url;
use yii\web\Controller;
use yii\web\NotFoundHttpException;
use yii\filters\VerbFilter;
use yii\web\Response;
use common\models\User;

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

    public function actionCarSearch()
    {

        if(!\Yii::$app->request->isAjax && !empty($_GET['category'])){
            $this->redirect('car');
        }

        $tecdocSearch = new TecdocSearch();

        if (isset($_COOKIE['car']) && !empty($car = unserialize($_COOKIE['car'], ["allowed_classes" => false]))) {
            $tecdocSearch->load($car, '');
            $tecdocSearch['category'] = $_GET['category'] ?? 0;
        }


        return $this->render('car', compact('tecdocSearch'));
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

        $tecdocSearch= new TecdocSearch();
        $tecdocSearch->load($form, '');



//        return Html::a($car_text, '/car');
        return Json::encode([
            'car_name' => Html::a($car_text, '/car'),
            'select_render' => $this->renderAjax('cat-selector',
                compact('tecdocSearch'))
        ]);
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
            $phone = (new User)->getPhone($_REQUEST['UnregisteredUser']['phone']);
            $shop_text = '<p><b>Незарегистрированный пользователь</b> сделал заказ. Содержание заказа : </p>';
        } else {
            $order->user_id = Yii::$app->user->id;
            $phone = Yii::$app->user->identity->getPhone();
            $shop_text = '<p>Пользователь  <b>' . Yii::$app->user->identity->email . '</b> сделал заказ. Содержание заказа : </p>';
        }



        $order->summ = $cart->getSumm();
        $products = $cart->getCart();
        $order_content = '';
        $count = 1;

        foreach ($products as $id => $array) {
            $product = $this->findModel($id);
            $car_text='';
            if(!empty($array['car'])){
            $car_text='<i>'.$array['car'].'</i><br>';
            }
            $order_content .= '<p><b>' . $count . '. ' . $product->name . '</b> (' . $product->code . ') <br>' .$car_text. $array['qty'] . ' шт.  - ' . round($product->getDiscountPrice() * $array['qty'], 2) . ' грн</p>';
            $count++;
        }


        $order_content .= '<h3> Всего: ' . round($cart->getSumm(), 2) . ' грн</h3>';

         $order_content .= 'Телефон: <b>' .$phone.'</b>';
         $shop_text .= $order_content;

        $order->order_content = $order_content;
        $order->save();

        if (!Yii::$app->user->isGuest) {
            $user_text = '<p>Вы сделали заказ (№'.$order->id.') на сайте <b>opora.dn.ua</b>. Содержание заказа : </p>' . $order_content;

            Yii::$app->mailer->compose()
                ->setTo(Yii::$app->user->identity->email)
                ->setFrom(['mail@opora.dn.ua' => 'Opora'])
                ->setSubject('Заказ № ' . $order->id)
                ->setHtmlBody($user_text)
                ->send();
        }

        Yii::$app->mailer->compose()
            ->setTo('ketovnv@gmail.com')
            ->setFrom(['mail@opora.dn.ua' => 'Opora'])
            ->setSubject('Заказ № ' . $order->id)
            ->setHtmlBody($shop_text)
            ->send();

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
