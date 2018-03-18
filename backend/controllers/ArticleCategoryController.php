<?php

namespace backend\controllers;

use backend\models\ArticleCategory;
use yii\web\Request;

class ArticleCategoryController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $articles = ArticleCategory::find()->all();
        return $this->render('index',compact('articles'));
    }
    public function actionAdd(){
        $model = new ArticleCategory();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->save(false)){
                    \Yii::$app->session->setFlash("seccess","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                //TODO
//                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model'));
    }
    public function actionEdit($id){
        $model = ArticleCategory::findOne($id);
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->save(false)){
                    \Yii::$app->session->setFlash("seccess","添加成功");
                    return $this->redirect(['index']);
                }
            }else{
                //TODO
//                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model'));
    }
    public function actionDel($id){
        if(ArticleCategory::findOne($id)->delete()){
            return $this->redirect('index');
        }
    }
}
