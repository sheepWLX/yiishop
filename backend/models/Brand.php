<?php

namespace backend\models;

use yii\db\ActiveRecord;

/**
 * This is the model class for table "brand".
 *
 * @property int $id
 * @property string $name 名称
 * @property string $logo 图像
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $intro 简介
 */
class Brand extends ActiveRecord
{
    /**
     * @inheritdoc
     */
    public $imgFile;
    public function rules()
    {
        return [
            [['name','sort','status'], 'required'],
            [['imgFile'],'image','skipOnEmpty' => true,'extensions' => ['jpg','png','gif']],
            [['intro'],'required']
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => '名称',
            'imgFile' => '图像',
            'sort' => '排序',
            'status' => '状态',
            'intro' => '简介',
        ];
    }
}
