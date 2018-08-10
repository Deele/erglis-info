<?php
/**
 * Main (fluid) layout
 */

use app\widgets\Alert;
use yii\helpers\Html;
use app\assets\AppAsset;

/**
 * @var \yii\web\View $this
 * @var string $content
 * @var \app\base\web\Controller $context
 */
$context = $this->context;

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
    <title><?= Html::encode(strip_tags($context->title)) ?></title>
    <?= $this->render('_icons') ?>
    <?php $this->head() ?>
</head>
<body class="page page_main-fluid-layout">
<?php $this->beginBody() ?>
<div class="wrap">
    <?= $this->render('_header') ?>
    <div class="container-fluid">
        <?= \app\widgets\Heading::widget($context->headingWidgetOptions) ?>
        <?= Alert::widget() ?>
        <main class="page__content"><?= $content ?></main>
    </div>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
