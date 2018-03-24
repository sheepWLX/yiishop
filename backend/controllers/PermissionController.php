<?php

namespace backend\controllers;

use backend\models\AuthItem;

class PermissionController extends \yii\web\Controller
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
        $pers = $auth->getPermissions();
//        引入视图
        return $this->render('index',compact('pers'));
    }

    /**
     * 权限添加
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
//        创建模型对象
        $model = new AuthItem();
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //        创建auth对象
            $auth=\Yii::$app->authManager;
//        创建权限
            $per = $auth->createPermission($model->name);
//        设置描述
            $per->description=$model->description;
//        权限入库
            if ($auth->add($per)) {
//                提示
                \Yii::$app->session->setFlash('success','权限'.$model->name.'添加成功');
                return $this->refresh();
            }
        }
//        引入视图
        return $this->render('add',compact('model'));

    }

    /**
     * 权限编辑
     * @param $name
     * @return string|\yii\web\Response
     */
    public function actionEdit($name){
//        创建模型对象
        $model = AuthItem::findOne($name);
        if($model->load(\Yii::$app->request->post()) && $model->validate()){
            //        创建auth对象
            $auth=\Yii::$app->authManager;
//        创建权限
            $per = $auth->createPermission($model->name);
//        设置描述
            $per->description=$model->description;
//        权限入库
            if ($auth->update($model->name,$per)) {
//                提示
                \Yii::$app->session->setFlash('success','权限'.$model->name.'修改成功');
                return $this->refresh();
            }
        }
//        引入视图
        return $this->render('edit',compact('model'));

    }

    /**
     * 删除权限
     * @param $name
     */
    public function actionDel($name){
//        创建auth对象
        $auth = \Yii::$app->authManager;
//        找到权限
        $per=$auth->getPermission($name);
//        var_dump($auth->remove($per));exit;
        if ($auth->remove($per)) {
            \Yii::$app->session->setFlash('success','删除'.$name.'成功');
            return $this->redirect(['index']);
        }
    }
}
