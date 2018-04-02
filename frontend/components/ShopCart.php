<?php
/**
 * Created by PhpStorm.
 * User: 王利祥
 * Date: 2018/3/31
 * Time: 13:26
 */

namespace frontend\components;


use frontend\models\Cart;
use yii\base\Component;
use yii\web\Cookie;

class ShopCart extends Component
{
    private $cart;
    public function __construct(array $config = [])
    {
        $getCookie=\Yii::$app->request->cookies;
        $this->cart = $getCookie->getValue('cart',[]);
        parent::__construct($config);
    }
//    增
    public function add($id,$num){
        $id = $id-0;
    //            判断当前添加的商品id在购物车中是否已经存
        if (array_key_exists($id,$this->cart)) {
            $this->cart[$id]+=(int)$num;
        }else{
            $this->cart[$id]=(int)$num;
        }
        return $this;
    }
//    删
    public function del($id){
        unset($this->cart[$id]);
        return $this;
    }
    public function flush(){
//        清空本地cookie
        $this->cart=[];
        return $this;
    }
//    改
    public function update($id,$num){
        $this->cart[$id]=$num;
        return $this;
    }
//    查
    public function get(){
        return $this->cart;
    }
//保存
    public function save(){
    //            设置Cookie对象
            $setCookie=\Yii::$app->response->cookies;
//            创建Cookie对象
            $cookie=new Cookie([
                'name'=>'cart',
                'value' =>$this->cart,
                'expire'=>time()+3600*24*30*12
            ]);
//            通过设置Cookie对象来添加一个Cookie
            $setCookie->add($cookie);
}
//数据库同步
    public function dbSyn(){
//                    取出cookie中的数据
//        $cart = (new ShopCart())->get();
//                    把数据同步到数据库中
        $userId = \Yii::$app->user->id;
        foreach($this->cart as $goodsId=>$num){
            $cartDb = Cart::findone(['user_id'=>$userId,'goods_id'=>$goodsId]);
            if ($cartDb){
                $cartDb->num+=$num;
            }else{
                $cartDb = new Cart();
                $cartDb->goods_id=$goodsId;
                $cartDb->user_id=$userId;
                $cartDb->num=$num;
            }
            $cartDb->save(false);
        }
        return $this;
    }
}