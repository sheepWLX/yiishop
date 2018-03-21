<h3>分类管理</h3>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table table-responsive table-bordered">
    <tr>
        <th>编号</th>
<!--        <th>树</th>-->
<!--        <th>左值</th>-->
<!--        <th>右值</th>-->
<!--        <th>深度</th>-->
        <th>分类名称</th>
        <th>简介</th>
        <th>父类id</th>
        <th>操作</th>
    </tr>
    <?php foreach ($models as $model): ?>
        <tr class="cate_tr" tree="<?=$model->tree?>" lft="<?=$model->lft?>" rgt="<?=$model->rgt?>">
            <td><?=$model->id?></td>
            <td><span class="glyphicon glyphicon-chevron-up"></span><?=$model->NameText?></td>
            <td><?=$model->intro?></td>
            <td><?=$model->prent_id?></td>

            <td>
                <a href="<?=\yii\helpers\Url::to(['edit','id'=>$model->id])?>" class="btn btn-success">编辑</a>
                <a href="<?=\yii\helpers\Url::to(['del','id'=>$model->id])?>" class="btn btn-danger">删除</a>
            </td>
        </tr>
    <?php endforeach; ?>
</table>
<?=\yii\widgets\LinkPager::widget([
    'pagination' => $page
])?>
<?php
$js=<<<JS
$(".cate_tr").click(function(){
var tr=$(this);
tr.find("span").toggleClass("glyphicon-chevron-up");
tr.find("span").toggleClass("glyphicon-chevron-down");

var lft_prent=tr.attr('lft');
var rgt_prent=tr.attr('rgt');
var tree_prent=tr.attr('tree');
// 当前类的左值 右值 树
$(".cate_tr").each(function(k,v){
var lft=$(v).attr('lft');
var rgt=$(v).attr('rgt');
var tree=$(v).attr('tree');
if(tree==tree_prent && lft-lft_prent>0 && rgt-rgt_prent<0){
$(v).fadeToggle();
}
});
// console.dir(this);
});
JS;
$this->registerJs($js);
?>
