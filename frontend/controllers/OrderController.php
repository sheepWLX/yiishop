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
use yii\helpers\Url;
use yii\web\Controller;
use EasyWeChat\Foundation\Application;
use Endroid\QrCode\QrCode;

class OrderController extends Controller
{
    public $enableCsrfValidation=false;
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
                $payType=PayType::findOne($payTypeId);
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
    public function actionOk($id){
        $order = Order::findOne($id);
        return $this->render('ok',compact('order'));
    }
    public function actionWx($id){
        $order=Order::findOne($id);
        //        配置
        $options=\Yii::$app->params['wx'];
//        var_dump($options);exit;
//        创建操作微信的对象
        $app = new Application($options);
//        通过$app得到支付对象
        $payment = $app->payment;

        $attributes = [
            'trade_type'       => 'NATIVE', // JSAPI，NATIVE，APP...
            'body'             => '路客商城',
            'detail'           => '商品详情',
            'out_trade_no'     => $order->trade_no,
            'total_fee'        => $order->price*100, // 单位：分
            'notify_url'       => Url::to(['order/notify'],true), // 支付结果通知网址，如果不设置则会使用配置里的默认地址
            //'openid'           => '当前用户的 openid', // trade_type=JSAPI，此参数必传，用户在商户appid下的唯一标识，
            // ...
        ];
        //通过订单信息生成订单
        $order = new \EasyWeChat\Payment\Order($attributes);
        $result = $payment->prepare($order);
//        echo 1;
//        var_dump($result);exit;
        if ($result->return_code == 'SUCCESS' && $result->result_code == 'SUCCESS'){
//    $prepayId = $result->prepay_id;
//echo $result->code_url;
            $qrCode = new QrCode($result->code_url);

            header('Content-Type: '.$qrCode->getContentType());
            echo $qrCode->writeString();

        }else{
            var_dump($result);
        }
    }

    /**
     * 微信异步通信地址
     */
    public function actionNotify(){
        //        配置
        $options=\Yii::$app->params['wx'];
//        var_dump($options);exit;
//        创建操作微信的对象
        $app = new Application($options);
        $response = $app->payment->handleNotify(function($notify, $successful){
            // 使用通知里的 "微信支付订单号" 或者 "商户订单号" 去自己的数据库找到订单
//            $order = 查询订单($notify->out_trade_no);
                $order=Order::findOne(['trade_no'=>$notify->out_trade_no]);
            if (!$order) { // 如果订单不存在
                return 'Order not exist.'; // 告诉微信，我已经处理完了，订单没找到，别再通知我了
            }

            // 如果订单存在
            // 检查订单是否已经更新过支付状态
            if ($order->status!=1) { // 假设订单字段“支付时间”不为空代表已经支付
                return true; // 已经支付成功了就不再更新了
            }

            // 用户是否支付成功
            if ($successful) {
                // 不是已经支付状态则修改为已经支付状态
               // $order->paid_at = time(); // 更新支付时间为当前时间
                $order->status = 2;//1 等待支付 2 已支付
            }

            $order->save(); // 保存订单

            return true; // 返回处理完成
        });

        return $response;
    }
}