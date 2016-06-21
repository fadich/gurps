<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use frontend\models\Profile;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;

/* @var $this yii\web\View */
/* @var $model frontend\models\Profile */
/* @var $form ActiveForm */

$this->title = 'Профиль';
?>
<div class="site-login">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Информация о пользователе:</p>

    <div class="profile">
        <div class="row">
            <div class="col-lg-5">
                <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                <?= $form->field($model, 'name') ?>

                <?= $form->field($model, 'birthday')->widget(DatePicker::className(), [
                    'name' => 'birthday',
                    'type' => DatePicker::TYPE_INPUT,
                    'language' => 'ru',
                    'options' => ['placeholder' => '01.01.1900'],
                    'pluginOptions' => [
                        'format' => 'dd.mm.yyyy',
                        'todayHighlight' => false,
                        'autoclose' => true,
                    ]
                ]); ?>

                <?= $form->field($model, 'info')->textarea(); ?>

                <?= $form->field($model, 'sex')->radioList([
                    'мужской' => 'мужской', 'женский' => 'женский'
                ]) ?>

                <div class="form-group">
                    <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
                </div>
            </div>
            <div class="col-lg-1"></div>
            <div class="col-lg-3">
                <?= $form->field($model, 'file')->fileInput() ?>
                <?php
                if (isset($model->avatar)) {
                    echo '<img src="' . '/gurps/frontend/web/' . $model->avatar .
                        '" width="308px">&nbsp;&nbsp;&nbsp;';
                } ?>

                <?php ActiveForm::end(); ?>

                <?php ActiveForm::begin(); ?>

                <?= Html::submitButton('Удалить изображение', [
                    'name' => 'delete_avatar',
                    'class' => 'btn btn-warning btn-sm',
                    'value' => '1',
                ]) ?>
                <?php ActiveForm::end(); ?>
            </div>
        </div>
    </div>
</div>
