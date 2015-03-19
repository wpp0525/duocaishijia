<html>
<head>
    <meta http-equiv="X-UA-Compatible" content="IE=8">
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link type="text/css" rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/order.css"/>
</head>

<body>
<div class="myOrder ">
	<a href="<?php echo Yii::app()->createUrl('/member/orderlist/admin'); ?>">我的订单:</a>
		<span><?php echo $Order->order_id;?></span>
	</h3>
</div>
<div class="tabs-container" style="width:1000px; margin-bottom: 60px;
    padding-left: 28px;">

<!--<ul class="tabs-nav">-->
<!--    <li class="current ks-switchable-trigger-internal164">-->
<!--	    <a  name="tab0">订单信息</a>-->
<!--    </li>-->
<!--</ul>-->

<div class="tabs-panels" style="top:0px;margin-left: 0">
<div class="info-box order-info ks-switchable-panel-internal165" style="display: block;">
<h2>订单信息</h2>
<div>
    <div class="addr_and_note">
        <dl>
            <dt>
                服务地址/联系人
                ：
            </dt>
            <dd>
                <?php
                echo $Order->receiver_name.' ，'.$Order->receiver_mobile.' ，';
                echo Order::model()->showDetailAddress($Order);
                ?>
            </dd>
            <dt>备注：</dt>
            <dd>
                <?php
                echo $Order->memo;
                ?>
            </dd>
        </dl>
    </div>

<hr>
<!-- 订单信息 -->
<div class="misc-info">
    <h3>订单信息</h3>
    <dl>
        <dt>订单编号：</dt>
        <dd>
            <?php
            echo $Order->order_id;
            ?>
        </dd>
        <dt>成交时间：</dt>
        <dd>
            <?php
            if($Order->create_time){
                echo date("Y年m月d日 H:i:s",$Order->create_time);
            }
            ?>
        </dd></dl>

	<?php if($Order->pay_time){ ?>
    <dl>
        <!--<dt>发货时间：</dt>
        <dd>
            <?php
/*            if($Order->ship_time){
                echo date("Y年m月d日 H:i:s",$Order->ship_time);
            }
            */?>
        </dd>-->

        <dt>付款时间：</dt>
        <dd>
            <?php
                echo date("Y年m月d日 H:i:s",$Order->pay_time);
            ?>
        </dd>

        <dt>&nbsp;</dt>
        <dd>&nbsp;</dd>
    </dl>
	<?php             }
	?>
    <div class="clearfix"></div>
</div>

<hr>
<div class="misc-info">
    <h3>订单详情</h3>
    <dl>
        <dt>订单状态：</dt>
        <dd>
            <?php
            echo $orderStatus;
            ?>
        </dd>
        <dt>支付状态：</dt>
        <dd>
            <?php
          echo Tbfunction::showPayStatus($Order->pay_status);
	         //   echo  $pay_status;
            ?>
        </dd>
    </dl>
    <dl>
        <!--<dt>发货状态：</dt>
        <dd>
            <?php
/*            echo Tbfunction::showShipStatus($Order->ship_status);
            */?>
        </dd>-->

        <dt>退款状态：</dt>
        <dd>
            <?php
            echo Tbfunction::showRefundStatus($Order->refund_status);
            ?>
        </dd>

        <dt>应首付款：</dt>
        <dd><?php echo $Order->pay_first ;?></dd>
    </dl>
    <div class="clearfix"></div>
