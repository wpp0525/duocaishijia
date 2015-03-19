<?php
$this->breadcrumbs=array(
    Yii::t('main','Store')=>array('index'),
//	$model->payment_id=>array('view','id'=>$model->payment_id),
    Yii::t('main','Update'),
);

$this->menu=array(
	array('label'=>Yii::t('main','Payments List'), 'icon'=>'list', 'url'=>array('index')),
	array('label'=>Yii::t('main','创建门店'), 'icon'=>'plus','url'=>array('create')),
	array('label'=>Yii::t('main','查看门店'), 'url'=>array('view', 'id'=>$model->store_id)),
	array('label'=>Yii::t('main','管理门店'), 'icon'=>'cog','url'=>array('admin')),
);
?>

<h1><?php echo Yii::t('main','更新门店');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>