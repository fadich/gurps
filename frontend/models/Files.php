<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "files".
 *
 * @property integer $id
 * @property string $path
 * @property integer $status
 */
class Files extends \yii\db\ActiveRecord
{
    const STATUS_DELETED = 0;
    const STATUS_ACTIVE = 10;

    public $file;

    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'files';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            ['status', 'default', 'value' => self::STATUS_ACTIVE],
            ['status', 'in', 'range' => [self::STATUS_ACTIVE, self::STATUS_DELETED]],

            [['path'], 'string', 'max' => 128],

            ['file', 'safe'],
            [['file'], 'file', 'skipOnEmpty' => true],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'path' => 'Path',
            'status' => 'Status',
        ];
    }

    /**
     * @return bool
     */
    public function updateFile ()
    {
        if(!$this->validate()) {
            return false;
        }

        $file = new Files();

        $file->path = $this->path;
        return $this->save() ? true : false;

    }
}
