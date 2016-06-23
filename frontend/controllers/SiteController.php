<?php
namespace frontend\controllers;

use common\models\User;
use frontend\models\Profile;
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
                        'actions' => ['login', 'signup', 'error', 'about', 'request-password-reset'],
                        'allow' => true,
                    ],
                    [
                        'actions' => ['logout', 'index', 'profile', 'world'],
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
        if (Yii::$app->request->post('create_world') &&  Yii::$app->request->post('create_world') == 1) {
            return $this->redirect('index.php/site/world');
        }

        return $this->render('index');
    }

    /**
     * Logs in a user.
     *
     * @return mixed
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
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
            if ($user = $model->signup()) {
                $profile->user_id = $user->id;
                if ($profile->initProfile($profile)) {
                    if (Yii::$app->getUser()->login($user)) {
                        return $this->goHome();
                    }
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
     * }s profile.
     *
     * @return mixed
     */
    public function actionProfile()
    {
        $model = ($model = Profile::findOne(['user_id' => Yii::$app->user->id]))
            ? $model : new Profile();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $model->file = UploadedFile::getInstance($model, 'file');
            if (isset($model->file)) {
                if ($model->file->extension != 'png'
//                    && $model->file->extension != 'jpg' && $model->file->extension != 'jpeg'
                ) {
                    Yii::$app->session->setFlash('error', 'Файл должен иметь расширение *.png .');
                    return $this->render('profile', [
                        'model' => $model
                    ]);
                } else {
                    $model->file->saveAs('uploads/pictures/avatars/' . $model->id . '_' .
                        str_replace('\'', '', (str_replace('"', '', str_replace(' ', '_', $model->name)))) .
                        '.' . $model->file->extension);
                    $model->avatar = 'uploads/pictures/avatars/' . $model->id . '_' .
                        str_replace('\'', '', (str_replace('"', '', str_replace(' ', '_', $model->name)))) .
                        '.' . $model->file->extension;
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

        return $this->render('profile', [
            'model' => $model
        ]);
    }

    public function actionUser()
    {

    }

    /**
     * @return mixed
     */
    public function actionWorld()
    {
        $model = new World();

        return $this->render('world',
            [
                'model' => $model,
            ]);
    }

    /**
     * Requests password reset.
     *
     * @return mixed
     */
    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                Yii::$app->session->setFlash('success', 'Check your email for further instructions.');

                return $this->goHome();
            } else {
                Yii::$app->session->setFlash('error', 'Sorry, we are unable to reset password for email provided.');
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
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
}
