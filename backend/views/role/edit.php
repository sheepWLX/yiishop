<a href="index" class="btn btn-info">首页</a>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name')->textInput(['disabled'=>""]);
echo $form->field($model,'description');
echo $form->field($model,'permissions')->inline()->checkboxList($persArr);
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();