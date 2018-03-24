<h3>角色管理</h3>
<a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
<table class="table table-responsive table-bordered">
    <tr>
        <th>名称</th>
        <th>描述</th>
        <th>权限</th>
        <th>操作</th>
    </tr>
    <?php foreach ($roles as $role): ?>
    <tr>
        <td>
            <?=$role->name?>
        </td>
        <td>
            <?=$role->description?>
        </td>
        <td>
            <?php
//                得到当前角色对应的权限
                $auth = Yii::$app->authManager;
//                通过当前的角色名得到所有权限
                $pers=$auth->getPermissionsByRole($role->name);
                $html = "";
                foreach ($pers as $per){
                    $html .= $per->description."、";
                }
                $html= trim($html,'、');
                echo $html;
            ?>
        </td>
        <td>
            <a href="<?=\yii\helpers\Url::to(['edit','name'=>$role->name])?>" class="btn btn-success">编辑</a>
            <a href="<?=\yii\helpers\Url::to(['del','name'=>$role->name])?>" class="btn btn-danger">删除</a>
        </td>
    </tr>
    <?php endforeach; ?>
</table>
