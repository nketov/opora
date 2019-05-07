<?php


//use common\models\Product;
$this->title = 'Акции';
?>
<div class="content-header"><?=$this->title ?></div>
<br>
<div class="page-content">
    <?=$content ?>
</div>

<!--<div class="container-actions">-->
<!--    --><?// if (!Yii::$app->user->isGuest) { ?>
<!--        <div class="actions-first">-->
<!--            <h1>Только для Вас:</h1>-->
<!--            <h2>Специальное предложение</h2>-->
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
//                foreach ($actions as $key => $action) {
//
//                    $product=Product::findOne($key);
//
//                    if (!empty($product->price)) { ?>
<!--                        <tr>-->
<!--                            <td style="padding:10px">--><?//= $product->name ?><!--</td>-->
<!--                            <td style="padding:10px">--><?//= $product->code ?><!--</td>-->
<!--                            <td style="padding:10px">--><?//= $product->price . '&nbsp;грн' ?><!--</td>-->
<!--                            <td style="padding:10px">--><?//= $action . '&nbsp;%' ?><!--</td>-->
<!--                            <td style="padding:10px;color: #00a157;text-align: right">-->
<!--                                <b>--><?//= $product->getDiscountPrice() . '&nbsp;грн' ?><!--</b></td>-->
<!--                            <td style="padding:10px">-->
<!--                                <button type="button" class="btn btn-primary btn-sm product-view"-->
<!--                                        data-id="--><?//= $product->id ?><!--" title="Просмотр"><i-->
<!--                                            class="fa fa-eye"></i>-->
<!--                                </button>-->
<!--                            </td>-->
<!--                        </tr>-->
<!---->
<!--                        --><?php
//                    }
//                } ?>
<!--            </table>-->
<!--            --><?php //if (array_key_exists('', $actions)) echo '<h4 style="color: #0D3349">Скидка на все остальные товары: <span style="color:#1EBB30;font-size: 3rem ">' . $actions[''] . '%</span></h4>' ?>
<!---->
<!--            <h3>Удачных покупок!</h3>-->
<!--        </div>-->
<!---->
<!---->
<!--    --><?php //} else { ?>
<!--        <div class="actions-first">-->
<!--            <h3>Регистрируйтесь <a href="/login"><i class="fa fa-user-plus" aria-hidden="true"></i></a> для того, чтобы-->
<!--                получить персональное предложение!</h3>-->
<!--            <p></p>-->
<!--        </div>-->
<!--    --><?php //} ?>
<!---->
<!---->
<!---->
<!--        --><?php //for ($i = 0; $i < 3; $i++) { ?>
<!--            <div>-->
<!--                <img src="/images/actions/--><?//= $contents[$i]->image_name ?><!--" alt="sliderImg" style="width: 100% "/>-->
<!---->
<!--                <h2 class="action-head">--><?//= $contents[$i]->header ?><!--</h2>-->
<!--                <p class="action-text">--><?//= $contents[$i]->text ?><!--</p>-->
<!--                <p class="action-content">--><?//= $contents[$i]->content ?><!--</p>-->
<!--            </div>-->
<!--        --><?php //} ?>
<!---->
<!---->
<!--</div>-->




