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

    <div class="rows">

        <div class="col-lg-3">
            <?php
            echo SideNav::widget([
                'type' => SideNav::TYPE_PRIMARY,
                'class' => 'sidenav',
                'headingOptions' => ['class' => 'head-style'],
                'options' => [
//                    'style' =>
//                        'height:10%'
                ],
                'heading' => 'Список миров',
                'items' => $items,
            ]);
            ?>
        </div>

        <div class="col-lg-9">
            <?php ActiveForm::begin(); ?>
            <span id="name"><i style="color:#999;margin:1em 0"><h2>Выберете мир...</h2></i><hr></span>
            <?php ActiveForm::end(); ?>
            <div id="avatar"></div>
            <div id="scenasio"></div>
        </div>
    </div>
</div>

<script>
    function picked(id, name, avatar) {
        document.getElementById("name").innerHTML = '<button type="submit" class="btn btn-link" name="worldEdit" ' +
            'value="' + id + '"><h2><i style="color:#003873;">' + name + '</i></h2></button>';
        document.getElementById("avatar").innerHTML = '<img id="avatar" src="/gurps/frontend/web/' +
            avatar + '" width="100%">' +
            '<a href=""><img src="/gurps/frontend/views/src/images/play.png" width="20%" align="right"></a>' +
            '&nbsp;&nbsp;&nbsp';
        document.getElementById("scenasio").innerHTML = '<?php
            echo '<br><hr>Тут будут сценарии....';
            ?>'
    }
</script>
