<?php
	$cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/deal.css');
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/pptBox.js');
	$cs->registerScriptFile(Yii::app()->theme->baseUrl . '/js/lrtk.js');
	Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl.'/css/cart/review.css');
	Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl.'/js/review.js');

	$imageHelper=new ImageHelper();
	/** @var Item $item */
?>
<script type="text/javascript">
	function describe(n) {
		var tnum = $(".deal_describe_tit>li").length;
		for (i = 1; i <= tnum; i++) {
			if (i == n) {
				$("#describe_" + i).css("display", "");
				$(".deal_describe_tit>li")[i - 1].className = "current";
			} else {
				$("#describe_" + i).css("display", "none");
				$(".deal_describe_tit>li")[i - 1].className = "";
			}
		}
	}
</script>

<style type="text/css">
    #t1{vertical-align: middle;}
    #td1{font-size: 12px;text-align: center;}
    #td2{font-size: 12px}
</style>

<div class="warp_contant">
<?php $this->widget('widgets.leather.WKefu') ?>
<div class="deal_tip">
	<a href="<?php echo Yii::app()->baseUrl.'/'; ?>">首页>></a>
	<?php foreach ($this->breadcrumbs as $breadcrumb) {
		echo '<a href="' . $breadcrumb['url'] . '">' . $breadcrumb['name'] . '</a>';
	} ?>
</div>

<div class="deal container_24">
		<div class="deal_pic clearfix">
			<div>
				<ul id="idNum" class="hdnum">
					<?php

					    foreach ($item->itemImgs as $itemImg) {//左边的小图轮播
						if(!empty($itemImg->pic)){
							$picUrl=$imageHelper->thumb('70','70',$itemImg->pic);
							$picUrl=Yii::app()->baseUrl.$picUrl;
						}else {$picUrl=Yii::app()->baseUrl.$picUrl;
							$picUrl=$item->getHolderJs('70','70');}
					 	echo '<li><img src="' .$picUrl . '" width="70" height="70"  class="idNumSmall" ></li>';
					} ?>
				</ul>
			</div>
			<div style="width: 450px; height: 450px; overflow: hidden; position: relative;" id="idTransformView" >
				<ul id=idSlider class=slider>
					<?php
						foreach ($item->itemImgs as $itemImg) {   //大图轮播
							if($itemImg->pic){
								$picUrl=$imageHelper->thumb('450','450',$itemImg->pic);
								$picUrl=Yii::app()->baseUrl.$picUrl;
							} else {$picUrl=Yii::app()->baseUrl.$picUrl;//如果没有轮播图片使用默认的图片
							   $picUrl=$item->getHolderJs('450','450');}
							   echo '<div><a href="javascript:void(0)" target="_blank" rel="nofollow">
											             <img position=absolute   alt="' . $item->title . '" src="'  .$picUrl . '"
											             width="450" height="450" style="margin-top: 0;margin-left:20px " class="idSliderBig" />
											            </a>
											      </div>';
						}

					?>
				</ul>
			</div>
		</div>

<script language=javascript>
	mytv("idNum", "idTransformView", "idSlider", 450, <?php echo count($item->itemImgs); ?>, true,     2000,    5,        true,    "onmouseover");
	   //按钮容器aa，滚动容器bb，     滚动内容cc，滚动宽度dd，滚动数量ee，                      滚动方向ff，延时gg，滚动速度hh，自动滚动ii，
</script>

<form action="<?php echo Yii::app()->createUrl('order/checkout'); ?>" method="post" class="deal_info" id="deal">
<div class="deal_tit"><?php echo empty($title) ? $item->title : $title; ?></div>
<div class="deal_price">
                <span class="cor_red bold font30 deal_itemprice"><?php
		                $skusP = array();
		                if (is_array($item->skus)) {
			                foreach ($item->skus as $sku) {
				                $skusP[] = $sku->price;
			                }
			                if (count($skusP)) {
				                $maxPrice = max($skusP);
				                $minPrice = min($skusP);
				                if ($maxPrice == $minPrice) {
					                echo $maxPrice."/".$item->currency;
				                } else {
					                echo $minPrice.'-'.$maxPrice.'/'.$item->currency;
				                }
			                } else {
				                echo $item->price.'/'.$item->currency;
			                }
		                }


	                ?></span>
</div>
<!--            <div class="deal_sold">月销售量 <span class="cor_red bold">--><?php //echo $item->deal_count;?><!--</span>&nbsp;件</div>-->
<?php
	$skus = array();
	foreach ($item->skus as $sku) {
		$skuId[]=$sku->sku_id;
		$key = implode(';', json_decode($sku->props, true));
		$skus[$key] = json_encode(array('price' => $sku->price, 'stock' => $sku->stock));
	}
?>
<input id="IsShow" type="hidden" value="<?php echo $item->is_show;?>" />
<div style="display: none;font-size: 50px;font-weight: bold;" id="div_IsShow">服务已下架</div>

<!-- Begin not used -->
<!--<div class="deal_size" data-sku-key='<?php echo json_encode(array_keys($skus)); ?>'
                 data-sku-value='<?php echo json_encode($skus); ?>' data-sku-id="<?php if(isset($skuId))echo implode(',',$skuId);else echo $item->item_id; ?>">
                <?php
	$propImgs = CHtml::listData($item->propImgs, 'item_prop_value', 'pic');
	$itemProps = $propValues = array();
	foreach ($item->category->itemProps as $itemProp) {
		$itemProps[$itemProp->item_prop_id] = $itemProp;
		foreach ($itemProp->propValues as $propValue) {
			$propValues[$propValue->prop_value_id] = $propValue;
		}
	}
	$pvids = json_decode($item->props);
	foreach ($pvids as $pid => $pvid) {
		if (isset($itemProps[$pid]) && $itemProps[$pid]->is_sale_prop) {
			$itemProp = $itemProps[$pid];
			?>
                        <p><span><?php echo $itemProp->prop_name ?>：</span>
                            <?php if (is_array($pvid)) {
				foreach ($pvid as $v) {
					$ids = explode(':', $v);
					$propValue = $propValues[$ids[1]];
					if ($itemProp->is_color_prop && false) {
						?>
                                        <a href="javascript:void(0)" data-value="<?php echo $v; ?>" id="prop<?php echo str_replace(':','-',$v); ?>">
                                            <img alt="<?php echo $propValue->value_name; ?>"
                                                 src="<?php echo isset($propImgs[$v]) ? $propImgs[$v] : ''; ?>"
                                                 width="41" height="41"></a>
                                    <?php } else { ?>
                                        <a href="javascript:void(0)"
                                           data-value="<?php echo $v; ?>" id="prop<?php echo str_replace(':','-',$v); ?>"><?php echo $propValue->value_name; ?></a>
                                    <?php
					}
				}
			} else {
				$ids = explode(':', $pvid);
				$propValue = $propValues[$ids[1]];
				if ($itemProp->is_color_prop && false) {
					?>
                                    <a href="javascript:void(0)" data-value="<?php echo $pvid; ?>" id="prop<?php echo str_replace(':','-',$v); ?>">
                                        <img alt="<?php echo $propValue->value_name; ?>"
                                             src="<?php echo isset($propImgs[$pvid]) ? $propImgs[$pvid] : ''; ?>"
                                             width="41" height="41"></a>
                                <?php } else { ?>
                                    <a href="javascript:void(0)"
                                       data-value="<?php echo $pvid; ?>" id="prop<?php echo str_replace(':','-',$v); ?>"><?php echo $propValue->value_name; ?></a>
                                <?php
				}
			} ?>
                        </p>
                    <?php
		}
	} ?>
            </div>-->
