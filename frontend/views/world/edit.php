<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model frontend\models\World */
/* @var $file frontend\models\Files */
/* @var $form ActiveForm */

isset($model->name) ? $this->title = 'Редактирование "' . $model->name . '"' : $this->title = 'Новый мир';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="world-edit">
    <h1><?= Html::encode('Мир') ?></h1>

    <p><?php isset($model->name) ? $str = 'Редактирование игрового мира <i>"' . $model->name . '"</i>' :
            $str = 'Создание нового игрового мира';
        echo $str ?>:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin();
            $checkOwner = (Yii::$app->user->id == $model->user_id || $model->user_id == null) ? false : true;
            ?>
            <?= $form->field($model, 'name')->textInput(['readOnly' => $checkOwner]) ?>
            <?= $form->field($model, 'description')->textarea(['readOnly' => $checkOwner]) ?>
            <?php if ($model->user_id != null): ?>
<!--                <strong>Принадлежит</strong>-->
                <?= $form->field($model->getOwner()->one(), 'name')->textInput(['readOnly' => true]) ?>
            <?php endif; ?>
            <div class="form-group">
                <?php if (!$checkOwner) : ?>
                    <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-lg-1"></div>
        <div class="col-lg-3">
            <?php
            if (isset($file->path)) {
                echo '<img src="' . '/gurps/frontend/web/' .
                    $file->path .
                    '" width="480px">&nbsp;&nbsp;&nbsp;';
            } else {
                echo '<img src="' . '/gurps/frontend/web/uploads/pictures/worlds/avatars/unknown_world.png"
                            width="480px">&nbsp;&nbsp;&nbsp;';
            }
            if (!$checkOwner) : ?>
                <?= $form->field($file, 'file')->fileInput() ?>
                <?php
                ActiveForm::end(); ?>

                <?php ActiveForm::begin();
                if ($model->file_id != null): ?>
                    <?= Html::submitButton('Удалить изображение', [
                        'name' => 'delete_avatar',
                        'class' => 'btn btn-warning btn-sm',
                        'value' => '1',
                    ]) ?>
                <?php endif;
            endif;
            ActiveForm::end(); ?>
        </div>
    </div>
</div><!-- site-world -->
