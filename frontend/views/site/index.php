<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use frontend\models\World;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use kartik\sidenav\SideNav;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model \frontend\models\World */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'Миры';
?>
<style>
    .my-side-nav {
        position: fixed;
        width: 200px;
        /*background-color: rgba(0, 0, 0, 0.2);*/
        top: 83px;
        left: 0;
        bottom: 61px;
        overflow: auto;
        scrollbar-base-color: #ffeaff
        /*overflow-y: hidden;*/
        /*margin: auto;*/
        overflow-x: scroll;
    }

    .my-side-nav-search {
        position: fixed;
        top: 51px;
        left: 0;
        bottom: 61px;
        border-right: solid;
        border-color: rgba(0, 0, 0, 0.0);
        background-color: rgba(0, 0, 0, 0.5);
        border-width: 3px;
    }

    .world-selected {
        position: fixed;
        top: 51px;
        left: 225px;
        bottom: 61px;
        right: 0;
        overflow: auto;
        scrollbar-base-color: #ffeaff
        /*overflow-y: hidden;*/
        /*margin: auto;*/
        overflow-x: scroll;
    }

    .world-selected-content {
        width: 95%;
        top: 51px;
        left: 225px;
        bottom: 61px;
    }
</style>
<script>
    $(window).scroll(function () {
        $('#my-side-nav').css('left', originalLeft - $(this).scrollLeft());
    });
</script>

<div class="site-index">

    <?php $items[] = [
        'url' => Url::to(['/world/edit']),
        'label' => 'Создать',
        'icon' => 'plus',
    ];
    $model = $model->findAll(['status' => World::STATUS_ACTIVE]);
    foreach ($model as $item):
        $avatar = 'uploads/pictures/worlds/avatars/unknown_world.png';
        if (isset($item->file_id)) {
            $avatar = $item->getAvatar()->one()->path;
        }
        $items[] = [
            'label' => $item->name,
            'url' => 'javascript:picked(\'' . $item->id . '\', \'' . $item->name . '\', \'' . $avatar . '\')',
            'icon' => '',
        ];
    endforeach; ?>

    <div class="world-selected">
        <div class="world-selected-content">
            <span id="name"><i style="color:#999;margin:1em 0"><h2>Выберете мир...</h2></i><hr></span>
            &nbsp;&nbsp;&nbsp;&nbsp;
            <div id="avatar"></div>
            <br>
            <?php ActiveForm::begin(); ?>
            <div id="choose"></div>
            <?php ActiveForm::end(); ?>
        </div>
    </div>

    <div class="my-side-nav-search">
        <?php ActiveForm::begin(); ?>
        <?= Html::textInput('search', null, ['maxlength' => 32, 'style' => 'width:160px;']) ?>
        <?= Html::submitButton('<span class="glyphicon glyphicon-search"></span>', [
            'class' => 'btn btn-sm',
        ]) ?>
        <?php ActiveForm::end() ?>
    </div>

    <div class="my-side-nav">

        <?php echo SideNav::widget([
            'type' => SideNav::TYPE_DEFAULT,
            'class' => 'sidenav',
            'headingOptions' => ['class' => 'head-style'],
            'options' => [
                'style' =>
                    'background-color:#FFFFFF;',
            ],
            'items' => $items,
        ]);
        ?>
    </div>
</div>

<script>
    function picked(id, name, avatar) {
        document.getElementById("name").innerHTML = '<i><h2>' + name + '</h2></i><hr>';
//        document.getElementById("choose").innerHTML = '<button type="submit" class="btn btn-lg btn-primary" ' +
//            'name="worldEdit" value="' + id + '" style="font-size: 24px;">Выбрать</button>';
        document.getElementById("avatar").innerHTML = '<img id="avatar" src="/' +
            avatar + '" width="100%">' + '<a href="/world/scenario?id=' + id + '">' +
            '<img src="/src/images/play.png" width="20%" align="right"></a>' +
            '&nbsp;&nbsp;&nbsp;';
        document.getElementById("choose").innerHTML = '<button type="submit" class="btn btn-default" ' +
            'name="worldEdit" value="' + id + '" style="font-size: 14px;">Подробнее</button>';
    }
</script>
