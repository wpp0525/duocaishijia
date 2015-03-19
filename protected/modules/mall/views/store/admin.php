<?php

	$this->breadcrumbs = array(
		Yii::t('main', '查看店铺') => array('admin'),
//		Yii::t('main', '查看'),
	);
//	$this->menu = array(
//		array('label' => Yii::t('main','创建'), 'icon' => 'plus', 'url' => array('create')),
//	);
?>
<h1><?php echo Yii::t('main','查看店铺');?></h1>
<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'store-form',
		'action' => 'bulk',
		'htmlOptions' => array('enctype' => 'multipart/form-data'),
		'enableAjaxValidation' => false,
	));
?>
<?php
$this->widget('bootstrap.widgets.TbGridView',array(
	'id' => 'store_grid',
	'type' => 'striped bordered condensed',
	'dataProvider' => $model->search(),
	'filter' => $model,
	'columns' => array(
		array(
			'class' => 'CCheckBoxColumn',
			'name' => 'store_id',
			'value' => '$data->store_id',
		),

		array(
			'name' =>   'store_name',
            'header' => '店铺名称',
		),

		array(
			'name' =>   'state',
			'header' => '省份',
			'value' => 'Tbfunction::showArea($data->state)',
		),

		array(
		    'name' =>   'city',
			'header' => '城市',
			'value' => 'Tbfunction::showArea($data->city)',
		),
		//array(
		//	'name' =>   'district',
		//	'header' => '地区',
		//	'value' => 'Tbfunction::showArea($data->district)',
		//),
		array(
			'name' =>   'address',
			'header' => '详细地址',
		),
		array(
			'name' =>   'phone',
			'header' => '电话',
		),
		array(
			'name' =>   'create_time',
			'header' => '开店日期',
		),

		array(
			'class' => 'bootstrap.widgets.TbButtonColumn',
		),
	),
));
?>
<?php $this->endWidget(); ?>