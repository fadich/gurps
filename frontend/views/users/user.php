<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */


$this->title = $model->getProfile()->one()->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => 'index'];
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-user">
    <div class="rows">
        <div class="col-lg-13">

            <?php $form = ActiveForm::begin(); ?>

            <div class="rows">
                <div class="col-lg-5">
                    <?php
                    if (isset($model->getProfile()->one()->getAvatar()->one()->path)) {
                        echo '<img src="' . '/gurps/frontend/web/' .
                            $model->getProfile()->one()->getAvatar()->one()->path .
                            '" width="308px">&nbsp;&nbsp;&nbsp;';
                    } else {
                        echo '<img src="' . '/gurps/frontend/web/uploads/pictures/avatars/no_avatar.png"
                            width="308px">&nbsp;&nbsp;&nbsp;';
                    } ?>

                </div>
                <div class="col-lg-7">
                    <h4>
                        <p><strong><?= $model->getProfile()->one()->name ?></strong>
                            <a style="color:#999;margin:1em 0;font-size: medium"><?= $model->isOnline(); ?></a></p>
                        <hr>
                        <p><strong>Дата рождения: </strong><?= $model->getProfile()->one()->birthday ?></p>
                        <hr>
                        <p><strong>Пол: </strong><?= $model->getProfile()->one()->sex ?></p>
                        <hr>
                        <p><strong>Доп. информация: </strong><?= $model->getProfile()->one()->info ?></p>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <a></a>
            <hr>
            <h5>Worlds created <a id="worlds">(<span class="glyphicon glyphicon-plus"></span>)</a></h5>
        </div>
        <div class="col-lg-12">
            <a></a>
            <hr>
            <h5>Scenarios created <a id="worlds">(<span class="glyphicon glyphicon-plus"></span>)</a></h5>
        </div>
        <div class="col-lg-12">
            <a></a>
            <hr>
            <h5>Characters created <a id="worlds">(<span class="glyphicon glyphicon-plus"></span>)</a></h5>
        </div>
    </div>
    <?php ActiveForm::end(); ?>

</div><!-- user-user -->
