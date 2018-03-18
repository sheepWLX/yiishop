<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'sort');
echo $form->field($model,'status')->inline()->radioList(["禁用","激活"],['value'=>1]);
echo $form->field($model,'is_help')->inline()->radioList(['否','是'],['value'=>0]);
echo $form->field($model,'intro')->textarea();
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();