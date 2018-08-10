<?php
/**
 * User controller "index" action view
 *
 * @see \app\controllers\UserController::actionIndex()
 *
 * @var yii\web\View $this
 */
?><div class="alert alert-info">Welcome <?= Yii::$app->user->identity->username ?></div>
