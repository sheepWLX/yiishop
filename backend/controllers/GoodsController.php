<?php

namespace backend\controllers;

use backend\models\Brand;
use backend\models\Category;
use backend\models\Goods;
use backend\models\GoodsGallery;
use backend\models\GoodsIntro;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class GoodsController extends \yii\web\Controller
{
    public function actions()
    {
        return [
            'upload' => [
                'class' => 'kucha\ueditor\UEditorAction',
            ]
        ];
    }
    public function actionIndex()
    {
        $model = Goods::find();
        $minPrice = \Yii::$app->request->get('minPrice');
        $maxPrice = \Yii::$app->request->get('maxPrice');
        $keyword = \Yii::$app->request->get('keyword');
        $status = \Yii::$app->request->get('status');
//        echo $minPrice;
//        echo $maxPrice;
//        echo $key;
//        exit;
        if($minPrice){
            $model->andWhere("shop_price>={$minPrice}");
        }
        if($maxPrice){
            $model->andWhere("shop_price<={$maxPrice}");
        }
        if($keyword!==""){
            $model->andWhere("name like '%{$keyword}%' or sn like '%{$keyword}%'");
        }
        if($status==="0" || $status==="1"){
            $model->andWhere(['status'=>$status]);
        }
//        $count = $model->count();
        $pages=new Pagination([
            'totalCount' => $model->count(),
            'pageSize' => 5
        ]);
        $models = $model->offset($pages->offset)->limit($pages->limit)->all();
        return $this->render('index',compact('models','pages'));
    }
    public function actionAdd(){
        $model = new Goods();
        $goodsIntro = new GoodsIntro();
        $goodsGallery = new GoodsGallery();
        $request =new Request();
//        把所有商品分类传过来
        $cates = Category::find()->orderBy('tree,lft')->all();
        $catesArr =ArrayHelper::map($cates,'id','nameText');
        $brand = Brand::find()->orderBy('id')->all();
        $brandArr = ArrayHelper::map($brand,'id','name');
        if($request->isPost){
            $model->load($request->post());
            $goodsIntro->load($request->post());
            if($model->validate() && $model->validate()){
//                echo $model->sn;exit;
                if(!$model->sn){
                    $dayTime = strtotime(date("Ymd"));
                    $count = Goods::find()->where(['>','create_time',$dayTime])->count();
//                    echo date('Ymd',$dayTime);
//                    echo $count;exit;
                    $count=$count+1;
                    $dayTime = date('Ymd',$dayTime);
                    $sn = $dayTime.sprintf('%05s', $count);
//                    var_dump($sn);exit;
//                    $model->create_time;
                    $model->sn=$sn;
                }
                if ($model->save()) {
                    $goodsIntro->goods_id=$model->id;
                    $goodsIntro->save();
//                    var_dump($model->imgFiles);exit;
                    foreach ($model->imgFiles as $imgFile){
           $goodsGallery = new GoodsGallery();
           $goodsGallery->goods_id=$model->id;
           $goodsGallery->path=$imgFile;
           $goodsGallery->save();
                    }
                    \Yii::$app->session->setFlash('success','添加商品成功');
                    return $this->redirect('index');
                }
            }else{
                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model','catesArr','brandArr','goodsIntro','goodsGallery'));
    }
    public function actionEdit($id){
        $model = Goods::findOne($id);
        $goodsIntro = GoodsIntro::findOne(['goods_id'=>$id]);
//        $goodsGallery = new GoodsGallery();
        $request =new Request();
//        把所有商品分类传过来
        $cates = Category::find()->orderBy('tree,lft')->all();
        $catesArr =ArrayHelper::map($cates,'id','nameText');
        $brand = Brand::find()->orderBy('id')->all();
        $brandArr = ArrayHelper::map($brand,'id','name');
        if($request->isPost){
            $model->load($request->post());
            $goodsIntro->load($request->post());
            if($model->validate() && $model->validate()){
//                echo $model->sn;exit;
                if(!$model->sn){
                    $dayTime = strtotime(date("Ymd"));
                    $count = Goods::find()->where(['>','create_time',$dayTime])->count();
                    $count=$count+1;
                    $dayTime = date('Ymd',$dayTime);
                    $sn = $dayTime.sprintf('%05s', $count);
//                    var_dump($sn);exit;
//                    $model->create_time;
                    $model->sn=$sn;
                }
                if ($model->save()) {
//                    $goodsIntro->goods_id=$model->id;
                    $goodsIntro->save();
//                    var_dump($model->imgFiles);exit;
                    GoodsGallery::deleteAll(['goods_id'=>$id]);
                    foreach ($model->imgFiles as $imgFile){
                       $goodsGallery = new GoodsGallery();
                       $goodsGallery->goods_id=$model->id;
                       $goodsGallery->path=$imgFile;
                       $goodsGallery->save();
                    }
                    \Yii::$app->session->setFlash('success','添加商品成功');
                    return $this->redirect('index');
                }
            }else{
                var_dump($model->errors);exit;
            }
        }
        $imgFiles=GoodsGallery::find()->where(['goods_id'=>$id])->all();
        $imgFiles=array_column($imgFiles,'path');
//        var_dump($imgFiles);exit;
        $model->imgFiles=$imgFiles;
        return $this->render('add',compact('model','catesArr','brandArr','goodsIntro','goodsGallery'));
    }
    public function actionDel($id){
        GoodsGallery::deleteAll(['goods_id'=>$id]);
        GoodsIntro::findOne(['goods_id'=>$id])->delete();
        Goods::findOne($id)->delete();
        return $this->redirect('index');
    }
}
