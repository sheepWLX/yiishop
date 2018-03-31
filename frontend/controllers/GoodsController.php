<?php

namespace frontend\controllers;

use backend\models\Goods;
use yii\helpers\Json;
use yii\web\Cookie;

class GoodsController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }
    public function actionDetail($id){
        $good=Goods::findOne($id);
        return $this->render('detail',compact('good'));
    }
//    添加购物车
    public function actionAddCart($id,$amount){
        if(\Yii::$app->user->isGuest){
//            游客
//            得到Cookie对象
            $getCookie=\Yii::$app->request->cookies;
            $cart = $getCookie->getValue('cart',[]);

            $id = $id-0;
//            var_dump($id);
//            var_dump($cart);exit;
//            判断当前添加的商品id在购物车中是否已经存
//            var_dump(array_key_exists($id,$cart));exit;
            if (array_key_exists($id,$cart)) {
                $cart[$id]+=(int)$amount;
            }else{
                $cart[$id]=(int)$amount;
            }
//            设置Cookie对象
            $setCookie=\Yii::$app->response->cookies;
//            创建Cookie对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value' =>$cart
            ]);
//            通过设置Cookie对象来添加一个Cookie
            $setCookie->add($cookie);
            return $this->redirect(['cart-list']);
        }else{
//            登录
            echo 2;
        }
    }
//    购物车列表
    public function actionCartList(){
        if(\Yii::$app->user->isGuest){
//            从cookie中取出购物车数据
            $cart=\Yii::$app->request->cookies->getValue('cart',[]);
            $goodIds = array_keys($cart);
//            取出购物车的所有商品
            $goods=Goods::find()->where(['in','id',$goodIds])->all();

        }else{

        }
        return $this->render('list',compact('goods','cart'));
    }
    public function actionUpdateCart($id,$amount){
//echo $id;exit;
        if(\Yii::$app->user->isGuest){
            $cart = \Yii::$app->request->cookies->getValue('cart',[]);
//            $id = $id-0;
            $cart[$id]=$amount;
//            设置Cookie对象
            $setCookie=\Yii::$app->response->cookies;
//            创建Cookie对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value' =>$cart
            ]);
//            通过设置Cookie对象来添加一个Cookie
            $setCookie->add($cookie);
        }
    }
    public function actionDelCart($id){
        if(\Yii::$app->user->isGuest){
            $cart = \Yii::$app->request->cookies->getValue('cart',[]);
//            $id = $id-0;
            unset($cart[$id]);
//            设置Cookie对象
            $setCookie=\Yii::$app->response->cookies;
//            创建Cookie对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value' =>$cart
            ]);
//            通过设置Cookie对象来添加一个Cookie
            $setCookie->add($cookie);
            return Json::encode([
                'status'=>1,
                'msg'=>'删除成功'
            ]);
        }
    }
}
