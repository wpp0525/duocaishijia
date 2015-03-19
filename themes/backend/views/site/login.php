<?php
/* @var $this SiteController */
/* @var $model LoginForm */
/* @var $form CActiveForm  */

$this->pageTitle=Yii::app()->name . ' - Login';
$this->breadcrumbs=array(
	'Login',
);
?>

<h1>Login</h1>

<p>请填写您的登陆凭据，格式如下：</p>

<div class="form">

<?php $form=$this->beginWidget('bootstrap.widgets.TbActiveForm', array(
	'id'=>'login-form',
//    'type'=>'horizontal',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,
	),
)); ?>

	<p class="note">带<span class="required"> * </span>的必须填写。</p>

	<?php echo $form->textFieldControlGroup($model,'username'); ?>

	<?php echo $form->passwordFieldControlGroup($model,'password',array(
        'hint'=>'Hint: You may login with <kbd>demo</kbd>/<kbd>demo123</kbd> or <kbd>admin</kbd>/<kbd>admin123</kbd>',
    )); ?>

	<?php echo $form->checkBoxControlGroup($model,'rememberMe'); ?>

	<div class="form-actions">
        <?php echo TbHtml::formActions(array(
            TbHtml::submitButton('Login', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
        )); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->
