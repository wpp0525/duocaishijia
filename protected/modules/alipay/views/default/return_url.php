<?php
	/* *
	 * 功能：支付宝页面跳转同步通知页面
	 * 版本：3.3
	 * 日期：2012-07-23
	 * 说明：
	 * 以下代码只是为了方便商户测试而提供的样例代码，商户可以根据自己网站的需要，按照技术文档编写,并非一定要使用该代码。
	 * 该代码仅供学习和研究支付宝接口使用，只是提供一个参考。

	 *************************页面功能说明*************************
	 * 该页面可在本机电脑测试
	 * 可放入HTML等美化页面的代码、商户业务逻辑程序代码
	 * 该页面可以使用PHP开发工具调试，也可以使用写文本函数logResult，该函数已被默认关闭，见alipay_notify_class.php中的函数verifyReturn
	 */

	require_once("alipay.config.php");
	require_once("lib/alipay_notify.class.php");
?>
<!DOCTYPE HTML>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<script language="JavaScript" src="/js/jquery.js"></script>

	<?php
		//计算得出通知验证结果
		$alipayNotify = new AlipayNotify($alipay_config);
		$verify_result = $alipayNotify->verifyReturn();
		if($verify_result) {  //验证成功
			/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
			//请在这里加上商户的业务逻辑程序代码

			//——请根据您的业务逻辑来编写程序（以下代码仅作参考）——
			//获取支付宝的通知返回参数，可参考技术文档中页面跳转同步通知参数列表

			//商户订单号
			//  echo "out_trade_no:$out_trade_no  --total_fee:$total_fee  --trade_no:$trade_no" ;
 		echo "你的订单号: $out_trade_no ". '<br>' ;
		echo "淘宝交易流水号: $trade_no ". '<br>' ;
 		echo "你的支付金额: $total_fee  ". '<br>' ;

			?>
			<script type="text/javascript">
				$(document).ready(function(){

				var  orderid = "<?php echo $out_trade_no  ?>";

                if(orderid.length == 14) { //如果是单笔订单
					$.ajax({
						type: 'POST',
						url: "/order/payfinish" ,
						cache:false,
						dataType:'json',
						data:{
							order_id:"<?php echo $out_trade_no  ?>",
							pay_type:"1",       //支付方法, 1代表支付宝,2代表微信,8代表pos结算,9代表现金
							amount:"<?php echo $total_fee  ?>",
							pay_menthod_id:"1",
							payment_sn:"<?php echo $trade_no  ?>",
						},
						success:function(data) {
							if(data.success){
								//  alert('chengong');
								//  alert(data);  //成功之后，返回成功的数据并打印
							}else{
								alert('!start_err: '+data.msg+' :error_end_err! ');  //打印错误信息
							}
						},
						error : function() {
							alert("异常22！");
						}
					});
                }else{  //多笔订单，进行分个订单的判断
	                $.ajax({
		                type: 'POST',
		                url: "/order/payfinishs" ,
		                cache:false,
		                dataType:'json',
		                data:{
			                order_id:"<?php echo $out_trade_no  ?>",
			                pay_type:"0",
			                amount:"<?php echo $total_fee  ?>",
			                pay_menthod_id:"1",
			                payment_sn:"<?php echo $trade_no  ?>",

		                },
		                success:function(data) {
			                if(data.success){
				           //     alert('chenggong2');
				           //     alert(data);
			                }else{
				                alert('!start_err2: '+data.msg+' :error_end_err2! ');  //打印错误信息
			                }
		                },
		                error : function() {
			                alert("异常33！");
		                }
	                });
                  }
				});

				<!-- 用于支付成功后，做的下一步操作 现在查询出的是订单的数据，点击确定时应该查询出我所有的订单状态-->
				function goOrder(){
					$.ajax({
						type: 'POST',
						url: "/orderApi/search" ,
						cache:false,
						dataType:'json',
						data:{
							order_id: "<?php echo $out_trade_no  ?>"
						},
						success:function(data) {
						//	 alert(data);  //如果成功，就打印相应信息。
						},
						error : function() {
							alert("异常！");
						}
					});
				}
			</script>
		  <?php

			if($_REQUEST['trade_status'] == 'TRADE_FINISHED' || $_REQUEST['trade_status'] == 'TRADE_SUCCESS') {
				//判断该笔订单是否在商户网站中已经做过处理
				//如果没有做过处理，根据订单号（out_trade_no）在商户网站的订单系统中查到该笔订单的详细，并执行商户的业务程序
				//如果有做过处理，不执行商户的业务程序
                echo"<div class='box' style='width: 1180px; margin: 10px auto;'>
                    <div class='box-title'>订单支付成功！</div>
                        <div class='box-content'>
                       恭喜您，您的订单已经支付成功！我们将尽快联系您，确认订单。<br/>
                         </div>
                    </div>'";
            }
        else {
				echo "trade_status=".$_REQUEST['trade_status'];
			}

		
			 ?>
			<style>
				.new-btn-login-sp{
					border:1px solid #D74C00;
					padding:1px;
					display:inline-block;
				}
			</style>

<!--原来写的，先不用-->
<!--	<span class="new-btn-login-sp">-->
<!--                <button class="new-btn-login" onclick="goOrder()" type="button" style="text-align:center;">确 认</button>-->
<!--     </span>-->

<!--	<input class="new-btn-login-sp2" type="button" style="height: 37px;width: 152px;" value="返还我的订单列表"-->
<!--	  onclick="window.location.href='--><?php //echo Yii::app()->getBaseUrl(true) ."/member/orderlist/admin"; ?><!--' "/>-->

	<?php
		//——请根据您的业务逻辑来编写程序（以上代码仅作参考）——

		/////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////////
		}
		else {
			//验证失败
			//如要调试，请看alipay_notify.php页面的verifyReturn函数
			echo "验证失败11";
		}
	?>

	<title>支付宝纯网关接口</title>
</head>
<body>
</body>
</html>