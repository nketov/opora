<?php

/* @var $this \yii\web\View */

/* @var $content string */

use yii\helpers\Html;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\Content;

AppAsset::register($this);
$siteContent = Content::findOne(1);

?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <?= $this->render('_head.php', compact('siteContent')) ?>
    <?= Html::csrfMetaTags() ?>
    <?php $this->head() ?>
</head>
<body class="wrap">
<?php $this->beginBody() ?>


<? //= $this->render('_cart.php') ?>
<?= $this->render('_header.php', compact('siteContent')) ?>
<? //= $this->render('_top_catalog.php') ?>


<main class="main-content">
    <?= Alert::widget() ?>
    <?= $content ?>
</main>

<?= $this->render('_footer.php', compact('siteContent')) ?>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
