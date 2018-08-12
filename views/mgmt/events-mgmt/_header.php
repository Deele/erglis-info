<?php
/**
 * Events management controller header view
 *
 * @see \app\controllers\mgmt\EventsMgmtController
 *
 * @var yii\web\View $this
 * @var app\modules\events\models\Event $model
 * @var \app\controllers\mgmt\EventsMgmtController $context
 */

$context = $this->context;
if (!isset($model)) {
    $model = null;
}

/** @noinspection CssUnusedSymbol */
$this->registerCss(
    <<<CSS
.model_class {
    float: right;
    margin: 0;
}
CSS
);
?>
<?= $this->render('_menu', ['activate' => 'events']) ?>
<h2 class="model_class"><?= Yii::t('app.modules.events.Event', 'Events') ?></h2>
<?php if (!is_null($model)): ?>
    <h3>
        <span class="model_title"><?= $model->about ?></span>
        <span class="model_id badge"><?= $model->id ?></span>
    </h3>
<?php endif; ?>
