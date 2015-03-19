<?php

	$this->breadcrumbs = array(
//		Yii::t('main', '查看产品属性图片') => array('admin'),
//		Yii::t('main', '查看'),
	);
	$this->menu = array(
		array('label' => Yii::t('main','创建'), 'icon' => 'plus', 'url' => array('create')),
	);
?>
<h1><?php echo Yii::t('main','查看产品属性图片');?></h1>
<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'itempropimg-form',
		'action' => 'bulk',
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
		'enableAjaxValidation' => false,
	));
?>
<?php
$this->widget('bootstrap.widgets.TbGridView',array(
	'id' => 'itemPropImg_grid',
	'type' => 'striped bordered condensed',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
			'class' => 'CCheckBoxColumn',
			'name' => 'item_prop_img_id',
			'value' => '$data->item_prop_img_id',
		),
		array(
			'name' =>   'item_id',
			'header' => '产品',
			'value' => 'Tbfunction::showItem($data->item_id)',
		),
		array(
			'name' =>   'item_props',
			'header' => '属性',
			'value' => 'Tbfunction::showProps($data->item_props)',
		),

		array(
			'header' => '图片',
			'value' => 'Tbfunction::showPics($data->item_prop_img_id)',
		),

		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
));
?>
<?php $this->endWidget(); ?>