<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<a href="<?=\yii\helpers\Url::to(['recycle'])?>" class="btn btn-default">回收站</a>
<table class="table table-responsive table-bordered">
    <tr>
        <th>编号</th>
        <th>名称</th>
        <th>排序</th>
        <th>状态</th>
        <th>简介</th>
        <th>图像</th>
        <th>操作</th>
    </tr>
    <?php foreach ($brands as $brand): ?>
    <tr>
        <td><?=$brand->id?></td>
        <td><?=$brand->name?></td>
        <td><?=$brand->sort?></td>
        <td><?php
            if($brand->status){
                echo "<span class='glyphicon glyphicon-ok' style='color: green' ></span>";
            }else{
                echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>";
            }
            ?></td>
        <td><?=$brand->intro?></td>
        <td><img src="/<?=$brand->logo?>" height="30" alt=""></td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['edit','id'=>$brand->id])?>" class="btn btn-success">编辑</a>
            <a href="<?=\yii\helpers\Url::to(['del','id'=>$brand->id])?>" class="btn btn-danger">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page
])?>