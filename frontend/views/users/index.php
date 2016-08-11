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
                            '/uploads/pictures/avatars/no_avatar.png';
                        if (isset($avatar->path)) {
                            echo '<img src="/' . $avatar->path .
                                '" width="160px">&nbsp;&nbsp;&nbsp;';
                        } else {
                            echo '<img src="' . '/uploads/pictures/avatars/no_avatar.png"
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
<div class="div-size-users-index" id="ddl">
    <div>
        <p><b>Поиск</b></p>
        <input class="drop-down-list-user-search"> </input>
        <button><span class="glyphicon glyphicon-search"></span></button>
    </div>
    <hr>
    <div>
        <p c><b>Сортировка по:</b></p>
        <?= Html::dropDownList('dasdas', 'das', [
            '1' => 'дате регистрации',
            '2' => 'имени',
            '3' => 'по кол-во созданих миров',
            '4' => 'по кол-во созданныйх персонажей',
            '5' => 'по кол-во созданныйх сценариев',
        ],
            [
                'class' => 'drop-down-list-user-sort',
                'title' => 'Выберете атрибут для сортировки...',
            ]); ?>
        <input type="radio" name="sort" value="asc" checked><span class="button-users-sort glyphicon glyphicon-arrow-up"></span>
        <input type="radio" name="sort" value="desc"><span class="button-users-sort glyphicon glyphicon-arrow-down"></span>
<!--        <p>-&nbsp;&nbsp;&nbsp;&nbsp;дате регистрации-->
<!--            <button><span class="glyphicon glyphicon-arrow-up"></span></button>-->
<!--            <button><span class="glyphicon glyphicon-search"></button>-->
<!--        <p>-&nbsp;&nbsp;&nbsp;&nbsp;имени-->
<!--            <button><span class="glyphicon glyphicon-arrow-up"></span></button>-->
<!--            <button><span class="glyphicon glyphicon-search"></button>-->
<!--        </p>-->
<!--        <p>-&nbsp;&nbsp;&nbsp;&nbsp;по кол-во созданих миров-->
<!--            <button><span class="glyphicon glyphicon-arrow-up"></span></button>-->
<!--            <button><span class="glyphicon glyphicon-search"></button>-->
<!--        </p>-->
<!--        <p>-&nbsp;&nbsp;&nbsp;&nbsp;по кол-во созданныйх персонажей-->
<!--            <button><span class="glyphicon glyphicon-arrow-up"></span></button>-->
<!--            <button><span class="glyphicon glyphicon-search"></button>-->
<!--        </p>-->
<!--        <p>-&nbsp;&nbsp;&nbsp;&nbsp;имени-->
<!--            <button class="btn btn-link"><span class="glyphicon glyphicon-arrow-up"></span></button>-->
<!--            <button><span class="glyphicon glyphicon-search"></button>-->
<!--        </p>-->
    </div>
    <hr>
    <div>
        <p><b>Отображение:</b></p>
        <input type="radio" name="option" value="a1" checked>Всех пользователей<br>
        <input type="radio" name="option" value="a2">Только Online<br>
        <input type="radio" name="option" value="a3">Только Offline<br>
    </div>
    <br>
    <div>
        <input type="checkbox" name="option1" value="a1">Только с фотографией<br>
    </div>
    <br>
    <div>
        <input type="radio" name="option2" value="a1" checked>Отключить фильтр<br>
    </div>
    <div>
        <input type="radio" name="option2" value="a2">Всех мастеров<br>
    </div>
    <div>
        <input type="radio" name="option2" value="a3">Всех игроков<br>
    </div>
    <div>
        <input type="radio" name="option2" value="a3">Не является мастером не в одном из сценариев<br>
    </div>
    <div>
        <input type="radio" name="option2" value="a3">Не является игроком не в одном из сценариев<br>
    </div>
</div>
<div class="div-button-hide" id="ddl-header">
    <button type="button" class="btn btn-link" id="btn-hide-user-index" onclick="hide()"><span id="span-icon" class="glyphicon glyphicon-chevron-up"></span></button>
</div>
<script>
    function hide() {
        if ($('#span-icon').attr('class') == 'glyphicon glyphicon-chevron-up') {
            $('#ddl').slideUp();
            $('#ddl-header').css("background", "rgba(0, 0, 0, 0.02)");
            $('#span-icon').attr('class', 'glyphicon glyphicon-chevron-down');
        } else {
            $('#ddl').slideDown();
            $('#ddl-header').css("background", "rgba(0, 0, 0, 0.0)");
            $('#span-icon').attr('class', 'glyphicon glyphicon-chevron-up');
        }
    }
</script>

