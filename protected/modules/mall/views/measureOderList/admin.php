<?php

	$this->breadcrumbs = array(
		Yii::t('main', '查看预约') => array('admin'),
	);
?>
	<h1><?php echo Yii::t('main','查看预约单');?></h1>
<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id' => 'measure-form',
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
				'name' =>   'client_name',
				'header' => '姓名',
			),

			array(
				'name' =>   'client_state',
				'header' => '省份',
				'value' => 'Tbfunction::showArea($data->client_state)',
			),

			array(
				'name' =>   'client_city',
				'header' => '城市',
				'value' => 'Tbfunction::showArea($data->client_city)',
			),
			array(
				'name' =>   'client_address',
				'header' => '详细地址',
			),
			array(
				'name' =>   'client_mobile',
				'header' => '手机',
			),
			array(
				'name' =>   'client_phone',
				'header' => '固话',
			),
			array(
				'name' =>   'client_memo',
				'header' => '备注',
			),
			array(
				'name' =>   'total',
				'header' => '预算',
			),
			array(
				'name' =>   'status',
				'header' => '状态',
				'value' => 'Tbfunction::showMeasureStatus($data->status,$data->news)',
			),
			array(
				'value' => 'Tbfunction::getNews($data->news)',
				'htmlOptions'=>array(
					'class'=>'newsOrder',
				),
			),
			array(
				'class' => 'bootstrap.widgets.TbButtonColumnQuery',
				'template' => '{view}',
				'buttons' => array(
					'deliverstore' => array(
						'label' => '看',
						'options' => array(
							'class' => 'btn btn-info',
						),
					),
				),
			),
		),
	));
?>
<?php $this->endWidget(); ?>
<script>
	$(".view").click(function(){
		if($(this).parent().parent().children('.newsOrder').text()=='new'){
			var href = $(this).attr('href');
			var Id = href.substr(href.lastIndexOf('=')+1);
			var data = {'Id' :Id };
			$.post("order/deleteMeasureNews",data , function(response) {
			}, 'json' );
		}

	});
</script>