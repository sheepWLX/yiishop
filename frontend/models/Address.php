<?php

namespace frontend\models;

use Yii;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "address".
 *
 * @property int $id
 * @property int $user_id 用户ID
 * @property string $name 姓名
 * @property string $province 省份
 * @property string $city 市
 * @property string $county 区县
 * @property string $address 地址
 * @property string $mobile 手机号
 * @property int $status 状态,1默认 0非默认
 */
class Address extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','province','city','county','address','mobile'],'required'],
            [['status'],'safe']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'user_id' => '用户ID',
            'name' => '姓名',
            'province' => '省份',
            'city' => '市',
            'county' => '区县',
            'address' => '地址',
            'mobile' => '手机号',
            'status' => '状态,1默认 0非默认',
        ];
    }
}
