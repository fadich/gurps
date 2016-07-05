<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "session".
 *
 * @property integer $id
 * @property integer $user_id
 * @property integer $time
 */
class Session extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'session';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['user_id', 'time'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'time' => 'Time',
        ];
    }

    /**
     * @inheritdoc
     */
    public static function findByUserId($user_id)
    {
        return static::findOne(['user_id' => $user_id]);
    }

    /**
     * @param Session $session
     * @return Session|bool
     */
    public function updateSession ($session)
    {
        $this->user_id = $session->user_id;
        $this->time = $session->time;

        return $session->save() ? $session : false;
    }
}
