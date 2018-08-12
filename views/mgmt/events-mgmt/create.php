<?php
/**
 * Events management controller create action view
 *
 * @see \app\controllers\mgmt\EventsMgmtController::actionCreate()
 *
 * @var yii\web\View $this
 * @var app\modules\events\models\Event $model
 */
?><div class="event-create">
    <?= $this->render('_header', ['model' => $model]) ?>
    <?= $this->render('_form', [
        'model' => $model,
    ]) ?>
</div>