<!-- End not used -->

<?php
	//获取套餐属性
//	$propsComb= json_decode($item->props);
//	foreach($propsComb as $key=>$propCom){
//		$itemPropCom = ItemProp::model()->findByPk
//			($key);
//		if($itemPropCom->is_combo_prop){
//			$taocan = array();
//			foreach($propCom as $propvalues1){
//				$ids1 = explode(':', $propvalues1);
//				$propvalues3 = PropValue::model()->findByPk($ids1[1]); //拿到第二个值，也就是套餐的编码
//				$taocan[] = $propvalues3;
//			}
//			// dump($taocan);
//			//  var_dump($taocan[0]->item_prop_id);
//		}
//	}
	//获取 预购列表的所有属性和值
	$itemProps = $itemPropList = $itemPropDropDown = array();
	$hasColorProps = false;
	foreach ($item->category->itemProps as $itemProp) {
		if (!$hasColorProps) {
			$hasColorProps = $itemProp->is_color_prop;
		}
		$itemPropList[$itemProp->item_prop_id]  = $itemProp->is_sale_prop;

		foreach ($itemProp->propValues as $propValue) {
			$itemProps[$itemProp->item_prop_id. ":". $propValue->prop_value_id] = $propValue->value_name;
		}

	}
//	dump(json_decode($item->props));
	foreach(json_decode($item->props) as $key => $value) {

		if ($itemPropList[$key]) {//过滤掉套餐的情况，如果套餐为空还要加其他的控制
			$dropDownList = array();
            if(is_array($value)){
			foreach ($value as $propKey) {
				$dropDownList[$propKey] = $itemProps[$propKey];
			}
			$itemPropDropDown[$propKey] = $dropDownList;
		}
        }
	}
//	 var_dump($itemPropDropDown);
?>
<div class="form-group" style=" float:left;overflow:hidden;border-top:1px dashed #ececec;width:570px;">
	<div>
		<label class="col-xs-2 control-label" style="float: left;margin-bottom:0px;width:61px;padding-top:17px;"><b style="float: left;font-size: 14px;display: inline-block;">服务城市</b>
			<span class="required" style="display: inline-block;float: left;">*</span></label>


	<div class="city" style="padding-top:13px">
		<?php
			/*
			$stores = DcStore::model()->findAll(array(
				'select'   => 'state',
				'distinct' => 'true',
			));
			$state = array();

			foreach ($stores as $store) {
				$state[] = $store->state;
			}
			$db = new CDbCriteria();
			$db->addInCondition('area_id', $state);
			$state_data = Area::model()->findAll($db);
			$state = CHtml::listData($state_data, "area_id", "name");
			$s_default = $model->isNewRecord ? '' : $model->state;
			echo '&nbsp;'.CHtml::dropDownList('AddressResult[city]', $s_default, $state,
					array(
						'empty' => '请选择省',
						));*/
			$stores = DcStore::model()->findAll(array(
				'select' => 'state',
				'distinct' => 'true',
			));
			$state = array();

			foreach($stores as $store){
				$state[] = $store->state;
			}
			$db = new CDbCriteria();
			$db->addInCondition('area_id', $state);
			$state_data=  Area::model()->findAll($db);

			//$state_data = Area::model()->findAll("grade=:grade",
			//    array(":grade" => 1));
			$state = CHtml::listData($state_data, "area_id", "name");
			$s_default = $model->isNewRecord ? '' : $model->state;
			echo '&nbsp;' . CHtml::dropDownList('AddressResult[state]', $s_default, $state,
					array(
						'empty' => '请选择省份',
						'ajax' => array(
							'type' => 'GET', //request type
							'url' => CController::createUrl('/member/Delivery_address/DynamicStoreCities'), //url to call
							'update' => '#AddressResult_city', //selector to update
							'data' => 'js:"AddressResult_state="+jQuery(this).val()',
						),
                        'style'=>'margin-left:0px',
                    ));
			//empty since it will be filled by the other dropdown
			$c_default = $model->isNewRecord ? '' : $model->city;
			if (!$model->isNewRecord) {
				$city_data = Area::model()->findAll("parent_id=:parent_id",
					array(":parent_id" => $model->state));
				$city = CHtml::listData($city_data, "id", "name");
			}
			$city_update = $model->isNewRecord ? array() : $city;
			echo '&nbsp;&nbsp;' . CHtml::dropDownList('AddressResult[city]', $c_default, $city_update,
					array(
						'empty' => '请选择城市',
						'ajax' => array(
							'type' => 'GET', //request type
							'url' => CController::createUrl('/member/Delivery_address/DynamicStoreDistrict'), //url to call
							'update' => '#AddressResult_district', //selector to update
							'data' => 'js:"AddressResult_city="+jQuery(this).val()',
						),
                        'style'=>'margin-left:4px'
                    ));
//			$d_default = $model->isNewRecord ? '' : $model->district;
//			if (!$model->isNewRecord) {
//				$district_data = Area::model()->findAll("parent_id=:parent_id",
//					array(":parent_id" => $model->city));
//				$district = CHtml::listData($district_data, "id", "name");
//			}
//			$district_update = $model->isNewRecord ? array() : $district;
//			echo '&nbsp;&nbsp;' . CHtml::dropDownList('AddressResult[district]', $d_default, $district_update,
//					array(
//						'empty' => '请选择地区',
//					)
//				);
		?>
	</div>
	</div>
	<div style="margin-top: 10px">
		<span style="color: #898989;">温馨提示:多彩饰家服务现已在全国多个城市开通，如您城市尚不在其中，敬请期待...</span>
	</div>
</div>
<div class="prop-content">
<!--	--><?php //if ($hasColorProps): ?>
<!--		<div class="prop-color" >-->
<!--			<span class="color-label">颜    色：</span>-->
<!--			<div class="color-content">-->
<!--				<ul>-->
<!--					--><?php //foreach($item->propImgs as $propImage): ?>
<!--						<li class="color-list" data-url="--><?php //echo $propImage->pic; ?><!--">-->
<!--							<img src="--><?php //echo $propImage->pic; ?><!--" width="48" height="48">-->
<!--							<span class="color-list-name">--><?php //echo $propImage->item_prop_value; ?><!--</span>-->
<!--						</li>-->
<!--					--><?php //endforeach; ?>
<!--				</ul>-->
<!--			</div>-->
<!--			<div><img class="color-demo" src="" width="320" height="320"></div>-->
<!--		</div>-->
<!--	--><?php //endif; ?>

