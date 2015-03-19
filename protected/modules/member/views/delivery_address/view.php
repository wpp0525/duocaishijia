<?php
$this->breadcrumbs=array(
	'服务地址'=>array('admin'),
	'详细地址#'.$model->contact_id,
);

//$this->menu=array(
//	array('label'=>'List AddressResult', 'url'=>array('index')),
//	array('label'=>'Create AddressResult', 'url'=>array('create')),
//	array('label'=>'Update AddressResult', 'url'=>array('update', 'id'=>$model->contact_id)),
//	array('label'=>'Delete AddressResult', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->contact_id),'confirm'=>'Are you sure you want to delete this item?')),
//	array('label'=>'Manage AddressResult', 'url'=>array('admin')),
//);
?>
<div class="my_info">
    <a class="current">我的服务地址</a>
</div>
<div class="profile_info1">
    <div class="box-title" style="font-size: 14px">查看服务地址#<?php echo $model->contact_id; ?></div>
    <div class="box-content">
<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		'contact_id',
		'user_id',
		'contact_name',
//		'country',
		's.name',
		'c.name',
		'd.name',
//		'zipcode',
		'address',
		'phone',
		'mobile_phone',
		'memo',
		'is_default',
		'create_time',
		'update_time',
	),
)); ?>
    </div>
</div>