<?php
$this->breadcrumbs=array(
    Yii::t('main','Prop Imgs')=>array('admin'),
	$model->item_prop_img_id,
);

$this->menu=array(
//	array('label'=>Yii::t('main','门店列表'), 'icon'=>'list', 'url'=>array('index')),
	array('label'=>Yii::t('main','创建'), 'icon'=>'plus','url'=>array('create')),
	array('label'=>Yii::t('main','Update'), 'icon'=>'pencil','url'=>array('update', 'id'=>$model->item_prop_img_id)),
	array('label'=>Yii::t('main','Delete'), 'icon'=>'trash', 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->item_prop_img_id),'confirm'=>'Are you sure you want to delete this item?')),
	array('label'=>Yii::t('main','Manage'), 'icon'=>'cog','url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main','查看颜色图片');?></h1>

<?php $this->widget('bootstrap.widgets.TbDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		array(
			'name' =>   'item_id',
			'value' => Tbfunction::showItem($model->item_id),
		),

		array(
			'name' =>   'item_props',
			'value' => Tbfunction::showProps($model->item_props),
		),

		array(
			'name' => '图片',
			'value' => Tbfunction::showPics($model->item_prop_img_id),
		),
	),
)); ?>
