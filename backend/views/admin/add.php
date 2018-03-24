<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'username');
echo $form->field($model,'password_hash');
echo $form->field($model,'status')->inline()->radioList(['禁用','激活'],['value'=>1]);
echo $form->field($model,'roles')->inline()->checkboxList($rolesArr);
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();