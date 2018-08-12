<?php
/**
 * Events management controller view form view
 *
 * @see \app\controllers\mgmt\EventsMgmtController::actionCreate()
 * @see \app\controllers\mgmt\EventsMgmtController::actionUpdate()
 *
 * @var yii\web\View $this
 * @var app\modules\events\models\Event $model
 */

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$horizontalFieldConfig = [
    'labelOptions' => ['class' => 'col-sm-4 control-label'],
    'template'     => "{label}<div class=\"col-sm-8\">{input}\n{hint}\n{error}</div>"
];
?><div class="company-form">
    <div class="clearfix"></div>
    <hr/>
    <?php $form = ActiveForm::begin(); ?>
    <div class="form-horizontal">
        <?= $form->field($model, 'about', $horizontalFieldConfig)->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'event_type_id', $horizontalFieldConfig)
                 ->label(Yii::t('app.modules.events.Event', 'Type'))
                 ->dropDownList(
                     \app\base\models\EventSearch::getEventTypeFilterValues(false, false)
                 ) ?>
        <?= $form->field($model, 'status', $horizontalFieldConfig)->radioList(
            $model->statuses,
            [
                'class' => 'radio'
            ]
        ) ?>
        <?= $form->field($model, 'location', $horizontalFieldConfig)->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'location_degrees', $horizontalFieldConfig)->textInput(['maxlength' => true]) ?>
        <?= $form->field($model, 'description', $horizontalFieldConfig)->textarea(['maxlength' => true]) ?>
    </div>
    <div class="row">
    <div class="row">
        <div class="col-sm-offset-4 col-sm-8">
            <div class="form-group">
                <?= Html::submitButton(Yii::t('app.common', 'Save'), ['class' => 'btn btn-success']) ?>
            </div>
        </div>
    </div>
    <?php ActiveForm::end(); ?>
</div>
