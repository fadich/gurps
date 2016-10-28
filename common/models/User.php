<?php
namespace common\models;

use frontend\models\Profile;
use frontend\models\Session;
use frontend\models\SignupForm;
use frontend\models\World;
use Yii;
use yii\base\NotSupportedException;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * User model
 *
 * @property integer $id
 * @property string $email
 * @property string $password_hash
 * @property string $password_reset_token
 * @property string $auth_key
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $password write-only password
 *
 * @property Profile $profile
 */
class User extends ActiveRecord implements IdentityInterface
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $password;
    public $newPassword;
    public $rePassword;
    public $oldEmail;
    public $order;
    public $sort;

    private $_user;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return '{{%user}}';
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'email'         => 'Адрес электронной почты',
            'auth_key'      => 'Пользовательский ключ',
            'password'      => 'Пароль',
            'newPassword'   => 'Новый пароль',
            'rePassword'    => 'Повторите пароль (новый)',
        ];
    }

    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            TimestampBehavior::className(),
        ];
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['oldEmail', 'trim'],
            ['oldEmail', 'email'],
            ['oldEmail', 'string', 'max' => 255, 'min' => 6],

            ['email', 'trim'],
            ['email', 'required'],
            ['email', 'email'],
            ['email', 'string', 'max' => 255, 'min' => 6],
            ['email', 'unique', 'targetClass' => '\common\models\User',
                'message' => 'Извините, данный адрес уже занят.'],

            ['password', 'required'],
            ['password', 'string', 'min' => 6],
            ['password', 'validatePass'],
            
            [['newPassword', 'rePassword'], 'string', 'min' => 6],
            ['newPassword',  'validateRePassword'],

            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @param $attribute
     * @return bool
     */
    public function validateRePassword($attribute)
    {
        if (!$this->hasErrors()):
            if ($this->newPassword !== $this->rePassword):
                $this->addError($attribute, 'Пароли не совпадают.');
                return false;
            endif;
        endif;
        return true;
    }

    /**
     * Validates the password.
     * This method serves as the inline validation for password.
     *
     * @param string $attribute the attribute currently being validated
     * @param array $params the additional name-value pairs given in the rule
     */
    public function validatePass($attribute, $params)
    {
        if (!$this->hasErrors()) {
            $user = $this->getUser();
            if (!$user || !$user->validatePassword($this->password)) {
                $this->addError($attribute, 'Неверный адрес или пароль.');
            }
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
            $this->_user = User::findByEmail($this->oldEmail);
        }

        return $this->_user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return static::findOne(['id' => $id, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        throw new NotSupportedException('"findIdentityByAccessToken" is not implemented.');
    }

    /**
     * Finds user by email
     *
     * @param string email
     * @return static|null
     */
    public static function findByEmail($email)
    {
        return static::findOne(['email' => $email, 'status' => self::STATUS_ACTIVE]);
    }

    /**
     * Finds user by password reset token
     *
     * @param string $token password reset token
     * @return static|null
     */
    public static function findByPasswordResetToken($token)
    {
        if (!static::isPasswordResetTokenValid($token)) {
            return null;
        }

        return static::findOne([
            'password_reset_token' => $token,
            'status' => self::STATUS_ACTIVE,
        ]);
    }

    /**
     * Finds out if password reset token is valid
     *
     * @param string $token password reset token
     * @return boolean
     */
    public static function isPasswordResetTokenValid($token)
    {
        if (empty($token)) {
            return false;
        }

        $timestamp = (int)substr($token, strrpos($token, '_') + 1);
        $expire = Yii::$app->params['user.passwordResetTokenExpire'];
        return $timestamp + $expire >= time();
    }

    /**
     * @inheritdoc
     */
    public function getId()
    {
        return $this->getPrimaryKey();
    }

    /**
     * @inheritdoc
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * @inheritdoc
     */
    public function validateAuthKey($authKey)
    {
        return $this->getAuthKey() === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return boolean if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->security->validatePassword($password, $this->password_hash);
    }

    /**
     * Generates password hash from password and sets it to the model
     *
     * @param string $password
     */
    public function setPassword($password)
    {
        $this->password_hash = Yii::$app->security->generatePasswordHash($password);
    }

    /**
     * Generates "remember me" authentication key
     */
    public function generateAuthKey()
    {
        $this->auth_key = Yii::$app->security->generateRandomString(32);
    }

    /**
     * Generates new password reset token
     */
    public function generatePasswordResetToken()
    {
        $this->password_reset_token = Yii::$app->security->generateRandomString() . '_' . time();
    }

    /**
     * Removes password reset token
     */
    public function removePasswordResetToken()
    {
        $this->password_reset_token = null;
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getProfile()
    {
        return $this->hasOne(Profile::className(), ['user_id' => 'id'])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getSessionTable()
    {
        return $this->hasOne(Session::className(), ['user_id' => 'id'])->one();
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getWorlds()
    {
        return $this->hasMany(World::className(), ['user_id' => 'id'])->all();
    }

    /**
     * Set Online status to user
     */
    public function setOnline()
    {
        $session            = ($session = $this->getSessionTable()) ? $session : new Session();
        $session->user_id   = $this->id;
        $session->time      = time();
        $session->updateSession($session);
    }

    /**
     * @param null $time
     * @return string
     */
    public function getStatusStr($time = null)
    {
        if ($time === null) {
            $status = $this->getSessionTable()->time ?? 0;
        } else {
            $status = $time;
        }
//        $timezone = 0;
        if ((time() - $status) < 600) {
            return 'Онлайн';
        } elseif ((time() - $status) < 86400) {
            return 'был в сети ' . date('в H:i', $status);
        } elseif ((time() - $status) < 31104000) {
            return 'был в сети ' . date('d.m.Y в H:i', $status);
        } else {
            return 'Оффлайн';
        }
    }

    public function isOnline(int $time = 0)
    {
        return (time() - ($time ? $time : (int)$this->getSessionTable()->time)) < 600;
    }

    public function getEmail()
    {
        return $this->email;
    }

    /**
     * @param User $user
     * @return bool
     */
    public function updateUser(User $user)
    {
        if (!$this->validate()) {
            return null;
        }
        $this->email = $user->email;
        $this->generateAuthKey();
        if ($user->newPassword !== null && strlen($user->newPassword) > 5 && $user->newPassword === $this->rePassword) {
            $this->setPassword($user->newPassword);
        }
        return $this->save() ? true : false;
    }

    public function getUsers($order = 'name', $sort = 'asc')
    {
        $orderFilter = [
            'name',
            'date',
            'worlds',
        ];
        $sortFilter = [
            'asc',
            'desc',
        ];
        $order    = (in_array($order, $orderFilter)) ? $order : 'name';
        $sort     = (in_array($sort,  $sortFilter))  ? $sort  : 'asc';
        \Yii::$app->db->createCommand("SET SQL_MODE = ' '")->execute();
        try {
            $users = \Yii::$app->db->createCommand(
                "SELECT 
                   us.id             AS id,
                   pr.name           AS name,
                   us.created_at     AS date,
                   pr.birthday       AS birthday,
                   pr.info           AS info,
                   fl.path           AS avatar,
                   sn.time           AS status,
                   count(wd.id)      AS worlds
                 FROM user           us
                 LEFT JOIN profile   pr ON us.id     = pr.user_id
                 LEFT JOIN files     fl ON pr.avatar = fl.id
                 LEFT JOIN session   sn ON us.id     = sn.user_id
                 LEFT JOIN world     wd ON us.id     = wd.user_id
                 WHERE us.status = :status AND wd.status = :world_status /* AND avatar > 0 */
                 GROUP BY us.id
                 ORDER BY {$order} {$sort}"
            )->bindValues([
                ':status'       => self::STATUS_ACTIVE,
                'world_status'  => World::STATUS_ACTIVE,
            ])->queryAll();
            $this->sort         = $sort === 'asc' ? 'desc' : 'asc';
            $this->order        = $order;
            return $users;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
