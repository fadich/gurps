<?php

namespace frontend\models;

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
            [['description'], 'string'],
            [['name'], 'string', 'max' => 64, 'min' => 4],
            [['name'], 'unique'],
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
}
