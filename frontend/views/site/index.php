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
    .index-div {
        top:                20vh;
        position:           absolute;
        bottom:             6.4vh;
        height:             73%;
        width:              59.3%;
        overflow:           auto;
        border-left:        2px;
        background:         rgba(220, 220, 220, 0.5);
    }

    .search-div {
        background:         rgba(0, 220, 220, 0.9);
    }

    .search-index {
        height:             30px;
        width:              250px;
        top:                250px;
        border-top:         0;
        border-right:       0;
        border-left:        0;
        border-bottom:      2px solid rgb(0, 0, 0);
        background:         rgba(255, 255, 255, 0);
        transition:         0.4s;
    }

    .search-index:focus {
        width:              400px;
        transition:         0.7s;
    }

    .round {
        margin-left:        90%;
        width:              70px;
        height:             70px;
        border-radius:      60px;               /* Радиус скругления */
        border:             3px solid green;    /* Параметры рамки */
        box-shadow:         0 0 2px #666;       /* Параметры тени */
    }

    .table {
        width: 90%;
        text-align: center;
    }
</style>

<div><h1><i>Выберете мир...</i></h1></div>
<div class="search-div">
    <div style="position: fixed">
        <input onclick="input()" placeholder="Поиск" class="search-index blok transition"> </input>
    </div>
</div>
<div class="index-div">
    <hr>
    <?php for ($i = 0; $i < 50; $i++): ?>
        <table class="table">
            <tr>
                <td height="50px" width="60%" align="left"><h2>Мир какой-то</h2></td>
                <td height="50px" width="10%"><img src="http://media-cache-ec0.pinimg.com/736x/9a/45/72/9a45729d3404bccd07a3281e8b3a12ec.jpg" class="round"> </td>
            </tr>
        </table>
        <hr style="100%">
    <?php endfor; ?>

</div>