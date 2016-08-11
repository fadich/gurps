<?php
namespace frontend\models;

use yii\base\Model;
use common\models\User;

/**
 * Signup form
 */
class SignupForm extends Model
{
    public $email;
    public $password;
    public $rePassword;
    public $userId;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'trim'],
            ['email', 'required', 'message' => 'Необходимо ввести адрес электронной почты.'],
            ['email', 'email', 'message' => 'Значение поля не является правильным email адресом'],
            [
                'email', 'string', 'max' => 255, 'min' => 6,
                'tooShort' => 'Адрес электронной почты должен содержать не менее 3-х символов',
                'tooLong' => 'Адрес электронной почты должен содержать не более 255-х символов',
            ],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'Извините, данный адрес уже занят.'],

            ['password', 'match', 'pattern' => '/[a-zA-Z0-9_-]+/',
                'message' => 'Пароль может состоять из только символов латинского алфавита, а также дифисов или нижних подчеркиваний.'],
            ['password', 'required', 'message' => 'Необходимо ввести пароль.'],
            ['password', 'string', 'min' => 6, 'message' => 'Парль должен содержать не менее 6-и символов.'],

            ['rePassword', 'required', 'message' => 'Необходимо ввести пароль.'],
            ['rePassword', 'validateRePassword'],
        ];
    }

    
    public function validateRePassword($attribute)
    {
        if (!$this->hasErrors()):
            if ($this->password !== $this->rePassword):
                $this->addError($attribute, 'Пароли не совпадают.');
            endif;
        endif;
    }

    public function attributeLabels()
    {
        return [
            'email' => '',
            'password' => '',
            'rePassword' => '',
        ];
    }

    /**
     * Signs user up.
     *
     * @return User|null the saved model or null if saving fails
     */
    public function signup()
    {
        if (!$this->validate()) {
            return null;
        }
        $user = new User();
//        $user->email = $this->email;
//        $user->setPassword($this->password);
//        $user->generateAuthKey();
//        $user->generatePasswordResetToken();
//        $user->created_at = time();
//        $user->updated_at = time();
        $user->db->createCommand("INSERT INTO user " .
            "(email, auth_key, password_hash, created_at, updated_at) VALUES ".
            "(:email, :auth_key, :password_hash, :created_at, :updated_at)")
            ->bindValues([
                ':email' => $this->email,
                ':auth_key' => \Yii::$app->security->generateRandomString(),
                ':password_hash' => \Yii::$app->security->generatePasswordHash($this->password),
                ':created_at' => time(),
                ':updated_at' => time(),
            ])->execute();
        $user = User::findByEmail($this->email);
        return $user;
    }
}
