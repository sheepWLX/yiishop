<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\web\UploadedFile;

class BrandController extends \yii\web\Controller
{
    /**
     * 显示列表页
     * @return string
     */
    public function actionIndex()
    {
        $model = Brand::find()->where(['status'=>1]);
        //        得到总条数
        $count = $model->count();
//        创建一个分页对象
        $page = new Pagination([
            'pageSize' => 3,//每页显示条数
            'totalCount' => $count//总条数
        ]);
        $brands=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('index',compact('brands','page'));
    }

    /**
     * 添加功能
     * @return string|\yii\web\Response
     */
    public function actionAdd(){
        $model = new Brand();
        if(\Yii::$app->request->isPost){
//            绑定数据
            $model->load(\Yii::$app->request->post());
//            验证
            if($model->validate()){
                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
                $imgPath = "";
                if($model->imgFile!=null){
//                    定义文件路径
                    $imgPath = "images/".uniqid().".".$model->imgFile->extension;
//                    移动临时文件
                    $model->imgFile->saveAs($imgPath,false);
                }
                $model->logo=$imgPath;
//                保存数据
                if($model->save()){
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model'));
    }

    /**
     * @param $id 需要修改的对象的id
     * 编辑功能
     * @return string|\yii\web\Response
     */
    public function actionEdit($id){
        $model = Brand::findOne($id);
        if(\Yii::$app->request->isPost){
//            绑定数据
            $model->load(\Yii::$app->request->post());
            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
            $imgPath = "";
            if($model->imgFile!=null){
//                    定义文件路径
                $imgPath = "images/".uniqid().".".$model->imgFile->extension;
//                    移动临时文件
                $model->imgFile->saveAs($imgPath,false);
            }
//            验证
            if($model->validate()){
                if($imgPath){
//                    删除修改前的文件
                    if($model->logo){
                        unlink($model->logo);
                    }
                    $model->logo=$imgPath;
                }
//                保存数据
                if($model->save()){
                    return $this->redirect(['index']);
                }
            }else{
                var_dump($model->errors);exit;
            }
        }
        return $this->render('add',compact('model'));
    }
    public function actionDel($id){
        $model = Brand::findOne($id);
        $model->status = 0;
        $model->save();
        return $this->redirect('index');
    }
    public function actionRecycle()
    {
        $model = Brand::find()->where(['status'=>0]);
        //        得到总条数
        $count = $model->count();
//        创建一个分页对象
        $page = new Pagination([
            'pageSize' => 3,//每页显示条数
            'totalCount' => $count//总条数
        ]);
        $brands=$model->offset($page->offset)->limit($page->limit)->all();
        return $this->render('recycle',compact('brands','page'));
    }
    public function actionReduction($id){
        $model = Brand::findOne($id);
        $model->status = 1;
        $model->save();
        return $this->redirect('recycle');
    }
}