<!--	<div class="prop-auto1">-->
<!--		--><?php //if(isset($taocan)) { ?>
<!--			<span class="list-label">套    餐：</span>-->
<!--			--><?php
//			echo CHtml::hiddenField('propsval', '', array(
//				'id' => '',
//			));
//			?>
<!--			<div class="prop-item1">-->
<!--				--><?php
//					foreach($taocan as $taocan2){
//						echo  CHtml::link($taocan2->value_name, 'javascript:void(0)',
//							array(
//								'class' => 'area-link',
//								'name'=>'propslist',
//								'value'=>$taocan2->item_prop_id . ':' . $taocan2->prop_value_id,
//							));
//					}
//				?>
<!--			</div>-->
<!--		--><?php //} ?>
<!--	</div>-->

	<div class="prop-auto">
		<div class="prop-list" style="display:">
			<span class="list-label" style="width: 0px"></span>
			<?php
				echo CHtml::hiddenField('props[]', '', array(
					'id' => '',
				));
			?>
			<div class="prop-item">
				<?php $i=0;
					foreach ($itemPropDropDown as $keyval=>$dropDownList) {
						$keyval1 = strstr($keyval,':',true);
						$itempropname = ItemProp::model()->findByPk($keyval1);

						if (empty($props) || !isset($dropDownList[$props])) {
							echo '<div name="prop_list[]" style="float:left;width: 100%">';?>
							<div class="prop_tile" style="width: 100%;display: inline-block">
							<span style='float:left' class='list-label'><?php echo $itempropname->prop_name?>:</span>
							<?php if(isset($itempropname->prop_title)){ ?>
							<a class="prop_title" value="<?php echo $itempropname->prop_desc; ?>" style="float:left;height: 35px;line-height:30px;margin-left: 10px;"><?php echo $itempropname->prop_title ?></a>

							<?php }
							echo '</div>';
							echo '<ul style="float:left;padding-left: 0px;margin-left: 71px;">';
							echo CHtml::hiddenField('props_'.$i.'[]', '', array(
								'id' => 'props0',
							));
							$picUrl = '';
							foreach($dropDownList as $keyProp=>$keyVal){  /*显示颜色属性的模块*/
								if($itempropname->is_color_prop){     //颜色属性的值，要变化
									$imgaeId = substr($keyProp,stripos($keyProp,':')+1);
									$imageStr = PropValue::model()->findByPk($imgaeId)->pic;
										$picUrl=$imageHelper->thumb('48','48',$imageStr);
							?>
							<a name="propslist" class="item_color area-link1" href="javascript:void(0)" value="<?php echo $keyProp?>">
								<img style="margin:5px;margin-bottom: 0px" width="48px" height="48px" src="<?php echo $picUrl?>">
							<span style="height:20px;line-height:24px"><?php echo $keyVal?></span>
							</a>
									<?php
									/*
								echo  CHtml::link($keyVal, 'javascript:void(0)',
									array(
										'class' => 'item_color area-link'.$i,
										'name'=>'propslist',
										'value'=>$keyProp,
										'style' => 'background-image:url('.$picUrl.')',
									));*/
								}else{
									echo  CHtml::link($keyVal, 'javascript:void(0)',
										array(
											'class' => 'area-link'.$i,
											'name'=>'propslist',
											'value'=>$keyProp,
											'style' => 'background-image:url('.$picUrl.')',
										));
								}
							}
							$i++;
							echo "</ul>";
							echo '</div>';
//							echo CHtml::dropDownList('prop_list[]', '', $dropDownList, array(
//								'empty' => '选择'.$itempropname->prop_name,
//								'id' => '',
//							));
						}
					}

//					if ($item->currency == "㎡") {
//						echo CHtml::textField('qty[]', '1', array(
//							'placeholder' => '面积',
//							'id' => '',
//						));
//						echo CHtml::tag('span', array('class' => 'prop-count'), '㎡');
//					} else {
//						echo CHtml::textField('qty[]', '1', array(
//							'id' => '',
//						));
//						echo CHtml::tag('span', array('class' => 'prop-count'),'次' );
//					}
				?>
			</div>
			</div>
<!--		--><?php
//			echo "<div class='item_price1' style='height: 20px;float: left;width: 100%;margin-top: -8px;'>";
//		echo "<span class='list-label'>单价：</span>";
//		echo '<span name="item_price[]" class = "item_price">0.00元</span>';
//		echo "</div>";?>

			<?php if ($item->currency == "㎡") { ?>
			<div class="item_area" style="margin-bottom:25px;width: 100%">
			<div style="width: 100%">
			<span style="float: left;height: 35px;margin-right: -12px;margin-top: 4px;font-weight: bolder;  font-size: 14px;width:62px;"> 刷新面积:（预估）</span>
			<a class="prop_title"
			   value="您需要提供使用本服务的墙面面积估算，以便初步确定总体价格。如果您不知道墙面面积数据，您可以提供所选择本服务的房间地面面积，多彩饰家采用“墙面面积＝地面面积x2.5”公式计算该房间的墙面面积。但最终面积数以实际测量为准。"
			   style="float:left;height: 35px;line-height:30px;margin-left: 20px;">
				不知道墙面面积？
			</a>
			</div>
				<div style="float: left;width: 100%;margin-left: 67px;">
<!--				<span style="float: left;margin-right: 15px">预估:</span>-->
				<input style="float: left;margin-top:2px" name="qty" type="radio"value="2" class = "area_low" checked="checked"/><span  style="float: left">墙面面积</span>
				<input style="float: left;margin-top:2px" name="qty" type="radio"value="1" class="area_low"/><span style="float: left">地面面积</span>
				<?php echo CHtml::textField('qty[]', '50', array(
				'placeholder' => '面积',
				'class' =>'area_qty',
				 'name' => 'qty',
				'id' => '',
				));
				echo CHtml::tag('span', array('class' => 'prop-count'), '㎡');
					echo '</div>';}else{

				echo '<div class="item_area" style="margin-bottom:25px;">';
				echo '<span style="float: left;font-weight: bolder;font-size: 14px">数量:</span>';?>
				<div style="display: inline;float: left;">
			   <a style="float: left;margin-top: 3px" href="javascript:void(0)" class="minus"><span class="glyphicon glyphicon-minus-sign btn-reduce pull-left"></span></a>
				<?php echo CHtml::textField('qty[]', '1', array(
					'class' =>'area_qty',
					'id' => '',
				));?>
				<a style="float: left;margin-top: 3px" href="javascript:void(0)" class="add pull-left"><span class="glyphicon glyphicon-plus-sign btn-add " style="margin-left: 5px" ></span></a>
				</div>
				<?php echo CHtml::tag('span', array('class' => 'prop-count'),"$item->currency" );
			}?>


<!--			</div>-->

			<!--			<div class="prop-button">-->
<!--				--><?php
//					if (empty($props)) {
//						echo CHtml::tag('span', array(
//							'class' => 'prop-button-txt prop-add',
//						), '添加');
//					}
//				?>
<!--			</div>-->
			<!--                <a href="javascript:void(0)" class="minus"><span class="glyphicon glyphicon-minus-sign btn-reduce pull-left"></span></a>-->
			<!--                    <input  id="qty" name="qty" value="--><?php //echo $item->min_number; ?><!--"style="width:30px; text-align:center; float:left;"  />-->
			<!--                 <a href="javascript:void(0)" class="add pull-left"><span class="glyphicon glyphicon-plus-sign btn-add " style="margin-left: 5px" ></span></a>-->
			<!--                <span style="float:left">（库存剩余 <label id="stock">--><?php //echo $item->stock; ?><!--</label> 台)</span>-->
		</div>
	</div>
</div>
<?php
	$dealcount = intval($item->stock);
?>
<div class="total_props">
	<span class="total_props_name"></span>
</div>
<div class="total-price">
	<span class="total-price-tit">预估:</span>
	<?php echo CHtml::hiddenField('qtys[]','', array('id' => ''));?>
	<span name = "qtys" id="qtys" class="total-qty-content">0</span>
	<span class="total-price-content">0.00元</span>
