<?php

/* @var $this yii\web\View */
use yii\helpers\Url;
$this->title = 'Сообщения';
?>
<div class="site-index">

    <div class="jumbotron">
        <h1>Добро пожаловать!</h1>

        <p class="lead">You have successfully created your Yii-powered application.</p>

        <?php if (!Yii::$app->user->isGuest) : ?><p><a class="btn btn-lg btn-success" href="<?=Url::toRoute(['messages'])?>">Сообщения</a></p><?php endif; ?>
    </div>

    <div class="body-content">

    </div>
</div>
