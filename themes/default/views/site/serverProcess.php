<?php
	$cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/server-process.css');
?>

<div class="wrap">
	<div class="title">
		<a href="<?php echo Yii::app()->baseUrl.'/'; ?>">首页>></a>
		<a href="/site/serverProcess">服务流程</a>
	</div>
	<div class="line">
	</div>
	<div class="detailImg">
	   <img  src="/images/server-process.jpg"/>
	</div>
</div>

