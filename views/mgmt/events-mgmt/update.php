<?php
/**
 * Events management controller update action view
 *
 * @see \app\controllers\mgmt\EventsMgmtController::actionUpdate()
 *
 * @var yii\web\View $this
 * @var app\modules\events\models\Event $model
 */
?><div class="event-update">
    <?= $this->render('_header', ['model' => $model]) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
