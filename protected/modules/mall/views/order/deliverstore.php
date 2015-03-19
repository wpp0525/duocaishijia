<?php
	/**
	 * Created by PhpStorm.
	 * author: shuai.du@jago-ag.cn
	 * Date: 3/12/14
	 * Time: 11:42 AM
	 *
	 * 分配单子到相应的店铺
	 */
?>
<?php
	$form = $this->beginWidget('CActiveForm', array(
		'id'     => 'deliver-form',
		'enableAjaxValidation' => true,
		'enableClientValidation' => true,
	));
?>

<div class="store-info">
	<ul>
		<li class="area-info">
			<?php echo CHtml::label('地区', 'Area_name', array('class' => 'area-label')); ?>
			<?php echo $form->dropDownList($area, 'name', $areaInfo, array(
				'empty' => '请选择地区',
				'class' => 'area-list',
				'ajax'  => array(
					'type'   => 'POST',
					'url'    => Yii::app()->createUrl('mall/order/dynamicStore'),
					'update' => '#Order_store_id',
					'data'   => array(
						'YII_CSRF_TOKEN' => Yii::app()->request->getCsrfToken(),
						'district'       => 'js:jQuery(this).val()'
					),
				),
			)); ?>
		</li>
		<li class="stroe-info">
			<?php echo CHtml::label('请选择门店', 'Order_store_id', array('class' => 'store-label')); ?>
			<?php echo $form->dropDownList($order, 'store_id', array(), array(
				'empty' => '请选择门店',
				'class' => 'store-list',
			)); ?>
		</li>

	</ul>
</div>

 <?php echo CHtml::hiddenField('backurl',Yii::app()->request->urlReferrer)?>

<?php
	echo TbHtml::formActions( array(
		TbHtml::submitButton('确认', array('color' => TbHtml::BUTTON_COLOR_SUCCESS) ),
	));
   ?>

	<?php
		$this->endWidget();
	?>




