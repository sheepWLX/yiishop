<?php

namespace frontend\models;

use Yii;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $username 用户名
 * @property string $auth_key 令牌
 * @property string $password_hash 密码
 * @property string $password_reset_token
 * @property string $email 邮箱
 * @property int $status 状态
 * @property int $created_at 创建时间
 * @property int $updated_at 编辑时间
 * @property string $mobile 电话号码
 * @property int $login_time 登录时间
 * @property int $login_ip 登录IP
 */
class User extends \yii\db\ActiveRecord
{
    public $password;//密码
    public $repassword;//确认密码
    public $checkCode;//验证码
    public $captcha;//短信验证
    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['username','password','repassword','mobile'], 'required'],
            ['repassword','compare','compareAttribute' => 'password'],
            [['checkCode'],'captcha','captchaAction' =>'user/code' ],
            [['mobile'],'match','pattern' =>'/(13|14|15|17|18|19)[0-9]{9}/','message' => '请输入正确的手机号' ],
            [['captcha'],'validateCaptcha']//自定义规则
        ];
    }
    public function validateCaptcha($attribute, $params)
    {
        //        通过手机号取出之前发出的code
        $codeOld = \Yii::$app->session->get('tel_'.$this->mobile);
//        判断输入验证码时候正确
        if($this->captcha!=$codeOld){
            $this->addError($attribute, '验证码错误');
        }
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'username' => '用户名',
            'auth_key' => '令牌',
            'password_hash' => '密码',
            'password_reset_token' => 'Password Reset Token',
            'email' => '邮箱',
            'status' => '状态',
            'created_at' => '创建时间',
            'updated_at' => '编辑时间',
            'mobile' => '电话号码',
            'login_time' => '登录时间',
            'login_ip' => '登录IP',
        ];
    }

}
