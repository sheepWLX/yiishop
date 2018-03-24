<?php

namespace backend\models;

use Yii;

/**
 * This is the model class for table "mulu".
 *
 * @property int $id
 * @property string $name
 * @property string $icon
 * @property string $url
 * @property int $parent_id
 */
class Mulu extends \yii\db\ActiveRecord
{
    /**
     * @inheritdoc
     */
    public static function tableName()
    {
        return 'mulu';
    }

    /**
     * @inheritdoc
     */
    public function rules()
    {
        return [
            [['parent_id'], 'integer'],
        ];
    }

    /**
     * @inheritdoc
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'icon' => 'Icon',
            'url' => 'Url',
            'parent_id' => 'Parent ID',
        ];
    }
    public static function menu(){
        $menuAll=[];
//        得到所有一级目录
        $menus=self::find()->where(['parent_id'=>0])->all();
        foreach ($menus as $menu){
            $newMenu=[];
            $newMenu['label']=$menu->name;
            $newMenu['icon']=$menu->icon;
            $newMenu['url']=$menu->url;
            $menusSon = self::find()->where(['parent_id'=>$menu->id])->all();
            foreach ($menusSon as $menuSon){
                $newMenuSon=[];
                $newMenuSon['label']=$menuSon->name;
                $newMenuSon['icon']=$menuSon->icon;
                $newMenuSon['url']=$menuSon->url;
                $newMenu['items'][]=$newMenuSon;
            }
            $menuAll[]=$newMenu;
        }
        return $menuAll;
    }
}
