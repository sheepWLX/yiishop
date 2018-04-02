<?php

namespace frontend\controllers;

use backend\models\Goods;
use frontend\components\ShopCart;
use frontend\models\Cart;
use yii\helpers\ArrayHelper;
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
        if (Goods::findOne($id)===null) {
            return $this->redirect(['index/index']);
        }
        if(\Yii::$app->user->isGuest){
//            $cart = new ShopCart();
            (new ShopCart())->add($id,$amount)->save();
//            游客
//            得到Cookie对象
//            $getCookie=\Yii::$app->request->cookies;
//            $cart = $getCookie->getValue('cart',[]);

//            $id = $id-0;
////            判断当前添加的商品id在购物车中是否已经存
//            if (array_key_exists($id,$cart)) {
//                $cart[$id]+=(int)$amount;
//            }else{
//                $cart[$id]=(int)$amount;
//            }
////            设置Cookie对象
//            $setCookie=\Yii::$app->response->cookies;
////            创建Cookie对象
//            $cookie=new Cookie([
//                'name'=>'cart',
//                'value' =>$cart
//            ]);
////            通过设置Cookie对象来添加一个Cookie
//            $setCookie->add($cookie);
        }else{
//            登录
            $userId = \Yii::$app->user->id;
            $cart = Cart::findone(['user_id'=>$userId,'goods_id'=>$id]);
            if ($cart){
                $cart->num+=$amount;
            }else{
                $cart = new Cart();
                $cart->goods_id=$id;
                $cart->user_id=$userId;
                $cart->num=$amount;
            }
            $cart->save();
        }
        return $this->redirect(['cart-list']);
    }
//    购物车列表
    public function actionCartList(){
        if(\Yii::$app->user->isGuest){
//            从cookie中取出购物车数据
            $cart=\Yii::$app->request->cookies->getValue('cart',[]);
        }else{
            $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->all();
            $cart = ArrayHelper::map($cart,'goods_id','num');
        }
        $goodIds = array_keys($cart);
//            取出购物车的所有商品
        $goods=Goods::find()->where(['in','id',$goodIds])->all();
        return $this->render('list',compact('goods','cart'));
    }

    /**
//     * 修改商品数量
     * @param $id 商品id
     * @param $amount 商品数量
     */
    public function actionUpdateCart($id,$amount){
        if(\Yii::$app->user->isGuest){
            (new ShopCart())->update($id,$amount)->save();
//            $cart = \Yii::$app->request->cookies->getValue('cart',[]);
////            $id = $id-0;
//            $cart[$id]=$amount;
////            设置Cookie对象
//            $setCookie=\Yii::$app->response->cookies;
//            创建Cookie对象
//            $cookie=new Cookie([
//                'name'=>'cart',
//                'value' =>$cart
//            ]);
////            通过设置Cookie对象来添加一个Cookie
//            $setCookie->add($cookie);
        }
    }

    /**
     * 删除购物车商品
     * @param $id 商品id
     * @return string
     */
    public function actionDelCart($id){
        if(\Yii::$app->user->isGuest){
//            $cart = \Yii::$app->request->cookies->getValue('cart',[]);
////            $id = $id-0;
//            unset($cart[$id]);
////            设置Cookie对象
//            $setCookie=\Yii::$app->response->cookies;
//            创建Cookie对象
//            $cookie=new Cookie([
//                'name'=>'cart',
//                'value' =>$cart,
//                'expire'=>time()+3600*24*30*12
//            ]);
//            通过设置Cookie对象来添加一个Cookie
//            $setCookie->add($cookie);
            return Json::encode([
                'status'=>1,
                'msg'=>'删除成功'
            ]);
        }
    }
}
