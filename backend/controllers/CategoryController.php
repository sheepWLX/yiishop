<?php

namespace backend\controllers;


use backend\models\Category;
use function Sodium\compare;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\Request;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Category::find();
        $count = $model->count();
        $page= new Pagination([
            'pageSize' => 3,
            'totalCount' => $count
        ]);
        $models=$model->offset($page->offset)->limit($page->limit)->all();
//        echo "<pre>";
//        var_dump($models);exit;
        return $this->render('index', compact('models','page'));
    }
    public function actionAdd(){
        $model = new Category();
        $request = new Request();
        $catesArr = Category::find()->asArray()->all();
        $catesArr[]=['id'=>0,'name'=>'一级分类','parent_id'=>0];
        $catesJson = Json::encode($catesArr);
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                if($model->prent_id==0){
                    $model->makeRoot();
                    \Yii::$app->session->setFlash('success','添加分类:'.$model->name."成功");
                    return $this->refresh();
                }else{
                    $catePatent = Category::findOne($model->prent_id);

                    $model->prependTo($catePatent);
                    \Yii::$app->session->setFlash('success',"添加{$catePatent->name}分类的子分类:".$model->name."成功");
                    return $this->refresh();
                }
            }else{
//                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model','catesJson'));
    }
    public function actionEdit($id){
        $model = Category::findOne($id);
        $request = new Request();
        $catesArr = Category::find()->asArray()->all();
        $catesArr[]=['id'=>0,'name'=>'一级分类','parent_id'=>0];
        $catesJson = Json::encode($catesArr);
        if($request->isPost){
            $model->load($request->post());

            if($model->validate()){
                if($model->prent_id==0){
                    $model->makeRoot();
                    \Yii::$app->session->setFlash('success','添加分类:'.$model->name."成功");
                    return $this->refresh();
                }else{
                    $catePatent = Category::findOne($model->prent_id);

                    $model->prependTo($catePatent);
                    \Yii::$app->session->setFlash('success',"添加{$catePatent->name}分类的子分类:".$model->name."成功");
                    return $this->refresh();
                }
            }else{
//                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model','catesJson'));
    }
}
