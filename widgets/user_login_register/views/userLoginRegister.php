<?php
/**
 * User login & register widget "login" view
 *
 * @see \app\widgets\user_login_register\UserLoginRegister::run()
 *
 * @author Nils (Deele) <deele@tuta.io>
 *
 * @var yii\web\View $this
 * @var $context \app\widgets\user_login_register\UserLoginRegister
 */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$context = $this->context;
?><div class="row">
    <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-0">
        <h2 class="hidden-lg text-center">Login</h2>
        <?php $form = ActiveForm::begin([
            'id' => 'login-form',
        ]); ?>
        <?= $form->field($context->loginModel, 'username')->textInput(['autofocus' => true]) ?>
        <?= $form->field($context->loginModel, 'password')->passwordInput() ?>
        <div class="form-group">
            <?= Html::submitButton('Login', ['class' => 'btn btn-style2', 'name' => 'login-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
    <div class="col-xs-12 col-xs-offset-0 col-sm-8 col-sm-offset-2 col-md-6 col-md-offset-3 col-lg-6 col-lg-offset-0">
        <h2 class="hidden-lg text-center">Register</h2>
        <?php $form = ActiveForm::begin([
            'id' => 'register-form',
        ]); ?>
        <?= $form->field($context->registerModel, 'username')->textInput() ?>
        <?= $form->field($context->registerModel, 'password')->passwordInput() ?>
        <?= $form->field($context->registerModel, 'password_repeat')->passwordInput() ?>
        <div class="term-and-conditions term-and-conditions_mini">
            <?= \app\widgets\terms_and_conditions\TermsAndConditions::widget() ?>
        </div>
        <?= $form->field($context->registerModel, 'accept_terms_conditions')->checkbox() ?>
        <?= $form->field($context->registerModel, 'accept_newsletters')->checkbox() ?>
        <div class="form-group">
                <?= Html::submitButton('Register', ['class' => 'btn btn-style2', 'name' => 'register-button']) ?>
        </div>
        <?php ActiveForm::end(); ?>
    </div>
</div>
