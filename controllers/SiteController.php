<?php

namespace app\controllers;

use app\models\History;
use app\models\LoginForm;
use app\models\MessageForm;
use app\models\Messages;
use app\models\MessageUserForm;
use app\models\RegisterForm;
use app\models\User;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only'  => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow'   => true,
                        'roles'   => ['@'],
                    ],
                ],
            ],
            'verbs'  => [
                'class'   => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    public function actions()
    {
        return [
            'error'   => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class'           => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    public function actionIndex()
    {
        return $this->render('index');
    }

    public function actionMessages()
    {

        if (Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $current_user_id = Yii::$app->user->id;

        $model = new MessageForm();

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $loginemail = $model->loginemail;
            $msg        = $model->msg;

            if (User::findByUsername($loginemail)) {
                $second_user_id = User::findByUsername($loginemail)->id;
            } else if (User::findByEmail($loginemail)) {
                $second_user_id = User::findByEmail($loginemail)->id;
            } else {
                Yii::$app->session->setFlash('error', "Пользователь не найден");
            }
            if (!empty($current_user_id) && !empty($msg) && !empty($second_user_id)) {



                $users_query = History::find()->Where(['user_id_2' => $second_user_id])->all();

                if (empty($users_query)) {
                    $history = new History;
                    $history->user_id_1 = $current_user_id;
                    $history->user_id_2 = $second_user_id;
                    $history->save();
                }
                $messages            = new Messages;
                $messages->msg       = $msg;
                $messages->user_id_1 = $current_user_id;
                $messages->user_id_2 = $second_user_id;

                if ($messages->save()) {
                    Yii::$app->session->setFlash('success', "Сообщение отправлено");
                    return $this->refresh();
                }
            }
        }

        $users_query = History::find()->orwhere(['user_id_1' => $current_user_id])->orWhere(['user_id_2' => $current_user_id])->all();

        $users_list = [];

        foreach ($users_query as $user) {
            if ($user->user_id_1 != $current_user_id) {
                $new_user_id = $user->user_id_1;
                $new_user_s  = 'user_id_1';
            }
            if ($user->user_id_2 != $current_user_id) {
                $new_user_id = $user->user_id_2;
                $new_user_s  = 'user_id_2';
            }
            $new_user          = [];
            $new_user["id"]    = $new_user_id;
            $new_user["login"] = User::findById($new_user_id)->login;
            $new_user["email"] = User::findById($new_user_id)->email;
            array_push($users_list, $new_user);
        }

        return $this->render('messages', [
            'model'      => $model,
            'users_list' => $users_list,
        ]);
    }

    public function actionMessage()
    {
        if (isset($_GET['id']) && $_GET['id'] != '' && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            $second_user_id = $_GET['id'];

            $second_user_login = User::findById($second_user_id)->login;
            $second_user_email = User::findById($second_user_id)->email;

            $current_user_id = Yii::$app->user->id;

            $messages_list = Messages::find()->orwhere(['user_id_1' => $current_user_id, 'user_id_2' => $second_user_id, 'user_hidden_1' => 0])->orWhere(['user_id_1' => $second_user_id, 'user_id_2' => $current_user_id, 'user_hidden_2' => 0])->all();

            $model = new MessageUserForm();

            if ($model->load(Yii::$app->request->post()) && $model->validate()) {
                $messages            = new Messages;
                $messages->msg       = $model->msg;
                $messages->user_id_1 = $current_user_id;
                $messages->user_id_2 = $second_user_id;
                if ($messages->save()) {
                    Yii::$app->session->setFlash('success', "Сообщение отправлено");
                    return $this->refresh();
                }
            }

            return $this->render('message', [
                'model'             => $model,
                'messages_list'     => $messages_list,
                'current_user_id'   => $current_user_id,
                'second_user_id'    => $second_user_id,
                'second_user_login' => $second_user_login,
                'second_user_email' => $second_user_email,
            ]);
        }
    }

    public function actionDeletemessage()
    {
        if (isset($_GET['id']) && $_GET['id'] != '' && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            $message_id = $_GET['id'];
            $message = Messages::find()->where(['id' => $message_id])->one();
            $message->delete();
            return $this->redirect(['messages']);
        }
    }

    public function actionEditmessage()
    {
        if (isset($_GET['id']) && $_GET['id'] != '' && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            $message_id = $_GET['id'];
        }

        $message = Messages::find()->where(['id' => $message_id])->one();

        $model = new MessageUserForm();
        $model->msg = $message->msg;

        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $msg = $model->msg;

            $message->msg = $msg;

            if ($message->save()) {
                Yii::$app->session->setFlash('success', "Сообщение отредактировано");
                return $this->refresh();
            }
        }

        return $this->render('editmessage', [
            'model'   => $model,
            'message' => $message,
        ]);
    }

    public function actionHidemessages()
    {
        if (isset($_GET['id']) && $_GET['id'] != '' && filter_var($_GET['id'], FILTER_VALIDATE_INT)) {
            $current_user_id = Yii::$app->user->id;
            $second_user_id  = $_GET['id'];

            $messages_list = Messages::find()->orwhere(['user_id_1' => $current_user_id, 'user_id_2' => $second_user_id, 'user_hidden_1' => 0])->orWhere(['user_id_1' => $second_user_id, 'user_id_2' => $current_user_id, 'user_hidden_2' => 0])->all();

            foreach ($messages_list as $message) {
                if ($message->user_id_1 == $current_user_id) {
                    $message->user_hidden_1 = 1;
                } else {
                    $message->user_hidden_2 = 1;
                }
                $message->save();
                Yii::$app->session->setFlash('success', "Сообщения скрыты");
            }

            return $this->redirect(['messages']);
        }
    }

    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->redirect(['messages']);
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    public function actionRegister()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }
        $model = new RegisterForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            $user           = new User();
            $user->login    = $model->login;
            $user->email    = $model->email;
            $user->password = Yii::$app->security->generatePasswordHash($model->password);
            if ($user->save()) {
                return $this->goHome();
            }
        }
        $model->password = '';
        return $this->render('register', [
            'model' => $model,
        ]);
    }

    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

}
