<?php
/* @var $this yii\web\View */
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'prent_id')->hiddenInput(['value'=>0]);
echo \liyuze\ztree\ZTree::widget([
    'setting' => '{
			data: {
				simpleData: {
					enable: true,
					pIdKey: "prent_id",
				}
			},
            callback: {
            onClick: onClick
            }
		}',
    'nodes' => $catesJson
]);
echo $form->field($model,'intro')->textarea();
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();
?>
<script>
    function onClick(e,treeId, treeNode) {
        $("#category-prent_id").val(treeNode.id);
//        $("#w1").style(display:none);
        var treeObj = $.fn.zTree.getZTreeObj("tree");
        treeObj.expandAll(true)
    }
</script>
<?php
$js=<<<WLX
    var treeObj = $.fn.zTree.getZTreeObj("w1");
treeObj.expandAll(true);
WLX;

$this->registerJs($js);
?>
