<?php

namespace backend\controllers;

use backend\models\Brand;
use yii\data\Pagination;
use yii\helpers\Json;
use yii\web\UploadedFile;
use crazyfd\qiniu\Qiniu;

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
//                $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//                $imgPath = "";
//                if($model->imgFile!=null){
////                    定义文件路径
//                    $imgPath = "images/".uniqid().".".$model->imgFile->extension;
////                    移动临时文件
//                    $model->imgFile->saveAs($imgPath,false);
//                }
//                $model->logo=$imgPath;
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
//            $model->imgFile=UploadedFile::getInstance($model,'imgFile');
//            $imgPath = "";
//            if($model->imgFile!=null){
////                    定义文件路径
//                $imgPath = "images/".uniqid().".".$model->imgFile->extension;
////                    移动临时文件
//                $model->imgFile->saveAs($imgPath,false);
//            }
//            验证
            if($model->validate()){
//                if($imgPath){
////                    删除修改前的文件
//                    if($model->logo){
//                        unlink($model->logo);
//                    }
//                    $model->logo=$imgPath;
//                }
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
     * @param $id 需要删除的对象的id
     * 删除功能
     * @return \yii\web\Response
     */
    public function actionDel($id){
        $model = Brand::findOne($id);
        $model->status = 0;
        $model->save();
        return $this->redirect('index');
    }

    /**
     * 显示回收站
     * @return string
     */
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

    /**
     * @param $id
     * @return \yii\web\Response
     */
    public function actionReduction($id){
        $model = Brand::findOne($id);
        $model->status = 1;
        $model->save();
        return $this->redirect('recycle');
    }

    public function actionUpload(){
//        $uploadType = \Yii::$app->params['uploadType'];
//        switch ($uploadType){
//            case "local":
//
//            break;
//            case "qiniu":
//
//            break;
//        }
        $fileObj=UploadedFile::getInstanceByName('file');
//        var_dump($fileObj);
        if($fileObj!==null){
            $filePath = "images/".uniqid().".".$fileObj->extension;
            if($fileObj->saveAs($filePath,false)){
                $result = [
                    'code'=>0,
                    'url'=>"/".$filePath,
                    'attachment'=>$filePath
                ];
                return Json::encode($result);

            }
        }else{
            $result = [
                'code'=>1,
                'msg'=>'error'
            ];
            return Json::encode($result);
        }

    }
    public function actionQiniuUpload(){

        $ak = '90QcoN7eLVY87wNcQ9NO25d7F9S8JkACVJY4HmRN';
        $sk = 'mNP4t_XmlsLZOg13qK6vfWcN4JT0OjM9pPa1Qtjy';
        $domain = 'http://p5q5flwyg.bkt.clouddn.com';
        $bucket = 'yiishop';
        $zone = 'south_china';
        $qiniu = new Qiniu($ak, $sk,$domain, $bucket,$zone);
        $key = uniqid();
        $key .= strtolower(strrchr($_FILES['file']['name'], '.'));

        $qiniu->uploadFile($_FILES['file']['tmp_name'],$key);
        $url = $qiniu->getLink($key);
        $result = [
                    'code'=>0,
                    'url'=>$url,
                    'attachment'=>$url
                ];
                return Json::encode($result);
    }
}
