<?php
	$this->breadcrumbs=array(
		Yii::t('main','添加门店')=>array('index'),
		Yii::t('main','Create'),
	);

	$this->menu=array(
		array('label'=>Yii::t('main','门店列表'), 'icon'=>'list', 'url'=>array('index')),
		array('label'=>Yii::t('main','管理门店'), 'icon'=>'cog','url'=>array('admin')),
	);
?>

	<h1><?php echo Yii::t('main','创建');?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>