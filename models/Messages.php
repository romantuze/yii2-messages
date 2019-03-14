<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "messages".
 *
 * @property int $id
 * @property string $msg
 * @property int $user_id_1
 * @property int $user_id_2
 * @property int $user_hidden_1
 * @property int $user_hidden_2
 * @property string $created
 */
class Messages extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'messages';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['msg'], 'string'],
            [['user_id_1', 'user_id_2'], 'required'],
            [['user_id_1', 'user_id_2', 'user_hidden_1', 'user_hidden_2'], 'integer'],
            [['created'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'msg' => 'Msg',
            'user_id_1' => 'User Id 1',
            'user_id_2' => 'User Id 2',
            'user_hidden_1' => 'User Hidden 1',
            'user_hidden_2' => 'User Hidden 2',
            'created' => 'Created',
        ];
    }
}