</div>
<input type="hidden" id="item_id" name="item_id" value="<?php echo $item->item_id; ?>" />
<!--            <input type="hidden" id="props" name="props" value="" />-->
<div  class="deal_add_car" data-url="<?php echo Yii::app()->createUrl('cart/add'); ?>"><a href="javascript:void(0)" id="addToShopCart" ><span class="cart-icon"></span>加入服务车</a></div>
<div class="deal_add" data-url="<?php echo Yii::app()->createUrl('user/user/isLogin'); ?>" ><?php echo CHtml::link("立即购买", 'javascript:void(0);')?></div>
<!--            <div  class="deal_collect" data-url="--><?php //echo Yii::app()->createUrl('member/wishlist/addWish'); ?><!--" ><a href="javascript:void(0)">立即收藏</a></div>-->
<!-- Modal -->
<?php if(isset($item->warn_desc) && $item->warn_desc!=''){?>
<div class="warn_item"><span>温馨提示:<?php echo $item->warn_desc;?>
</span><?php if(isset($item->items_url)){ echo  CHtml::link("<选购>",Yii::app()->createUrl($item->items_url),array('target'=>'_blank'));}?>
</span><?php echo  CHtml::link("<详情点击>","$item->warn_url");?>
</div>
<!--<div class="warn_item"> <a href='--><?php //echo Yii::app()->createUrl($item->warn_url);?><!--'>--><?php //echo $item->warn_desc ?><!--</a> </div>-->
<?php }?>
<div tabindex="-1" class="modal fade in" id="myModal" role="dialog" aria-hidden="false" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="border: 0px solid rgba(0, 0, 0, 0.2);">
			<form role="form" id="log-out-box">

				<div id="warning-load" >
					<div id="logo"><?php echo Yii::app()->name ?></div>
                    <button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="log-out-close" style="position: absolute;top: 0;left: 330px;">×</button>

					<div class="user">
						<div> 用户名：</div>
						<input class="txt form-control" id="user" name="user" type="text" placeholder="请输入用户名"/>
					</div>
					<div id="ajax"></div>
					<div class="user">
						<div> 密码：</div>
						<input class="txt form-control" id="password" name="password" type="password" placeholder="请输入密码"/>
						<div><span class="errorPassword" style="color:red;font-size: 12px"></span></div>
					</div>
					<button id="log-btn-div"  name="button" type="button"  class="btn-success btn">登录</button>
					<div id="register">
						<a href="<?php echo Yii::app()->createUrl('user/registration'); ?>" class="link" target="_blank"><u>免费注册</u></a>
						<a href="<?php echo Yii::app()->createUrl('user/recovery'); ?>" class="link" target="_blank"><u>忘记密码？</u></a>
						<!--                                    <a href="javascript:void" class="link buy-without-login" id="buy-without-login" ><u>免登陆直接购买</u></a>-->
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
</form>
</div><!-- /.modal-content -->
</div><!-- /.modal-dialog -->

<!-- Modal -->
<div tabindex="-1" class="modal fade in" id="myModal-1" role="dialog" aria-hidden="false" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content clearfix" style="width:200px;height:150px;border:1px solid black;padding:10px 10px;" id="myModal-2-content">
			<s id="mymodal-1-png" class="pull-left"></s> <span class="pull-left">成功加入服务车！</span>

			<button class="close pull-right" aria-hidden="true" data-dismiss="modal" type="button"></button>
			<button class="btn btn-success center-block" aria-hidden="true" data-dismiss="modal" ">确定</button>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>
<!-- Modal -->
<div tabindex="-1" class="modal fade in" id="myModal-2" role="dialog" aria-hidden="false" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content" style="width:560px;height:310px;border:1px solid black;padding:20px 20px;" id="myModal-1-content">
			<div class="clearfix">
				<s id="mymodal-1-png" class="pull-left"></s> <span class="pull-left">成功加入收藏夹！</span>
				<button class="close pull-right" aria-hidden="true" data-dismiss="modal" type="button"></button>
				</br>
			</div>
			<div width="100%" id="look-collect"> 你可以<a href="/member/wishlist/admin"><font color="#3388BB" src="/member/wishlist/admin">查看收藏夹</font></a></div>
			<hr />
			<div class="col-xs-6 pull-left" align="left">收藏此服务的人还喜欢</div>
			<div class="col-xs-6 pull-right" align="right" style="visibility: hidden"><a><font color="#3388BB">换一组</font></a></div>
			<!--            <div>-->
			<!--                <ul class="clearfix">-->
			<!--                    <li class="col-xs-2">-->
			<!--                        <a href="/basic/item/57" title=""target="_blank"><img src="/basic/upload/item/manclothes/.tmb/thumb_d_adaptiveResize_70_70.jpg" class="li-img" alt=""></a>-->
			<!--                        <div width="100%" align="center" height="15px">$1299.00</div>-->
			<!--                    </li>-->
			<!--                    <li class="col-xs-2">-->
			<!--                        <a href="/basic/item/59" title=""target="_blank"><img src="/basic/upload/item/manclothes/.tmb/thumb_iii_adaptiveResize_70_70.jpg" alt=""></a>-->
			<!--                        <div width="100%" align="center" height="15px">$1299.00</div>-->
			<!--                    </li>-->
			<!--                    <li class="col-xs-2">-->
			<!--                        <a href="/basic/item/35" title=""target="_blank"><img src="/basic/upload/item/manclothes/.tmb/thumb_T1vyPGFhxeXXXXXXXX_!!0-item_pic.jpg_460x460q90_adaptiveResize_70_70.jpg" class="li-img" alt=""></a>-->
			<!--                        <div width="100%" align="center" height="15px">$1299.00</div>-->
			<!--                    </li>-->
			<!--                    <li class="col-xs-2">-->
			<!--                        <a href="/basic/item/37" title=""target="_blank"><img src="/basic/upload/item/manclothes/.tmb/thumb_1015622205-1_u_1_adaptiveResize_70_70.jpg" class="li-img" alt=""></a>-->
			<!--                        <div width="100%" align="center" height="15px">$1299.00</div>-->
			<!--                    </li>-->
			<!--                    <li class="col-xs-2">-->
			<!--                        <a href="/basic/item/58" title=""target="_blank"><img src="/basic/upload/item/manclothes/.tmb/thumb_f_adaptiveResize_70_70.jpg" class="li-img" alt=""></a>-->
			<!--                        <div width="100%" align="center" height="15px">$1299.00</div>-->
			<!--                    </li>-->
			<!--                    <li class="col-xs-2">-->
			<!--                        <a href="/basic/item/31" title=""target="_blank"><img src="/basic/upload/item/manclothes/.tmb/thumb_01_adaptiveResize_70_70.jpg" class="li-img" alt=""></a>-->
			<!--                        <div width="100%" align="center" height="15px">$1299.00</div>-->
			<!--                    </li>-->
			<!--                </ul>-->
			<!--            </div>-->

			<div>
				<ul class="clearfix">
					<?php
						$recommendItems=Item::model()->findAll(array(
							'condition'=>'category_id='.$item->category_id,
							'limit'=>6,
						));
						$num=count(recommendItems);
						if($num>0){
							foreach($recommendItems as $value){
								if($value->getMainPic()){
									$picUrl=$imageHelper->thumb('70','70',$value->getMainPic());
									$picUrl=Yii::app()->baseUrl.$picUrl;
								}else $picUrl=$item->getHolderJs('70','70');
								?>
								<li class="col-xs-2">
									<a href=""><img class="li-img" alt="" src="<?php echo $picUrl?>" width="70" height="70"/></a>
									<div width="100%" align="center" height="15px"><?php echo $value->price?></div>
								</li>
							<?php
							}
						}else echo"No data";
					?>

				</ul>
			</div>

			<button class="btn btn-success center-block" aria-hidden="true" data-dismiss="modal">确定</button>

		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<!-- Modal -->
