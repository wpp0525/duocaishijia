<?php
$this->breadcrumbs=array(
    Yii::t('main','Store')=>array('index'),
	$model->store_id,
);

$this->menu=array(
//	array('label'=>Yii::t('main','门店列表'), 'icon'=>'list', 'url'=>array('index')),
	array('label'=>Yii::t('main','创建'), 'icon'=>'plus','url'=>array('create')),
//	array('label'=>Yii::t('main','Update Payments'), 'icon'=>'pencil','url'=>array('update', 'id'=>$model->payment_id)),
	array('label'=>Yii::t('main','Delete'), 'icon'=>'trash', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->store_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('main','Manage'), 'icon'=>'cog','url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main','查看门店');?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'store_name',
		'erpID',
		'state',
		'city',
		'address',
		'phone',
		'create_time',
	),
)); ?>
