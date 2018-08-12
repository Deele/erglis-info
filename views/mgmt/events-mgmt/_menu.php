<?php
/**
 * Events management controller menu view
 *
 * @see \app\controllers\mgmt\EventsMgmtController::actionIndex()
 *
 * @var yii\web\View $this
 * @var string $activate
 */

/** @noinspection CssUnusedSymbol */
$this->registerCss(
    <<<CSS
.events-mgmt-nav {
    margin-bottom: 10px;
}
CSS
);

if (!isset($activate) || is_null($activate)) {
    $activate = '';
}

$items = [];

// Events
$item = [
    'label' => 'Events',
    'url'   => ['mgmt/events-mgmt/index'],
];
if ($activate === 'events') {
    $item['active'] = true;
}
$items[] = $item;

?>
<?= \yii\bootstrap\Nav::widget([
    'options' => [
        'class' => 'events-mgmt-nav nav nav-tabs',
    ],
    'items'   => $items
]) ?>
