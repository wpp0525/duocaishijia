<div class="form">
	<?php $form=$this->beginWidget('CActiveForm', array(
		'id'=>'store-form',
		'enableAjaxValidation'=>false,
	)); ?>

	<p class="note">带<span class="required"> * </span>的必须填写。</p>

	<?php echo $form->errorSummary($model); ?>

	<div class="row1" style="">
		<?php echo $form->labelEx($model,'store_name'); ?>
		<?php echo $form->textField($model,'store_name',array('size'=>45,'maxlength'=>45,'style' => 'height: 30px')); ?>
<!--		--><?php //echo $form->error($model,'store_name'); ?>
	</div>

	<div class="row1">
		<?php echo $form->labelEx($model,'erpID'); ?>
		<?php echo $form->textField($model,'erpID',array('size'=>45,'maxlength'=>45 ,'style' => 'height: 30px')); ?>
	</div>

	<div class="row1">
		<?php
			$state_data = Area::model()->findAll("grade=:grade",
				array(":grade" => 1));
			$state = CHtml::listData($state_data, "area_id", "name");
			$s_default = $model->isNewRecord ? '' : $model->state;
			echo $form->labelEx($model, 'state');
			echo CHtml::dropDownList('state', $s_default, $state,
				array(
					'class' => 'form-control form-control1',
					'empty' => '请选择省份',
					'ajax' => array(
						'type' => 'GET', //request type
						'url' => CController::createUrl('/mall/order/dynamiccities'), //url to call
						'update' => '#city', //selector to update
						'data' => 'js:"receiver_state="+jQuery(this).val()',
					)));
		?>
	</div>
	<div class="row1">
		<?php
			$c_default = $model->isNewRecord ? '' : $model->city;
			if (!$model->isNewRecord) {
				$city_data = Area::model()->findAll("parent_id=:parent_id",
					array(":parent_id" => $model->state));
				$city = CHtml::listData($city_data, "area_id", "name");
			}
			$city_update = $model->isNewRecord ? array() : $city;
			echo $form->labelEx($model, 'city');
			echo CHtml::dropDownList('city', $c_default, $city_update,
				array(
					'class' => 'form-control form-control1',
					'empty' => '请选择城市',
					'ajax' => array(
						'type' => 'GET', //request type
						'url' => CController::createUrl('/mall/order/dynamicdistrict'), //url to call
						'update' => '#district', //selector to update
						'data' => 'js:"receiver_city="+jQuery(this).val()',
					)));
		?>
	</div>
	<div class="row1">
		<?php
			$d_default = $model->isNewRecord ? '' : $model->district;
			if (!$model->isNewRecord) {
				$district_data = Area::model()->findAll("parent_id=:parent_id",
					array(":parent_id" => $model->city));
				$district = CHtml::listData($district_data, "area_id", "name");
			}
			$district_update = $model->isNewRecord ? array() : $district;
			echo $form->labelEx($model, 'district');
			echo CHtml::dropDownList('district', $d_default, $district_update,
				array(
					'class' => 'form-control form-control1',
					'empty' => '请选择地区',
				)
			);
		?>
	</div>

	<div class="row1">
		<?php echo $form->labelEx($model,'phone'); ?>
		<?php echo $form->textField($model,'phone',array('size'=>20,'maxlength'=>20,'style' => 'height: 30px')); ?>
		<!--		--><?php //echo $form->error($model,'phone'); ?>
	</div>

	<div class="row1">
		<?php echo $form->labelEx($model,'address'); ?>
		<?php echo $form->textField($model,'address',array('size'=>255,'maxlength'=>255,'style' => 'height: 30px;width:620px')); ?>
<!--		--><?php //echo $form->error($model,'order_id'); ?>
	</div>


	<div class="form-actions">
		<?php  echo TbHtml::formActions(array(
			TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
		)); ?>

	</div>
	<?php $this->endWidget(); ?>

</div><!-- form -->