<?php

namespace frontend\controllers;

use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use common\models\User;
use Yii;
use yii\helpers\Url;
use yii\web\Session;
use yii\rest\Controller;

class UsersController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['index', 'user'],
                'rules' => [
                    // deny all POST requests
                    [
                        'allow' => true,
                        'verbs' => ['POST']
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@', '?'],
                    ],
                    // everything else is denied
                ],
            ],
        ];
    }

    public function beforeAction($action)
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
        }
        return parent::beforeAction($action);
    }
    
    /**
     * display all users
     *
     * @return mixed
     */
    public function actionIndex()
    {
        $model = new User();
        
        if ($id = Yii::$app->request->post('id')) {
            return $this->redirect(['users/user/' . $id,]);
        }
        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * display specific user
     *
     * @return mixed
     */
    public function actionUser()
    {
        $model = User::findOne(['id' => Yii::$app->request->get('id')]);

        if ($model === null) {
            Yii::$app->session->setFlash('error', 'Пользователь не найден.');
            return $this->redirect(['users/index']);
        }
        return $this->render('user', [
            'model' => $model,
        ]);
    }

}
