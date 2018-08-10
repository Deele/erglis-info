<?php
/**
 * Errors controller error action view
 *
 * @see \app\controllers\system\ErrorsController::actionError()
 */

use yii\helpers\Html;

/**
 * @var yii\web\View $this
 * @var string $name
 * @var string $message
 * @var Exception $exception
 */
?><div class="error-view container text-center">
    <h1><?= Html::encode($name) ?></h1>
    <div class="alert alert-danger"><?= nl2br(Html::encode($message)) ?></div>
    <p><?= Yii::t(
        'app.common.misc.ErrorMessages',
        'The above error occurred while the Web server was processing your request.'
    ) ?></p>
    <footer class="text-muted"><?= Yii::$app->name ?> @ <?= date('Y-m-d H:i:s') ?></footer>
</div>
