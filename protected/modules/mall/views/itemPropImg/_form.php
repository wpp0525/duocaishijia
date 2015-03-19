<?php
  $items = Item::model()->findAll();
?>
<div class="form">
	<?php  $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
		'id' => 'item-prop-form',
		'htmlOptions' => array(
			'class' => 'form-horizontal',
			'enctype'=>'multipart/form-data',
		),
		'enableAjaxValidation' => false,
	));$form = new TbActiveForm();?>

	<p class="note">带<span class="required"> * </span>的必须填写。</p>

	<?php echo $form->errorSummary($model); ?>

	<?php
		list($items) = $model->getItems();
		echo $form->dropDownListControlGroup($model, 'item_id', $items,
			array(
			'class'=>'selected-form',
			'data-id' => 'item_id',
			'empty'=>'请选择产品',
			'ajax' => array(
				'type'=>'GET', //request type
				'url'=>CController::createUrl('/mall/itemPropImg/getProps'), //url to call
				'update'=>'#ItemPropImg_item_props', //selector to update
				'data'   => 'js:"item_id="+jQuery(this).val()',
			),)
		);

		$item_props = $model->isNewRecord ? array() : $model->item_props;
		if(!is_array($item_props)){
			$item_props1[0] = Tbfunction::showProps($model->item_props);
		}else{
			$item_props1 = $item_props;
		}
		echo  $form->dropDownListControlGroup($model,'item_props',$item_props1);
		echo $form->label($model,'图片',array('class'=>'pics'));
		echo '<div class="prop_pics">';
		$this->widget('ext.elFinder.ServerFileInput', array(
			'model' => new ItemPropImgs(),
			'attribute' => 'pic',
			'models' => $model->itemPropImgs,
			'attributes' => array('item_prop_img_id'),
			'filebrowserBrowseUrl' => Yii::app()->createUrl('mall/elfinder/view'),
			'isMulti' => true,
		));
		echo '</div>';
		echo '</div>';
	?>

	<?php ?>
	<div class="form-actions">
		<?php  echo TbHtml::formActions(array(
			TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		)); ?>

	</div>
	<?php $this->endWidget(); ?>

</div><!-- form -->