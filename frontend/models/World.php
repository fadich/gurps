<?php

namespace frontend\models;

use common\models\User;
use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "world".
 *
 * @property integer $id
 * @property integer $user_id
 * @property string $name
 * @property string $description
 * @property integer $file_id
 * @property integer $status
 * @property integer $created_at
 * @property integer $updated_at
 */
class World extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $search;

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
    public static function tableName()
    {
        return 'world';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['file_id'], 'integer'],
            [['description', 'search'], 'string'],
            [['name'], 'string', 'max' => 64, 'min' => 4],
            [['name'], 'unique'],
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'name' => 'Название',
            'description' => 'Описание',
        ];
    }

    /**
     * @param World $world
     * @return World|null
     */

    public function updateWorld($world)
    {
        if (!$this->validate()) {
            return null;
        }

        $world->user_id = Yii::$app->user->id;
        $world->name = $this->name;
        $world->file_id = $this->file_id;
        $world->description = $this->description;

        return $world->save() ? $world : false;
    }

    /**
     * @return bool
     */
    public function deleteAvatar()
    {
        if (!isset($this->file_id)) {
            return false;
        }
        if (!Files::findOne(['id' => $this->file_id])->deleteFile()) {
            return false;
        }
        $this->file_id = null;
        return $this->save() ? true : false;
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getAvatar()
    {
        return $this->hasOne(Files::className(), ['id' => 'file_id']);
    }

    /**
     * @return \yii\db\ActiveQuery
     */

    public function getOwner()
    {
        return $this->hasOne(User::className(), ['id' => 'user_id'])->one()->getProfile();
    }

    public function deleteWorld()
    {
        $this->name .= '-deleted' . $this->id;
        $this->status = self::STATUS_DELETED;
        return $this->save() ? true : false;
    }

    public function resetWorld()
    {
        $this->name = mb_substr($this->name, 0, $this->name - mb_strlen('-deleted' . $this->id,
                'UTF-8'), 'UTF-8');

        $checkName = self::findOne(['name' => $this->name]);

        if (isset($checkName->name) && strtolower($this->name) == strtolower($checkName->name)) {
            $this->name .= '_' . $this->id;
            $checkName = self::findOne(['name' => $this->name]);
            if (isset($checkName->name) && strtolower($this->name) == strtolower($checkName->name)) {
                $this->name .= '_' . time();
                $checkName = self::findOne(['name' => $this->name]);
                if (isset($checkName->name) && $this->name == $checkName->name) {
                    $this->name = Yii::$app->security->generateRandomString(18);
                }
            }
        }

        $this->status = self::STATUS_ACTIVE;

        return $this->save() ? true : false;
    }
}
