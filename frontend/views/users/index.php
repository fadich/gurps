<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\widgets\DetailView;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */
/* @var $session \yii\web\Session */


$this->title = 'Пользователи';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">
    <div class="row">
        <div class="col-lg-13">
            <?php
            $model = $model->findAll(['status' => $model::STATUS_ACTIVE]);
            foreach ($model as $item):
                $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-lg-5">
                        <?= Html::submitButton('<h4>' . $item->getProfile()->one()->name . '</h4>', [
                            'class' => 'btn btn-link',
                            'name' => 'id',
                            'value' => $item->id,
                        ]) ?>
                        <span style="color:#999;margin:1em 0"><?= $item->isOnline(); ?></span>
                        <br>
                        <?php $avatar = $item->getProfile()->one()->getAvatar()->one();
                        if (isset($avatar->path)) {
                            echo '<img src="' . '/gurps/frontend/web/' . $avatar->path .
                                '" width="160px">&nbsp;&nbsp;&nbsp;';
                        } else {
                            echo '<img src="' . '/gurps/frontend/web/uploads/pictures/avatars/no_avatar.png"
                            width="160px">&nbsp;&nbsp;&nbsp;';
                        } ?>

                    </div>
                    <div class="col-lg-5">
                        <h3>Информация</h3>
                        <h5>
                            <p>Дата рождения: <?php echo $item->getProfile()->one()->birthday ?> </p>
                            <p>Доп. информация: <?php (strlen($item->getProfile()->one()->info) > 150) ?
                                $str = trim(mb_substr($item->getProfile()->one()->info, 0, 150, "UTF-8")) . '...' :
                                $str = $item->getProfile()->one()->info;
                            echo $str;
                            ?> </p>
                        </h5>
                    </div>
                </div>
                <hr>
                <?php ActiveForm::end();
            endforeach; ?>
        </div>
    </div>
</div>
