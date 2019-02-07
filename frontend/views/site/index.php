<div class="container-actions">


<?php foreach (\common\models\Product::find()->all() as $product){
    echo "<h2>".$product->name."</h2>";

}

    ?>
</div>
