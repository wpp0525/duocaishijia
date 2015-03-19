<?php
echo $form->textFieldControlGroup($model, 'title');
echo $form->textFieldControlGroup($model, 'erpID');
//if($model->stock) {
//    echo  $form->textFieldControlGroup($model, 'stock', array('help' => '库存默认为1000','readonly' => true));
//}

echo  $form->textFieldControlGroup($model, 'min_number', array('help' => '最少订货量默认为1'));
echo  $form->textFieldControlGroup($model, 'price');
echo  $form->dropDownListControlGroup($model, 'currency', array('㎡' => '平方米', '台/次' => '台/次'));
//echo  $form->textFieldControlGroup($model, 'outer_id');
	echo  $form->textFieldControlGroup($model, 'items_url');
	echo  $form->textFieldControlGroup($model, 'warn_desc');
	echo  $form->textFieldControlGroup($model, 'warn_url');
echo  $form->dropDownListControlGroup($model, 'language', array('zh_cn' => '中文', 'en_us' => 'English'));
$url = Yii::app()->createUrl('mall/item/getChildAreas');
list($countryAreas, $stateAreas, $citeAreas) = $model->getAreas();
echo  $form->dropDownListControlGroup($model, 'country', $countryAreas,
    array('class' => 'area area-country', 'data-child-area' => 'area-state', 'data-url' => $url));
echo  $form->dropDownListControlGroup($model, 'state', $stateAreas, array('class' => 'area area-state', 'data-child-area' => 'area-city', 'data-url' => $url));
//echo  $form->dropDownListControlGroup($model, 'city', $citeAreas, array('class' => 'area-city'));
//echo  $form->textFieldControlGroup($model, 'Keywords');
//echo  $form->textFieldControlGroup($model, 'description' );
//echo  $form->textFieldControlGroup($model, 'title1');

?>



