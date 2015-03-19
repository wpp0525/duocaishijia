<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
    "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <title><?php echo Yii::app()->params[t];
        echo Yii::app()->params[title1];
        echo Yii::app()->params[title]; ?></title>
    <meta http-equiv="Content-Type" content="text/html;charset=utf-8"/>
    <meta name="keywords" content="<?php echo Yii::app()->params[h];
    echo Yii::app()->params['keywords']; ?>">
    <meta name="description" content="<?php echo Yii::app()->params[d];
    echo Yii::app()->params['description'];
     ?>">
    <meta http-equiv="content-language" content="zh"/>
    <meta http-equiv="Cache-Control" content="max-age=7200"/>
    <meta name="renderer" content="ie-stand">
    <meta content="chrome=1" http-equiv="X-UA-Compatible"/>
<!--    <meta name="renderer" content="ie-comp">-->
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/bootstrap.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/celiang.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/common.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->baseUrl; ?>/css/common.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->baseUrl; ?>/css/form.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/product.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/member.css'/>
    <link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->theme->baseUrl; ?>/css/post.css'/>
    <?php Yii::app()->clientScript->registerCoreScript('jquery'); ?>
	<link type='text/css' rel='stylesheet' href='<?php echo Yii::app()->baseUrl; ?>/js/jquery-1.4.2/jquery.min.js'/>
	<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/bootstrap.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/common.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->baseUrl; ?>/js/common.js"></script>
    <script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/passwordCheck.js"></script>
    <script type="text/javascript" src="<?php echo F::baseUrl(); ?>/js/holder.js"></script>


	<script type="text/javascript">

			$(document).ready(function () {
				//首先将#back-to-top隐藏
				$("#back-to-top").hide();
				//当滚动条的位置处于距顶部100像素以下时，跳转链接出现，否则消失
				$(function () {
					$(window).scroll(function () {
							if ($(window).scrollTop() > 100) {
								$("#back-to-top").fadeIn(1500);
							} else {
								$("#back-to-top").fadeOut(1500);
							}
						}
					);
					//当点击跳转链接后，回到页面顶部位置
					$("#back-to-top").click(function () {
						$('body,html').animate({
							scrollTop: 0}, 1000);
						return false;
					});
				});
			});
	</script>
</head>
<body>
<div class="top">
    <div class="container">
<!--        --><?php //$this->widget('widgets.default.WTopNav'); ?>
	    <?php $this->widget('widgets.default.WTopNav'); ?>

    </div>
</div>
<div class="clear"></div>
<div class="head">
	<div style="margin:20px 0"></div>
    <div class="logo">
        <a href="<?php echo Yii::app()->getBaseUrl(true); ?>">
            <?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/logo.png') ?>
        </a></div>
    <?php echo CHtml::beginForm(Yii::app()->createUrl('catalog/index'), 'get', array('class' => 'search')); ?>


    <div class="search_form">
	    <input id="key" name="key" type="text"  class="input01 txtSearch" maxlength="20" placeholder="输入你要的服务商品...">
        <button></button>
    </div>
    <div class="search_hot">
        热门搜索：
        <?php foreach (array('快捷刷新', '壁纸', '清洗服务') as $v) {
            echo CHtml::link($v, Yii::app()->createUrl('catalog/index', array('key' => $v)));
        } ?>
    </div>
    <?php echo Chtml::endForm(); ?>
    <a href="<?php echo Yii::app()->createUrl('cart/index'); ?>">
        <div class="shopping_car1">
	        <span class="cor_red bold"><?php echo count(Yii::app()->cart); ?></span>
	        服务车
        </div>
    </a>
	<!--<a href="<?php /*echo Yii::app()->createUrl('cart/index'); */?>">
		<div class="shopping_check">
			申请预约测量
		</div>
	</a>-->
	<div class="telnum" >
	<a class="telphone">
		<?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/telnum.png') ?>
	</a></div>

	<div class="twodimensioncode" >
	<a>
		<?php echo CHtml::image(Yii::app()->request->baseUrl . '/images/twodimensioncode.png') ?>
	</a></div>

</div>
<div class="nav">
    <?php $this->widget('widgets.leather.WMainMenu') ?>
</div>
<?php if (Yii::app()->params['ads']) {
    echo $this->renderPartial('picture', array('ads' => Yii::app()->params['ads']), true, true);
} ?>

<div class="clear"></div>
<div class="container">
    <?php echo $content; ?>
</div>
<div id="top"><p id="back-to-top"><a href="#top"><span></span></a></p></div>
<div class="clear"></div>
<div class="footer">
	<?php
		$sql = 'select category_id,title,`key` from page;';
		$command = Yii::app()->db->createCommand($sql);
		$rows = $command->queryAll();

		$attr = Category::model()->getAllTree();
//		$file=Yii::app()->request->getHostInfo()."/file.cache";
//		$cacheArray = unserialize(file_get_contents($file));

		$catlogs =  Category::model()->getCatlog($attr,13);

	?>
	<div class="foot_div">
    <div class="foot_c">

	        <?php
		        $attrPages = $catlogs['list'];
		        foreach($attrPages as $attrPage){
			        echo '<div class="foot_new">';
			        echo '<ul class="pd_ca_list">';
			        echo '<li><span class="font14 bold">'.$attrPage['name'].'</span></li>';
			        foreach($rows as $row){
				        if($row['category_id'] == $attrPage['id']){
					        echo '<li><a href="/page/'.$row['key'].'">'.$row['title'].'</a></li>';
				        }
			        }
			        echo '</ul></div>';

		        };
		        echo '<div class="foot_help">';
		        echo '<ul class="pd_ca_list">';
		        echo '<li><span class="font14 bold">'.$catlogs['name'].'</span></li>';

		        foreach ($rows as $row) {
			        if ($row['category_id'] == $catlogs['id']) {
				        echo '<li><a href="/page/'.$row['key'].'">'.$row['title'].'</a></li>';
			        }
		        }
		        echo '</ul></div>';
	        ?>
