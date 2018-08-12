<?php
/**
 * Site controller "event" action view
 *
 * @see \app\controllers\SiteController::actionEvent()
 *
 * @var yii\web\View $this
 * @var \app\modules\events\models\Event $event
 */

?><div class="site-event" style="margin-bottom: 30px;">
    <h2><?= $event->about ?></h2>
    <h3><?= $event->location ?></h3>
    <div><?= $event->description ?></div>
</div>
