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

$users =  $model->getUsers(\Yii::$app->request->get('order'), \Yii::$app->request->get('sort'));
$order  = $model->order;
$sort   = $model->sort;

?>
<!--     Users sort panel       -->

<div class="div-button-hide" id="ddl-header">
    <p>Параметры сортировки</p>
    <button type="button" class="button-hide btn btn-link" id="btn-hide-user-index" onclick="hide()"><span
            id="span-icon" class="glyphicon
            <?= \Yii::$app->request->get() ? 'glyphicon-chevron-down' : 'glyphicon-chevron-up' ?>"></span></button>
</div>
<div class="div-size-users-index" id="ddl"<?= \Yii::$app->request->get() ? '' : 'hidden' ?>>
    <form>
        <div class="row">
            <div class="col-lg-4">
                <div>
                    <input placeholder="Поиск" class="drop-down-list-user-search">
                    <button><span class="glyphicon glyphicon-search"></span></button>
                </div>
                <hr>
                <div>
                    <p><b>Сортировка по:</b></p>
                    <?= Html::dropDownList('order', $model->order, [
                        'name'          => 'имени',
                        'date'          => 'дате регистрации',
                        'status'        => 'online',
                        'worlds'        => 'по кол-ву созданих миров',
                        'scenarios'     => 'по кол-ву созданныйх сценариев',
                        'characters'    => 'по кол-ву созданныйх персонажей',
                    ],
                        [
                            'class'     => 'drop-down-list-user-sort',
                            'title'     => 'Выберете атрибут для сортировки...',
                        ]); ?>
                    <button type="radio" class="<?= $sort ?>" name="sort"
                            value="<?= $sort ?>" id="search" onload="search()">
                        <span class="button-users-sort glyphicon glyphicon-arrow-up" id="sort"></span>
                    </button>
                </div>
            </div>
            <div class="col-lg-4">
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
            </div>
            <div class="col-lg-4">
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
        </div>
    </form>
</div>

<div class="user-index">
    <h1><?= Html::encode($this->title) ?></h1>
    <p>Список активных пользователей:</p>
    <div class="row">
        <?php if ($users):
            foreach ($users as $user):
                $form = ActiveForm::begin(); ?>
                <div class="row">
                    <div class="col-lg-5">
                        <?= Html::submitButton('<h4 style="color:#003873;">' . $user['name'] . '</h4>', [
                            'class' => 'btn btn-link',
                            'name' => 'id',
                            'value' => $user['id'],
                        ]) ?>
                        <span style="color:#999;margin:1em 0"><?= $model->isOnline($user['status']) ?></span>
                        <br>
                        <?php $avatar = $user['avatar'] ? $user['avatar'] :
                            'uploads/pictures/avatars/no_avatar.png'; ?>
                        <?= '<img src="/' . $avatar . '" width="160px">&nbsp;&nbsp;&nbsp;' ?>
                    </div>
                    <div class="col-lg-6">
                        <h3>Информация</h3>
                        <h5>
                            <p>Дата рождения:   <?= $user['birthday'] ?> </p>
                            <p>Доп. информация: <?= $str = (strlen($user['info']) > 150) ?
                                    trim(mb_substr($user['info'], 0, 150, "UTF-8")) . '...' : $user['info']; ?> </p>
                        </h5>
                    </div>
                </div>
                <hr>
                <?php ActiveForm::end();
            endforeach;
        endif; ?>
    </div>
</div>
<script>
    function hide() {
        if ($('#span-icon').attr('class') == 'glyphicon glyphicon-chevron-down') {
            $('#ddl').slideUp();
            $('#span-icon').attr('class', 'glyphicon glyphicon-chevron-up');
        } else {
            $('#ddl').slideDown();
            $('#span-icon').attr('class', 'glyphicon glyphicon-chevron-down');
        }
    }
    function search() {
        if ($('#search').attr('class') == 'asc') {
            $('#sort').attr('class', 'glyphicon glyphicon-arrow-down').attr('title', 'Нажмите для сортировки по возрастанию');
        } else {
            $('#sort').attr('class', 'glyphicon glyphicon-arrow-up').attr('title', 'Нажмите для сортировки по убыванию');
        }
    }
</script>

