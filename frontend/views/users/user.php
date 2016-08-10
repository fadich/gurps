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
<style>
    .info {
        border-radius: 5px;
        font-size: 18px;
        font-style: italic;
    }
    .info:hover {
        background-color: #ededed;
        border-radius: 5px;
        color: #000000;
    }
</style>
<div class="user-user">
    <div class="rows">
        <div class="col-lg-13">
            <div class="rows">
                <div class="col-lg-5">
                    <?php
                    if (isset($model->getProfile()->one()->getAvatar()->one()->path)) {
                        echo '<img src="' . $model->getProfile()->one()->getAvatar()->one()->path .
                            '" width="308px">&nbsp;&nbsp;&nbsp;';
                    } else {
                        echo '<img src="' . 'uploads/pictures/avatars/no_avatar.png"
                            width="308px">&nbsp;&nbsp;&nbsp;';
                    } ?>

                </div>
                <div class="col-lg-7">
                    <h4>
                        <p><strong><span class="info"><?= $model->getProfile()->one()->name ?></span></strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="color:#999;margin:1em 0;font-size: medium"><?= $model->isOnline(); ?></span>
                        </p>
                        <hr>
                        <p><strong>Дата рождения: </strong>
                            <span class="info"><?= $model->getProfile()->one()->birthday ?></span>
                        </p>
                        <hr>
                        <p><strong>Пол: </strong>
                            <span class="info"><?= $model->getProfile()->one()->sex ?></span>
                        </p>
                        <hr>
                        <p><strong>Доп. информация: </strong>
                            <div class="info"><?= $model->getProfile()->one()->info ?></div>
                        </p>
                    </h4>
                </div>
            </div>
        </div>
        <div class="col-lg-12">
            <a></a>
            <hr>
            <div style="background-color: #F0f0f0;border-radius: 5px;">
                <h5>&nbsp;&nbsp;
                    <span id="showWorld"><button class="btn btn-link" onclick="showWorlds()">
                            <span class="glyphicon glyphicon-plus"></span>
                        </button>
                    </span>
                    Созданные миры
                </h5>
            </div>
            <div id="worlds"></div>
        </div>
        <div class="col-lg-12">
            <a></a>
            <hr>
            <h5>Scenarios created
                <button id="scenarios" class="btn btn-link" onclick="showScenarios()">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </h5>
        </div>
        <div class="col-lg-12">
            <a></a>
            <hr>
            <h5>Characters created
                <button id="characters" class="btn btn-link" onclick="showCharacters()">
                    <span class="glyphicon glyphicon-plus"></span>
                </button>
            </h5>
        </div>
    </div>
</div><!-- user-user -->

<script>
    function showWorlds() {
        document.getElementById("worlds").innerHTML = '<?php
            $worlds = $model->getWorlds()->all();
            $deleted = false;
            foreach ($worlds as $world) {
                if ($world->status == \frontend\models\World::STATUS_ACTIVE) {
                    echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;'
                        . '<a href="/index.php/world/edit?id=' . $world->id . '">' .
                        $world->name . '</a>;<br>';
                } else {
                    $deleted = true;
                }

            }
            if ($deleted) {
                echo '<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Удаленные:<br>';
                foreach ($worlds as $world) {
                    if ($world->status == \frontend\models\World::STATUS_DELETED) {
                        echo '&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;' .
                            mb_substr($world->name, 0, $world->name - mb_strlen('-deleted' . $world->id,
                                    'UTF-8'), 'UTF-8') . ';';
                        if ($world->user_id == Yii::$app->user->id) {
                            echo '&nbsp;&nbsp;&nbsp;&nbsp;(' .
                                Html::a('Восстановить', '/gurps/frontend/web/index.php/world/reset?id=' . $world->id) .
                                ')';
                        }
                        echo '<br>';
                    }
                }
            }
            ?>';

        document.getElementById("showWorld").innerHTML = '<button class="btn btn-link" ' +
            'onclick="hideWorlds()">' +
            '<span class="glyphicon glyphicon-minus"></span></button>';
    }
    function hideWorlds() {
        document.getElementById("worlds").innerHTML = '';

        document.getElementById("showWorld").innerHTML = '<button class="btn btn-link" ' +
            'onclick="showWorlds()">' +
            '<span class="glyphicon glyphicon-plus"></span></button>';
    }
</script>
