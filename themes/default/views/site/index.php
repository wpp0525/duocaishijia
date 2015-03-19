<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/slides.jquery.js'); ?>
<?php Yii::app()->clientScript->registerScriptFile(Yii::app()->theme->baseUrl . '/js/pptBox.js'); ?>

<?php
$imageHelper=new ImageHelper();
Yii::app()->plugin->render('Hook_Login');
$i = 1;
?>

<div class="warp_contant">
    <?php $this->widget('widgets.leather.WKefu') ?>
	<div class="warp_tab contaniner_24">
		<?php
			foreach ($hotAds as $hotad) {
		?>
	<div class="warp_tab_c" id="pop_<?php echo $i; ?>">
			<div class="product_cate_tit">
				<label>
					<span class="item_id"></span>
					<span class="item_name"><?php echo $hotad->title ?></span>
				</label>
			</div>
			<?php $i++;
				$itemUrl = Yii::app()->createUrl($hotad->url);
			?>
			<div class="product_pd_hot">
				<div class="tab_img_hot"><a href="<?php ;echo $itemUrl; ?>">
						<?php
							$picUrl=$hotad->pic;
							if(!empty($picUrl)){
							$picUrl=$imageHelper->thumb('578','338',$picUrl);
							$picUrl=yii::app()->baseUrl. $picUrl;
							echo CHtml::image($picUrl, $hotad->title, array('width' => '578', 'height' => '338'));
						}else {
							$picUrl=$hotad->getHolderJs('578','338');
						?> <img alt="<?php echo $hotad->title; ?>" src="<?php echo $picUrl; ?>"
						        width="220" height="220"></a>
					<?php
						}
					?>
					</a>
				</div>
			</div>
	</div>
	    <?php }

	    ?>
