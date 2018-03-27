<?php

namespace frontend\controllers;

use frontend\models\User;
use Mrgoon\AliSms\AliSms;
use yii\helpers\Json;

class UserController extends \yii\web\Controller
{
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
}
