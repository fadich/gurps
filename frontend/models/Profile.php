<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;
use yii\web\UploadedFile;
use yii\behaviors\TimestampBehavior;

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
            ['name', 'required'],
            ['name', 'trim'],
            ['name', 'string', 'max' => 24, 'min' => 3],

            ['user_id', 'integer'],
            ['sex', 'string', 'max' => 10],
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
            'name' => 'Имя',
            'sex' => 'Пол',
            'birthday' => 'Дата рождения',
            'avatar' => '',
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

        return $profile->save() ? $profile : false;
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
}
