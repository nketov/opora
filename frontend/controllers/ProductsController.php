<?php

namespace frontend\controllers;

use app\models\UserCars;
use common\components\TecDoc;
use common\models\Order;
use common\models\Post;
use common\models\PostSearch;
use common\models\PTS;
use common\models\Synonym;
use common\models\TecdocSearch;
use frontend\components\NovaPoshta;
use frontend\models\Cart;
use frontend\models\OrderForm;
use frontend\models\UnregisteredUser;
use yii\data\ActiveDataProvider;
use yii\helpers\Html;
use yii\helpers\Json;
use Yii;
use common\models\Product;
use common\models\PS;
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

    public function beforeAction($action)
    {

        ini_set('memory_limit', '4096M');
        return parent::beforeAction($action);
    }



    public function actionCategory($category)
    {

        $searchModel = new PS();
        $searchModel->category_code=$category;
        $dataProvider = $searchModel->search($_REQUEST);
        return $this->render('category', compact('products', 'searchModel', 'dataProvider'));

    }

    /**
     * @return string
     */
    public function actionTextSearch()
    {
        $searchModel = new PTS();
        $dataProvider = $searchModel->search($_REQUEST);

        $sellProvider = new ActiveDataProvider([
            'query' => Post::find()->andWhere(['type'=>0,'status'=>1]),
            'sort'=>array(
                'defaultOrder'=>['time' => SORT_DESC],
            ),
            'pagination' => [
                'pageSize' => 5,
            ],
        ]);



        $buyProvider = new ActiveDataProvider(array(
            'query' => Post::find()->andWhere(array('type'=>1,'status'=>1)),
            'sort'=>array(
                'defaultOrder'=> array('time' => SORT_DESC),
            ),
            'pagination' => array(
                'pageSize' => 5,
            ),
        ));

        $query_array = ['or',
            ['like', 'title', trim($searchModel->text)],
            ['like', 'article', trim($searchModel->text)],
            ['like', 'text', trim($searchModel->text)],
        ];

        $synonyms = Synonym::getSynonyms($searchModel->text);
        foreach ($synonyms as $syn) {
            $query_array[] = ['like', 'title', $syn];
            $query_array[] = ['like', 'text', $syn];
        }

        $buyProvider->query->andFilterWhere($query_array);
        $sellProvider->query->andFilterWhere($query_array);

        return $this->render('text_search', compact('searchModel', 'dataProvider','buyProvider','sellProvider'));

    }

    public function actionCarSearch()
    {

//        if (!\Yii::$app->request->isAjax && !empty($_GET['category'])) {
//            $this->redirect('car');
//        }

        $tecdocSearch = new TecdocSearch();
        $tecdocSearch->load($_REQUEST);

        if (!empty($car=TecDoc::getCookieCar())) {
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

        $car_render = '<h2>'. $car_text.'</h2>';
        $car_render .= TecDoc::getTypeInfo($form['mod_id'],$form['year'],$form['type_id']);

        $car_vin = !empty($form['vin']) ? "VIN-код:&nbsp;".$form['vin'] : "";

        return Json::encode([
            'car_name' => $car_text,
            'car_render' => $car_render,
            'car_vin' => $car_vin,
            'select_render' => $this->renderAjax('cat-selector',
                compact('tecdocSearch'))
        ]);
    }

    public
    function actionVinModal($position)
    {
        $user = \Yii::$app->user->identity;
        $user_car = UserCars::find()->where([
            'position' => $position,
            'user_id' => $user->id
        ])->one();


        return $this->renderAjax('vin-modal-content', compact('user_car'));

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



            if (!$user_car = UserCars::find()->where([
                'position' => $position,
                'user_id' => $user->id
            ])->one()) {
                $user_car = new UserCars();
                $user_car->user_id = $user->id;
            }


            $user_car->load(TecDoc::getCookieCar(), '');
            $user_car->position = $position;

            if ($user_car->save()) {
                if ($user_car->year) {
                    $user_car->car_name .= ', ' . $user_car->year . ' г.в.';
                }
                $res = Html::a($user_car->car_name, '/', ['class' => 'choose-garage']);
            }



        return Json::encode([
            'link' => $res,
            'vin' => Html::a($user_car->vin ?? "Укажите VIN-код", '/', ['class' => 'choose-vin']),
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


    public function actionLiqpay($id)
    {
        return $this->render('liqpay', [
            'model' => Order::findOne($id),
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

        $xml_date = $xml_order->appendChild($xml->createElement('Date'));
        $xml_date->appendChild($xml->createTextNode(date('d-m-Y G:i:s',time())));

        $xml_status = $xml_order->appendChild($xml->createElement('Status'));
        $xml_status->appendChild($xml->createTextNode('Новый'));


        $xml_phone = $xml_order->appendChild($xml->createElement('Phone'));
        $xml_phone->appendChild($xml->createTextNode('0'.$orderForm->phone));
        $xml_FIO = $xml_order->appendChild($xml->createElement('FIO'));
        $xml_FIO->appendChild($xml->createTextNode($orderForm->FIO));
        $xml_delivery = $xml_order->appendChild($xml->createElement('Delivery'));
        $xml_delivery->appendChild($xml->createTextNode(OrderForm::deliveryName($orderForm->delivery)));
        $xml_payment = $xml_order->appendChild($xml->createElement('Payment'));
        $xml_payment->appendChild($xml->createTextNode(OrderForm::paymentName($orderForm->payment)));

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
        $order_content .= 'Способ оплаты: <b>' . OrderForm::paymentName($orderForm->payment) . '</b><br>';
        $order_content .= '</div>';
        $shop_text .= $order_content;

        $order->order_content = $order_content;
        $order->save();

        $xml_order_id->appendChild(
            $xml->createTextNode($order->id));

        $xml->formatOutput = true;
        $content = $xml->saveXML();
        $xml->save(Url::to('@backend/1C_files/orders/order_'.$order->id.'.xml'));
        $xml->save(Url::to('@backend/logs/orders/order_'.$order->id.'.xml'));



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
            ->setTo(['ketovnv@gmail.com','mail@opora.dn.ua'])
//            ->setTo(['ketovnv@gmail.com'])
            ->setFrom(['mail@opora.dn.ua' => 'Opora'])
            ->setSubject('Заказ № ' . $order->id)
            ->setHtmlBody($shop_text)
            ->send();

        $cart->resetCart();
        Yii::$app->session->setFlash('success', 'Ваш заказ отправлен!');

        if($orderForm->payment == OrderForm::PAYMENT_LIQPAY){
            $this->redirect(Url::to(['/liqpay/'.$order->id]));
        } else {
            $this->redirect(Url::to(['/']));
        }
    }


    //    ---------------------ORDER_DROPDOWNs-----------------



    public function actionNpPaymentDropDown()
    {

        $data = $_POST['depdrop_all_params'];

        $out = [];
        foreach (OrderForm::paymentNamesList($data['orderform-delivery']) as $key=>$val) {
            $out[] = ['id' => $key, 'name' => $val];
        }

        return Json::encode(['output' => $out, 'selected' => $_GET['payment']]);
    }



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
