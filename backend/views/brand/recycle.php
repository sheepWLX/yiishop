<a href="<?=\yii\helpers\Url::to(['index'])?>" class="btn btn-default">首页</a>
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
            <td>
                <?php
                if(strpos($brand->logo,'http://')!==false){
                    echo \yii\bootstrap\Html::img($brand->logo,['height'=>'50']);
                }else{
                    echo \yii\bootstrap\Html::img("/".$brand->logo,['height'=>'50']);
                }
                ?>
            </td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['reduction','id'=>$brand->id])?>" class="btn btn-success">还原</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page
])?>