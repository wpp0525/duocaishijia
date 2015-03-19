<div class="view">

	<b><?php echo CHtml::encode($data->getAttributeLabel('store_id')); ?>:</b>
	<?php echo CHtml::link(CHtml::encode($data->payment_id), array('view', 'store_id'=>$data->payment_id)); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('store_name')); ?>:</b>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('state')); ?>:</b>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('city')); ?>:</b>
	<br />

<!--	<b>--><?php //echo CHtml::encode($data->getAttributeLabel('district')); ?><!--:</b>-->
<!--	--><?php //echo CHtml::encode(Tbfunction::showArea($data->district)); ?>
<!--	<br />-->

	<b><?php echo CHtml::encode($data->getAttributeLabel('address')); ?>:</b>
	<?php echo CHtml::encode($data->address); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('phone')); ?>:</b>
	<?php echo CHtml::encode($data->phone); ?>
	<br />

	<b><?php echo CHtml::encode($data->getAttributeLabel('create_time')); ?>:</b>
	<?php echo CHtml::encode($data->create_time); ?>
	<br />

</div>