<div tabindex="-1" class="modal fade in" id="myModal-3" role="dialog" aria-hidden="false" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content clearfix" style="width:200px;height:150px;border:1px solid black;padding:10px 10px;" id="myModal-2-content">
			<s id="mymodal-1-png" class="pull-left"></s> <span class="pull-left">您已收藏过该产品！</span>

			<button class="close pull-right" aria-hidden="true" data-dismiss="modal" type="button"></button>
			<button class="btn btn-success center-block" aria-hidden="true" data-dismiss="modal" onclick="history.go(1)">确定</button>
		</div><!-- /.modal-content -->
	</div><!-- /.modal-dialog -->
</div>

<div tabindex="-1" class="modal fade in" id="myModal-4" role="dialog" aria-hidden="false" aria-labelledby="myModalLabel" style="display: none;">
	<div class="modal-dialog" >
		<div class="modal-content" style="width: 720px;height: 360px;border-radius:0px;margin-top:80px">
			<div style="float: right">
				<a href="javascript:void(0)">
					<img id="delete-pic" src="/images/delete.png">
				</a>
			</div>
			<div style="width: 600px;margin: 30px auto;text-align: center">
				<span name="prop_title2" class="prop_title2" style="font-size: 20px;font-weight: bold"></span>
			</div>
			<div style="width: 600px;margin: 30px auto" >
			<p name="prop_desc" class="prop_desc"  style="font-size: 14px;
				line-height: 20px;
				text-indent: 2em"></p>
			</div>
	    </div>
	</div>
</div>

<div class="container" style="padding-top:30px">
	<div class="col-lg-3">
		<div class="pd_l_nv">
			<!--            <div class="pd_l_ti">-->
			<!--                <a href="--><?php //echo Yii::app()->baseUrl.'/'; ?><!--">首页>></a>-->
			<!--                --><?php //foreach ($this->breadcrumbs as $breadcrumb) {
				//                    echo '<a href="' . $breadcrumb['url'] . '">' . $breadcrumb['name'] . '</a>';
				//                } ?>
			<!--            </div>-->
			<h2>所有分类</h2>
			<?php
				$root = Category::model()->findByPk(3);
				$children = $root->children()->findAll();
				$params = array();
				if (!empty($_GET['key'])) {
					$params['key'] = $_GET['key'];
				}
				foreach ($children as $child) {
					$params['cat'] = $child->getUrl();
					echo '<div class="pd_l_ca"><a href="' . Yii::app()->createUrl('catalog/index', $params) . '">' . $child->name . '</a></div>';
//					echo '<div class="pd_l_ca"><a href="#">' . $child->name . '</a></div>';
					echo '<ul class="pd_ca_list" >';
					$leafs = $child->children()->findAll();
					foreach ($leafs as $leaf) {
						$params['cat'] = $leaf->getUrl();
						echo '<li><a href="' . Yii::app()->createUrl('catalog/index', $params) . '">' . $leaf->name . '</a></li>';
//						echo '<li><a href="#">' . $leaf->name . '</a></li>';
					}
					echo '</ul>';
				} ?>
		</div>
		<div class="pd_l_intr">
			<h2>推荐服务</h2>
			<ul class="pd_intr_list">
				<?php
					/*
					$recommendItems=Item::model()->best()->findAll(array(
						'limit'=>3,
					));*/
					$recommendItems = Item::model()->findAll(array(
						'condition'=>'is_best=:is_best and is_show=:is_show',
						'params'=>array(
							':is_best' => 1,
							':is_show' => 1,
						),
						'limit' => 3,
					));

					$num=count($recommendItems);

					if($num>0){
						foreach($recommendItems as $value){
							if($value->getMainPic()){
								$picUrl=$imageHelper->thumb('200','200',$value->getMainPic());
								$picUrl=Yii::app()->baseUrl.$picUrl;
							}else $picUrl=$item->getHolderJs('200','200');
							?>
							<li>
								<div class="intr_list_img"><a href='<?php echo $value->item_id ?>'><img alt="" src="<?php echo $picUrl?>" width="200" height="200" style=""/></a></div>
								<div class="intr_list_tit"><a href=""><?php echo $value->getTitle()?></a></div>
								<div class="intr_list_price"><span class="cor_red bold" style="color:#4aa01e; "><?php echo $value->price. '/'. $value->currency; ?></span></div>
							</li>
						<?php
						}
					}else echo"No data";
				?>

			</ul>
		</div>
	</div>
	<div class="col-lg-9">
		<ul class="deal_describe_tit">
			<li onclick="describe(1);" class="current" >服务描述</li>
<!--			<li onclick="describe(2);">顾客评价（<span class="cor_red">--><?php //echo $item->review_count;?><!--</span>）</li>-->
			<li onclick="describe(2);">月成交量（<span class="cor_red"><?php echo $item->deal_count;?></span>）</li>
		</ul>
		<div class="deal_describe"  id="describe_1"  style="margin-top:-30px">
			<table style="width: 85%;font-size:14px" >
				<tr>
					<td id="td2">服务名称：<?php echo $item->title;?></td>
					<td id="td2">服务分类：<?php echo $item->category->name ?></td>
				</tr>
				<tr>
					<td id="td2">价格:<span class="price_3"><?php echo $item->price;?>元</span></td>
					<td id="td2">单位：<?php echo $item->currency ;?></td>
<!--					<td>点击次数：--><?php //echo $item->click_count ?><!--</td>-->
				</tr>
				<tr>
					<td id="td2">服务：<span class="fuwu"></span></td>
				</tr>
			</table>
			<?php echo $item->desc; ?>
		</div>
<!--		<div class="deal_describe" id="describe_2" style="display:none;">-->
<!--			--><?php //   $this->widget('widgets.default.WReview',array(
//				'_itemId'=> $item->item_id,
//				'_entityId'=>'1',
//			))?>
<!--		</div>-->

		<div class="deal_describe" id="describe_2" style="display:none;">
			<?php
				$num=count($item->orderItems);
				if($num>0){
					?>
					<table class="table table-bordered table-hover table-striped" >
						<colgroup>
							<col class="col-user">
							<col class="col-title">
							<col class="col-price">
							<col class="col-quantity">
							<col class="col-time">
							<col class="col-status">
						</colgroup>
						<thead id="table-th">
						<tr>
							<th class="th1" id="t1" >买家</th>
							<th class="th1" id="t1">服务名称</th>
							<th class="th1" id="t1">价格</th>
							<th class="th1" id="t1">购买数量</th>
							<th class="th1" id="t1">成交时间</th>
							<th class="th1" id="t1">状态</th>
						</tr>
						</thead>
						<tbody>
						<?php
							foreach($orderItems as $orderItem) {
								/** @var OrderItem $orderItem */
								?>
								<tr>
									<td class="td" id="td1"><?php echo Tbfunction::getUser($orderItem->order->user_id);?></td>
									<td class="td" id="td1"><?php echo $orderItem->title;?></td>
									<td class="td" id="td1"><?php echo $orderItem->price;?></td>
									<td class="td" id="td1"><?php echo $orderItem->area;?></td>
									<td class="td" id="td1"><?php echo date("Y-m-d H:i",$orderItem->order->create_time);?></td>
									<td class="td" id="td1"><?php echo $orderItem->order->status? '完成':'未完成';?></td>
								</tr>
							<?php
							}
						?>
						</tbody>
					</table>
				<?php
				}
				else echo "No data";
			?>

		</div>
	</div>
