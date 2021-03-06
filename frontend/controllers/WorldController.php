<?php

namespace frontend\controllers;

use frontend\models\World;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use frontend\models\Files;
use yii\web\UploadedFile;
use yii\web\Controller;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use common\models\User;

class WorldController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => \yii\filters\AccessControl::className(),
                'only' => ['edit', 'reset'],
                'rules' => [
                    // deny all POST requests
                    [
                        'allow' => true,
                        'verbs' => ['POST']
                    ],
                    // allow authenticated users
                    [
                        'allow' => true,
                        'roles' => ['@'],
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
     * @return mixed
     */
    public function actionEdit()
    {
        $model = ($model = World::findOne(['id' => Yii::$app->request->get('id')]))
            ? $model : new World();

        if ($model->status === World::STATUS_DELETED) {
            Yii::$app->session->setFlash('warning', 'Данный мир удален.');
            return $this->redirect('/');
        }

        $file = ($file = Files::findOne(['id' => $model->file_id]))
            ? $file : new Files();

        if ($model->load(Yii::$app->request->post()) && $model->validate() &&
            $file->load(Yii::$app->request->post()) && $file->validate()
        ) {
            $file->file = UploadedFile::getInstance($file, 'file');
            if (isset($file->file)) {
                if ($file->file->extension != 'png'
                    && $file->file->extension != 'jpg' && $file->file->extension != 'jpeg'
                ) {
                    Yii::$app->session->setFlash('error', 'Файл должен иметь расширение *.png, *.jpg или *.jpeg .');
                    return $this->render('edit', [
                        'model' => $model,
                        'file' => $file,
                    ]);
                } else {
                    if (isset($model->file_id)) {
                        unlink($model->getAvatar()->one()->path);
                    }
                    $file->file->saveAs('uploads/pictures/worlds/avatars/' . time() .
                        str_replace('\'', '', (str_replace('"', '', str_replace(' ', '_', $model->name)))) .
                        '.' . $file->file->extension);
                    $file->path = 'uploads/pictures/worlds/avatars/' . time() .
                        str_replace('\'', '', (str_replace('"', '', str_replace(' ', '_', $model->name)))) .
                        '.' . $file->file->extension;
                    $file->updateFile($file);
                    $model->file_id = $file->id;
                }
            }
            if ($model->updateWorld($model)) {
                Yii::$app->session->setFlash('success', 'Редактирование произведено успешно.');
                return $this->redirect(['edit',
                    'id' => $model->id
                ]);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при редактировании.');
            }
        }

        if (Yii::$app->request->post('delete_avatar')) {
            if (!$model->deleteAvatar()) {
                Yii::$app->session->setFlash('error', 'Ошибка удаления изображения.');
            }
        }

        if (Yii::$app->request->post('delete')) {
            if (!$model->deleteWorld()) {
                Yii::$app->session->setFlash('error', 'Ошибка удаления мира.');
            } else {
                return $this->redirect('/');
            }
        }

        return $this->render('edit', [
            'model' => $model,
            'file' => $file,
        ]);
    }

    public function actionReset()
    {
        $model = ($model = World::findOne(['id' => Yii::$app->request->get('id')])) ? $model : new World();

        if ($model->id === null) {
            Yii::$app->session->setFlash('error', 'Ошибка восстановления.');
            return $this->goHome();
        }

        if ($model->status === World::STATUS_DELETED && $model->user_id == Yii::$app->user->id) {
            if ($model->resetWorld()) {
                Yii::$app->session->setFlash('success', 'Мир "' . $model->name . '" успешно восстановлен.');
                return $this->redirect('/world/edit?id=' . $model->id);
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка восстановления.');
                return $this->goHome();
            }
        } else {
            Yii::$app->session->setFlash('error', 'Ошибка восстановления.');
            return $this->goHome();
        }
    }

}
