<?php

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */
/* @var $model \frontend\models\SignupForm */
/* @var $profile \frontend\models\Profile */

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

$this->title = 'Регистрация';
$this->params['breadcrumbs'][] = ['label' => 'Вход', 'url' => 'login'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-signup">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Пожалуйста, заполните следующие поля для регистрации:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(['id' => 'form-signup']); ?>

            <?= $form->field($model, 'email')->textInput([
                'class' => 'input input-login input-border',
                'type' => 'mail',
                'placeholder' => 'Адрес электронной почты',
            ]) ?>

            <?= $form->field($profile, 'name')->textInput([
//                'pattern' => '^[0-9a-zA-ZА-Яа-яЁё\s]+$',
                'class' => 'input input-login input-border',
                'placeholder' => 'Имя пользователя',
            ]) ?>

            <?= $form->field($model, 'password')->passwordInput([
                'class' => 'input input-login input-border',
                'placeholder' => 'Пароль',
            ]) ?>

            <?= $form->field($model, 'rePassword')->passwordInput([
                'class' => 'input input-login input-border',
                'placeholder' => 'Повторите пароль',
            ]) ?>

            <div class="form-group">
                <?= Html::submitButton('Готово', ['class' => 'btn btn-primary', 'name' => 'signup-button']) ?>
            </div>

            <?php ActiveForm::end(); ?>
        </div>
    </div>
</div>
