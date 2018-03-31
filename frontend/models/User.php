<?php

namespace frontend\models;

use Yii;
use yii\behaviors\TimestampBehavior;
use yii\web\IdentityInterface;

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
class User extends \yii\db\ActiveRecord implements IdentityInterface
{
    public $password;//密码
    public $repassword;//确认密码
    public $checkCode;//验证码
    public $captcha;//短信验证
    public $rememberMe;//记住密码 自动登录

    const SCENARIO_LOGIN = 'login';
//    const SCENARIO_REGISTER = 'register';

    public function scenarios()
    {
        $scenarios = parent::scenarios();
        $scenarios[self::SCENARIO_LOGIN] = ['username', 'password','checkCode','rememberMe'];
        $scenarios['register'] = ['username', 'email', 'password','repassword','mobile','checkCode','captcha'];
        return $scenarios;
    }
    public function behaviors()
    {
        return [
            [
                'class'=>TimestampBehavior::className(),
                'attributes' => [
                    self::EVENT_BEFORE_INSERT=>['created_at','updated_at'],
                    self::EVENT_BEFORE_UPDATE=>['created_at']
                ]
            ]
        ];
    }

    public function rules()
    {
        return [
            [['username','password','repassword','mobile'], 'required'],
            ['repassword','compare','compareAttribute' => 'password','on'=>'register'],
            [['checkCode'],'captcha','captchaAction' =>'user/code' ],
            [['mobile'],'match','pattern' =>'/(13|14|15|17|18|19)[0-9]{9}/','message' => '请输入正确的手机号','on'=>'register' ],
            [['captcha'],'validateCaptcha','on'=>'register'],//自定义规则
            [['rememberMe'],'safe','on'=>self::SCENARIO_LOGIN]//验证码
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

    /**
     * Finds an identity by the given ID.
     * @param string|int $id the ID to be looked for
     * @return IdentityInterface the identity object that matches the given ID.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * Finds an identity by the given token.
     * @param mixed $token the token to be looked for
     * @param mixed $type the type of the token. The value of this parameter depends on the implementation.
     * For example, [[\yii\filters\auth\HttpBearerAuth]] will set this parameter to be `yii\filters\auth\HttpBearerAuth`.
     * @return IdentityInterface the identity object that matches the given token.
     * Null should be returned if such an identity cannot be found
     * or the identity is not in an active state (disabled, deleted, etc.)
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    /**
     * Returns an ID that can uniquely identify a user identity.
     * @return string|int an ID that uniquely identifies a user identity.
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Returns a key that can be used to check the validity of a given identity ID.
     *
     * The key should be unique for each individual user, and should be persistent
     * so that it can be used to check the validity of the user identity.
     *
     * The space of such keys should be big enough to defeat potential identity attacks.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @return string a key that is used to check the validity of a given identity ID.
     * @see validateAuthKey()
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * Validates the given auth key.
     *
     * This is required if [[User::enableAutoLogin]] is enabled.
     * @param string $authKey the given auth key
     * @return bool whether the given auth key is valid.
     * @see getAuthKey()
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key==$authKey;
    }
}