<!--            --><?php //foreach ($hotItems as $hostItemName => $hotItemList) { ?>
<!--                <div class="warp_tab_c" id="pop_--><?php //echo $i; ?><!--" >-->
<!--	                <div class="product_cate_tit">-->
<!--		                <label>-->
<!--			                <span class="item_id">--><?php //echo $i; ?><!--</span>-->
<!--			                <span class="item_name">--><?php //echo $hostItemName; ?><!--</span>-->
<!--		                </label>-->
<!--		                <a href="--><?php //echo Yii::app()->baseUrl.'/catalog/index?cat='.$hotItemList['id'].'&sort=newd'; ?><!--">查看详情>></a>-->
<!--	                </div>-->
<!--                    --><?php //$i++;
//                    foreach ($hotItemList['item'] as $hotItem) {
//                        $itemUrl = Yii::app()->createUrl('item/view', array('id' => $hotItem->item_id));
//                        ?>
<!--                        <div class="product_pd_hot">-->
<!--                            <div class="tab_img_hot"><a href="--><?php //echo $itemUrl; ?><!--">-->
<!--                                    --><?php
//                                    $picUrl=$hotItem->getMainPic();
//                                    if(!empty($picUrl)){
//                                        $picUrl=$imageHelper->thumb('1178','340',$picUrl);
//                                        $picUrl=yii::app()->baseUrl. $picUrl;
//                                        echo CHtml::image($picUrl, $hotItem->title, array('width' => '1178', 'height' => '340'));
//                                    }else {
//                                        $picUrl=$hotItem->getHolderJs('1178','340');
//                                       ?><!-- <img alt="--><?php //echo $hotItem->title; ?><!--" src="--><?php //echo $picUrl; ?><!--"-->
<!--                                         width="220" height="220"></a>--><?php
//                                    }
//                                    ?>
<!--                                </a>-->
<!--                            </div>-->
<!--                        </div>-->
<!--                    --><?php //} ?>
<!--                </div>-->
<!--            --><?php //} ?>

    <div class="warp_product">
        <?php $isFrist = true;
        foreach ($newItems as $category_name => $categoryInfo) {
 ?>
                <div class="col-lg-12">
                    <div class="product_cate_tit">
	                    <label>
		                    <span class="item_id"></span>
		                     <span class="item_name"><?php echo $category_name; ?></span>
	                    </label>
	                    <a href="<?php echo Yii::app()->baseUrl.'/catalog/index?cat='. $categoryInfo['id']. '&sort=newd';?>">更多新品>></a>
                    </div>
                    <div class="product_c">
                        <div class="product_list">
                            <?php $i++;
                            for ($i1 = 0; $i1 < 4; $i1++) {
                                if(!empty($categoryInfo['item'][$i1])){
                                $newItem = $categoryInfo['item'][$i1];
                                $itemUrl = Yii::app()->createUrl('item/view', array('id' => $newItem->item_id));
                                ?>
                                <div class="product_d">
                                    <div class="product_img"><a href="<?php echo $itemUrl; ?>">
                                            <?php
                                                if( $newItem->getMainPic()){
                                                    $image=new ImageHelper();
                                                    $picUrl=$image->thumb('270','270', $newItem->getMainPic());
                                                    $picUrl=Yii::app()->baseUrl.$picUrl;
                                                }else $picUrl=$newItem->getHolderJs('270','270');
                                            ?>
                                            <img alt="<?php echo $newItem->title; ?>" src="<?php echo $picUrl; ?>"
                                                 width="270" height="270"></a>
                                    </div>
                                    <div class="product_name">
                                        <a href="<?php echo $itemUrl; ?>"><?php echo $newItem->title; ?></a>
                                    </div>
                                    <div class="product_price">
                                        <div class="product_price_n"><?php echo $newItem->price. '/'. $newItem->currency; ?></div>
<!--                                        <div class="product_price_v"><a href="--><?php //echo $itemUrl; ?><!--">详情点击</a></div>-->
                                    </div>
                                </div>
                            <?php }} ?>
                        </div>
                    </div>
                </div>
<!--           --><?php //} else { ?>
<!--                <div class="product_cate contaniner_24">-->
<!--                    <div class="product_cate_tit--><?php //echo $num; ?><!--"><label>--><?php //echo $category_name; ?><!--</label><a href="--><?php //echo Yii::app()->baseUrl.'/'.Menu::model()->getUrl($category_name).'&sort=newd';?><!--">更多新品>></a></div>-->
<!--                    <div class="product_ca">-->
<!--                        <div class="product_list_ca">-->
<!--                            --><?php //foreach ($items as $newItem) {
//                                $itemUrl = Yii::app()->createUrl('item/view', array('id' => $newItem->item_id));
//                                ?>
<!--                                <div class="product_e">-->
<!--                                    <div class="product_img"><a href="--><?php //echo $itemUrl; ?><!--">-->
<!--                                            --><?php
//                                            if( $newItem->getMainPic()){
//                                            $image=new ImageHelper();
//                                            $picUrl=$image->thumb('220','220', $newItem->getMainPic());
//                                            $picUrl=Yii::app()->baseUrl.$picUrl;
//                                            }else $picUrl=$newItem->getHolderJs('220','220');
//                                            ?>
<!--                                            <img alt="--><?php //echo $newItem->title; ?><!--" src="--><?php //echo $picUrl; ?><!--"-->
<!--                                                 width="220" height="220"></a>-->
<!--                                    </div>-->
<!--                                    <div class="product_name">-->
<!--                                        <a href="--><?php //echo $itemUrl; ?><!--">--><?php //echo $newItem->title; ?><!--</a>-->
<!--                                    </div>-->
<!--                                    <div class="product_price">-->
<!--                                        <div-->
<!--                                            class="product_price_n">--><?php //echo $newItem->currency . $newItem->price ?><!--</div>-->
<!--                                        <div class="product_price_v"><a href="--><?php //echo $itemUrl; ?><!--">详情点击</a></div>-->
<!--                                    </div>-->
<!--                                </div>-->
<!--                            --><?php //} ?>
<!--                        </div>-->
<!--                    </div>-->
<!--                </div>-->
<!--            --><?php //}
            $num++;
            $isFrist = false;
        } ?>
    </div>

	<div class="warp_product">
		<div class="col-lg-12">
			<div class="product_cate_tit">
				<label>
					<span class="item_id"></span>
					<span class="item_name">走进多彩</span>
				</label>
			</div>
			<div class="product_w">
				<div class="product_s">
					<div class="product_img1">
						<img src="upload/about/aboutduocai.jpg" width="232" height="328">
					</div>
					<div class="title">
						<h3>关于多彩</h3>

						<p>多彩饰家，家居服务型电商。以全新的B2C、B2B结合O2O模式引领家居装饰电子商务行业发展，是全国最领先的家居电子商务平台，业务覆盖了全国各个地区。</p>

						<p>多彩饰家作为家居后软装行业O2O模式的率先倡导者和推动者，在总结多年管理经验的基础上相继在北京、上海、廊坊成立了多彩饰家品牌管理有限公司。</p>

						<div class="more"><a href="http://www.zgdcsj.com/" target="/blank" style="color:#48A11E ;">详情点击>></a></div>
					</div>
				</div>
				<div class="product_f">
					<div class="product_img2"
					     style="background:url('/upload/about/jiajubackground.jpg') ;background-size:448px 328px;">
						<h3>家居美容小知识</h3>
						<p>
							想让家具永葆青春靓丽？那就定期帮它做个“美容”吧，现如今家居美容原来越普遍，它是一种常见的维修方式，针对各种成品家具或建材表面材料掉色、划伤、破损后的复原处理。这些表面材料可以是油漆、皮革、塑料、涂料、金属以及布料等等。
							<a href="/site/homeBeauty" style="color:#48A11E ;">详情点击>></a></p>
					</div>
				</div>
				<div class="product_g">
					<div class="luntan">
						<a><p>案例展示</p>
							<span>为您提供值得信赖的优质服务</span>
						</a>
					</div>
					<div class="huiyuan">
						<a><p>会员社区</p>
							<span>关于多彩社区引导多彩生活</span>
						</a>
					</div>
				</div>

			</div>
		</div>
	</div>
