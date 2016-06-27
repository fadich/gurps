<?php

use yii\helpers\Html;
use yii\bootstrap\ActiveForm;

/* @var $this yii\web\View */
/* @var $form yii\bootstrap\ActiveForm */

$this->title = 'My Yii Application';
?>
<div class="site-index" id="create">
    <?php $form = ActiveForm::begin(); ?>

    <?= Html::submitButton('Создать', [
        'class' => 'btn btn-primary',
        'name' => 'create_world',
        'value' => 1,
    ]); ?>

    <?php $form = ActiveForm::end(); ?>

    <?= Html::button('Hello + 1', [
        'class' => 'btn btn-primary',
        'onClick' => 'hello()'
    ]); ?>
    <?= Html::button('Hello + 5', [
        'class' => 'btn btn-primary',
        'onClick' => 'helloFive()'
    ]); ?>
    <br>
    <?= Html::button('Hello  - 1', [
        'class' => 'btn btn-primary',
        'onClick' => 'helloMinus()'
    ]); ?>
    <?= Html::button('Hello - 5', [
        'class' => 'btn btn-primary',
        'onClick' => 'helloMinusFive()'
    ]); ?>

    <div id="text-hello">Value of "Hello" = 0.</div>

    <script>
        var i = 0;
        function hello() {
            if (i + 1 <= 100) {
                i++;
                document.getElementById("text-hello").innerHTML = 'Value of \"Hello\" = ' + i + '.';
            }
            else {
                document.getElementById("text-hello").innerHTML =
                    'Value of \"Hello\" = ' + i + '.<br>Value of \"Hello\" cannot be greater than 100';
            }
        }
        function helloFive() {
            if (i + 5 <= 100) {
                i += 5;
                document.getElementById("text-hello").innerHTML = 'Value of \"Hello\" = ' + i + '.';
            }
            else {
                document.getElementById("text-hello").innerHTML =
                    'Value of \"Hello\" = ' + i + '.<br>Value of \"Hello\" cannot be greater than 100';
            }
        }
        function helloMinus() {
            if (i - 1 >= 0) {
                i--;
                document.getElementById("text-hello").innerHTML = 'Value of \"Hello\" = ' + i + '.';
            }
            else {
                document.getElementById("text-hello").innerHTML =
                    'Value of \"Hello\" = ' + i + '.<br>Value of \"Hello\" cannot be less than 0';
            }
        }
        function helloMinusFive() {
            if (i - 5 >= 0) {
                i -= 5;
                document.getElementById("text-hello").innerHTML = 'Value of \"Hello\" = ' + i + '.';
            }
            else {
                document.getElementById("text-hello").innerHTML =
                    'Value of \"Hello\" = ' + i + '.<br>Value of \"Hello\" cannot be less than 0';
            }
        }
    </script>
    <!--    -->
    <!--    <div class="jumbotron">-->
    <!--        <h1>Congratulations!</h1>-->
    <!---->
    <!--        <p class="lead">You have successfully created your Yii-powered application.</p>-->
    <!---->
    <!--        <p><a class="btn btn-lg btn-success" href="http://www.yiiframework.com">Get started with Yii</a></p>-->
    <!--    </div>-->
    <!---->
    <!--    <div class="body-content">-->
    <!---->
    <!--        <div class="row">-->
    <!--            <div class="col-lg-4">-->
    <!--                <h2>Heading</h2>-->
    <!---->
    <!--                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et-->
    <!--                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip-->
    <!--                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu-->
    <!--                    fugiat nulla pariatur.</p>-->
    <!---->
    <!--                <p><a class="btn btn-default" href="http://www.yiiframework.com/doc/">Yii Documentation &raquo;</a></p>-->
    <!--            </div>-->
    <!--            <div class="col-lg-4">-->
    <!--                <h2>Heading</h2>-->
    <!---->
    <!--                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et-->
    <!--                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip-->
    <!--                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu-->
    <!--                    fugiat nulla pariatur.</p>-->
    <!---->
    <!--                <p><a class="btn btn-default" href="http://www.yiiframework.com/forum/">Yii Forum &raquo;</a></p>-->
    <!--            </div>-->
    <!--            <div class="col-lg-4">-->
    <!--                <h2>Heading</h2>-->
    <!---->
    <!--                <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit, sed do eiusmod tempor incididunt ut labore et-->
    <!--                    dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip-->
    <!--                    ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu-->
    <!--                    fugiat nulla pariatur.</p>-->
    <!---->
    <!--                <p><a class="btn btn-default" href="http://www.yiiframework.com/extensions/">Yii Extensions &raquo;</a></p>-->
    <!--            </div>-->
    <!--        </div>-->
    <!---->
    <!--    </div>-->
</div>
