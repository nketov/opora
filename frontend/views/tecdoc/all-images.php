<div>
    <?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    foreach ($products as $product) {
        if (!empty($product->tecdoc_images)) {

            echo '<h1 style="font-weight: bold">' . $product->name . '</h1>';

            foreach ($product->tecdoc_images as $image) {

                echo Html::img('/images/Foto/' . $image['PATH']);
                echo '<br>';
                echo '<br>';
            }

            echo '<hr>';
            echo '<hr>';
            echo '<hr>';
        }
    }

    ?>
</div>


