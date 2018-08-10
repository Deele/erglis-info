<?php
/**
 * Blank layout
 */

use yii\helpers\Html;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 */

$lang = Yii::$app->language;
$charset = Yii::$app->charset;

AppAsset::register($this);

$this->beginPage();
?><!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="<?= $charset ?>">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode(strip_tags($this->title)) ?></title>
    <?= $this->render('_icons') ?>
    <?php $this->head() ?>
</head>
<body class="page page_blank-layout">
<?php $this->beginBody() ?>
<main class="page__content"><?= $content ?></main>
<?php $this->endBody() ?>
</body>
</html><?php
$this->endPage();
