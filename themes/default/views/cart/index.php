<?php
$cs = Yii::app()->clientScript;
$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/deal.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/cart/core.css');
Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/cart/box.css');
$this->breadcrumbs = array(
    '服务车',
);
Yii::app()->clientScript->registerCoreScript('jquery');
?>
<script type="text/javascript">
    $(document).ready(function () {
        $("#updateCart").click(function (event) {
            $('#cartForm').submit();
        });
    });

</script>
<?php $imageHelper=new ImageHelper(); ?>
<div class="box" style="width: 1180px">
        <div class="box-self container_24" style="padding-top:3px; border-bottom:3px solid #48a11c;">
	        我的服务车
        </div>
		<div class="box-content cart container_24">
        <?php echo CHtml::beginForm(array('/order/checkout'), 'POST', array('id' => 'cartForm')) ?>
        <table class="table" id="cart-table">
           <thead>
            <tr>
                <th><?php echo CHtml::checkBox('checkAllPosition', true, array('data-url' => Yii::app()->createUrl('cart/getPrice'))); ?></th>
	            <th class="col-md-2" >服务</th>
	            <th class="col-md-3" >服务内容</th>
	            <th class="col-md-1" >单价</th>
	            <th class="col-md-1">墙面面积/数量</th>
	            <th class="col-md-1" >小计</th>
	            <th class="col-md-1">操作</th>
<!--                <th class="col-md-2" style="width: 0px">服务</th>-->
<!--<!--                <th class="col-md-3">名称</th>-->
<!--                <th class="col-md-3" style="width: 0px">服务内容</th>-->
<!--                <th class="col-md-1" style="width: 0px">单价</th>-->
<!--                <th class="col-md-1" style="width: 0px">墙面面积/数量</th>-->
<!--                <th class="col-md-1" style="width: 0px">小计</th>-->
<!--                <th class="col-md-1" style="width: 0px">操作</th>-->
            </tr>
            </thead>
            <?php

            $cart = Yii::app()->cart;
            $items = $cart->getPositions();
