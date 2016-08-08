<?php
namespace frontend\controllers;

use common\models\User;
use frontend\models\Files;
use frontend\models\Profile;
use frontend\models\Session;
use frontend\models\World;
use Yii;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use common\models\LoginForm;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\web\UploadedFile;
use yii\web\Cookie;

/**
 * Site controller
 */
class SiteController extends Controller
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
                        'actions' => ['login', 'signup', 'error', 'about', 'request-password-reset',
                            'reset-password'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile'],
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

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
        }

        if (Yii::$app->request->post('worldEdit') != null) {
            return $this->redirect(['world/edit',
                'id' => Yii::$app->request->post('worldEdit'),
            ]);
        }

        if (Yii::$app->request->cookies['worldDeleted']) {
            Yii::$app->session->setFlash('info', 'Мир <i>"' .
                Yii::$app->request->cookies['worldName'] . '"</i> успешно удален.');
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'worldDeleted',
                'value' => null,
            ]));
            Yii::$app->response->cookies->add(new Cookie([
                'name' => 'worldName',
                'value' => null,
            ]));
        }

        $model = new World();

        if (Yii::$app->request->post('create_world') && Yii::$app->request->post('create_world') == 1) {
            return $this->redirect('index.php/world/edit');
        }

        return $this->render('index', [
            'model' => $model,
        ]);
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            $user = User::findByEmail($model->email);
            $user->setOnline();
            return $this->goHome();
        } else {
            return $this->render('login', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        $user = User::findIdentity(Yii::$app->user->id);
        $user->setOnline();
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return mixed
     */
    public function actionContact()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
        }

        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail(Yii::$app->params['adminEmail'])) {
                Yii::$app->session->setFlash('success', 'Thank you for contacting us. We will respond to you as soon as possible.');
            } else {
                Yii::$app->session->setFlash('error', 'There was an error sending email.');
            }

            return $this->refresh();
        } else {
            return $this->render('contact', [
                'model' => $model,
            ]);
        }
    }

    /**
     * Displays about page.
     *
     * @return mixed
     */
    public function actionAbout()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
        }

        return $this->render('about');
    }

    /**
     * Signs user up.
     *
     * @return mixed
     */
    public function actionSignup()
    {
        $model = new SignupForm();
        $profile = new Profile();
        if ($model->load(Yii::$app->request->post()) && $profile->load(Yii::$app->request->post())) {
            if ($model->validate() && $profile->validate()) {
                $user = new User();
                if ($user->signup($model)) {
                    if (Yii::$app->user->login($user, 0)) {
                        $profile->user_id = Yii::$app->user->id;
                        if ($profile->initProfile($profile)) {
                            if (!Yii::$app->user->isGuest) {
                                $user = User::findIdentity(Yii::$app->user->id);
                                $user->setOnline();
                                return $this->goHome();
                            }
                            return $this->goHome();
                        }
                    }
                } else {
                    Yii::$app->session->setFlash('error', 'Ошибка регистрации.');
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
            'profile' => $profile,
        ]);
    }

    /**
     * Display
     * profile.
     *
     * @return mixed
     */
    public function actionProfile()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
        }

        $model = ($model = Profile::findOne(['user_id' => Yii::$app->user->id]))
            ? $model : new Profile();
        $file = ($file = Files::findOne(['id' => $model->avatar]))
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
                    return $this->render('profile', [
                        'model' => $model,
                        'file' => $file,
                    ]);
                } else {
                    if (isset($model->avatar)) {
                        unlink($model->getAvatar()->one()->path);
                    }
                    $file->file->saveAs('uploads/pictures/avatars/' . time() .
                        str_replace('\'', '', (str_replace('"', '', str_replace(' ', '_', $model->name)))) .
                        '.' . $file->file->extension);
                    $file->path = 'uploads/pictures/avatars/' . time() .
                        str_replace('\'', '', (str_replace('"', '', str_replace(' ', '_', $model->name)))) .
                        '.' . $file->file->extension;
                    $file->updateFile($file);
                    $model->avatar = $file->id;
                }
            }
            if ($model->updateProfile($model)) {
                Yii::$app->session->setFlash('success', 'Редактирование произведено успешно.');
            } else {
                Yii::$app->session->setFlash('error', 'Ошибка при редактировании.');
            }
        }

        if (Yii::$app->request->post('delete_avatar')) {
            if (!$model->deleteAvatar()) {
                Yii::$app->session->setFlash('error', 'Ошибка удаления изображения.');
            }
        }

        if (Yii::$app->request->post('User')) {
            $user = $model->getUser()->one();
            $user->oldEmail = $user->email;
            $user->email = Yii::$app->request->post('User')['email'];
            if ($user->load(Yii::$app->request->post()) && $user->validate()) {
                if ($user->updateUser($user)) {
                    Yii::$app->session->setFlash('success', 'Редактирование произведено успешно.');
                }
            } else {
                Yii::$app->session->setFlash('info', 'Изменения не сохранены.' .
                    '<br>Возможно, данные введены не корректно.');
            }
        }

        return $this->render('profile', [
            'model' => $model,
            'file' => $file,
        ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
            return $this->goHome();
        }

        $model = new PasswordResetRequestForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Проверьте свою электронную почту для получения
                дальнейших инструкций.');
                return $this->redirect('login');
            } else {
                Yii::$app->session->setFlash('error', 'К сожалению, пароль не может быть отправлен
                по данному адресу электронной почты.');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        if (!Yii::$app->user->isGuest) {
            $user = User::findIdentity(Yii::$app->user->id);
            $user->setOnline();
            return $this->goHome();
        }

        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'Новый парль успешно сохранен.');
            return $this->redirect('login');
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
