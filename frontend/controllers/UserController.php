<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\User;

class UserController extends \yii\web\Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
//                'only' => ['login', 'signup', 'error'],
                'rules' => [
                    [
                        'actions' => ['index',],
                        'allow' => true,
                    ],
                    [
                        'actions' => [],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actionIndex()
    {
        $model = new User;
        return $this->render('index', [
            'model' => $model,
        ]);
    }

}