</div>

<script type="text/javascript">
$(function () {


//    if($('#IsShow').val()==0||$('#stock').text()==0) {
	if($('#IsShow').val()==0) {
		$('.deal_size,.deal_num,.deal_add,.deal_add_car,.deal_collect').hide();
		$('#div_IsShow').toggle();
	}
	var skus = <?php echo json_encode($skus); ?>;
	var skuCounts = <?php echo count($skus); ?>;
	var emptySkus = [];
	var emptySkusArray=new Array();
	for (var i in skus) {
		var sku = $.parseJSON(skus[i]);
		if (sku['stock'] == "0") {
			emptySkus.push(i);
		}
	}
	if(emptySkus.length>0){
		for(var a in emptySkus){
			var data=emptySkus[a].split(';');
			emptySkusArray[a]=new Array();
			for( var b in data){
				emptySkusArray[a][b]=data[b];
			}
		}
	}
	/**立即购买**/
	function findSkuId(selectPropValue){
		var select = $('.deal_size');
		var skuKey = select.data('sku-key');
		for(var a in skuKey){
			if(skuKey[a]==selectPropValue){
				var num=a;
			}
		}
		var skuKeyId=select.data('sku-id');
		var selectAdd=$('.deal_add');
		selectAdd.find('a').attr({
			'data-url':selectAdd.data('url')+"?position=Sku"+skuKeyId[num]
		});
	}
//        $('.deal_add').click(function(){
//            var selectProps = $('.prop-select,.img-prop-select');
//            if (selectProps.length < $('.deal_size p').length) {
//                $('.deal_size').addClass('prop-div-select');
//            } else {
//                $.post($('.deal_add_car').data('url'), $('#deal').serialize(), function(response) {
//                    if(response.status=='success'){
//                        location.href=$('.deal_add a').data('url');
//                    }else{
//                        showPopup('system error');
//                    }
//                },'json');
//            }
//        })
	function findSameValue(a,b){
		var num1= a.length;
		var num2= b.length;
		var flag=0;
		if(num2-num1==0){
			for(var c in a){
				if(a[c]==b[c]&&flag==c){
					flag++;
				}
			}if(flag==num1-1){
				return true;
			}
		}
		if(num2-num1==1){
			for(var c in a){
				if(a[c]==b[c]){
					flag++;
				}
			}
		}
		if(flag==num1){
			return true;
		}else return false;
	}
	/* begin new code */
	$('#deal').delegate('.prop-list select', 'change', function () {
		updatePrice();
	});

	$('#deal').delegate('[name="qty[]"]', 'keyup', function (event) {
		var $this = $(this);
		var val = $this.val();

		switch (event.keyCode) {
			case 8:
				if (/^[\d*]*([\.?][\d]{0,2})?$/.test(val)) {
					updatePrice();
				}

				break;
			default:
				if (!/^[\d*]*([\.?][\d]{0,2})?$/.test(val)) {
					alert('面积必须为数字，小数点后最多两位！');
					return false;
				}

				if ($this.val() < 0 || $this.val() == 0) {
					$this.val('1');
				}

				updatePrice();

				break;
		}
	});

	$('#deal').delegate('.prop-delete', 'click', function () {
		var $this = $(this);
		var $propList = $this.parents('.prop-list');
		$propList.remove();
		updatePrice();
	});

	$('.prop-add').click(function () {
		var propListLength = $('.prop-list').length;

		if (propListLength < skuCounts) {
			var $this = $(this);
			var itemHtml = $this.parents('.prop-list').children('.prop-item').html();

			$this.parents('.prop-list').after('<div class="prop-list">' +
				'<span class="list-label"></span>' +
				'<input type="hidden" value="" name="props[]">' +
				'<div class="prop-item">' + itemHtml + '</div>' +
				'<div class="prop-button">' +
				'<span class="prop-button-txt prop-delete">删除</span>' +
				'</div>' +
				'</div>');
		}
	});
	$(".item_color").mouseover(function () {
		var $this = $(this);
		var url = $this.children('img').attr('src');
		$('#idTransformView').attr('src', url).show();
	});

	$(".item_color").mouseout(function () {
		$('idTransformView').attr('src', '').hide();
	});

	$('.area-link').click(function () {
		//把套餐的值赋值给隐藏域。
		$('.area-link').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="propsval"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});
	$('.area-link2').click(function () {
		//把值赋值给隐藏域。
		$('.area-link2').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="props_2[]"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});
	$('.area-link0').click(function () {
		//把值赋值给隐藏域。
		$('.area-link0').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="props_0[]"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});
	$('.area-link1').click(function () {
		//把值赋值给隐藏域。
		$('.area-link1').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="props_1[]"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});
	$('.area-link3').click(function () {
		//把值赋值给隐藏域。
		$('.area-link3').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="props_3[]"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});
	$('.area-link4').click(function () {
		//把值赋值给隐藏域。
		$('.area-link4').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="props_4[]"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});
	$('.area-link5').click(function () {
		//把值赋值给隐藏域。
		$('.area-link5').removeClass("li_color");
		$(this).addClass("li_color");
		$('[name="props_5[]"]').val($(this).attr("value"));
		updatePrice ();
		//alert($(this).attr("value"));
	});

	$('input[name="qty"]').change(function(){
		updatePrice();
	});

	function getQty(value){
		var qty = $('[name="qty[]"]').val();
		if(value==1){
			return qty*2.5 ;
		}else{
			return qty;
		}

	}
	function updatePrice () { //只要选择一次列表，就会调用

		if( $('[name="propsval"]').length>0 ){
			var $propstaocan  = $('[name="propsval"]');
			// alert($propstaocan.val());
		}
		var checked = $('input[name="qty"]:checked').val();
		var qty = getQty(checked);
		var $propLists = $('.prop-list');
		var totalPrice = 0;
		var totalQty = 0;
		$.each ($propLists, function(key, propList){
			var propStyle = '<?php if (empty($props)): echo ''; else: echo ';'. $props; endif; ?>';
			var selectLength = $(propList).children('.prop-item').children('select').length;
//			if (selectLength == 0) {
//				$(propList).hide()
//			} if {
//				$.each($(propList).children('.prop-item').children('select'), function(k, select){
//					if ($(select).val()) {
//						propStyle = propStyle + ';' + $(select).val();
//						//  alert(propStyle);
//					} else {
//						return false;
//					}
//				});
			var prop_item = $(propList).children('.prop-item').children('#props0');
			$('[id="props0"]').each(function(i){
				if ($(this).val()) {
					propStyle = propStyle + ';' + $(this).val();
					} else {
						return false;
					}
			});
//			$.each($(".prop-item").find('#props0'),function(){
//					if ($(this).val()) {
//						propStyle = propStyle + ';' + $(this).val();
//					} else {
//						return false;
//					}
//				});
			if( $('[name="propsval"]').length>0 ){  //如果存在，才允许赋值
				if($propstaocan.val()!='')
				{
					propStyle = propStyle + ';' +$propstaocan.val();
					//	alert('最后的'+propStyle);
				}
			}

			propStyle = propStyle.replace(';', '');
			if ($.parseJSON(skus[propStyle])) {
				$(propList).children('[name="props[]"]').val(propStyle);
				var price = $.parseJSON(skus[propStyle])['price'];
				$('.deal_itemprice').text(price+'元/'+'<?php echo $item->currency; ?>');
				$('[name="item_price[]"]').text(price+'元/'+'<?php echo $item->currency; ?>');
				$('.price_3').text(price+'元');

//				totalQty = totalQty + $.parseJSON($(propList).children('.prop-item').children('[name="qty[]"]').val());
				totalQty = qty;
				totalPrice = qty*$.parseJSON(skus[propStyle])['price'];
//				totalPrice = totalPrice + $.parseJSON(skus[propStyle])['price']*$(propList).children('.prop-item').children('[name="qty[]"]').val();

				//  alert($.parseJSON(skus[propStyle])['price']); //打印数据拿到的值。
			} else {
				$(propList).children('[name="props[]"]').val('');
			}
		});

		var props_name = '';
		var fuwu = '';
		$('.li_color').each(function(){
			if($(this).children('span').text()!=''){
				props_name = props_name + '+' + $(this).children('span').text();
				fuwu = fuwu+';' +  $(this).children('span').text();
			}else{
				if($(this).html()!=''){
					props_name = props_name + '+' + $(this).html();
					fuwu = fuwu+';' + $(this).html();
				}else{
					return false;
				}
			}
		});

		props_name = props_name.replace('+', '');
		fuwu = fuwu.replace(';', '');
		if(props_name!=''){
			$('.total_props_name').text('您已选购:'+props_name);
			$('.fuwu').text(fuwu);
		}
		$('.total-price-content').text(totalPrice.toFixed(2) + '元');

		$('.total-qty-content').text(totalQty+'<?php echo $item->currency; ?>');
		$('[name="qtys[]"]').val(totalQty);
	}

	/* end new code */
	$('.deal_size a').click(function () {
		var selectClass = $(this).find('img').length ? 'img-prop-select' : 'prop-select';
		if($(this).attr('class') !='disable'){
			if ($(this).attr('class') && $(this).attr('class').indexOf(selectClass) !== -1) {
				$(this).removeClass(selectClass);
			} else {
				$(this).parent().find('a').removeClass(selectClass);
				$(this).addClass(selectClass);
			}
			var selectPropValue = [];
			$('.prop-select,.img-prop-select').each(function () {
				selectPropValue.push($(this).data('value'));
			});
			/***将库存为0的选项加上disable属性****/
			if(emptySkus.length>0){
				var disableRemoveFlag=true;
				for(var b in emptySkusArray){
					if(findSameValue(selectPropValue,emptySkusArray[b])){
						var selectDisable=$("#prop"+emptySkusArray[b][emptySkusArray[b].length-1].replace(/:/,'-'));
						selectDisable.addClass('disable');
						selectDisable .removeClass( 'prop-select');
						disableRemoveFlag=false;
					}
					if(disableRemoveFlag){
						while($('.disable').length){
							$('.disable').removeClass('disable');
						}
					}
				}
			}
			selectPropValue = selectPropValue.join(';');
			$('#props').val(selectPropValue);
			if (skus[selectPropValue]) {
				findSkuId(selectPropValue);
				var sku = $.parseJSON(skus[selectPropValue]);
				var price = $('.deal_price').find('span:eq(0)');
				price.text(price.text().substr(0, 1) + sku['price']);
				var price = $('.deal_price').find('strong');
				price.text(price.text().substr(0, 1) + sku['price']);
				$('#stock').text(sku['stock']);
			}
			if($('.deal_size').data('sku-value').length==0){
				var selectAdd=$('.deal_add');
				selectAdd.find('a').attr({
					'data-url':selectAdd.data('url')+"?position=Item"+$('.deal_size').data('sku-id')
				})
			}
		}
	});
	$('.add').click(function () {
		$('#num').text(parseInt($('#num').text()) + 1);
		$('#qty').val(parseInt($('#qty').val()) + 1);
		$("input[name='qty[]']").val(parseInt($("input[name='qty[]']").val()) + 1);
		var  num = $('input[name = qty]').val();
		var i = <?php echo $dealcount?>;
		updatePrice();
//          if(num >i ){
//            alert('服务数量不能大于库存！');
//            $('#num').text(parseInt($('#num').text()) - 1);
//            $('#qty').val(parseInt($('#qty').val()) - 1);
//        }
	});
	$('.minus').click(function () {
		$('#num').text(parseInt($('#num').text()) - 1);
		$('#qty').val(parseInt($('#qty').val()) - 1);
		$("input[name='qty[]']").val(parseInt($("input[name='qty[]']").val()) - 1);
		var num = $('input[name = "qty[]"]').val();
		if(num < 1){
			alert('服务数量不能为负！');
			$('#num').text(parseInt($('#num').text()) + 1);
			$('#qty').val(parseInt($('#qty').val()) + 1);
            $("input[name='qty[]']").val(parseInt($("input[name='qty[]']").val()) + 1);
		}
		updatePrice();
	});


	$('.deal_add_car').click(function() {

		var props1 = '';
	    $('[id="props0"]').each(function(i){

		    if(!$(this).val()&& props1==''){
			    props1 = $(this).parent().parent().children('.prop_tile').children('.list-label').text();

		    }
	    });

		props1=props1.replace(":","!");
		props1=props1.replace("：","!");
		var area = $("#AddressResult_city").val();
		$("#log-btn-div").click(function() {carLogin();});
		$("#buy-without-login").hide();
		var selectProps = $('.prop-select,.img-prop-select');
		//        if (selectProps.length < $('.deal_size p').length) {
//            showPopup("请添加服务规格。");
//            $('.deal_size').addClass('prop-div-select');
//        }
		var checked = $('input[name="qty"]:checked').val();
		var qty = getQty(checked);
		var curr = '<?php echo $item->currency; ?>';
		var props = $("[name='props[]']");
		if(area == ''){
			showPopup("请选择已开通城市");
		}else if(props.val()==''){
			showPopup("请选择"+props1);
		}else if (qty<50 && curr=='㎡'){
			showPopup("面积必须大于50㎡");
		}else {
			$('.deal_size').removeClass('prop-div-select');
			$.post($(".deal_add").data('url'), function(response){
				if (response.status == 'login') {
					$.post($('.deal_add_car').data('url'), $('#deal').serialize(), function(response) {
						if(response.status=='success'){
							var num=$('.shopping_car1').children('.cor_red').text();
							num=parseInt(num)+response.total;
							$('.shopping_car1').children().text(num);
							$("#myModal-1").modal('show');
						}else if(response.status=='props'){
							showPopup("请添加完整的服务规格。");
						}else
							showPopup(response.status);
					},'json');
				} else {
					$('#myModal').modal('show');
				}
			}, 'json');
		}
	});

    $('.deal_add').click(function() {

	    var props1 = '';
	    $('[id="props0"]').each(function(i){

		    if(!$(this).val()&& props1==''){
			    props1 = $(this).parent().parent().children('.prop_tile').children('.list-label').text();

		    }
	    });

	    props1=props1.replace(":","!");
	    props1=props1.replace("：","!");
        var area = $("#AddressResult_city").val();
        $("#log-btn-div").click(function() {carLogin();});
        $("#buy-without-login").hide();
        var selectProps = $('.prop-select,.img-prop-select');
        var checked = $('input[name="qty"]:checked').val();
        var qty = getQty(checked);
        var curr = '<?php echo $item->currency; ?>';
        var props = $("[name='props[]']");
        if(area == ''){
            showPopup("请选择已开通城市");
        }else if(props.val()==''){
            showPopup("请选择"+props1);
        }else if (qty<50 && curr=='㎡'){
            showPopup("面积必须大于50㎡");
        }else {
            $("#log-btn-div").click(function() {llogin();});
            $("#buy-without-login").show();
            var selectProps = $('.prop-select,.img-prop-select');
            var props = $("[name='props[]']");
            if(props.val()==''){
                showPopup("请添加服务规格。");
            }else if (selectProps.length < $('.deal_size p').length) {
                $('.deal_size').addClass('prop-div-select');
            } else {
                $('.deal_size').removeClass('prop-div-select');
//                $('#deal').submit();
                $.post($(this).data('url'), function(response){
                    if (response.status == 'login') {
                        $('#deal').submit();
                    } else {
//                     $('#loginPage')
                        $('#myModal').modal('show');
                    }
                }, 'json');
            }
        }
    });

    $('.deal_collect').click(function() {
		$("#log-btn-div").click(function() {collectLogin();});
		$("#buy-without-login").hide();
		$.post($(".deal_add").data('url'), function(response){
			if (response.status == 'login') {
				$.post($(".deal_collect").data('url'),$('#item_id').serialize(),function(response){
					if(response.status=='exist'){
						$("#myModal-3").modal('show');
					}else
						$("#myModal-2").modal('show');
				},'json');
			} else {
				$('#myModal').modal('show');
			}
		}, 'json');

//        $.post($(this).data('url'), $('#item_id').serialize(), function(response) {
//            if(response.status=='exist'){
//                $("#myModal-3").modal('show');
//            }else
//                $("#myModal-2").modal('show');
//        },'json');
	});


	$('.buy-without-login').click(function() {
		$('#deal').submit();
	});

	$('.prop_title').click(function(){
		var prop_desc = $(this).attr("value");
		var prop_title = $(this).text();
		$("[name='prop_title2']").text(prop_title);
		$("[name='prop_desc']").text(prop_desc);
		$('#myModal-4').modal('show');
	});

	$('#delete-pic').click(function(){
		$('#myModal-4').modal('hide');
	});

	updatePrice();
});



