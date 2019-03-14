<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * MessageUserForm is the model behind the reg form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class MessageUserForm extends Model
{
    public $msg;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['msg'], 'required','message'=>'Заполните поле'],
        ];
    }

    
}
