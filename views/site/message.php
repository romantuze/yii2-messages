<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;

$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?> пользователю <?=$second_user_login; ?> (<?=$second_user_email; ?>)</h1>
 

    <?php if (empty($messages_list)) : ?>
        <p>Нет сообщений.</p>
        <hr />
    <?php endif; ?>

    <?php foreach($messages_list as $message) : ?>
        <?php if ($current_user_id == $message->user_id_1) : ?>
        <div class="message my-message">
            <p>
            <div class="message-date"><?=$message->created; ?></div>
            <div class="message-text"><?=$message->msg; ?></div>
            <div class="message-buttons">
            <a href="<?=Url::toRoute(['editmessage','id'=>$message->id])?>">редактировать сообщение</a><br>
            <a href="<?=Url::toRoute(['deletemessage','id'=>$message->id])?>">удалить сообщение</a>
            </div>
            </p>
            <hr />
        </div>
        <?php else : ?>
        <div class="message">
            <p>
            <div class="message-date"><?=$message->created; ?></div>
            <div class="message-text"><?=$message->msg; ?></div>
            </p>
            <hr />
        </div>
        <?php endif; ?>
    <?php endforeach; ?>

     <?php if (!empty($messages_list)) : ?>
        <p><a href="<?=Url::toRoute(['hidemessages','id'=>$second_user_id])?>">Скрыть цепочку сообщений</a></p>
    <?php endif; ?>  

    <h3>
       Напишете сообщение:
    </h3>

    <?php $form = ActiveForm::begin([
        'id' => 'msg-form',
        'layout' => 'horizontal',
        'fieldConfig' => [
            'template' => "{label}\n<div class=\"col-lg-3\">{input}</div>\n<div class=\"col-lg-8\">{error}</div>",
            'labelOptions' => ['class' => 'col-lg-1 control-label'],
        ],
    ]); ?>
        <?= $form->field($model, 'msg')->textarea(['rows' => '6'])->label('Сообщение') ?>     

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>

</div>
