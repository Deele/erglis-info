<?php
/**
 * Management layout
 */

use app\widgets\Alert;
use yii\helpers\Html;
use yii\widgets\Breadcrumbs;
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
<body class="page page_management-layout">
<?php $this->beginBody() ?>
<div class="wrap">
    <?= $this->render('_header') ?>
    <div class="container-fluid">
        <?= \app\widgets\Heading::widget($context->headingWidgetOptions) ?>
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <div class="row">
            <div class="col-sm-2">
                <?= \yii\bootstrap\Nav::widget([
                    'items' => [
                        [
                            'label' => 'Home',
                            'url' => ['site/index'],
                        ],
                        [
                            'label' => 'Dashboard',
                            'url' => ['mgmt/management/index'],
                        ],
                    ],
                ]) ?>
            </div>
            <div class="col-sm-10">
                <header>
                    <h1></h1>
                    <?= Alert::widget() ?>
                </header>
                <main><?= $content ?></main>
            </div>
        </div>
    </div>
    <?= $this->render('_footer') ?>
</div>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