//	            dump($items);


            if (empty($items)) {
                ?>
                <tr>
                    <td colspan="8" style="padding:10px">您的服务车是空的!</td>
                </tr>
            <?php
            } else {
	            $defaultItemId = $total = 0;
                foreach ($items as $key => $item) {
	                $itemId = $item->item_id;

	                $propnuber=count($item->cartProps);

	                if ($itemId != $defaultItemId || $propnuber<=$i) {
		                $i = 0;
		                $defaultItemId = $itemId;
	                }

//	                if(!is_array($item->cartProps)){
//		                $cartProps[] =  $item->cartProps;
//
//	                }
	                $keys = $item->getId();
	                $keyId = $keys[$i];

	                while($keyId!=$key && $i<count($keys)){
		                $i++;
		                $keyId = $keys[$i];
	                }
	                if($i==count($keys)){
		                continue;
	                }
                    ?>

                    <tbody id="<?php echo $keyId;?>" style="height: 106px; "><tr class="tr"><?php
                        $itemUrl = Yii::app()->createUrl('item/view', array('id' => $item->item_id));
                        ?>
                        <td style="display:none;">

                            <?php echo CHtml::hiddenField('item_id[]', $item->item_id, array('id' => '','class' => 'item-id'));
                            echo CHtml::hiddenField('props[]', empty($item->cartProps[$i]) ? '' : $item->cartProps[$i],  array('id' => '','class' => 'props'));?>
                        </td>
                        <td ><?php echo CHtml::checkBox('position[]', true, array('value' => $key, 'data-url' => Yii::app()->createUrl('cart/getPrice'))); ?></td>
	                    <?php
		                    if ($item->getMainPic()) {
			                    $picUrl = $imageHelper->thumb('70', '70', $item->getMainPic());
			                    $picUrl = Yii::app()->baseUrl.$picUrl;
		                    } else {
			                    $picUrl = $item->getHolderJs('70', '70');
		                    }
	                    ?>
                        <td>
	                        <a href="<?php echo $itemUrl; ?>"><?php echo CHtml::image($picUrl, $item->title, array('width' => '80px', 'height' => '80px')); ?></a><br />
                            <?php echo CHtml::link($item->title, $itemUrl); ?>
                        </td>
<!--                        <td>--><?php //echo CHtml::link($item->title, $itemUrl); ?><!--</td>-->
                        <td>
	                        <?php
		                        if (is_array($item->sku)) {
			                        foreach ($item->sku as $sku) {
				                        $skuProps = implode(';', json_decode($sku->props, true));

				                        if ($skuProps == $item->cartProps[$i]) {
					                        $skuPropName = implode(';', json_decode($sku->props_name, true));
				                        }
			                        }
			                        echo !isset($skuPropName) ? '' : $skuPropName;
		                        } else {
			                        echo empty($item->sku) ? '' : implode(';', json_decode($item->sku->props_name, true));
		                        }

	                        ?>
                        </td>
                        <td>
	                        <div id="Singel-Price">
		                        <?php
			                        $price = $item->getPrice();
			                        if(is_array($price)){echo $price[$item->cartProps[$i]];}
			                        else{
				                        echo $price;
			                        }

		                        ?>
	                        </div>
                        </td>


                        <td>
                            <!-- <span class="glyphicon glyphicon-minus-sign1 btn-reduce" ></span> -->
	                        <?php echo CHtml::textField('quantity[]', $item->getQuantity($keyId), array( 'style'=>'width: 50px;vertical-align: top;','size' => '10', 'class'=>'quantity','maxlength' => '10', 'data-url' => Yii::app()->createUrl('cart/update'))); ?>
                            <input id="pre_quantity" class="pre_quantity" type="hidden" value="<?php echo $item->getQuantity($keyId);?>"/>
                            <!-- <span class="glyphicon glyphicon-plus-sign1 btn-add"></span>-->
                            <div id="stock-error"></div><input id="pre_quantity" type="hidden"  />
                        </td>

                        <td><div id="SumPrice" style="display:inline-flex;"><?php echo $item->getSumPrice(true, $item->cartProps[$i],$keyId); ?></div>元</td>
                        <td>
	                        <?php
		                        echo CHtml::link('移除', array('/cart/remove', 'key' => $keyId));

	                        ?>
                        </td>
                    </tr></tbody>
                    <?php
		                $total += $item->getSumPrice(true, $item->cartProps[$i],$keyId);
		                $i++;
	                ?>
                <?php
                }
            } ?>
            <tfoot>
            <tr>
                <td colspan="8" style="padding:10px;text-align:right ;height: 50px; border-top: 3px solid #dddddd;">总计：<label id="total_price" style="font-size:14px;"><?php echo $total;?></label>元</td>
            </tr>
            <tr>
	            <td colspan="8" style="vertical-align:middle;padding-top: 25px;">
		            <input class="btn btn-danger pull-left" type="button"  value="清空服务车"
		                   onclick="window.location.href='<?php echo Yii::app()->createUrl('cart/clear'); ?>'"/>
		            <!--                    <button class="btn btn-danger pull-left">-->
		            <?php //echo CHtml::link('清空服务车', array('/cart/clear'), array('class' => 'btn1')) ?><!--</button>-->

		            <input type="button" class="btn1 btn btn-success"
		                   id="checkout" value="结算">
		            <input class="btn btn-primary"
		                   id="btn-primary" type="button"
		                   value="继续购物" onclick="window.location.href='/'"/>
		            <!--                    <button class="btn btn-primary"-->
		            <!--                            style="float:right;padding:1px 10px;margin-right: 10px;"  id="btn-primary">-->
		            <?php //echo CHtml::link('继续购物', array('./'), array('class' => 'btn1')) ?><!--</button>-->
	            </td>
            </tr>
            </tfoot>
        </table>
        <?php echo CHtml::endForm(); ?>
    </div>
</div>

<!-- Modal-->
<div tabindex="-1" class="modal fade in" id="myModal" role="dialog" aria-hidden="false" aria-labelledby="myModalLabel"
     style="display: none;">
	<div class="modal-dialog">
		<div class="modal-content">

			<form role="form" id="log-out-box">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true" id="log-out-close">×
				</button>
				<div id="warning-load">
					<div id="logo"><?php echo Yii::app()->name ?></div>

					<div class="user">
						<div> 用户名：</div>
						<input class="txt form-control" id="user" name="user" type="text" placeholder="请输入用户名"/>
					</div>
					<div id="ajax"></div>
					<div class="user">
						<div> 密码：</div>
						<input class="txt form-control" id="password" name="password" type="password"
						       placeholder="请输入密码"/>
					</div>
					<button id="log-btn-div" name="button" type="button" class="btn-success btn">登录</button>
					<div id="register">
						<a href="<?php echo Yii::app()->createUrl('user/registration'); ?>" class="link"><u>免费注册</u></a>
<!--						<a href="javascript:void" class="link buy-without-login"-->
<!--						   id="buy-without-login"><u>免登陆直接购买</u></a>-->
					</div>
				</div>
			</form>
		</div>
	</div>