</div>
<!-- 订单信息 -->
<table>
    <colgroup>
        <col class="item">
        <col class="sku">
        <!-- 宝贝 -->

        <col class="status">
        <!-- 交易状态 -->

        <col class="service">
        <!-- 服务 -->

        <col class="price">
        <!-- 单价（元） -->

        <col class="num">
        <!-- 数量 -->

        <col class="discount">
        <!-- 优惠 -->

        <col class="order-price">

        <!-- 合计（元） -->
        <!-- 买/卖家信息 -->
    </colgroup>
    <tbody class="order">
    <tr class="sep-row">
        <td colspan="8"></td>
    </tr>
    <tr class="order-hd">
        <th class="item " style="width:31%">服务名称</th>
                           <th class="sku" style="width:24%">服务内容</th>
                           <th class="price" style="width:15%">单价(元)</th>
                           <th class="num" style="width:15%">数量</th>
                           <th class="order-price last" style="width:15%">服务总价（元）</th>
    </tr>

    <?php
    foreach($Order_item as $orderItems){

        ?>
        <tr class="order-item">
            <td class="item">
                <div class="pic-info">
                    <div class="pic s50">
                        <a target="_blank" href="javascript:void(0)" title="商品图片">
                            <img alt="查看宝贝详情" src="<?php
	                           if(isset( $orderItems->pic)) {
		                           echo $orderItems->pic;
	                           }else{
		                           $itemimg =  ItemImg::model()->findByAttributes(array(
			                            'item_id' => $orderItems->item_id
		                            ));
		                           echo $itemimg->pic;
	                            }
                            ?>" />
                        </a>
                    </div>
                </div>
                <div class="text-info-font txt-info ">
                    <div class="desc">
	                    <?php $itemUrl = Yii::app()->createUrl('item/view', array('id' => $orderItems->item_id));?>
                        <span class="name"><a href="<?php echo $itemUrl;?>" title="" target="_blank"><?php echo $orderItems->title ?></a></span>
                        <br>
                    </div>
                </div>
            </td>
            <td class="sku">

                <div class="props"><span><?php
			                if(isset($orderItems->props_name)){
				                echo implode(',',json_decode($orderItems->props_name, true));
			                }else{
				                echo $orderItems->title;

			                } ?></span></div>
            </td>

            <td class="price">
                <?php
                echo $orderItems->price;
                ?>
            </td>
            <td class="num">
                <?php
                echo $orderItems->area;
                ?>
            </td>
            <td class="order-price" rowspan="1">
                <?php
                echo $orderItems->total_price;
                ?>
                <!--<li>
                    (快递: <?php /*echo $Order->ship_fee;*/?>)
                </li>-->
            </td>
        </tr>

        <?php
    }
    ?>
    <?php
	    if ($Order->pay_fee == 0 && $Order->pay_first != 0) {
		    $count = $Order->pay_first;
	    } else {
		    $count = $Order->total_fee - $Order->pay_fee;
	    }
    ?>
    <tr class="order-ft">
	    <td colspan="8" style="padding-right: 0;" >
		    <?php if($count!=0){?>
			    <a class="go-pay" href='<?php echo Yii::app()->createUrl('alipay/default/alipayapi',array(
				    'order_id'      => $Order->order_id,
				    'amount'        => $count,
				    'pay_method_id' => $Order->pay_method_id,
			    )) ?>'></a>
		    <?php }else{?>
			    <a class="go-pay" style="background:#FFF"></a>
		    <?php }?>


		    <div class="get-money getAllMoney" >
			    服务总金额：
			    <span>
			    <?php
				    echo $Order->total_fee;
			    ?>
			    </span>元
			    <span style="padding-left: 50px" >  </span>
			    实付款：
			    <span >
			    <?php
				    echo $Order->pay_fee;
			    ?>
			    </span>元
		    </div>
<!---->
<!---->
<!---->
<!--		        	        <input class="btn-orderdetail" type="button" style="height: 37px;width: 152px;" value="去付款"-->
<!--	               onclick="window.location.href='--><?php //echo Yii::app()->createUrl('alipay/default/alipayapi',array(
//		               'order_id'      => $Order->order_id,
//		               'amount'        => $orderItems->total_price-$Order->pay_fee,
//		               'pay_method_id' => '1',
//	               )) ?><!--' "/>-->
	    </td>

    </tr>
    </tbody>

</table>
</div>
</div>
</div>
</div>
</body>
</html>
