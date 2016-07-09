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
        $avatar = '/gurps/frontend/web/uploads/pictures/worlds/avatars/unknown_world.png';
        if (isset($item->file_id)) {
            $avatar = $item->getAvatar()->one()->path;
        }
        $items[] = [
            'label' => $item->name,
            'items' => [
                [
                    'label' => 'Выбрать',
                    'url' => 'javascript:picked(\'' . $item->name . '\', \'' . $avatar . '\')',
                ],
                [
                    'label' => 'Просмотр',
                    'url' => Url::to(['/world/edit?id=' . $item->id]),
                ]
            ],
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
            <h2 id="name"><i style="color:#999;margin:1em 0">Выберете мир...</i></h2>
            <div id="avatar"></div>
            <div id="scenasio"></div>
        </div>

    </div>
</div>

<script>
    function picked(name, avatar) {
        document.getElementById("name").innerHTML = '<strong><i>' + name + '</i></strong>';
        document.getElementById("avatar").innerHTML = '<img id="avatar" src="' +
            avatar + '" width="100%">&nbsp;&nbsp;&nbsp';
        document.getElementById("scenasio").innerHTML = '<?php
            echo '<hr>Тут будут сценарии....';
            ?>'
    }
</script>
