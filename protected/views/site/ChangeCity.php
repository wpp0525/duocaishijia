<div class="form">
	<?php

		$form=$this->beginWidget('CActiveForm', array(
			'id'=>'getCity',
			'method'=>'POST',
			'action'=>'WTopNav.php',
			'enableAjaxValidation'=>false,
			'enableClientValidation'=>true
		));
	?>
	<!--城市所在地址-->
<!--	<li class="area-info">-->
<!--		--><?php // echo CHtml::label ('所在城市:', 'DcStore_city', array('class' => 'area-label'));  ?>
<!--		--><?php
//
//			foreach ($stores as $store) {
//				$area = Area::model()->findByPk($store->city); //根据相应的主键拿到值
//				echo  CHtml::link($area->name  , '/'. $area->area_id , array('class' => 'area-link' ,'name'=>$area->area_id) );
//			}
//		?>
<!--	</li>-->
	<div class="warp_contant">

		<div class="area-info">
			<?php  echo CHtml::label ('所在城市:', 'DcStore_city', array('class' => 'area-label'));  ?>

			<?php
				foreach($sectionsArea as $key=>$section){
				if(count($section)){
					$ections = Section::model()->findByPk($key);
					echo CHtml::label ($ections->section, '' , array('class'=> 'section'));
					echo '<div class="area_li">' ;
					foreach($section as $areaId){
						$area = Area::model()->findByPk($areaId);
						echo '<div class="area-link">';
						echo  CHtml::link($area->name  , '/'. $area->area_id , array('name'=>$area->area_id) );
						echo '</div>';
					}
					echo '</div>' ;
				}
			}?>
		</div>
		</div>

	<?php $this->endWidget(); ?>
	<?php echo CHtml::endForm() ?>
</div>