$(".area-link1").click(function () {  //点击颜色属性，轮换相应的图片
	//alert( $(this).attr("value"));
	//alert( $("#item_id").attr("value"));
	var item_id ='<?php echo $item->item_id ?>';
	var data = { item_props: $(this).attr("value") ,item_id:  item_id  }; //data是要post的过去的值，json 串

	$.post("../item/GetPicture",data , function(response) {    //respose  是要返还过来的值

		var item_props_imgs2 = eval(response.item_props_imgs);
		$(".idNumSmall").each(function(index){           //更换小图的src
				//alert( item_props_imgs2[index] );
				var tmp = item_props_imgs2[index];
				$(this).attr("src" ,tmp);
			})

		$(".idSliderBig").each(function(index){ //更换大图的src
			//alert( item_props_imgs2[index] );
			var tmp2 = item_props_imgs2[index];
			$(this).attr("src" ,tmp2);
		})

	}, 'json' );

})



function collectLogin() {
	var data = { username: $("#user").val(), password: $("#password").val() };
	$.post("../user/login/llogin",data , function(response) {
		if (response.status == 'login') {
			$('#top_right1').load(location.href+" #top_right1");
			$('#myModal').modal('hide');
			$('.deal_collect').trigger('click');
		} else {
			$('.errorPassword').text("密码或用户名错误！");
		}
	}, 'json');
}