</div>
</div>
<!--<div class="clear"></div>-->
<!--    <div class="col-lg-8">-->
<!--        <div class="box">-->
<!--            <div class="box-title">最新新闻</div>-->
<!--            <div class="box-content"></div>-->
<!--        </div>-->
<!--        <div class="box">-->
<!--            <div class="box-title">产品视频</div>-->
<!--            <div class="box-content"></div>-->
<!--        </div>-->
<!--    </div>-->
<!--    <div class="col-lg-4">-->
<!--        <div class="box">-->
<!--            <div class="box-title">客户留言</div>-->
<!--            <div class="box-content"></div>-->
<!--        </div>-->
<!--    </div-->
<!--</div>-->
<div class="clear"></div>
<script type="text/javascript">
    //保证导航栏背景与图片轮播背景一起显示
//    $("#mainbody").removeClass();
//    $("#mainbody").addClass("index_bg01");
    $(function () {
        //滚动Banner图片的显示
        $('#slides').slidesjs({
            width: 940,
            height: 400,
            navigation: {
                active:true,
                effect:"fade"
            },
            effect:{
                fade:{
                    speed:200
                }
            },
            play: {
                active: true,
                // [boolean] Generate the play and stop buttons.
                // You cannot use your own buttons. Sorry.
                effect: "fade",
                // [string] Can be either "slide" or "fade".
                interval: 5000,
                // [number] Time spent on each slide in milliseconds.
                auto: true,
                // [boolean] Start playing the slideshow on load.
                swap: true,
                // [boolean] show/hide stop and play buttons
                pauseOnHover: false,
                // [boolean] pause a playing slideshow on hover
                restartDelay: 2500
                // [number] restart delay on inactive slideshow
            }
        });
    });
    function change_bg(n) {
        var tnum = $(".tab_t_list>li").length;
        for (i = 1; i <= tnum; i++) {
            if (i == n) {
                $("#pop_" + i).css("display", "");
                $(".tab_t_list>li")[i - 1].className = "current";
            } else {
                $("#pop_" + i).css("display", "none");
                $(".tab_t_list>li")[i - 1].className = "";
            }
        }
    }
</script>