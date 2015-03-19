<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm',array(
	'id'=>'language-form',
	'enableAjaxValidation'=>false,
)); ?>

	<p class="help-block">带<span class="required"> * </span>的必须填写。</p>

	<?php echo $form->errorSummary($model); ?>

	<?php echo $form->textFieldControlGroup($model,'code',array('class'=>'span5','maxlength'=>10)); ?>

	<?php echo $form->textFieldControlGroup($model,'name',array('class'=>'span5','maxlength'=>20)); ?>

	<div class="form-actions">
		<?php  echo TbHtml::formActions(array(
            TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
            TbHtml::resetButton('Reset'),
        )); ?>
	</div>

<?php $this->endWidget(); ?>
