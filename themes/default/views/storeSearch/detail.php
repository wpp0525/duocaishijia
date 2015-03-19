<?php
	$cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/store-search.css');
?>

<div>
	<div class="title">
		<a href="<?php echo Yii::app()->baseUrl.'/'; ?>">首页>></a>
		<a href="/storeSearch/index">服务门店查询>></a>
		<a href="/storeSearch/detail?id=<?php echo $store->store_id?>"><?php echo $store->store_name; ?></a>
	</div>
	<div class="line">
	</div>
	<div class="detailImg">
		<?php if($store->detailImg){
			$storeDetailUrl=$store->detailImg;
		}else{
			$storeDetailUrl=$store->getHolderJs('1150','1250');
		}?>
	   <img  src="<?php echo $storeDetailUrl; ?>"/>
	</div>
</div>

