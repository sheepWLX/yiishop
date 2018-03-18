<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'title');
echo $form->field($model,'sort')->textInput(['value'=>100]);
echo $form->field($model,'status')->inline()->radioList(['禁用','激活'],['value'=>1]);
echo $form->field($model,'category_id')->dropDownList($cateArr);
echo $form->field($model,'intro')->textarea();
echo $form->field($content,'content')->widget('kucha\ueditor\UEditor',[]);;
echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-info']);
\yii\bootstrap\ActiveForm::end();