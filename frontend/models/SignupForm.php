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


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255, 'min' => 6],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'Извините, данный адрес уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],

            ['rePassword', 'required'],
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
            'email' => 'Адрес электронной почты',
            'password' => 'Пароль',
            'rePassword' => 'Повторите пароль',
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
        $user->email = $this->email;
        $user->setPassword($this->password);
        $user->generateAuthKey();
        
        return $user->save() ? $user : null;
    }
}
