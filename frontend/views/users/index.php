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
    <h1><?= Html::encode($this->title) ?></h1>

    <p>Список активных пользователей:</p>

    <div class="row">
        <div class="col-lg-10">
            <?php
            $model = $model->findAll(['status' => $model::STATUS_ACTIVE]);
            foreach ($model as $item):
                $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-lg-5">
                        <?= Html::submitButton('<h4 style="color:#003873;">' . $item->getProfile()->one()->name . '</h4>', [
                            'class' => 'btn btn-link',
                            'name' => 'id',
                            'value' => $item->id,
                        ]) ?>
                        <span style="color:#999;margin:1em 0"><?= $item->isOnline(); ?></span>
                        <br>
                        <?php $avatar = $item->getProfile()->one() ? $item->getProfile()->one()->getAvatar()->one() :
                            '/gurps/frontend/web/uploads/pictures/avatars/no_avatar.png';
                        if (isset($avatar->path)) {
                            echo '<img src="' . '/gurps/frontend/web/' . $avatar->path .
                                '" width="160px">&nbsp;&nbsp;&nbsp;';
                        } else {
                            echo '<img src="' . '/gurps/frontend/web/uploads/pictures/avatars/no_avatar.png"
                            width="160px">&nbsp;&nbsp;&nbsp;';
                        } ?>

                    </div>
                    <div class="col-lg-6">
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
<div class="div-size-users-index">
    <div>
        <p><b>Поиск</b></p>
        <input> </input>
        <button><span class="glyphicon glyphicon-search"></span></button>
    </div>
    <hr>
    <div>
        <p><b>Показать:</b></p>
        <input type="radio" name="option" value="a1" checked>Всех пользователей<Br>
        <input type="radio" name="option" value="a2">Только Online<Br>
        <input type="radio" name="option" value="a3">Только Offline<Br>
    </div>
    <hr>
    <div>
        <input type="checkbox" name="option1" value="a3">Только с фотографией<Br>
    </div>
    <hr>
    <div>
        <p><b>Отсортировать по:</b></p>
        <p><input type="radio" name="option2" value="a1">Дате регистрации<Br> <button><span class="glyphicon glyphicon-search"></span></button> <button><span class="glyphicon glyphicon-search"></button></p>
        <input type="radio" name="option2" value="a3">Имени<Br>
        <input type="radio" name="option2" value="a3">По кол-во созданих миров<Br>
        <input type="radio" name="option2" value="a3">По кол-во созданныйх персонажей<Br>
        <input type="radio" name="option2" value="a3">Имени<Br>
    </div>
</div>
</div>

