<?php

/* @var $this yii\web\View */
use yii\helpers\Html;
use yii\bootstrap\ActiveForm;
use yii\helpers\Url;
$this->title = 'Сообщения';
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="site-about">
    <h1><?= Html::encode($this->title) ?></h1>

    <h3>
       Список собеседников:
    </h3>
    <?php if (empty($users_list)) : ?>
        <p>Нет сообщений.</p>
    <?php endif; ?>
     <hr />
    <?php foreach($users_list as $user) : ?>
        <div class="message">
        <p><a href="<?=Url::toRoute(['message','id'=>$user["id"]])?>"><?=$user["login"]; ?> (<?=$user["email"]; ?>)</a> </p>
       <p> <a href="<?=Url::toRoute(['message','id'=>$user["id"]])?>">Написать сообщение</a></p>
       <hr />
     </div>
    <?php endforeach; ?>

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

        <?= $form->field($model, 'loginemail')->textInput(['autofocus' => true])->label('Логин или email') ?>

        <?= $form->field($model, 'msg')->textarea(['rows' => '6'])->label('Сообщение') ?>     

        <div class="form-group">
            <div class="col-lg-offset-1 col-lg-11">
                <?= Html::submitButton('Отправить', ['class' => 'btn btn-primary', 'name' => 'login-button']) ?>
            </div>
        </div>

    <?php ActiveForm::end(); ?>


</div>
