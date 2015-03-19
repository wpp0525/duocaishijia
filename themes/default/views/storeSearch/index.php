<?php
	$cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/store-search.css');
	$i=1;
?>

<div>
	<div class="title">
		<a href="<?php echo Yii::app()->baseUrl.'/'; ?>">首页>></a>
		<a href="/storeSearch/index">服务门店查询</a>
	</div>

	<div class="line">
	</div

	<div class="imgs">
		<?php
			foreach ($stores as $store) {
				if($store->pic){
					$storeUrl=$store->pic;
				}else{
					$storeUrl=$store->getHolderJs('265','265');
				}
				if( ($i-1)%4 == 0){
						$i++;
						?>
						<div class=" img1">
							<div > <a href="<?php echo Yii::app()->baseUrl.'/storeSearch/detail?id='.$store->store_id ;?>"><img  class="image1" src="<?php echo $storeUrl;?>"/></a></div>
							<div class="store-name"><a href="<?php echo Yii::app()->baseUrl.'/storeSearch/detail?id='.$store->store_id ;?>"><?php echo $store->store_name;?></a></div>

						</div>
				<?php }else{?>
						<div class="img2">
							<div > <a href="<?php echo Yii::app()->baseUrl.'/storeSearch/detail?id='.$store->store_id ;?>"><img  class="image2" src="<?php echo $storeUrl;;?>"/></a></div>
							<div class="store-name"><a href="<?php echo Yii::app()->baseUrl.'/storeSearch/detail?id='.$store->store_id ;?>"><?php echo $store->store_name;?></a></div>
						</div>
						<?php $i++; }?>
		    <?php } ?>
		<div class="img100000"></div>
	</div>

</div>


