<?php
/* @var $this yii\web\View */
/* @var $model backend\models\Goods */

?>
<h3>商品管理</h3>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info pull-left">添加</a>
<form class="form-inline pull-right">
    <select class="form-control" name="status">
        <option>请选择状态</option>
        <option value="0">禁用</option>
        <option value="1">激活</option>
    </select>
    <div class="form-group">
        <input type="text" class="form-control" id="minPrice" placeholder="最小价格" size="5" name="minPrice" value="<?=Yii::$app->request->get('minPrice')?>">
    </div>
    -
    <div class="form-group">
        <input type="text" class="form-control" id="maxPrice" placeholder="最大价格" size="5" name="maxPrice"value="<?=Yii::$app->request->get('maxPrice')?>">
    </div>
    <div class="form-group">
        <label for="key">搜索</label>
        <input type="text" class="form-control" id="keyword" placeholder="搜索货号或商品名称" name="keyword" value="<?=Yii::$app->request->get('keyword')?>">
    </div>
    <button type="submit" class="btn btn-default"><span class="glyphicon glyphicon-search"></span></button>
</form>
<table class="table table-responsive table-bordered">
    <tr>
        <th>编号</th>
        <th>商品名称</th>
        <th>商品货号</th>
        <th>市场价格</th>
        <th>本店价格</th>
        <th>库存</th>
        <th>排序</th>
        <th>状态</th>
        <th>品牌编号</th>
        <th>商品LOGO</th>
        <th>商品分类编号</th>
        <th>录入时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
         <tr >
            <td><?=$model->id?></td>
            <td><?=$model->name?></td>
            <td><?=$model->sn?></td>
            <td><?=$model->market_price?></td>
            <td><?=$model->shop_price?></td>
            <td><?=$model->stock?></td>
            <td><?=$model->sort?></td>
            <td>
                <?php if($model->status){
                    echo "<span class='glyphicon glyphicon-ok' style='color: green'></span>";
                }else{
                    echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>";
                }?>
            </td>
            <td><?=$model->brand_id?></td>
            <td>
                <?php
                    echo \yii\helpers\Html::img($model->logo,['height'=>50]);
                ?>
            </td>
            <td><?=$model->goods_category_id?></td>
            <td><?=date('Ymd H:i:s',$model->create_time)?></td>

            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$model->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$model->id])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $pages
])?>

