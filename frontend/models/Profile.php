<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;
use common\models\User;

/**
 * This is the model class for table "profile".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $sex
 * @property string $birthday
 * @property string $avatar
 * @property integer $created_at
 * @property integer $updated_at
 * @property string $info
 */
class Profile extends \yii\db\ActiveRecord
{
    public $updated_at_date;
    public $created_at_date;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'profile';
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
            ['name', 'required', 'message' => 'Необходимо ввести имя пользовтеля.'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 24, 'min' => 3, 'message' => '"Имя пользователя" может содержать от 3-х до 24-х символов'],
            ['name', 'match', 'pattern' => '/[a-zA-Zа-яА-Я0-9_-]+/',
                'message' => 'Имя состоит из букв латинского или русского алфавитов, дифисов, подчеркиваний или апострофов.'],

            [['user_id', 'updated_at_date', 'created_at_date'], 'integer'],
            ['sex', 'string', 'max' => 10],
            ['sex', 'in', 'range' => ['мужской', 'женский']],
            ['birthday', 'string', 'max' => 16],
            ['avatar', 'integer'],
            ['info', 'trim'],
            ['info', 'string', 'max' => 1024],
//
//            ['file', 'safe'],
//            [['file'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'user_id' => '',
            'name' => '',
            'sex' => 'Пол',
            'birthday' => 'Дата рождения',
            'avatar' => '',
            'updated_at_date' => 'Последнее обновление',
            'created_at_date' => 'Дата создания',
//            'file' => 'Аватар',
            'info' => 'Дополнительная информация'
        ];
    }

    /**
     * @param Profile $profile
     * @return Profile|null
     */
    public function initProfile($profile)
    {
        if (!$this->validate()) {
            return null;
        }
        $profile->name = $this->name;
        return $profile->save() ? $profile : null;
    }

    /**
     * @param Profile $profile
     * @return Profile|null
     */

    public function updateProfile ($profile)
    {
        if (!$this->validate()) {
            return null;
        }
        $profile->user_id = Yii::$app->user->id;
        $profile->name = $this->name;
        $profile->sex = $this->sex;
        $profile->birthday = $this->birthday;
        $profile->avatar = $this->avatar;
        $profile->info = $this->info;

        return $profile->save() ? $profile : false;
    }

    public function deleteAvatar ()
    {
        if (!isset($this->avatar)) {
            return false;
        }
        if (!Files::findOne(['id' => $this->avatar])->deleteFile()){
            return false;
        }
        $this->avatar = null;
        return $this->save() ? true : false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getAvatar ()
    {
        return $this->hasOne(Files::className(), ['id' => 'avatar']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */
    public function getUser ()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id']);
    }
}