<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/* @var $this yii\web\View */
/* @var $model common\models\User */
/* @var $form ActiveForm */

$profile = $model->getProfile();
$this->title = $profile->name;
$this->params['breadcrumbs'][] = ['label' => 'Пользователи', 'url' => '/users'];
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
                    if (isset($profile->getAvatar()->path)) {
                        echo '<img src="/' . $profile->getAvatar()->path .
                            '" width="308px">&nbsp;&nbsp;&nbsp;';
                    } else {
                        echo '<img src="' . '/uploads/pictures/avatars/no_avatar.png"
                            width="308px">&nbsp;&nbsp;&nbsp;';
                    } ?>

                </div>
                <div class="col-lg-7">
                    <h4>
                        <p><strong><span class="info"><?= $profile->name ?></span></strong>
                            &nbsp;&nbsp;&nbsp;&nbsp;
                            <span style="color:#<?= $model->isOnline() ? '4C4' : '999' ?>;
                                         margin:1em 0;font-size: medium"><?= $model->getStatusStr(); ?></span>
                        </p>
                        <hr>
                        <p><strong>Дата рождения: </strong>
                            <span class="info"><?= $profile->birthday ?></span>
                        </p>
                        <hr>
                        <p><strong>Пол: </strong>
                            <span class="info"><?= $profile->sex ?></span>
                        </p>
                        <hr>
                        <p><strong>Доп. информация: </strong>
                        <div class="info"><?= $profile->info ?></div>
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
                            <span id="show-worlds" class="glyphicon glyphicon-plus"></span>
                        </button>
                    </span>
                    Созданные миры
                </h5>
            </div>
            <div id="worlds" hidden>
                <?php $worlds = $model->getWorlds();
                if ($worlds) {
                    $deleted = false;
                    foreach ($worlds as $world) {
                        if ($world->status == \frontend\models\World::STATUS_ACTIVE) { ?>
                            &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;
                                <a href="/index.php/world/edit/<?= $world->id ?>">
                                <?= $world->name ?></a>;<br>
                        <?php } else {
                            $deleted = true;
                        }

                    }
                    if ($deleted) { ?>
                        <br>&nbsp;&nbsp;&nbsp;&nbsp;Удаленные:<br>
                        <?php if ($worlds) {
                            foreach ($worlds as $world) {
                                if ($world->status == \frontend\models\World::STATUS_DELETED) { ?>
                                    &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;-&nbsp;&nbsp;&nbsp;&nbsp;
                                        <?= mb_substr($world->name, 0, $world->name - mb_strlen('-deleted' . $world->id,
                                                'UTF-8'), 'UTF-8') . ';'; ?>
                                    <?php if ($world->user_id == Yii::$app->user->id) { ?>
                                        &nbsp;&nbsp;&nbsp;&nbsp;(<?= Html::a('Восстановить', '/world/reset/' . $world->id) ?>);
                                    <?php } ?>
                                    <br>
                                <?php }
                            }
                        }
                    }
                } else { ?>
                    &nbsp;&nbsp;&nbsp;&nbsp;Пользователь не создал ни одного мира...'
                <?php } ?>
            </div>
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
        if ($('#show-worlds').attr('class') == 'glyphicon glyphicon-plus') {
            $("#worlds").slideDown();
            $('#show-worlds').attr('class', 'glyphicon glyphicon-minus');
        } else {
            $("#worlds").slideUp();
            $('#show-worlds').attr('class', 'glyphicon glyphicon-plus');
        }
    }
</script>
