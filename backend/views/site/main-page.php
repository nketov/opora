<?php
$this->title = 'Товары на главной странице: ';
?>

<div class="box ">
    <div class="box-body">
        <div id="grid-main-page">
            <?php foreach ($models as $model) {
                echo '<div data-key="' . $model->id . '" data-toggle="modal" data-target="#main-page-modal">';

                echo $this->render('_main_img', [
                    'product' => $model->product,
                ]);

                echo '</div>';
            } ?>
        </div>
    </div>
</div>

