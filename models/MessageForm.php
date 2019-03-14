<?php

namespace app\models;

use Yii;
use yii\base\Model;

/**
 * MessageForm is the model behind the reg form.
 *
 * @property User|null $user This property is read-only.
 *
 */
class MessageForm extends Model
{
    public $loginemail;
    public $msg;

    /**
     * @return array the validation rules.
     */
    public function rules()
    {
        return [
            [['loginemail', 'msg'], 'required','message'=>'Заполните поле'],
        ];
    }

    
}
