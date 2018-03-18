<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<a href="<?=\yii\helpers\Url::to(['recycle'])?>" class="btn btn-default">回收站</a>
<table class="table table-responsive table-bordered">
    <tr>
        <th>编号</th>
        <th>标题</th>
        <th>简介</th>
        <th>分类</th>
        <th>排序</th>
        <th>状态</th>
        <th>创建时间</th>
        <th>操作</th>
    </tr>
    <?php foreach ($articles as $article): ?>
        <tr>
            <td><?=$article->id?></td>
            <td><?=$article->title?></td>
            <td><?=$article->intro?></td>
            <td><?=$article->category->name?></td>
            <td><?=$article->sort?></td>
            <td><?php
                if($article->status){
                    echo "<span class='glyphicon glyphicon-ok' style='color: green' ></span>";
                }else{
                    echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>";
                }
                ?></td>
            <td>
                <?=date('Ymd H:i:s',$article->create_time)?>
            </td>
            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$article->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$article->id])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page
])?>