</div>
<script type="text/javascript">
  $(document).ready(function(){

      $(".btn-add").on('click',function(){
          $(this).siblings(".quantity").val(Number( $(this).siblings(".quantity").val())+1);
          if(!$(this).siblings("#stock-error").text()) {
            $(this).siblings(".pre_quantity").val(Number( $(this).siblings(".pre_quantity").val())+1);
          }
          update($(this).siblings(".quantity"));
      });
      $(".btn-reduce").on('click',function(){
          var change_quantity = Number( $(this).siblings(".quantity").val());
          $(this).siblings("#stock-error").find("#num-error").remove();
          if(change_quantity <= 1){
              $(this).siblings(".quantity").val(1);
              $(this).siblings(".pre_quantity").val(1);
              $(this).siblings("#stock-error").find("#error-message").remove();
              $(this).siblings("#stock-error").append("<div id=\"num-error\" style=\"color:red\">商品数量不能小于1</div>");
          }else{
              $(this).siblings(".pre_quantity").val(change_quantity-1);
              $(this).siblings(".quantity").val(change_quantity-1);
              update($(this).siblings(".quantity"));
          }
      });
  });

    $(function(){
        $('[name="position[]"]').change(function() {
	        var itemNums = $('[name="position[]"]').length;
	        var checkedLength = $('[name="position[]"]:checked').length;
	        var $this = $(this);
	        var $checkAll = $("#checkAllPosition");
			if($this.attr('checked')){
				$this.parents("tr").addClass('tr');
			}else{
				$this.parents("tr").removeClass('tr');
			}
	        //$this.parents("tr").toggleClass("class", $this.checked);
	        $checkAll.attr("checked", checkedLength == itemNums);

            if(checkedLength == 0) {
                $("#checkout").attr('disabled',true);
            } else {
                $("#checkout").removeAttr('disabled');
            }
        });
        $("#checkAllPosition").change(function() {
            if(!$("#checkAllPosition").attr('checked')) {
                $("#checkout").attr('disabled',true);
            } else {
                $("#checkout").removeAttr('disabled');
            }
        });
        $(".quantity").keyup(function() {
            var tmptxt = $(this).val();
            $(this).val(tmptxt.replace(/\D|^0/g, ''));
        }).bind("paste", function() {
                var tmptxt = $(this).val();
                $(this).val(tmptxt.replace(/\D|^0/g, ''));
            }).css("ime-mode", "disabled");

	    $("#checkout").click(function () {
		    $.post("<?php echo Yii::app()->createUrl('user/user/isLogin'); ?>", function (response) {
			    if (response.status == 'login') {
				    $('#cartForm').submit();
			    } else {
				    $('#myModal').modal('show');
			    }
		    }, 'json');
	    });

	    $("#log-btn-div").click(function () {
		    carLogin();
	    });
    });//输入验证，保证只有数字。

    function update(quantity) {
        var tr = quantity.closest('tr');
        var sku_id = tr.find("#position");
        var qty = quantity;
        var item_id = tr.find(".item-id");
        var props = tr.find(".props");
        var cart=parseInt($(".shopping_car").find("span").html());
        var sumPrice= parseFloat(tr.find("#SumPrice").html());
        var singlePrice=parseFloat( tr.find("#Singel-Price").html());
        var data = {'item_id': item_id.val(), 'props': props.val(), 'qty': qty.val(),'sku_id':sku_id.val()};
        $.post('/cart/update', data, function (response) {
            tr.find("#error-message").remove();
            tr.find("#num-error").remove();
            if (!response) {
                $(".shopping_car").find("span").html(cart-sumPrice/singlePrice+parseInt(qty.val()));
                tr.find("#SumPrice").html(parseFloat(qty.val()) * parseFloat(singlePrice));
               update_total_price();
            }
            tr.find("#stock-error").append(response);
            if(quantity.siblings('#stock-error').find("#error-message").text()) {
                quantity.val(Number(quantity.val())-1);
            }
        });
    }
    function update_total_price() {
        var positions = [];
        $('[name="position[]"]:checked').each(function () {
            positions.push($(this).val());
        });
        $.post('/cart/getPrice', {'positions': positions}, function (data) {
            if (!data.msg) {
                $('#total_price').text(data.total);
            }
        }, 'json');
    }

  function carLogin () {
	  var data = {
		  username: $("#user").val(),
		  password: $("#password").val()
	  };

	  $.post("../user/login/llogin", data, function (response) {
		  if (response.status == 'login') {
			  $('#myModal').modal('hide');
			  $.post('/cart/addToUser', {position: 1}, function (json) {
				  if (json.status == 'success') {
					  $('#cartForm').submit();
				  } else {
					  alert("登录失败！请重新登陆")
				  }
			  }, 'json');
		  } else {
			  alert("用户名或密码不对！");
		  }
	  }, 'json');
  }
</script>