<html>
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=8">
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
	<link type="text/css" rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/order.css"/>
</head>
<body>

<?php
$this->breadcrumbs = array(
    '我的订单' => array('admin'),
    '管理',
);
?>
<div class="box" style="width:1000px;">
    <div class="box-title">我的订单</div>
	<div class="line"></div>
<!--    <div class="box-content clearfix">-->
      <?php
        $this->widget('zii.widgets.grid.CGridView', array(
            'id' => 'order-grid',
            'dataProvider' => $model->MyOrderSearch(),
            'filter' => $model,
	        'template'=>'{items}{pager}{summary}',
	        'summaryText'=>'第<span style="color: #008000;">{start}</span>-<span style="color:#008000;">{end}</span>条,
	                共<span style="color:#008000;">{count}</span>条&nbsp;&nbsp;
	                页数:<span style="color:#008000;">{page}/{pages}</span>页',
	        'pager'=>array(
	            'class'=>'CLinkPager',//定义要调用的分页器类，默认是CLinkPager，需要完全自定义，还可以重写一个，参考我的另一篇博文：http://blog.sina.com.cn/s/blog_71d4414d0100yu6k.html
	            // 'cssFile'=>false,//定义分页器的要调用的css文件，false为不调用，不调用则需要亲自己css文件里写这些样式
	             'header'=>'翻页：',//定义的文字将显示在pager的最前面
	           //  'footer'=>'footer1',//定义的文字将显示在pager的最后面
	            'firstPageLabel'=>'首页',//定义首页按钮的显示文字
	            'prevPageLabel'=>'上一页',//定义上一页按钮的显示文字
	            'nextPageLabel'=>'下一页',//定义下一页按钮的显示文字
	            'lastPageLabel'=>'尾页',//定义末页按钮的显示文字

	            //关于分页器这个array，具体还有很多属性，可参考CLinkPager的API
            ),
            'columns' => array(
	            array(
		            'htmlOptions'=>array('width'=>"30px"),
		            'class' => 'CCheckBoxColumn',
		            'name'=>'id',
		            'value'=> '$data->id',
		            'id'=>'ids',
		            'headerTemplate'=>'{item}',
		            'selectableRows'=>2,
	            ),
	            array(
                    'name' => 'order_id',
                    'value' => 'Tbfunction::link_user($data->order_id)',
                ),
                array(
                    'name' => 'status',
                    'value' => '$data->getOrderWholeStatus()',
                ),
                array(
                    'name' => 'title',
                    'value' => '$data->getTitle()',
                ),
                'total_fee',
//                'ship_fee',
                'pay_fee',
                array(
                    'name' => 'create_time',
                    'value' => 'date("Y-m-d H:i:s", $data->create_time)',
                ),
                array(
                    'name' => 'pay_status',
                    'value' => 'Tbfunction::showPayStatus($data->pay_status)',
                    'filter' => Tbfunction::ReturnPayStatus(),
                ),
                array(
                    'value' => 'Tbfunction::view_user($data->order_id)',
                ),
            ),
            //'cssFile' => '/css/order-list.css ',
        ));
        ?>

	<a class="go-pay" value='<?php echo Yii::app()->createUrl('alipay/default/alipayapi',array(
//		'order_id'      => $Order->order_id,
//		'amount'        => $count,
//		'pay_method_id' => $Order->pay_method_id,
		'order_id'      => 22222,
		'amount'        => 333,
		'pay_method_id' => 1,
	)) ?>'   href="#" > </a>
	<a class="getsum_amt" style="font-size: 15px;margin-top:34px;margin-right:60px;float:right; color: #333333;"> 待支付总计:</a>
	<a class="sum_amt"    style="font-size: 16px;margin-top:34px;margin-right:-150px;float:right;color: #FF5500;font-weight: 700;"></a>
 </div>


<script type="text/javascript">
	$(function(){
		/* 计算支付金额模块*/

//		$('[name="ids[]"]').change(function() {
		$(document).on("change", "input[name='ids[]']", function(e) {

			var obj2= $('[name="ids[]"]:checked');
			var orders={};
			for(var i=0;i<obj2.length;i++)
			{
				var order2 =	obj2.eq(i).parents('td').next().children('a');
				orders[i] = order2.text();
			}
			var data= {order:orders};

			$.post("<?php echo Yii::app()->createUrl('mergeorder/DealOrder');?>",data,function (response) {
				$('.sum_amt').text(response.total_fee);
			}, 'json');
		});

		/* 计算去支付的模块*/
//		$('.go-pay').click(function() {
		$(document).on("click", ".go-pay", function(e) {
		    var itemselect= $('[name="ids[]"]:checked').length;

			if(itemselect==0){
				alert('请至少选择一个订单进行支付');
			}else{
				var obj= $('[name="ids[]"]:checked');
				var orderss={};
				for(var i=0;i<obj.length;i++)
				{
					var order =	obj.eq(i).parents('td').next().children('a');
					orderss[i] = order.text();
				}
				var data= {order:orderss };

				$.post("<?php  echo Yii::app()->createUrl('mergeorder/DealOrderss');?>",data,function (response) {
					var morder ='order_id='+response.merge_order_ids + '&';
					var amount ='amount='+response.total_fee + '&';

					var alipayurl = $('.go-pay').attr('value');
					alipayurl =alipayurl.replace("order_id=22222&",morder);
					alipayurl =alipayurl.replace("amount=333&",amount);

					$('.go-pay').attr('value',alipayurl) ;
					location.href=alipayurl;
				},'json');
		    }
	    });



	});

</script>
</body>
</html>