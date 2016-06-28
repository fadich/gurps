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

    <p><?php isset($model->name) ? $str = 'Редактирование игрового мира <i>"'. $model->name . '"</i>' :
            $str = 'Создание нового игрового мира';
        echo $str?>:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin(); ?>
            <?= $form->field($model, 'name')->textInput() ?>
            <?= $form->field($model, 'description')->textarea() ?>

            <div class="form-group">
                <?= Html::submitButton('Редактировать', ['class' => 'btn btn-primary']) ?>
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
            } ?>
            <?= $form->field($file, 'file')->fileInput() ?>

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
</div><!-- site-world -->
