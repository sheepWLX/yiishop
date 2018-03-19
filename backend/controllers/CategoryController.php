<?php

namespace backend\controllers;


use backend\models\Category;
use function Sodium\compare;
use yii\data\Pagination;
use yii\db\Exception;
use yii\helpers\Json;
use yii\web\Request;

class CategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $model = Category::find()->orderBy('tree,lft');
        $count = $model->count();
        $page= new Pagination([
            'pageSize' => 20,
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
//                异常捕获
                try{
                    if($model->prent_id==0){
                        $model->save();
                        \Yii::$app->session->setFlash('success','修改分类:'.$model->name."成功");
                        return $this->redirect('index');
                    }else{
                        $catePatent = Category::findOne($model->prent_id);

                        $model->prependTo($catePatent);
                        \Yii::$app->session->setFlash('success',"修改{$catePatent->name}分类的子分类:".$model->name."成功");
                        return $this->redirect('index');
                    }
                }catch(Exception $exception){
                    \Yii::$app->session->setFlash('danger',$exception->getMessage());
                    return $this->refresh();
                }

            }else{
//                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model','catesJson'));
    }
    public function actionDel($id){
        $model = Category::findOne($id);
        if($model->rgt-$model->lft==1) {
            $model->delete();
        }else{
            \Yii::$app->session->setFlash('danger','有子级分类不能删除');
            return $this->redirect('index');
        }
    }
}
