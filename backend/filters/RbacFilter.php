<?php
/**
 * Created by PhpStorm.
 * User: 王利祥
 * Date: 2018/3/24
 * Time: 20:40
 */

namespace backend\filters;


use yii\base\ActionFilter;

class RbacFilter extends ActionFilter
{
    public function beforeAction($action)
    {

        if(!\Yii::$app->user->can($action->uniqueId)){
            \Yii::$app->session->setFlash('danger','权限不够无法跳转');
header("Location: {$_SERVER['HTTP_REFERER']}");
            return false;
        }
        return parent::beforeAction($action);
    }
}