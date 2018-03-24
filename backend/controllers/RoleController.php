<?php

namespace backend\controllers;

use backend\models\AuthItem;
use yii\helpers\ArrayHelper;

class RoleController extends \yii\web\Controller
{
    /**
     * 权限列表
     * @return string
     */
    public function actionIndex()
    {
//        创建aut对象
        $auth=\Yii::$app->authManager;
//        找到所有权限
        $roles = $auth->getRoles();
//        引入视图
        return $this->render('index',compact('roles'));
    }

    /**
     * 角色添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
//        创建模型对象
        $model = new AuthItem();
        //        创建auth对象
        $auth=\Yii::$app->authManager;
//        得到所有权限
        $pers=$auth->getPermissions();
        $persArr = ArrayHelper::map($pers,'name','description');
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

//        创建权限
            $role = $auth->createRole($model->name);
//        设置描述
            $role->description=$model->description;
//        角色入库
            if ($auth->add($role)) {
//                判断是否添加权限
                if ($model->permissions){
                    //                给当前角色添加权限
                    foreach($model->permissions as $perName){
//                    通过权限名称得到权限对象
                        $per=$auth->getPermission($perName);
//                    给角色添加权限
                        $auth->addChild($role,$per);
                }

                }
//                提示
                \Yii::$app->session->setFlash('success','角色'.$model->name.'添加成功');
                return $this->refresh();
            }
        }
//        引入视图
        return $this->render('add',compact('model','persArr'));

    }

    /**
     * 编辑角色
     * @param $name
     * @return string|\yii\web\Response
     */
    public function actionEdit($name){
//        创建模型对象
        $model = AuthItem::findOne($name);
        //        创建auth对象
        $auth=\Yii::$app->authManager;
//        得到所有权限
        $pers=$auth->getPermissions();
        $persArr = ArrayHelper::map($pers,'name','description');
        $roleRers = $auth->getPermissionsByRole($name);
//        得到当前角色对应的所有权限
        $model->permissions=array_keys($roleRers);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){

//        得到权限
            $role = $auth->getRole($model->name);
//        设置描述
            $role->description=$model->description;
//        角色入库
            if ($auth->update($model->name,$role)) {
//                删除当前角色对应的所有权限
                $auth->removeChildren($role);
//                判断是否添加权限
                if ($model->permissions){
                    //                给当前角色添加权限
                    foreach($model->permissions as $perName){
//                    通过权限名称得到权限对象
                        $per=$auth->getPermission($perName);
//                    给角色添加权限
                        $auth->addChild($role,$per);
                }

                }
//                提示
                \Yii::$app->session->setFlash('success','角色'.$model->name.'添加成功');
                return $this->redirect(['index']);
            }
        }
//        引入视图
        return $this->render('edit',compact('model','persArr'));

    }


    /**
     * 删除权限
     * @param $name
     */
    public function actionDel($name){
//        创建auth对象
        $auth = \Yii::$app->authManager;
//        找到角色
        $role=$auth->getRole($name);
//        var_dump($auth->remove($role));exit;
        if ($auth->remove($role)) {
            \Yii::$app->session->setFlash('success','删除'.$name.'成功');
            return $this->redirect(['index']);
        }
    }
//    判断当前用户有没有权限
public function actionCheck(){
        \Yii::$app->user->can();
}

}
