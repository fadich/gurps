<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use kartik\date\DatePicker;
use yii\bootstrap\Modal;
use skeeks\yii2\ckeditor\CKEditorWidget;
use skeeks\yii2\ckeditor\CKEditorPresets;

/* @var $this yii\web\View */
/* @var $model frontend\models\Profile */
/* @var $file frontend\models\Files */
/* @var $form ActiveForm */

$this->title = 'Профиль';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-profile">
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Информация о пользователе <i><?php echo $model->name; ?></i>:</p>

    <div class="profile">
        <div class="row">
            <div class="col-lg-12">
                <div class="row">
                    <div class="col-lg-5">
                        <?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>

                        <?= $form->field($model, 'name')->textInput(['pattern' => '^[0-9a-zA-ZА-Яа-яЁё\s]+$']) ?>

                        <?= $form->field($model, 'birthday')->widget(DatePicker::className(), [
                            'name' => 'birthday',
                            'type' => DatePicker::TYPE_INPUT,
                            'language' => 'ru',
                            'options' => [
                                'placeholder' => '01.01.1930 ‒ 12.12.2009',
                                'pattern' => '(0[1-9]|1[0-9]|2[0-9]|3[01]).(0[1-9]|1[012]).(1[9][3-9][0-9]|2[0][0][0-9])'
                            ],
                            'pluginOptions' => [
                                'format' => 'dd.mm.yyyy',
                                'todayHighlight' => false,
                                'autoclose' => true,
                            ]
                        ]); ?>

                        <?= $form->field($model, 'info')->widget(CKEditorWidget::className(), [
                            'options' => ['rows' => 6],
                            'preset' => CKEditorPresets::BASIC,
                        ]) ?>

                        <?= $form->field($model, 'sex')->radioList([
                            'мужской' => 'мужской', 'женский' => 'женский'
                        ]) ?>

                        <?= $form->field($file, 'file')->fileInput() ?>

                        <div class="form-group">
                            <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
                        </div>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-3">
                        <?php
                        if (isset($model->getAvatar()->one()->path)) {
                            echo '<img src="/' . $model->getAvatar()->one()->path .
                                '" width="308px">&nbsp;&nbsp;&nbsp;';
                        } else {
                            echo '<img src="' . '/uploads/pictures/avatars/no_avatar.png"
                            width="308px">&nbsp;&nbsp;&nbsp;';
                        } ?>

                        <?php ActiveForm::end(); ?>

                        <?php ActiveForm::begin();
                        if ($model->avatar != null): ?>
                            <?= Html::submitButton('Удалить изображение', [
                                'name' => 'delete_avatar',
                                'class' => 'btn btn-warning btn-sm',
                                'value' => '1',
                            ]) ?>
                        <?php endif;
                        ActiveForm::end(); ?>
                    </div>
                </div>
                <hr>
                <div class="row">
                    <div class="col-lg-5"><?php $model->created_at_date = date('d.m.Y', $model->created_at);
                        $model->updated_at_date = date('d.m.Y', $model->updated_at); ?>
                        <p><?= $form->field($model, 'updated_at_date')->
                            textInput(['readOnly' => true]) ?></p>
                        <p><?= $form->field($model, 'created_at_date')->
                            textInput(['readOnly' => true]) ?></p>
                    </div>
                    <div class="col-lg-1"></div>
                    <div class="col-lg-5">
                        <p><?= $form->field($model->getUser()->one(), 'email')->
                            textInput(['readOnly' => true]) ?></p>
                        <p><?= $form->field($model->getUser()->one(), 'auth_key')->
                            textInput(['readOnly' => true,]) ?></p>
                        <p align="right"><?php Modal::begin([
                                'header' => '<h2 align="center">Учетные данные</h2>',
                                'toggleButton' => [
                                    'label' => 'Редактировать учетные данные',
                                    'class' => 'btn btn-sm btn-default',
                                ],
                            ]);
                            ActiveForm::begin(); ?>

                            <?= $form->field($model->getUser()->one(), 'email')->textInput(); ?>

                            <?= $form->field($model->getUser()->one(), 'password')->passwordInput(); ?>

                            <?= $form->field($model->getUser()->one(), 'newPassword')->passwordInput(); ?>

                            <?= $form->field($model->getUser()->one(), 'rePassword')->passwordInput(); ?>

                        <div class="form-group">
                            <?= Html::submitButton('Сохранить', ['class' => 'btn btn-primary']); ?>
                        </div>
                        <?php ActiveForm::end();
                        Modal::end(); ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
