<?php
use yii\helpers\Html;
?>
<header id="site-header">
    <img id="site-header-logo" class="img-rounded" src="/images/main/logo.jpg">
    <?php echo $this->render('_header_text_search'); ?>
    <?php
    $car_text='Выберите свой автомобиль';

    if(isset($_COOKIE['car']) && !empty($car = unserialize($_COOKIE['car'], ["allowed_classes" => false]))) {
        $car_text=$car['car_name'];
        if ($car['year']){
            $car_text .=', '.$car['year'].' г.в.';
        }
    }

    echo "<h4 class='header-car'>" .Html::a($car_text,'/car'). "</h4>";
    ?>
</header>





