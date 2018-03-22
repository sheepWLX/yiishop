<?php
/* @var $this yii\web\View */
?>
<h1>欢迎登陆</h1>
    <a href="<?=\yii\helpers\Url::to(['add'])?>" class="btn btn-info">添加</a>
    <table class="table table-responsive table-bordered">
        <tr>
            <th>编号</th>
            <th>用户名</th>
            <th>状态</th>
            <th>登录时间</th>
            <th>登陆ip</th>
            <th>操作</th>
        </tr>
        <?php foreach ($admins as $admin): ?>
            <tr>
                <td><?=$admin->id?></td>
                <td><?=$admin->username?></td>
                <td><?php
                    if($admin->status){
                        echo "<span class='glyphicon glyphicon-ok' style='color: green' ></span>";
                    }else{
                        echo "<span class='glyphicon glyphicon-remove' style='color: red'></span>";
                    }
                    ?>
                </td>
                <td>
                    <?=date('Ymd H:i:s',$admin->login_at)?>
                </td>
                <td>
                    <?=long2ip($admin->login_ip)?>
                </td>
                <td>
                    <a href="<?=\yii\helpers\Url::to(['edit','id'=>$admin->id])?>" class="btn btn-success">编辑</a>
                    <a href="<?=\yii\helpers\Url::to(['del','id'=>$admin->id])?>" class="btn btn-danger">删除</a>
                </td>
            </tr>
        <?php endforeach; ?>
    </table>