<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-11-21
 * Time: 下午4:21
 */ 
?>

<?php
$this->breadcrumbs = array(
	'购物车' => array('/cart'),
	'订单成功',
);
	echo CHtml::beginForm(array('/orderApi/pay'), 'POST', array('id' => 'orderPayForm')) ;
?>
<div>
	<div><input type="text"  name="order_id" value=<?php echo $orderId ?>></div><br>
	<div><input type="text"  name="amount" value=<?php echo $amount ?>></div><br>
	<div><input type="hidden"  name="pay_method_id" value=<?php echo $payment_method_id ?>></div>
	<div><input type="hidden"  name="payment_sn" value="000001"></div>
	<div><input type="hidden"  name="pay_type" value="000001"></div>
	<div class="buttons">
		<?php echo CHtml::submitButton('确认付款'); ?>
	</div>

</div>