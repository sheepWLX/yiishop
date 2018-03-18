<?php
/**
 * Created by PhpStorm.
 * User: 王利祥
 * Date: 2018/3/18
 * Time: 11:35
 */

namespace backend\components;


use creocoder\nestedsets\NestedSetsQueryBehavior;

class MenuQuery extends \yii\db\ActiveQuery
{
    public function behaviors() {
        return [
            NestedSetsQueryBehavior::className(),
        ];
    }
}