<?php

namespace frontend\controllers;

use frontend\models\Address;
use yii\helpers\Json;

class AddressController extends \yii\web\Controller
{
    public $enableCsrfValidation=false;
    public function actionIndex()
    {
        $addresss =  Address::find()->where(['user_id'=>\Yii::$app->user->id])->all();
        return $this->render('index',compact('addresss'));
    }
    public function actionAdd(){
        if(\Yii::$app->request->isPost){
            $address=new Address();
//            绑定
            $address->load(\Yii::$app->request->post());
//            验证
            if ($address->validate()) {
                $address->user_id=\Yii::$app->user->id;
                if($address->status===null){
                    $address->status=0;
                }else{
                    $address->status=1;
                    Address::updateAll(['status'=>0],['user_id'=>$address->user_id]);
                }
                if($address->save()){
                    $result=[
                        'status'=>1,
                        'msg'=>'操作成功'
                    ];
                    return Json::encode($result);
                }
            }else{
                $result=[
                    'status'=>0,
                    'msg'=>'操作失败'
                ];
                return Json::encode($result);
            }
        }
    }
    public function actionDel($id){
        if (Address::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->id])->delete()) {
            return $this->redirect(['address/index']);
        }
    }
    public function actionDefault($id){
        $address = Address::findOne(['id'=>$id,'user_id'=>\Yii::$app->user->id]);
        Address::updateAll(['status'=>0],['user_id'=>$address->user_id]);
        $address->status=1;
        $address->save();
        return $this->redirect('index');
    }
}
