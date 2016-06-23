<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */


$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="row">
        <div class="col-lg-10">
            <?php
            $model = $model->findAll(['status' => $model::STATUS_ACTIVE]);
            $form = ActiveForm::begin();
            foreach ($model as $item): ?>
                <div class="row">
                    <div class="col-lg-5">
                        <h4>
                            <p>
                                <a><?php echo $item->getProfile()->one()->name; ?></a>
                                <br> <br>
                                <?php if (isset($item->getProfile()->one()->avatar)) {
                                    echo '<img src="' . '/gurps/frontend/web/' . $item->getProfile()->one()->avatar .
                                        '" width="248px">&nbsp;&nbsp;&nbsp;';
                                } ?>
                            </p>
                        </h4>
                    </div>
                    <div class="col-lg-5">
                        <h4><b>Информация</b></h4>
                        <b>Дата рождения:</b>
                        <?php echo $item->getProfile()->one()->birthday; ?>
                        <br><b>Пол:</b>
                        <?php echo $item->getProfile()->one()->sex; ?>
                        <br><b>Доп. информация:</b>
                        <?php echo mb_substr($item->getProfile()->one()->info, 0, 150); ?>
                    </div>
                </div>
                <hr>
            <?php endforeach;
            ActiveForm::end(); ?>
        </div>
    </div>
</div>
