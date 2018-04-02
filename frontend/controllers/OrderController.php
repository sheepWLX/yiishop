<?php
/**
 * Created by PhpStorm.
 * User: 王利祥
 * Date: 2018/3/31
 * Time: 15:26
 */

namespace frontend\controllers;


use backend\models\Delivery;
use backend\models\Goods;
use backend\models\Order;
use backend\models\OrderDetail;
use backend\models\PayType;
use frontend\models\Address;
use frontend\models\Cart;
use yii\db\Exception;
use yii\helpers\ArrayHelper;
use yii\helpers\Json;
use yii\web\Controller;

class OrderController extends Controller
{
    public function actionIndex(){
//        判断有没有登录
        if(\Yii::$app->user->isGuest){
            return $this->redirect(['user/login','url'=>'/order/index']);
        }
//        用户id
        $userId = \Yii::$app->user->id;
        //        收货人地址
        $addresss = Address::find()->where(['user_id'=>$userId])->all();
//        配送方式
        $deliverys = Delivery::find()->all();
//        支付方式
        $payTypes = PayType::find()->all();
//        得到商品列表
        $cart=Cart::find()->where(['user_id'=>\Yii::$app->user->id])->all();
        $cart = ArrayHelper::map($cart,'goods_id','num');
        $goodIds = array_keys($cart);
//            取出购物车的所有商品
        $goods=Goods::find()->where(['in','id',$goodIds])->all();
        $shopPrice=0;//        商品总价
        $shopNum=0;//        商品总数
        foreach ($goods as $good){
            $shopPrice+=$good->shop_price*$cart[$good->id];
            $shopNum+=$cart[$good->id];
        }
        $shopPrice=$shopPrice;

        $requery = \Yii::$app->request;
        if ($requery->isPost){

            $db = \Yii::$app->db;
            //开启事务
            $transaction = $db->beginTransaction();

            try {

//            创建订单对象
                $order = new Order();
//            取出地址
                $addressId = $requery->post('address_id');
                $address = Address::findOne(['id'=>$addressId,'user_id'=>$userId]);
//            取出配送方式
                $deliveryId=$requery->post('delivery');
                $delivery=Delivery::findOne($deliveryId);
                //            支付方式
                $payTypeId=$requery->post('pay');
                $payType=Delivery::findOne($payTypeId);
//            给oreder赋值
                $order->name=$address->name;
                $order->province=$address->province;
                $order->city=$address->city;
                $order->area=$address->county;
                $order->detail_address=$address->address;
                $order->tel=$address->mobile;
                $order->delivery_id=$deliveryId;
                $order->delivery_name=$delivery->name;
                $order->delivery_price=$delivery->price;
                $order->payment_id=$payTypeId;
                $order->payment_name=$payType->name;
//            订单价格
                $order->price=$shopPrice+$delivery->price;
//            订单状态
                $order->status=1;
//            订单号
                $order->trade_no=date('YmdHis').rand(1000,9999);
                $order->create_time=time();
                if ($order->save(false)) {
                    foreach ($goods as $good){
                        $curGood=Goods::findOne($good->id);
                        if($cart[$good->id]>$curGood->stock){
//                            exit("库存不足");
//                            抛出异常
                            throw  new Exception('库存不足');
                        }
                        $orderDetail =new OrderDetail();
                        $orderDetail->order_id=$order->id;
                        $orderDetail->goods_id=$good->id;
                        $orderDetail->amount=$cart[$good->id];
                        $orderDetail->goods_name=$good->name;
                        $orderDetail->logo=$good->logo;
                        $orderDetail->price=$good->shop_price;
                        $orderDetail->total_price=$good->shop_price*$orderDetail->amount;
                        if ($orderDetail->save(false)) {
                            $curGood->stock=$curGood->stock-$cart[$good->id];
                            $curGood->save(false);
                        }

                    }

                }

                //提交事务
                $transaction->commit();
                return Json::encode([
                    'status'=>1,
                    'msg'=>'订单提交成功'
                ]);

            } catch(Exception $e) {

                //事务回滚
                $transaction->rollBack();

                return Json::encode([
                    'status'=>0,
                    'msg'=>$e->getMessage()
                ]);
            }



        }


        return $this->render('index',compact('addresss','deliverys','payTypes','cart','goods','shopPrice','shopNum'));
    }
}