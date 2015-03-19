<?php
$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<p>请填写您的登陆凭据，格式如下：</p>

<div class="form">
	<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
	'enableClientValidation'=>true,
	'htmlOptions'=>array('class'=>'well'),
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">带<span class="required"> * </span>的必须填写。</p>

	<?php echo $form->textFieldRow($model, 'username', array('class'=>'span3'));?>
	<?php echo $form->passwordFieldRow($model, 'password', array('class'=>'span3'));?>
	<?php echo $form->checkBoxRow($model, 'rememberMe');?>

	<div class="form-actions">
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'submit','type'=>'primary','label'=>'Submit', 'icon'=>'ok'));?>
		<?php $this->widget('bootstrap.widgets.TbButton', array('buttonType'=>'reset','label'=>'Reset'));?>
	</div>

	<?php $this->endWidget(); ?>
</div><!-- form -->
