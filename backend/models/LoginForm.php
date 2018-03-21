<?php
/**
 * Created by PhpStorm.
 * User: 王利祥
 * Date: 2018/3/21
 * Time: 20:52
 */

namespace backend\models;


use yii\base\Model;

class LoginForm extends Model
{
    public $username;
    public $password;
    public $rememberMe = true;
    public function rules()
    {
        return [
            [['username','password'],'required'],
            [['rememberMe'],'safe']
        ];
    }
    public function attributeLabels()
    {
        return [
            'username'=>'用户名',
            'password'=>'密码',
            'rememberMe'=>'记住我'
        ];
    }
}