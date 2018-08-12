<?php
/**
 * Events management controller view action view
 *
 * @see \app\controllers\mgmt\EventsMgmtController::actionView()
 *
 * @var yii\web\View $this
 * @var app\modules\events\models\Event $model
 */

use yii\helpers\Html;
use yii\widgets\DetailView;

?><div class="event-view">
    <?= $this->render('_header') ?>
    <p>
        <?= Html::a(Yii::t('app.common', 'Update'), ['update', 'id' => $model->id], ['class' => 'btn btn-primary']) ?>
        <?= Html::a(Yii::t('app.common', 'Delete'), ['delete', 'id' => $model->id], [
            'class' => 'btn btn-danger',
            'data' => [
                'confirm' => Yii::t('app.common', 'Are you sure you want to delete this item?'),
                'method' => 'post',
            ],
        ]) ?>
    </p>
    <?= DetailView::widget([
        'model' => $model,
        'attributes' => [
            'id',
            'about',
            'description',
            'location',
            'statusTitle',
            'typeTitle',
            'location_degrees',
            'begin_at',
            'end_at',
            'created_at',
            'updated_at',
        ],
    ]) ?>
</div>
