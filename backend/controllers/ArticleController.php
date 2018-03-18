<?php

namespace backend\controllers;


use backend\models\Article;
use backend\models\ArticleCategory;
use backend\models\ArticleContent;
use yii\data\Pagination;
use yii\helpers\ArrayHelper;
use yii\web\Request;

class ArticleController extends \yii\web\Controller
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
        $model = Article::find();
        $count = $model->count();
        $page = new Pagination([
            'pageSize' => 3,
            'totalCount' => $count
        ]);
        $articles=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',compact('articles','page'));
    }
    public function actionAdd(){
        $model = new Article();
//        文章分类表数据
        $category = ArticleCategory::find()->all();
        $cateArr = ArrayHelper::map($category,'id','name');
//        文章内容
        $content=new ArticleContent();
        $request = new Request();
        if($request->isPost){
            $model->load($request->post());
            if($model->validate()){
                if($model->save(false)){
                    $content->load($request->post());
                    if($content->validate()){
//                        给文章赋值id
                        $content->article_id=$model->id;
                        if ($content->save(false)) {
                            \Yii::$app->session->setFlash('seccess','添加成功');
                            return $this->redirect('index');
                        }
                    }else{
                        var_dump($model->errors);exit;
                    }
                }
            }else{
                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model','content','cateArr'));
    }
}
