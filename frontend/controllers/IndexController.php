<?php

namespace frontend\controllers;

use backend\models\Category;
use backend\models\Goods;

class IndexController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('index');
    }

    /**
     * 分类列表
     * @param $id 分类id
     * @return string
     */
    public function actionList($id){
//        通过分类id得到分类对象
        $cate=Category::findOne($id);
//        通过分类id找出所有子孙分类
        $sonCates=Category::find()->where(['tree'=>$cate->tree])->andWhere(['>','lft',$cate->lft])->asArray()->andWhere(['<','rgt',$cate->rgt])->all();
        //        通过当前二维数组提取成一维数组
        $cateIds=array_column($sonCates,'id');
//        var_dump($sonCates);exit;
//        得到当前分类的所有商品
        $goods=Goods::find()->where(['in','goods_category_id',$cateIds])->andWhere(['status'=>1])->orderBy('sort')->all();

//        var_dump($goods);exit;
        return $this->render('list',compact('goods'));
    }

}
