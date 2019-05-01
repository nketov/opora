<?php

use yii\helpers\Html;
use common\components\TecDoc;

?>
<header id="site-header">
    <a href="#" class="menu-btn btn btn-overlay">
        <span></span>
    </a>
    <div class="menu-overlay">
        <?= Html::a('О нас', '/about', ['class' => 'btn']) ?>
        <?= Html::a('Акции', '/actions', ['class' => 'btn']) ?>
        <?= Html::a('Объявления', '/post', ['class' => 'btn']) ?>
        <?= Html::a('Статьи', '/articles', ['class' => 'btn']) ?>
        <?= Html::a('Контакты', '/contact', ['class' => 'btn']) ?>
        <?= Html::a('Доставка', '/delivery', ['class' => 'btn']) ?>
        <?= Html::a('Оплата', '/payment', ['class' => 'btn']) ?>
        <?= Html::a('Гарантия', '/payment', ['class' => 'btn']) ?>
        <?= Html::a('Договора', '/guarantee ', ['class' => 'btn']) ?>
        <?= Html::a('Вакансии', '/vacancies', ['class' => 'btn']) ?>

    </div>

    <img id="site-header-logo" class="img-rounded center-block" src="/images/main/logo.jpg">
    <div id="site-header-name">А в т о м а г а з и н &nbsp;&nbsp;&nbsp; О П О Р А</div>
    <div id="site-header-icons">
            <a href="" class="twitter"><i class="fa fa-twitter"></i></a>
            <a href=""  class="facebook"><i class="fa fa-facebook"></i></a>
            <a href="" class="google"><i class="fa fa-google-plus"></i></a>

<!--        --><?//= Html::img('/images/icons/kyivstar.png') . Html::img('/images/icons/kyivstar.png') . Html::img('/images/icons/kyivstar.png')  ?>
    </div>

    <h4 class='header-user'>
        <?php if (Yii::$app->user->isGuest) { ?>
            <a class="btn" href="/login">Вход</a>
        <?php } else { ?>
            <a class="btn"  href="/cabinet"><i class="fa fa-user"></i>&nbsp;<?= Yii::$app->user->identity->email ?>
                <a class="btn" href="/site/logout"></i>Выход</a>
            </a>
        <?php } ?>
    </h4>


    <?= "<div id='header-phones'><span>"
    . Html::img('/images/icons/kyivstar.png') .
    "&nbsp;+38 (067) 580 97 61</span><span>"
    . Html::img('/images/icons/voodafone.png') .
    "&nbsp;+38 (066) 529 12 88
            </span></div>"; ?>


    <?= "<div id='header-buttons'>" ?>
    <?= Html::a('Продать</br>запчасть', '/post/create', ['class' => 'btn']) ?>
    <?= Html::a(Html::img('/images/leyka_white.png'), '/car', ['class' => 'btn leyka']) ?>
    <?= Html::a('Сделать </br> заказ', '/contact', ['class' => 'btn']) ?>
    <?= "</div>"; ?>

</header>