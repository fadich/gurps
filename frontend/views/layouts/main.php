<?php
/**
 * Показал Декстеру
 */

/* @var $this \yii\web\View */
/* @var $content string */

use yii\helpers\Html;
use yii\bootstrap\Nav;
use yii\bootstrap\NavBar;
use yii\widgets\Breadcrumbs;
use frontend\assets\AppAsset;
use common\widgets\Alert;
use common\models\User;

//$this->title = 'GURPS';
AppAsset::register($this);
?>
<?php $this->beginPage() ?>
<!DOCTYPE html>
<html lang="<?= Yii::$app->language ?>">
<head>
    <meta charset="<?= Yii::$app->charset ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <?= Html::csrfMetaTags() ?>
    <title><?= Html::encode($this->title) ?></title>
    <?php $this->head() ?>
</head>
<body onload="search()">
<?php $this->beginBody() ?>

<div class="wrap">
    <?php
    NavBar::begin([
        'brandLabel' => 'Главная',
        'brandUrl' => Yii::$app->homeUrl,
        'options' => [
            'class' => 'nav-color nav-text navbar navbar-inverse navbar-fixed-top',
//            'style' =>
//                'background-color: rgba(0, 0, 0, 0.8);'
        ],
    ]);
    $menuItems = [
//        ['label' => 'Home', 'url' => ['/site/index']],
        ['label' => 'О "gurps"', 'url' => ['/site/about']],
        ['label' => 'Пользователи', 'url' => ['/users/index']],
//        ['label' => 'Contact', 'url' => ['/site/contact']],
    ];
    if (!Yii::$app->user->isGuest) {
            $username = User::findOne(Yii::$app->user->id)->getProfile()->name;
        $menuItems[] = [
            'label' => 'Пользователь (' .
                $username
                . ')',
            'items' => [
                ['label' => 'Профиль', 'url' => ['/site/profile']],
                ['label' => 'Выйти', 'url' => ['/site/logout'],
                    'linkOptions' => ['data-method' => 'post']],
            ]
        ];
    } else {
//        $menuItems[] = ['label' => 'Signup', 'url' => ['/site/signup']];
        $menuItems[] = ['label' => 'Войти', 'url' => ['/site/login']];
    }
    echo Nav::widget([
        'options' => ['class' => 'navbar-nav navbar-right'],
        'items' => $menuItems,
    ]);
    NavBar::end();
    ?>

    <div class="container">
        <?= Breadcrumbs::widget([
            'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
        ]) ?>
        <?= Alert::widget() ?>
        <?= $content ?>
    </div>
</div>

<footer class="footer column">
    <div class="container">
        <p class="pull-left ">&copy; GURPS <?= date('Y') ?></p>

        <p class="pull-right"><?= Yii::powered() ?></p>
    </div>
</footer>
<?php $this->endBody() ?>
</body>
</html>
<?php $this->endPage() ?>
