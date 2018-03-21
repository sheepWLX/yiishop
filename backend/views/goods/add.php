<?php
/* @var $form \yii\bootstrap\ActiveForm*/
?>
<?php
$form = \yii\bootstrap\ActiveForm::begin();
echo $form->field($model,'name');
echo $form->field($model,'sn');
echo $form->field($model,'market_price');
echo $form->field($model,'shop_price');
echo $form->field($model,'stock');
echo $form->field($model,'sort')->textInput(['value'=>100]);
echo $form->field($model,'status')->radioList(['下架','上架'],['value'=>1]);
echo $form->field($model,'brand_id')->dropDownList($brandArr,['prompt'=>'选择一个分类']);
echo $form->field($model,'goods_category_id')->dropDownList($catesArr,['prompt'=>'选择一个分类']);
echo $form->field($model,'logo')->widget(\manks\FileInput::className(),[]);
echo $form->field($model,'imgFiles')->widget('manks\FileInput', [
    'clientOptions' => [
        'pick' => [
            'multiple' => true,
        ],
        'server' =>\yii\helpers\Url::to(['brand/qiniu-upload']),
//         'accept' => [
//         	'extensions' => 'png',
//         	'extensions' => 'jpg',
//         	'extensions' => 'gif',
//         ],
    ],
]);
echo $form->field($goodsIntro,'content')->widget(kucha\ueditor\UEditor::className(),[]);

echo \yii\helpers\Html::submitButton('提交',['class'=>'btn btn-success']);
\yii\bootstrap\ActiveForm::end();