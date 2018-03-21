<?php

namespace backend\models;

use Yii;
use yii\behaviors\TimestampBehavior;

/**
 * This is the model class for table "goods".
 *
 * @property int $id
 * @property string $name 商品名称
 * @property string $sn 品商货号
 * @property string $market_price 上场价格
 * @property string $shop_price 本店价格
 * @property int $stock 库存
 * @property int $sort 排序
 * @property int $status 商品状态
 * @property string $brand_id 商品品牌id
 * @property string $logo 商品logo
 * @property int $goods_category_id 商品分类id
 * @property int $create_time 录入时间
 */
class Goods extends \yii\db\ActiveRecord
{
    public $imgFiles;
    public function behaviors()
    {
        return [
            [
                'class' => TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT => ['create_time', 'update_time'],
                    self::EVENT_BEFORE_UPDATE => ['update_time'],
                ],
            ],
        ];
    }
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['market_price', 'shop_price','name','stock','sort','status','brand_id','goods_category_id'], 'required'],
            [['market_price','shop_price'],'number'],
            [['sn','imgFiles','logo','content'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '商品名称',
            'sn' => '品商货号',
            'market_price' => '市场价格',
            'shop_price' => '本店价格',
            'stock' => '库存',
            'sort' => '排序',
            'status' => '商品状态',
            'brand_id' => '商品品牌id',
            'logo' => '商品logo',
            'goods_category_id' => '商品分类id',
            'create_time' => '录入时间',
        ];
    }
}
