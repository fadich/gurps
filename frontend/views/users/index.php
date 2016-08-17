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
            <?php foreach ($model->getAllUsers() as $user):
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
                            <p>Дата рождения: <?= $user['birthday'] ?> </p>
                            <p>Доп. информация: <?= $str = (strlen($user['info']) > 150) ?
                                    trim(mb_substr($user['info'], 0, 150, "UTF-8")) . '...' : $user['info']; ?> </p>
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
    <form>
        <div>
            <input placeholder="Поиск" class="drop-down-list-user-search">
            <button><span class="glyphicon glyphicon-search"></span></button>
        </div>
        <hr>
        <div>
            <p><b>Сортировка по:</b></p>
            <?= Html::dropDownList('order', $model->order, [
                'name' => 'имени',
                'date' => 'дате регистрации',
                'status' => 'online',
                'worlds' => 'по кол-ву созданих миров',
                'scenarios' => 'по кол-ву созданныйх сценариев',
                'characters' => 'по кол-ву созданныйх персонажей',
            ],
                [
                    'class' => 'drop-down-list-user-sort',
                    'title' => 'Выберете атрибут для сортировки...',
                ]); ?>
            <button type="radio" class="<?= $model->sort ?>" name="sort"
                    value="<?= $model->sort == 'desc' ? 'asc' : 'desc' ?>" id="search" onload="search()">
                <span class="button-users-sort glyphicon glyphicon-arrow-up" id="sort"></span>
            </button>

            <!--        <button type="radio" name="sort" value="desc" title="По убыванию" disabled><span class="button-users-sort glyphicon glyphicon-arrow-down"></span></button>-->

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
    </form>
</div>

<div class="div-button-hide" id="ddl-header">
    <button type="button" class="button-hide btn btn-link" id="btn-hide-user-index" onclick="hide()"><span
            id="span-icon" class="glyphicon glyphicon-chevron-up"></span></button>
</div>

<?php echo '<pre>' ?>
<?php var_dump($_SERVER["HTTP_X_REAL_IP"]) ?>
<?php var_dump($_SERVER["HTTP_USER_AGENT"]) ?>
<?php var_dump($_SERVER["HTTP_ACCEPT_LANGUAGE"]) ?>
<?php var_dump($_SERVER["QUERY_STRING"]) ?>
<?php var_dump($_SERVER["REQUEST_TIME_FLOAT"]) ?>

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
    function search() {
        if ($('#search').attr('class') == 'desc') {
            $('#sort').attr('class', 'glyphicon glyphicon-arrow-down').attr('title', 'Нажмите для сортировки по возрастанию');
        } else {
            $('#sort').attr('class', 'glyphicon glyphicon-arrow-up').attr('title', 'Нажмите для сортировки по убыванию');
        }
    }
</script>

