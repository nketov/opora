<?php

use yii\helpers\Html;

/* @var $this \yii\web\View */
/* @var $content string */


if (Yii::$app->controller->action->id === 'login') {
    /**
     * Do not use this code in your template. Remove it.
     * Instead, use the code  $this->layout = '//main-login'; in your controller.
     */
    echo $this->render(
        'main-login',
        ['content' => $content]
    );
} else {

    if (class_exists('backend\assets\AppAsset')) {
        backend\assets\AppAsset::register($this);
    } else {
        app\assets\AppAsset::register($this);
    }

    dmstr\web\AdminLteAsset::register($this);

    $directoryAsset = Yii::$app->assetManager->getPublishedUrl('@vendor/almasaeed2010/adminlte/dist');
    ?>
    <?php $this->beginPage() ?>
    <!DOCTYPE html>
    <html lang="<?= Yii::$app->language ?>">
    <head>
        <meta charset="<?= Yii::$app->charset ?>"/>
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <?= Html::csrfMetaTags() ?>
        <link rel="icon" type="image/png" href="/images/main/logo_32.jpg" sizes="32x32">
        <title><?= Html::encode($this->title) ?></title>
        <?php $this->head() ?>
    </head>
    <body class=" <?= \dmstr\helpers\AdminLteHelper::skinClass() ?> hold-transition sidebar-mini">
    <?php $this->beginBody() ?>
    <div id="upload-overlay">
        <div>Подождите, пожалуйста, идёт загрузка и обработка файла, это может занять несколько минут...</div>
        <img src="/admin/images/uploader2.gif" alt=""></div>
    <div class="wrapper">


        <?= $this->render(
            'header.php',
            ['directoryAsset' => $directoryAsset]
        ) ?>

        <?= $this->render(
            'left.php',
            ['directoryAsset' => $directoryAsset]
        )
        ?>

        <?= $this->render(
            'content.php',
            ['content' => $content, 'directoryAsset' => $directoryAsset]
        ) ?>

    </div>

    <?php if (Yii::$app->controller->action->id == 'image-upload') {
        echo $this->render('_image_delete_modal.php');
    }?>

    <?php if (Yii::$app->controller->action->id == 'main-page') {
        echo $this->render('_main_page_modal.php');
    }?>


    <?php $this->endBody() ?>
    </body>
    </html>
    <?php $this->endPage() ?>
<?php } ?>
