<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use skeeks\yii2\ckeditor\CKEditorWidget;
use skeeks\yii2\ckeditor\CKEditorPresets;

/* @var $this yii\web\View */
/* @var $model frontend\models\World */
/* @var $file frontend\models\Files */
/* @var $form ActiveForm */

isset($model->name) ? $this->title = 'Мир "' . $model->name . '"' : $this->title = 'Новый мир';
$this->params['breadcrumbs'][] = $this->title;
?>
<style>
    .not-owner {
        background-color: #f5f5f5;
        border-radius: 5px;
        font-size: 18px;
        font-style: italic;
    }
    .not-owner:hover {
        background-color: #ededed;
        border-radius: 5px;
        color: #000000;
    }
</style>
<div class="world-edit">
    <?php isset($model->name) ? $str = 'Мир <i>"' . $model->name . '"</i>' :
        $str = 'Новый мир'; ?>
    <h1><?= $str ?></h1>


    <p><?php isset($model->name) ? $str = 'Игровой мир <i>"' . $model->name . '"</i>' :
            $str = 'Создание нового игрового мира';
        echo $str ?>:</p>

    <div class="row">
        <div class="col-lg-5">
            <?php $form = ActiveForm::begin();
            $checkOwner = (Yii::$app->user->id == $model->user_id || $model->user_id == null) ? false : true; ?>

            <?php if (!$checkOwner) : ?>

                <?= $form->field($model, 'name')->textInput(['readOnly' => $checkOwner]) ?>
                <?= $form->field($model, 'description')->widget(CKEditorWidget::className(), [
                    'options' => ['rows' => 6],
                    'preset' => CKEditorPresets::BASIC,
                ]) ?>
                <?= $form->field($file, 'file')->fileInput(['readOnly' => $checkOwner]) ?>

            <?php else: ?>
                <br>
                <p>
                    <strong>Название: </strong>
                    <span class="not-owner">
                        <?= $model->name ?>
                    </span>.
                </p>
                <hr>
                <p><strong>Описание: </strong>
                    <div class="not-owner"><br>
                        <?= $model->description ?>
                    </div>
                </p>
                <hr>
            <?php endif; ?>
            <?php if ($model->user_id != null): ?>
                <strong>Создатель: </strong>
                <span class="not-owner">
                    <?= $model->getOwner()->one()->name ?>
                </span>.
                <hr>
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
            ActiveForm::end();
            if (!$checkOwner) :
                ?>
                <?php ActiveForm::begin();
                if ($model->file_id != null): ?>
                    <?= Html::submitButton('Удалить изображение', [
                        'name' => 'delete_avatar',
                        'class' => 'btn btn-warning btn-sm',
                        'value' => '1',
                    ]) ?>
                <?php endif;
                ActiveForm::end();
            endif; ?>
        </div>
    </div>
    <?php if (!$checkOwner && $model->id != null): ?>
        <hr>
        <div align="right" id="delete">
            <button type="button" class="btn btn-danger" onclick="deleteWorld()">Удалить</button>
        </div>
        <br>
        <?php ActiveForm::begin(); ?>
        <div align="right" id="buttons"></div>
        <?php ActiveForm::end();
    endif; ?>
</div><!-- site-world -->
<script>
    function deleteWorld() {
        document.getElementById("delete").innerHTML = 'Вы уверены, что хотите удалить мир ' +
            "<i>\"<?= $model->name ?>\"</i>?";
        document.getElementById("buttons").innerHTML =
            '<?= Html::submitButton('Да', [
                'class' => 'btn btn-default',
                'name' => 'delete',
                'value' => '1',
            ]) ?>' +
            '&nbsp;' +
            '<?= Html::button('Нет', [
                'class' => 'btn btn-success', 'align' => 'right',
                'onclick' => 'deleteCancel()',
            ]) ?>';
    }

    function deleteCancel() {
        document.getElementById("delete").innerHTML =
            '<button type="button" class="btn btn-danger" onclick="deleteWorld()">Удалить</button>';
        document.getElementById("buttons").innerHTML = '';
    }
</script>
