<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \common\models\LoginForm */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Вход';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Для выполнения входа, заполните, пожалуйста, следующие поля:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'login-form']); ?>

            <?= $form->field($model, 'email')->textInput([
                'class' => 'input input-login input-border',
                'placeholder' => 'Адрес электронной почты',
                ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'class' => 'input input-login input-border',
                'placeholder' => 'Пароль',
            ]) ?>

            <?= $form->field($model, 'rememberMe')->checkbox([
                    'checked' => true
            ]) ?>

                <div style="color:#999;margin:1em 0">
                    Если Вы забыли пароль, Вы можете
                    <strong><?= Html::a('восстановить его', ['site/request-password-reset']) ?></strong>.
                </div>

                <div style="color:#999;margin:1em 0">
                    Если Вы не зарегистрированы, Вы можете
                    <strong><?= Html::a('зарегистрироваться', ['site/signup']) ?></strong>.
                </div>

                <div class="form-group">
                    <?= Html::submitButton('Войти', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
                </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