<!--            <ul>-->
<!--                <li><span class="font14 bold">新手指南</span></li>-->
<!--                <li>--><?php //echo CHtml::link('顾客必读', array('/page/index', 'key' => 'notice')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('订单的几种状态', array('/page/index', 'key' => 'orderstatus')); ?><!--</li>-->
<!--            </ul>-->
        </div>
<!--        <div class="foot_pay">-->
<!--            <ul>-->
<!--                <li><span class="font14 bold">购物指南</span></li>-->
<!--                <li>--><?php //echo CHtml::link('售后服务', array('/page/index', 'key' => 'service')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('网站使用条款', array('/page/index', 'key' => 'terms')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('免责条款', array('/page/index', 'key' => 'disclaimer')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('简单的购物流程', array('/page/index', 'key' => 'process')); ?><!--</li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="foot_set">-->
<!--            <ul>-->
<!--                <li><span class="font14 bold">支付/配送方式</span></li>-->
<!--                <li>--><?php //echo CHtml::link('支付方式', array('/page/index', 'key' => 'payment')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('网上支付小贴士', array('/page/index', 'key' => 'onlinepayment')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('关于如何验货', array('/page/index', 'key' => 'shippinginfo')); ?><!--</li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="foot_back">-->
<!--            <ul>-->
<!--                <li><span class="font14 bold">售后服务</span></li>-->
<!--                <li>--><?php //echo CHtml::link('售后政策', array('/page/index', 'key' => 'aftermarket')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('价格保护', array('/page/index', 'key' => 'priceprotection')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('退款说明', array('/page/index', 'key' => 'refund')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('取消订单', array('/page/index', 'key' => 'cancelorder')); ?><!--</li>-->
<!--            </ul>-->
<!--        </div>-->
<!--        <div class="foot_help">-->
<!--            <ul>-->
<!--                <li><span class="font14 bold">帮助中心</span></li>-->
<!--                <li>--><?php //echo CHtml::link('帮助中心', array('/page/index', 'key' => 'helpcenter')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('在线客服', array('/page/index', 'key' => 'contact')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('品质保证', array('/page/index', 'key' => 'qualityAssurance')); ?><!--</li>-->
<!--                <li>--><?php //echo CHtml::link('关于我们', array('/page/index', 'key' => 'about')); ?><!--</li>-->
<!--            </ul>-->
<!--        </div>-->
<!--<!--        <div class="foot_call">-->
<!--<!--            <p class="font14 bold">咨询电话</p>-->
<!--<!---->
<!--<!--            <p class="font14 bold cor_r">13967414054</p>-->
<!--<!--        </div>-->
<!--    </div>-->
	</div>
    <div class="foot_u">
        <p class="foot_link">
	        <?php $menu1 = Menu::model()->findByPk(5);
		          $descendants1 = $menu1->children()->findAll();
		        foreach($descendants1 as $descendant){
			        echo '<a href="'.Yii::app()->baseUrl.'/'.$descendant->url.'">'.$descendant->name.'</a>';
			        echo '|';
		        }
//		          dump($descendants1);
	        ?>
<!--            --><?php //echo CHtml::link('关于我们', array('/page/index', 'key' => 'about')); ?><!--|-->
<!--            --><?php //echo CHtml::link('联系我们', array('/page/index', 'key' => 'contact')); ?><!--|-->
<!--            --><?php //echo CHtml::link('人才招聘', array('/page/index', 'key' => '')); ?><!--|-->
<!--            --><?php //echo CHtml::link('商家入驻', array('/page/index', 'key' => 'join')); ?><!--|-->
<!--            --><?php //echo CHtml::link('广告服务', array('/page/index', 'key' => '')); ?><!--|-->
<!--            --><?php //echo CHtml::link('手机商城', array('/page/index', 'key' => '')); ?><!--|-->
<!--            --><?php //echo CHtml::link('友情链接', array('/page/index', 'key' => '')); ?><!--|-->
<!--            --><?php //echo CHtml::link('销售联盟', array('/page/index', 'key' => 'partner')); ?><!--|-->
<!--            --><?php //echo CHtml::link('资源交流', array('/page/index', 'key' => '')); ?>
        </p>

        <p>Copyright@2013 - 2015 <?php echo Yii::app()->name ?> All Rights Reserved. <a href="http://www.cnzz.com/stat/website.php?web_id=1254189666">站长统计</a></p>
	    <script type="text/javascript">var cnzz_protocol = (("https:" == document.location.protocol) ? " https://" : " http://");document.write(unescape("%3Cspan id='cnzz_stat_icon_1254189666'%3E%3C/span%3E%3Cscript src='" + cnzz_protocol + "s4.cnzz.com/z_stat.php%3Fid%3D1254189666%26show%3Dpic1' type='text/javascript'%3E%3C/script%3E"));</script>
        <p>Powered by <a href="http://www.hejia.cn">和佳软件</a></p>
    </div>
</div>
<!--<SCRIPT LANGUAGE="JavaScript" src=http://float2006.tq.cn/floatcard?adminid=9633518&sort=0 ></SCRIPT>-->

</body>
</html>
