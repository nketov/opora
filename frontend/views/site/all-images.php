<div>
    <?php

    use yii\helpers\Html;
    use yii\helpers\Url;

    foreach ($products as $product) {
        if (!empty($product->images)) {

            echo '<h1 style="font-weight: bold">' . $product->name . '</h1>';

            foreach (explode(';',$product->images) as $image) {

                echo Html::img('/images/1C_images/' . $image);
                echo '<br>';
            }

            echo '<hr>';
            echo '<hr>';
            echo '<hr>';
        }
    }

    ?>
</div>


