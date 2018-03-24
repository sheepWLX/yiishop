<?php

namespace backend\controllers;

use backend\models\Admin;
use backend\models\LoginForm;
use function Sodium\compare;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class AdminController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $admins = Admin::find()->all();
        return $this->render('index',compact('admins'));
    }
    //    给用户添加一个角色
    public function actionRole($roleName,$id){
        $model = new Admin();
//        实例化组件对象
        $auth=\Yii::$app->authManager;
//        通过角色找出角色对象
        $role=$auth->getRole($roleName);
//        把用户指派给角色
        $auth->assign($role,$id);
    }
    public function actionAdd(){
        $model = new Admin();
        //        实例化组件对象
        $auth=\Yii::$app->authManager;
        $roles=$auth->getRoles();

        $rolesArr=ArrayHelper::map($roles,'name','description');
//        $rolesArr=array_keys($roles);
//        var_dump(\Yii::$app->request->post());exit;
        $model->setScenario('add');
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->roles=\Yii::$app->request->post()['Admin']['roles']?\Yii::$app->request->post()['Admin']['roles']:"";
            //        通过角色找出角色对象
//            $role=$auth->getRole($roleName);
            $model->auth_key=\Yii::$app->security->generateRandomString();
            $model->login_ip=ip2long(\Yii::$app->request->userIP);
            $model->password_hash = \Yii::$app->security->generatePasswordHash($model->password_hash);
            $model->save();

//                判断是否添加权限
                if ($model->roles) {
                    //                给当前角色添加权限
                    foreach ($model->roles as $roleName) {
//        通过角色找出角色对象
                        $role=$auth->getRole($roleName);
                        //        把用户指派给角色
                        $auth->assign($role,$model->id);
                    }

                }
//
//            $roleName=$model->roles;
//            var_dump($model->roles);exit;
            return $this->redirect('index');
        }
        return $this->render('add',compact('model','rolesArr'));
    }
    public function actionEdit($id){
        $model = Admin::findOne($id);
        $password_hash=$model->password_hash;
//       echo  $password_hash=$model->password_hash;exit;
        $model->setScenario('edit');
//        $model->password_hash;exit;
//        $model->password_hash=$model->password_hash?:;
        if ($model->load(\Yii::$app->request->post()) && $model->validate()) {
            $model->auth_key=\Yii::$app->security->generateRandomString();
            $model->login_ip=ip2long(\Yii::$app->request->userIP);

            $model->password_hash = $model->password_hash?\Yii::$app->security->generatePasswordHash($model->password_hash):$password_hash;
            $model->save();
//            echo $model->status;exit;
            return $this->redirect('index');
        }
//        $admin->username='王利祥';
//        $admin->password_hash=\Yii::$app->security->generatePasswordHash('123456');
//        $admin->auth_key=\Yii::$app->security->generateRandomString();
//        $admin->login_ip=ip2long(\Yii::$app->request->userIP);
//        $admin->save();
        $model->password_hash=null;
        return $this->render('add',compact('model'));
    }
    public function actionDel($id){
        if (Admin::findOne($id)->delete()) {
            return $this->redirect('index');
        }
    }
    public function actionLogin(){
        if (!\Yii::$app->user->isGuest) {
            return $this->redirect(['index']);
        }
        $model = new LoginForm();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
//            var_dump($model->rememberMe);exit;
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
                        $model->addError('password','密码错误');
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