function carLogin() {
	var data = { username: $("#user").val(), password: $("#password").val() };
	$.post("/user/login/llogin",data , function(response) {
		if (response.status == 'login') {
			$('#top_right1').load(location.href+" #top_right1");
			$('#myModal').modal('hide');

			$('.deal_add_car').trigger('click');
		} else {
			$(".errorPassword").text("密码或用户名错误！");
		}
	}, 'json');
}

var xmlHttp
//    function test() {
//        window.open("http://yincart/user/login/test?username="+$("#user").val()+"&password="+$("#password").val());
//    }
function llogin() {
	xmlHttp=GetXmlHttpObject();
	if (xmlHttp==null)
	{
		alert ("Browser does not support HTTP Request")
		return
	}

	var url= "../user/login/llogin";
	var data = { username: $("#user").val(), password: $("#password").val() };
	url=url+"?username="+$("#user").val();
	url=url+"&password="+$("#password").val();
	xmlHttp.onreadystatechange=stateChanged(url);
//        xmlHttp.open("POST",url,true);
//        xmlHttp.send();
}

function stateChanged(url)
{
	var xmlHttp
	//    function test() {
	//        window.open("http://yincart/user/login/test?username="+$("#user").val()+"&password="+$("#password").val());
	//    }
	function llogin() {
		xmlHttp=GetXmlHttpObject();
		if (xmlHttp==null)
		{
			alert ("Browser does not support HTTP Request")
			return
		}

		var url= "http://yincart/user/login/llogin";
//        var data = { username: $("#user").val(), password: $("#password").val() };
		url=url+"?username="+$("#user").val();
		url=url+"&password="+$("#password").val();
		xmlHttp.onreadystatechange=stateChanged(url);
//        xmlHttp.open("POST",url,true);
//        xmlHttp.send();
	}

	function stateChanged(url)
	{
		$.post(url, function(response){
			if (response.status == 'login') {
				$('#deal').submit();
			} else {
				alert("Wrong username or password!");
			}
		}, 'json');

//            document.getElementById("user").innerHTML=xmlHttp.responseText;
		//$("#myModal").css("display","none");
		//      }
	}

	function GetXmlHttpObject()
	{
		var xmlHttp=null;

		try
		{
			// Firefox, Opera 8.0+, Safari
			xmlHttp=new XMLHttpRequest();
		}
		catch (e)
		{
			// Internet Explorer
			try
			{
				xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
			}
			catch (e)
			{
				xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
			}
		}
		return xmlHttp;
	}
	$.post(url, function(response){

		if (response.status == 'login') {
			$('#deal').submit();
		} else {
			$('.errorPassword').text("用户名或密码错误!");
		}
	}, 'json');

//            document.getElementById("user").innerHTML=xmlHttp.responseText;
	//$("#myModal").css("display","none");
	//      }
}

function GetXmlHttpObject()
{
	var xmlHttp=null;

	try
	{
		// Firefox, Opera 8.0+, Safari
		xmlHttp=new XMLHttpRequest();
	}
	catch (e)
	{
		// Internet Explorer
		try
		{
			xmlHttp=new ActiveXObject("Msxml2.XMLHTTP");
		}
		catch (e)
		{
			xmlHttp=new ActiveXObject("Microsoft.XMLHTTP");
		}
	}
	return xmlHttp;
}
</script>
