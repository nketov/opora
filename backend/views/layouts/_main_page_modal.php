<?php use common\models\Product;
use kartik\select2\Select2; ?>

<div class="modal modal-info fade" id="main-page-modal" style="display:none; ">

    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title">Смена товара</h4>
            </div>
            <div class="modal-body">
                <?php echo Select2::widget([
                    'id' => 'main-page-select',
                    'name' =>'main-page',
                    'value' => '',
                    'data' => Product::getActiveCodesArray(),
                    'theme' => Select2::THEME_CLASSIC,
                    'options' => ['placeholder' => 'Выберите товар',],
                    'pluginOptions' => [
                        'allowClear' => true
                    ],
                ]); ?>
            </div>
            <div class="modal-footer">
                <button id='cancel' type="button" class="btn btn-outline pull-left" data-dismiss="modal">Отменить</button>
                <button id='confirm' type="button" class="btn btn-outline">Сохранить</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div>
