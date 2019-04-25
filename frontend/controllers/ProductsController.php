<?php

namespace frontend\controllers;

use app\models\UserCars;
use common\models\Order;
use common\models\ProductTextSearch;
use common\models\TecdocSearch;
use frontend\components\NovaPoshta;
use frontend\models\Cart;
use frontend\models\OrderForm;
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
        return $this->render('text_search', compact('searchModel', 'dataProvider'));

    }

    public function actionCarSearch()
    {

        if (!\Yii::$app->request->isAjax && !empty($_GET['category'])) {
            $this->redirect('car');
        }

        $tecdocSearch = new TecdocSearch();
        $user=\Yii::$app->user->identity;

        if (!empty($car=$user->car)) {
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

        $tecdocSearch = new TecdocSearch();
        $tecdocSearch->load($form, '');


        return Json::encode([
            'car_name' => $car_text,
            'select_render' => $this->renderAjax('cat-selector',
                compact('tecdocSearch'))
        ]);
    }

    public
    function actionDeleteCar()
    {
        unset($_COOKIE['car']);
        setcookie('car', null, -1, '/');
        return $this->redirect('/car');
    }

    public
    function actionAddGarage($position)
    {
        $user = \Yii::$app->user->identity;
        $res = 'NULL';
        if ( !empty($car = $user->car)) {

            if (!$user_car = UserCars::find()->where([
                'position' => $position,
                'user_id' => $user->id
            ])->one()) {
                $user_car = new UserCars();
                $user_car->user_id = $user->id;
                $user_car->position = $position;
            }

            $user_car->load($car, '');

            if ($user_car->save()) {
                if ($car['year']) {
                    $car['car_name'] .= ', ' . $car['year'] . ' г.в.';
                }
                $res = Html::a($car['car_name'], '/', ['class' => 'choose-garage']);
            }
        }

        return Json::encode([
            'link' => $res,
            'delete' => Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), ['/'], ['class' => 'btn btn-black delete-garage', 'title' => 'Удалить автомобиль'])
        ]);

    }

    public
    function actionDeleteGarage($position)
    {
        $user_id = \Yii::$app->getUser()->id;

        $user_car = UserCars::find()->where([
            'position' => $position,
            'user_id' => $user_id
        ])->one();

        $user_car->delete();

        return Html::a('Добавить текущий автомобиль', '', ['class' => 'add-garage']);

    }

    public
    function actionChooseGarage($position)
    {

        unset($_COOKIE['car']);
        setcookie('car', null, -1, '/');
        $user_id = \Yii::$app->getUser()->id;

        $user_car = UserCars::find()->where([
            'position' => $position,
            'user_id' => $user_id
        ])->one();

        setcookie("car", serialize($user_car->attributes), time() + 3600 * 24 * 100, '/');

        if ($user_car->year) {
            $user_car->car_name .= ', ' . $user_car->year . ' г.в.';
        }
        return $user_car->car_name;
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
        $orderForm = new OrderForm();
        $orderForm->load(\Yii::$app->request->post());


        $xml = new \DOMDocument('1.0', 'windows-1251');
        $xml_order = $xml->appendChild($xml->createElement('Order'));
        $xml_order_id = $xml_order->appendChild($xml->createElement('Id'));
        $xml_user = $xml_order->appendChild($xml->createElement('User'));

        if (Yii::$app->user->isGuest) {
            $order->user_id = 0;
            $shop_text = '<p style="font-size: 1.25em"><b>Незарегистрированный пользователь</b> сделал заказ. Содержание заказа : </p>';
            $xml_user->appendChild($xml->createTextNode('Незарегистрированный пользователь'));
        } else {
            $order->user_id = Yii::$app->user->id;
            $shop_text = '<p style="font-size: 1.25em">Пользователь  <b>' . Yii::$app->user->identity->email . '</b> сделал заказ. Содержание заказа : </p>';
            $xml_user->appendChild($xml->createTextNode(Yii::$app->user->identity->email));
        }


        $order->summ = $cart->getSumm();
        $products = $cart->getCart();
        $order_content = '<div style="display: inline-block;background: linear-gradient(to bottom,#FAF7F0,#EEE); border-radius: 10px; padding: 15px">';
        $count = 1;


        $xml_products = $xml_order->appendChild($xml->createElement('Products'));
        foreach ($products as $id => $array) {
            $xml_product = $xml_products->appendChild($xml->createElement('Product'));
            $product = $this->findModel($id);


            $xml_product_name = $xml_product->appendChild($xml->createElement('Name'));
            $xml_product_name->appendChild($xml->createTextNode($product->name));
            $xml_product_code = $xml_product->appendChild($xml->createElement('Code'));
            $xml_product_code->appendChild($xml->createTextNode($product->code));
            $xml_product_qty = $xml_product->appendChild($xml->createElement('Quantity'));
            $xml_product_qty->appendChild($xml->createTextNode($array['qty']));
            $xml_product_price = $xml_product->appendChild($xml->createElement('Price'));
            $xml_product_price->appendChild($xml->createTextNode(round($product->getDiscountPrice() * $array['qty'], 2)));


            $car_text = '';
            if (!empty($array['car'])) {
                $car_text = '<i style="font-size: .7em">' . $array['car'] . '</i><br>';
                $xml_product_car = $xml_product->appendChild($xml->createElement('Car'));
                $xml_product_car->appendChild($xml->createTextNode($array['car']));
            }

            $order_content .=
                '<p><b>' . $count . '. <span color="#163">' . $product->name . '<span></b> (' . $product->code . ') <br>' .
                $car_text . $array['qty'] . ' ' . $product->unit . '  - <b>' . Yii::$app->formatter->asDecimal(round($product->getDiscountPrice() * $array['qty'], 2)) . '</b> грн</p>';
            $count++;
        }

        $xml_total_price = $xml_order->appendChild($xml->createElement('TotalPrice'));
        $xml_total_price->appendChild($xml->createTextNode(round($cart->getSumm(), 2)));

        $xml_phone = $xml_order->appendChild($xml->createElement('Phone'));
        $xml_phone->appendChild($xml->createTextNode('0'.$orderForm->phone));
        $xml_FIO = $xml_order->appendChild($xml->createElement('FIO'));
        $xml_FIO->appendChild($xml->createTextNode($orderForm->FIO));
        $xml_delivery = $xml_order->appendChild($xml->createElement('Delivery'));
        $xml_delivery->appendChild($xml->createTextNode(OrderForm::deliveryName($orderForm->delivery)));

        $order_content .= '<h3 style="color:#9f191f"> Всего: ' . Yii::$app->formatter->asDecimal(round($cart->getSumm(), 2)) . ' грн</h3>';
        $order_content .= 'Телефон: <b>' . (new User)->getPhone($orderForm->phone) . '</b><br>';
        $order_content .= 'Ф.И.О.: <b>' . $orderForm->FIO . '</b><br>';
        $order_content .= 'Способ доставки: <b>' . OrderForm::deliveryName($orderForm->delivery) . '</b><br>';
        if ($orderForm->delivery == OrderForm::DELIVERY_NOVA_POSHTA) {

            $region=$orderForm->NP->getAreaNameRu($orderForm->region_id);
            $city=$orderForm->NP->getCityNameRu($orderForm->city_id);
            $wh= $orderForm->NP->getWarehouseNameRu($orderForm->city_id, $orderForm->warehouse_id);

            $order_content .= 'Область: <b>' .$region  . '</b><br>';
            $order_content .= 'Город: <b>' .$city  . '</b><br>';
            $order_content .= 'Отделение: <b>' .$wh . '</b><br>';

            $xml_region = $xml_order->appendChild($xml->createElement('Region'));
            $xml_region->appendChild($xml->createTextNode($region));
            $xml_city = $xml_order->appendChild($xml->createElement('City'));
            $xml_city->appendChild($xml->createTextNode($city));
            $xml_wh = $xml_order->appendChild($xml->createElement('Warehouse'));            $xml_wh->appendChild($xml->createTextNode($wh));

        }
        if ($orderForm->delivery == OrderForm::DELIVERY_COURIER) {
            $order_content .= 'Адрес доставки: <b>' . $orderForm->courier_address . '</b><br>';
            $xml_address = $xml_order->appendChild($xml->createElement('Address'));
            $xml_address->appendChild($xml->createTextNode($orderForm->courier_address));
        }

        $order_content .= '</div>';
        $shop_text .= $order_content;

        $order->order_content = $order_content;
        $order->save();

        $xml_order_id->appendChild(
            $xml->createTextNode($order->id));

        $xml->formatOutput = true;
        $content = $xml->saveXML();
        $xml->save(Url::to('@backend/1C_files/orders/order_'.$order->id.'.xml'));



        if (!Yii::$app->user->isGuest) {
            $user_text = '<p style="font-size: 1.25em">Вы сделали заказ (№' . $order->id . ') на сайте <b>opora.dn.ua</b>. Содержание заказа : </p>' . $order_content;

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


    //    ---------------------ORDER_DROPDOWNs-----------------


    public function actionNpCityDropDown()
    {

        $data = $_POST['depdrop_all_params'];

        $np = new NovaPoshta();
        $list = $np->getCitiesByArea($data['np_region']);
        $out = [];
        foreach ($list as $item) {
            $out[] = ['id' => $item['Ref'], 'name' => $item['DescriptionRu']];
        }

        return Json::encode(['output' => $out, 'selected' => $_GET['city_id']]);
    }

    public function actionNpWarehouseDropDown()
    {

        $data = $_POST['depdrop_all_params'];

        $np = new NovaPoshta();

        $out = [];
        if (!empty($data['np_city'])) {
            $list = $np->getWarehouses($data['np_city'])['data'];
            foreach ($list as $item) {
                $out[] = ['id' => $item['Ref'], 'name' => $item['DescriptionRu']];
            }
        }
        return Json::encode(['output' => $out, 'selected' => $_GET['warehouse_id']]);
    }


    protected function findModel($id)
    {
        if (($model = Product::findOne($id)) !== null) {
            return $model;
        }
        throw new NotFoundHttpException('The requested page does not exist.');
    }
}
