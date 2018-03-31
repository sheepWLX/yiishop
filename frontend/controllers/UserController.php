<?php

namespace frontend\controllers;

use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    /**
     * 验证码图片
     * @return array
     */
    public function actions()
    {
        return [
            'code' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
                'minLength' => 4,
                'maxLength' => 4,
                'foreColor' => 0xFF0000
            ],
        ];
    }
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 用户注册
     */
    public function actionReg(){
        $request = \Yii::$app->request;
        if($request->isPost){
            $user = new User();
//            数据绑定
            $user->load($request->post());
//            后台验证
            if ($user->validate()) {
                //            令牌
                $user->auth_key=\Yii::$app->security->generateRandomString();
                $user->password_hash=\Yii::$app->security->generatePasswordHash($user->password);
                if ($user->save(false)) {
                    $result = [
                        'status'=>1,
                        'msg'=>'注册成功',
                        'data'=>"",
                    ];
                    return Json::encode($result);
                }
            }else{
                $result = [
                    'status'=>0,
                    'msg'=>'注册失败',
                    'data'=>$user->errors,
                ];
                return Json::encode($result);
            }
        }
        return $this->render('reg');
    }

    /**
     * 手机验证码验证
     * @param $mobile
     * @return int
     */
    public function actionSendSms($mobile){
//        发送验证码
        $code = rand(100000,999999);
//        把验证码发送出去
        $config = [
            'access_key' => 'LTAIqBXauBdpMBrL',
            'access_secret' => '0SGpfQQmC9DiCIEKpTJfN7OGHBLqhU',
            'sign_name' => '王利祥',
        ];

        $aliSms = new AliSms();//创建一个短信发送的对象
        $response = $aliSms->sendSms($mobile, 'SMS_128925086', ['code'=> $code], $config);
        if($response->Message=="OK"){
            //        把code保存Session
            $session=\Yii::$app->session;
            $session->set("tel_".$mobile,$code);
            return $code;
        }else{
            var_dump($response->Message);exit;
        }
    }

    public function actionLogin(){
        $requert=\Yii::$app->request;
        if($requert->isPost){
//            创建一个模型对象
            $model = new User();
//            设置场景
            $model->setScenario(User::SCENARIO_LOGIN);
//            绑定数据
            $model->load($requert->post());
//            后台验证
            if($model->validate()){
                $user=User::findOne(['username'=>$model->username]);
                if($user && \Yii::$app->security->validatePassword($model->password,$user->password_hash)){
//                    登录成功
                    \Yii::$app->user->login($user,$model->rememberMe?3600*24*7:0);
                    $user->login_time=time();
                    $user->login_ip=ip2long(\Yii::$app->request->userIP);
                    $user->save(false);
                    $result = [
                        'status'=>1,
                        'msg'=>'登录成功',
                        'data'=>null
                    ];
                    return Json::encode($result);
                }else{
//                    用户名或密码错误
                    $result = [
                        'status'=>0,
                        'msg'=>'用户名或密码错误',
                        'data'=>$model->errors,
                        'id'=>'username'
                    ];
                    return Json::encode($result);
                }
            }else{
//                验证失败
                $result = [
                    'status'=>0,
                    'msg'=>'输入有误',
                    'data'=>$model->errors,
                    'id'=>'changeCode'
                ];
                return Json::encode($result);
            }
        }
     return $this->render('login');
    }
    public function actionLogout(){
        if (\Yii::$app->user->logout()) {
            return $this->redirect(['index/index']);
        }
    }
}
