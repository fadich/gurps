<?php
namespace common\models;

use Yii;
use yii\base\ErrorException;
use yii\base\Model;

/**
 * Login form
 */
class LoginForm extends Model
{
    public $email;
    public $password;
    public $rememberMe = true;

    private $_user;


    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            // email and password are both required
            ['email', 'required', 'message' => 'Необходимо ввести адрес электронной почты.'],
            ['password', 'required', 'message' => 'Необходимо ввести пароль.'],
            ['email', 'email', 'message' => 'Значение поля не является правильным email адресом'],
            // password is validated by validatePassword()
            ['password', 'validatePassword'],
            ['rememberMe', 'boolean'],
        ];
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePassword($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный адрес электронной почты или пароль.');
            }
        }
    }

    public function attributeLabels()
    {
        return [
            'email' => '',
            'password' => '',
            'rememberMe' => 'Запомнить меня'
        ];
    }

    /**
     * Logs in a user using the provided email and password.
     *
     * @return boolean whether the user is logged in successfully
     */
    public function login()
    {
        if ($this->validate()) {
            return Yii::$app->user->login($this->getUser(), $this->rememberMe ? 3600 * 24 * 30 : 0);
        } else {
            return false;
        }
    }
    
    public function logUserSession()
    {
        try {
            \Yii::$app->db->createCommand(
                "INSERT INTO gr_user_session_info (`user_id`, `time`, `ip_address`, `soft`, `language`, `query`) 
                 VALUES (:user_id, :time, :ip_address, :soft, :language, :query)"
            )->bindValues([
                ':user_id' => \Yii::$app->user->identity->getId(),
                ':time' => time(),
                ':ip_address' => $_SERVER['HTTP_X_REAL_IP'],
                ':soft' => $_SERVER['HTTP_USER_AGENT'],
                ':language' => $_SERVER['HTTP_ACCEPT_LANGUAGE'],
                ':query' => $_SERVER['QUERY_STRING'],
            ])->execute();
        } catch (ErrorException $e) {
            echo 'Произошла ошибка' . $e;
        }
    }

    /**
     * Finds user by [[email]]
     *
     * @return User|null
     */
    protected function getUser()
    {
        if ($this->_user === null) {
            $this->_user = User::findByEmail($this->email);
        }

        return $this->_user;
    }
}
