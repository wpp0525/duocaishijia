<?php
	$hotCategories = Category::model()->hot()->level(2)->limit(3)->findAll();

	$hotItems = array();
	foreach ($hotCategories as $hotCategory) {
		$hotItems[] = array(
			'id'   => $hotCategory->category_id,
			'item' => Item::getItemsByCategory($hotCategory, 4),
		);
	}
?>



<div class="mainad">
	<div class="spa">

<!--		--><?php
//			for ($i = 1; $i <= count($hotItems); $i++) {
//				$hotitem = $hotItems[$i - 1];
//				$prop1 = ItemProp::model()->findAllByAttributes(array(
//					'category_id' => $hotitem["id"],
//					'is_color_prop' => '0',
//					'is_combo_prop' => '0',
//				));
//				$propValues = array();
//				foreach ($prop1 as $pro2) {
//					$propvale = PropValue::model()->findAllByAttributes(array(
//						'item_prop_id' => $pro2["item_prop_id"],
//					));
//					foreach ($propvale as $propvale1) {
//						$propValues[] = array(
//							'value_name'    => $propvale1->value_name,
//							'prop_value_id' => $propvale1->prop_value_id,
//							'item_prop_id' => $propvale1->item_prop_id,
//						);
//
//					}
//				}
//				$style = 'spa_list'.$i;
//				//dump($hotitem['item']);
//				if($hotitem["item"][0]->title=='家电清洗'){
//					echo '<div class='.$style.'>'."家居SPA";
//				}else{
//					echo '<div class='.$style.'>'.$hotitem["item"][0]->title;
//				}
//
//				echo '<ul>';
//				if($hotitem["item"][0]->title=='墙面美容'){
//					echo '<a href="/item/82">快捷刷新(涂料)</a>';
//					echo '<a href="/catalog/index?cat=134">艺术涂料(定制)</a>';
//					echo '<a href="/catalog/index?cat=133">壁纸</a>';
//				}
//				foreach ($propValues as $propValue) {
//					$props = $propValue['item_prop_id'].':'.$propValue['prop_value_id'];
//					echo '<a href="/item/'.$hotitem['item'][0]->item_id.'/'.$props.'">'.$propValue["value_name"].'</a>';
//				}
//				echo '</ul>';
//				echo '</div>';
//
//			}?>
				<div class="spa_list1">墙面美容
					<ul>
						<a href="/catalog/index?cat=130">快捷刷新（涂料）</a>
<!--						<a href="/catalog/index?cat=134">艺术涂料（定制）</a>-->
						<a href="/catalog/index?cat=133">壁纸</a>
                        <a href="http:/catalog/index?cat=163">墙面基层处理</a>
<!--						<a href="/catalog/index?cat=140">软包</a>-->
<!--						<a href="/catalog/index?cat=141">地板</a>-->
<!--						<a href="/catalog/index?cat=142">瓷砖</a>-->
<!--						<a href="/catalog/index?cat=143">防水</a>-->

					</ul>
                    <div style="clear: both"></div>
                    <div style="margin-top: 24px;"><span style="text-align: right;margin-bottom: 5px;font-size: 12px;font-style: italic">更多服务，敬请期待...</span></div>
				</div>
				<div class="spa_list2">家居SPA
					<ul>
						<a href="/catalog/index?cat=145">油烟机清洗</a>
<!--						<a href="/catalog/index?cat=146">冰箱清洗</a>-->
						<a href="/catalog/index?cat=147">空调清洗</a>
<!--						<a href="/catalog/index?cat=148">窗帘</a>-->
<!--						<a href="/catalog/index?cat=149">砂窗门</a>-->
<!--						<a href="/catalog/index?cat=150">沙发清洗/护理</a>-->
<!--						<a href="/catalog/index?cat=151">艺术装饰</a>-->
<!--						<a href="/catalog/index?cat=152">灯光</a>-->
					</ul>
                    <div style="clear: both"></div>
                    <div style="margin-top: 24px;"><span style="text-align: right;margin-bottom: 5px;font-size: 12px;font-style: italic">更多服务，敬请期待...</span></div>
				</div>
	</div>
	<div class="warp_banner index_bg01" id="mainbody">
		<div id="slides" class="banner">
			<a class="slidesjs-previous slidesjs-navigation" href="#"
			   style="top: 175px;width: 43px;position: absolute;left: 0;z-index: 9999;">
				<?php echo CHtml::image(Yii::app()->theme->baseUrl.'/image/banner_l.png', '上一页', array('width' => '43', 'height' => '43')); ?>
			</a>
			<?php
				$i = 0;
				foreach ($ads as $ad) {
					$i++;
					echo <<<EOF
                <div id="banner_pic_$i">
                    <a href="{$ad->url}" target="_blank" style="height: 412px; ">
                        <img alt="{$ad->title}" src="{$ad->pic}" width="984" height="412" style="margin-top: 0; ">
                    </a>
                </div>
EOF;
				}
			?>
			<a class="slidesjs-next slidesjs-navigation" href="#"
			   style="top: 175px;width: 43px;position: absolute;right: 0;z-index: 9999;">
				<?php echo CHtml::image(Yii::app()->theme->baseUrl.'/image/banner_r.png', '下一页', array('width' => '43', 'height' => '43')); ?>
			</a>
		</div>
	</div>
</div>