<? use app\models\UserCars;
use common\models\Product;
use common\models\Order;
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\widgets\MaskedInput;
use yii\widgets\Pjax;
use yii\grid\GridView;
use common\models\Post;

$this->title = 'Мой кабинет';


if (!Yii::$app->user->isGuest) {
?>
<div class="container-cabinet">
    <div>
        <h2>Мои объявления:</h2>
        <?php Pjax::begin(); ?>

        <?= GridView::widget([
            'dataProvider' => $postProvider,
            'showHeader' => false,
            'tableOptions' =>
                ['class' => 'table table-striped'],
            'columns' => [
//            ['class' => 'yii\grid\SerialColumn'],
                [
                    'attribute' => 'title',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'width:50%'],
                    'value' => function ($data) {
                        return Html::a($data->title, '/post/' . $data->id);
                    }
                ],

                [
                    'attribute' => 'time',
                    'contentOptions' => ['style' => 'text-align:right'],
                    'value' => function ($data) {
                        return Yii::$app->formatter->asDate($data->time);
                    }
                ],
                [
                    'attribute' => 'status',
                    'format' => 'raw',
                    'contentOptions' => ['style' => 'text-align:right'],
                    'value' => function ($data) {
                        return '<b>' . Post::getStatuses()[$data->status] . '</b>';
                    }
                ],

//            ['class' => 'yii\grid\ActionColumn'],
            ],
            'layout' => '{items}{pager}',
        ]); ?>
        <?php Pjax::end(); ?>
        <p>
            <?= Html::a('Разместить объявление', ['/post/create'], ['class' => 'btn btn-success pull-right', 'style' => 'z-index:1000']) ?>
        </p>
        <div>
            <h2>Мой номер телефона:</h2>
            <h3 style="padding-left:25px">
                <?= Yii::$app->user->identity->getPhone() ?> <a href="" title="Изменить">
                    <i class="fa fa-pencil-square-o phone-change" aria-hidden="true"></i>
                </a>
            </h3>
            <h2>Ф.И.О.:</h2>
            <h3 style="padding-left:25px">
                <?= Yii::$app->user->identity->FIO ?> <a href="" title="Изменить">
                    <i class="fa fa-pencil-square-o phone-change" aria-hidden="true"></i>
                </a>
            </h3>
        </div>

    </div>


    <?php if (!empty($lastOrders)) { ?>
        <div>
            <h2>Последние заказы:</h2>
            <table class="table table-hover table-responsive table-striped">
                <?php foreach ($lastOrders as $order) {
                    ?>
                    <tr>
                        <td style="padding:10px">№ <?= $order->id ?></td>
                        <td style="padding:10px"><?= Yii::$app->formatter->asDate($order->date) ?></td>
                        <td style="padding:10px">
                            <b><?= Order::getStatuses()[$order->status] ?></b></td>
                    </tr>
                    <?php
                } ?>
            </table>
        </div>
    <?php } ?>


    <div>
        <h2>Мой гараж:</h2>
        <table class="table table-hover table-striped garage">
            <?php for ($i = 1; $i <= 5; $i++) {
                $user_car = UserCars::find()->where([
                    'position' => $i,
                    'user_id' => \Yii::$app->getUser()->id
                ])->one();
                $link=Html::a('Добавить текущий автомобиль', '', ['class' => 'add-garage']);
                $delete= '';
                if ($user_car){
                    if ($user_car['year']) {
                        $user_car['car_name'] .= ', ' . $user_car['year'] . ' г.в.';
                    }
                    $link = Html::a($user_car['car_name'], '/', ['class' => 'choose-garage']);
                    $delete= Html::a(Html::tag('span', '', ['class' => 'glyphicon glyphicon-trash']), ['/'], ['class' => 'btn btn-black delete-garage', 'title' => 'Удалить автомобиль']);
                }

                ?>
                <tr data-key="<?= $i ?>">
                    <td style="padding:10px"> <?= $link ?></td>
                    <td style="padding:10px;text-align: right "><?= $delete ?></td>
                </tr>
                <?php
            } ?>
        </table>
    </div>


    <!--        <div>-->
    <!--            <h2>Специальное предложение:</h2>-->
    <!--            <table class="table table-hover table-responsive table-striped actions">-->
    <!--                <thead class="thead-dark">-->
    <!--                <tr>-->
    <!--                    <th>Товар</th>-->
    <!--                    <th>Код</th>-->
    <!--                    <th>Цена</th>-->
    <!--                    <th>Скидка</th>-->
    <!--                    <th style="padding:10px;color: #00a157;text-align: right">Цена со скидкой</th>-->
    <!--                    <th></th>-->
    <!--                </tr>-->
    <!--                </thead>-->
    <!---->
    <!--                --><?php
    //                if (!empty($actions))
    //                    foreach ($actions as $key => $action) {
    //
    //                        $product = Product::findOne($key);
    //
    //                        if (!empty($product->price)) { ?>
    <!--                            <tr>-->
    <!--                                <td style="padding:10px">--><? //= $product->name ?><!--</td>-->
    <!--                                <td style="padding:10px">--><? //= $product->code ?><!--</td>-->
    <!--                                <td style="padding:10px">--><? //= $product->price . '&nbsp;грн' ?><!--</td>-->
    <!--                                <td style="padding:10px">--><? //= $action . '&nbsp;%' ?><!--</td>-->
    <!--                                <td style="padding:10px;color: #00a157;text-align: right">-->
    <!--                                    <b>--><? //= $product->getDiscountPrice() . '&nbsp;грн' ?><!--</b></td>-->
    <!--                                <td style="padding:10px">-->
    <!--                                    <button type="button" class="btn btn-primary btn-sm product-view"-->
    <!--                                            data-id="--><? //= $product->id ?><!--" title="Просмотр"><i-->
    <!--                                                class="fa fa-eye"></i>-->
    <!--                                    </button>-->
    <!--                                </td>-->
    <!--                            </tr>-->
    <!---->
    <!--                            --><?php
    //                        }
    //                    } ?>
    <!--            </table>-->
    <!--            --><?php //if (!empty($actions) && array_key_exists('', $actions)) echo '<h4 style="color: #0D3349">Скидка на все остальные товары: <span style="color:#1EBB30;font-size: 3rem ">' . $actions[''] . '%</span></h4>' ?>
    <!---->
    <!--        </div>-->
    <!--    </div>-->


    <div id="phone-modal" class="modal" tabindex="-1" role="dialog">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <?php $form = ActiveForm::begin([
                    'enableClientValidation' => true,
                ]); ?>
                <div class="modal-header" style="text-align: center">
                    <h2 class="modal-title">Мой номер телефона и Ф.И.О.</h2>
                </div>
                <div class="modal-body" style="font-weight:  bold">
                    <?= $form->field($user, 'phone')->label(false)->widget(\yii\widgets\MaskedInput::className(), [
                        'mask' => '+38 (099) 999 99 99',
                        'clientOptions' => [
                            'removeMaskOnSubmit' => true,
                        ]
                    ])->textInput(['style' => 'width:165px;margin: 0 auto;']);
                    ?>
                    <?= $form->field($user, 'FIO')->textInput([
                        'placeholder' => 'Фамилия Имя Отчество',
                        'style' => 'width:325px;margin: 0 auto; text-align:center'
                    ])->label(false) ?>
                </div>
                <div class="modal-footer" style="text-align: center">
                    <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']) ?>
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Отмена</button>
                </div>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>

    <?php } ?>
				

