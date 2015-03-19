<div class="my_info">
    <a class="current">我的服务地址</a>
</div>
<?php
$this->breadcrumbs=array(
	'服务地址'=>array('admin'),
	'详细地址#'.$model->contact_id=>array('view','id'=>$model->contact_id),
	'更新',
);

$this->menu=array(
	array('label'=>'List AddressResult', 'url'=>array('index')),
	array('label'=>'Create AddressResult', 'url'=>array('create')),
	array('label'=>'View AddressResult', 'url'=>array('view', 'id'=>$model->contact_id)),
	array('label'=>'Manage AddressResult', 'url'=>array('admin')),
);
?>

<div class="profile_info1">
    <div class="box-title" style="font-size: 14px">更新服务地址#<?php echo $model->contact_id; ?></div>
    <div class="form-horizontal">
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
    </div>
</div>