<?php
use yii\helpers\Html;
?>
<header id="site-header">
    <img id="site-header-logo" class="img-rounded" src="/images/main/logo.jpg">

    <?= "<div id='header-contacts'>
            ".$siteContent->address."
            <br>
           
            Телефоны:<br>".$siteContent->phones_header."
            </div>";
    ?>

    <?= "<div id='header-time'>
            Мы работаем без выходных:<br><br>
            Понедельник - пятница: с 8-30 до 18-00<br>
            Суббота: с 8-30 до 16-00<br>
            Воскресенье: с 10-00 до 14-00<br>          
            </div>";
    ?>

    <?php
    $car_text='Выберите свой автомобиль';

    if(isset($_COOKIE['car']) && !empty($car = unserialize($_COOKIE['car'], ["allowed_classes" => false]))) {
        $car_text=$car['car_name'];
        if ($car['year']){
            $car_text .=', '.$car['year'].' г.в.';
        }
    }
    echo "<div></div>";
    echo "<h4 class='header-car'>" .Html::a($car_text,'/car'). "</h4>";
    echo $this->render('_header_text_search');

    ?>
</header>





