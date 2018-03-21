<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionAdd(){
        $admin = new Admin();
        $admin->username='王利祥';
        $admin->password_hash=\Yii::$app->security->generatePasswordHash('123456');
        $admin->auth_key=\Yii::$app->security->generateRandomString();
        $admin->login_ip=ip2long(\Yii::$app->request->userIP);
        $admin->save();
    }
    public function actionLogin(){
//        if (\Yii::$app->user->isGuest) {
//            return $this->redirect(['index']);
//        }
        $model = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
//                var_dump($model->rememberMe);exit;
                $admin=Admin::findOne(['username'=>$model->username]);
                if ($admin) {
                    if(\Yii::$app->security->validatePassword($model->password,$admin->password_hash)){
                        \Yii::$app->user->login($admin,$model->rememberMe?3600*24*7:0);
//                        $model->rememberMe?3600*24*7:0
                        $admin->login_at=time();
                        $admin->login_ip=ip2long(\Yii::$app->request->userIP);
                        $admin->save();
//                        exit;
                        \Yii::$app->session->setFlash('success','登录成功');
                        return $this->redirect(['index']);
                    }else{
                        $model->addError('username','密码错误');
                    }
//
                }else{
                    $model->addError('username','用户名不存在');
                }
            }
        }
        return $this->render('login',compact('model'));
    }
    public function actionLogout(){
        if (\Yii::$app->user->logout()) {
            return $this->redirect(['login']);
        }
    }
}
