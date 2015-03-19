<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/login_nav.js'); ?>

<?php
	$city = isset(Yii::app()->session['city']) ? Yii::app()->session['city'] : "110000";

	$area = Area::model()->findByPk($city);
	$cityName = $area->name;
?>
<div class="col-lg-6">
	<div class="top_left">

	<!--	<div class="img1"></div>-->
<!--把收藏功能去掉 --><?php //echo CHtml::link('收藏网站', F::baseUrl(), array(rel => "sidebar", 'href' => "addFavorite();")); ?>
  <?php	 echo '<b></b>';
	echo '<div class="img2"></div>';
	echo CHtml::label($cityName,'');   //不要在动这个熟悉了
	echo CHtml::link(' [更换]', array('/site/ChangeCity'));
	echo '<b></b>';
    echo  CHtml::link('官网',"http://www.zgdcsj.com/");?>
</div>
	</div>

<div class="col-lg-6"  >
	<div id="top_right1">
    <ul class="top_right_nav" >
        <?php if (Yii::app()->user->isGuest) { ?>
            <li><?php echo CHtml::link('登录', array('/user/login')) ?></li>
	        <s></s>
            <li><?php echo CHtml::link('注册', array('/user/registration')) ?></li>
	        <s></s>
	        <li><?php echo CHtml::link('我的订单', array('/member/orderlist/admin')) ?></li>
	        <s></s>
	        <li><?php echo CHtml::link('我的测量单', array('/member/measureOrderlist/admin')) ?></li>
        <?php }     else { ?>
	        <li>您好，<?php echo CHtml::link(Yii::app()->user->name, array('/user/profile/profile')); ?>，欢迎来到<?php echo Yii::app()->name ?>！</li>
	        <s></s>
	        <li><?php echo CHtml::link(Yii::t('main', 'log out'), array('/user/logout')) ?></li>
	        <s></s>
	        <li><?php echo CHtml::link('我的订单', array('/member/orderlist/admin')) ?></li>
	        <s></s>
	        <li><?php echo CHtml::link('我的测量单', array('/member/measureOrderlist/admin')) ?></li>
        <?php } ?>
    </ul>
		</div>
</div>

<?php
/*$this->widget('zii.widgets.CMenu', array(
    'htmlOptions'=>array("style"=>"margin: 0; padding: 0;"),
    'items'=>array(
        array('label'=>Yii::t('main', 'Member Center'), 'url'=>array('//member'),'itemOptions'=>array('class'=>'personal-center')),
        array('label'=>Yii::t('main', 'personal data'), 'url'=>array('//user/profile/edit'),'itemOptions'=>array('class'=>'personal-list')),
        array('label'=>Yii::t('main', 'delivery address'), 'url'=>array('//member/delivery_address/admin'),'itemOptions'=>array('class'=>'personal-list')),
        array('label'=>Yii::t('main', 'my order'), 'url'=>array('//member/orderlist/admin'),'itemOptions'=>array('class'=>'personal-list')),
        array('label'=>Yii::t('main', 'my collect'), 'url'=>array('//member/wishlist/admin'),'itemOptions'=>array('class'=>'personal)),
*/
?>