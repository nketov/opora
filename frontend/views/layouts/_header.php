<?php

use yii\helpers\Html;

?>
<header id="site-header">
    <img id="site-header-logo" class="img-rounded" src="/images/main/logo.jpg">

    <?= "<div id='header-contacts'>
            " . $siteContent->address . "
            <br>           
            Телефоны:<br>" . $siteContent->phones_header . "
            </div>";
    ?>

    <?= "<div id='header-time'>
            Мы работаем без выходных:<br>
            Понедельник - пятница: с 8-30 до 18-00<br>
            Суббота: с 8-30 до 16-00<br>
            Воскресенье: с 10-00 до 14-00<br>          
            </div>";
    ?>

    <?php
    $car_text = 'Выберите свой автомобиль';

    if (isset($_COOKIE['car']) && !empty($car = unserialize($_COOKIE['car'], ["allowed_classes" => false]))) {
        $car_text = $car['car_name'];
        if ($car['year']) {
            $car_text .= ', ' . $car['year'] . ' г.в.';
        }
    }

    echo "<h4 class='header-car'>" . Html::a($car_text, '/car') . "</h4>";


    ?>

    <h4 class='header-user'>
        <?php if (Yii::$app->user->isGuest) { ?>

            <a href="/login">Вход</a>
        <?php } else { ?>
            <a href="/cabinet"><i class="fa fa-user"></i>&nbsp;<?= Yii::$app->user->identity->email ?></a>
            <br>
            <a href="/site/logout">Выход</a>
        <?php } ?>
        <?=$this->render('_header_text_search');?>
    </h4>



</header>





