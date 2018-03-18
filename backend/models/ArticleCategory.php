<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "article_category".
 *
 * @property int $id
 * @property string $name 名称
 * @property int $sort 排序
 * @property int $status 状态
 * @property string $intro 简介
 * @property int $is_help 是否是帮助类
 */
class ArticleCategory extends \yii\db\ActiveRecord
{

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['name','sort','status','is_help'], 'required'],
            [['intro'],'safe'],
            [['name'],'unique']
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
            'sort' => '排序',
            'status' => '状态',
            'intro' => '简介',
            'is_help' => '是否是帮助类',
        ];
    }
}
