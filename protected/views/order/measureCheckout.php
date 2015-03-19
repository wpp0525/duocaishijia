<?php
	Yii::app()->clientScript->registerCoreScript('jquery');
	$cs = Yii::app()->clientScript;
	$cs->registerCssFile(Yii::app()->theme->baseUrl . '/css/deal.css');
?>


<div class="form measure-Form">

    <?php
        $form=$this->beginWidget('CActiveForm', array(
            'id'=>'measureForm',
            'enableAjaxValidation'=>false,
            'enableClientValidation'=>true,
        ));
    ?>
	<div>
		<span class="measure-title measure-title-font">预约服务</span>
		<span class="content">
			(请正确填写您的预约测量信息，带<span class="required">*</span>为必填项)
		</span>
	</div>


    <div class="measure-box  measure-box-title measure-item-font container_24">申请预约服务</div>
	<div id = "error-category" class="address-error-message"></div>
    <div class="form-horizontal" style="margin: 30px 0 40px 0">
	    <div class="measure_form_margin">
	    <!--手机号码-->
	    <div class="form-group">
		    <?php echo $form->LabelEx($model,'client_mobile',array(
			    'class'=>'col-xs-2 control-label measure-label'
		    )); ?>
		    <div class="col-xs-7 measure-text">
			    <?php echo $form->textField($model, 'client_mobile', array(
				    'data-id' => 'client_mobile', 'size'=>45,'maxlength'=>45,'class'=>'form-control',
				    'style'=>'text-align:left center;line-height:32px',
			    )); ?>
		    </div>
		    <?php echo $form->error($model, 'client_mobile'); ?>
	    </div>

        <!--联系人-->
        <div class="form-group">
            <?php echo $form->LabelEx($model,'client_name',array('class'=>'col-xs-2 control-label measure-label')); ?>
            <div class="col-xs-7  measure-text">
                <?php echo $form->textField($model,'client_name',array(
	                'data-id' => 'client_name','size'=>45,'maxlength'=>45,'class'=>'form-control',
	                'style'=>'text-align:left center;line-height:32px',
                )); ?>
            </div>
            <?php echo $form->error($model, 'client_name'); ?>
        </div>

        <div class="form-group" style="height:40px; overflow:hidden;border:1px solid white">
            <label class="col-xs-2 control-label measure-label" >所在地区<span class="required">*</span></label>
            <?php
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
//	            $state_data=  Area::model()->findAll("grade=:grade",
//                array(":grade"=>1));
            $state=CHtml::listData($state_data,"area_id","name");
            $s_default = $model->isNewRecord ? '' : $model->client_state;
            echo CHtml::dropDownList('client_state', $s_default, $state,
                    array(
	                    'class'=>'selected-form',
	                    'data-id' => 'client_state',
                        'empty'=>'请选择省份',
                        'ajax' => array(
                            'type'=>'GET', //request type
                            'url'=>CController::createUrl('/member/Delivery_address/DynamicStoreCities'), //url to call
                            'update'=>'#client_city', //selector to update
                            'data'   => 'js:"AddressResult_state="+jQuery(this).val()',

                        ),
                         'style'=>'font-size:14px',
                    ));

            //empty since it will be filled by the other dropdown
            $c_default = $model->isNewRecord ? '' : $model->client_city;
            if(!$model->isNewRecord){
                $city_data=Area::model()->findAll(
	                "parent_id=:parent_id",
                    array(":parent_id"=>$model->client_state));
	            $city=CHtml::listData($city_data,"area_id","name");
            }

	            $city_update = $model->isNewRecord ? array() : $city;
            echo '&nbsp;&nbsp;'.CHtml::dropDownList('client_city', $c_default, $city_update,
                    array(
	                    'class'=>'selected-form',
                        'data-id' => 'client_city',
                        'empty'=>'请选择城市',
                        'ajax' => array(
                            'type'=>'GET', //request type
                            'url'=>CController::createUrl('/member/Delivery_address/DynamicStoreDistrict'), //url to call
                            'update'=>'#client_district', //selector to update
                            'data'  => 'js:"AddressResult_city="+jQuery(this).val()',
                        ),
                        'style'=>'font-size:14px',
                    ));
//            $d_default = $model->isNewRecord ? '' : $model->client_district;
//            if(!$model->isNewRecord){
//                $district_data=Area::model()->findAll("parent_id=:parent_id",
//                    array(":parent_id"=>$model->client_city));
//                $district=CHtml::listData($district_data,"area_id","name");
//            }
//            $district_update = $model->isNewRecord ? array() : $district;
//            echo '&nbsp;&nbsp;'.CHtml::dropDownList('client_district', $d_default, $district_update,
//                    array(
//	                    'class'=>'selected-form',
//	                    'data-id' => 'client_district',
//                        'empty'=>'请选择地区',
//                    )
//                );
//            ?>
        </div>
<!--        <!--收货地址邮编-->
<!--        <div class="form-group">-->
<!--            --><?php //echo $form->Label($model,'client_zip',array('class'=>'col-xs-2 control-label  measure-label')); ?>
<!--            <div class="col-xs-7 measure-text">-->
<!--                --><?php //echo $form->textField($model, 'client_zip', array('data-id' => 'client_zip', 'size'=>45,'maxlength'=>45,'class'=>'form-control')); ?>
<!--            </div>-->
<!--        </div>-->
        <!--详细收货地址-->
        <div class="form-group">
            <?php echo $form->LabelEx($model,'client_address',array('class'=>'col-xs-2 control-label measure-label')); ?>
            <div class="col-xs-7 measure-text">
                <?php echo $form->textField($model, 'client_address',
	                array('data-id' => 'client_address',
	                      'size'=>45,'maxlength'=>45,
	                      'class'=>'form-control',
	                      'style'=>'text-align:left center;line-height:32px',)); ?>
            </div>
            <?php echo $form->error($model, 'client_address'); ?>
        </div>

        <div class="form-group">
            <?php echo $form->LabelEx($model,'client_phone',array('class'=>'col-xs-2 control-label measure-label')); ?>
            <div class="col-xs-7 measure-text">
                <?php echo $form->textField($model, 'client_phone', array(
	                'data-id' => 'client_phone',
	                'size'=>45,
	                'maxlength'=>45,
	                'class'=>'form-control',
	                'style'=>'text-align:left center;line-height:32px',)); ?>
            </div>
        </div>
		</div>
    </div>
<!--	预约类别-->
	<div class="measure-box container_24"><span class="measure-item-font">预约类别</span></div>

	<div class="category-error-message" id="error-category"></div>
    <div class="measure_form_margin">

	<div class="category-item">
		<?php
			/*
			$criteria = new CDbCriteria();
			$criteria->select = 'category_id';
			$criteria->distinct = true;
			$categorys = Item::model()->findAll($criteria);
			$categorys1 = array();
			foreach($categorys as $category1){
				$categorys1[] = $category1->category_id;
			}
			$criteria = new CDbCriteria();
			$criteria->addInCondition('category_id', $categorys1);
			$category_data=  Category::model()->findAll($criteria);*/
//			dump($category_data);
  		    $category_data = Category::model()->findAll('level=:level and root=:root and is_now_cat=:is_now_cat',array(':level'=>3, ':root'=>3, ':is_now_cat'=>1));
			$category = CHtml::listData($category_data, "category_id", "name");
			echo '<div id="review" class="review_1">';
			echo '<ul style="padding-left: 0px">';
			$i = 0 ;
			foreach ($category as $key=>$categorys){
				echo '<a class="review" href="javascript:void(0)" id="review'.$i.'" value="'.$key.'">'.$categorys.'</a>';
				$i++;
			}
			echo '</ul>';
			echo '</div>';
//			echo CHtml::dropDownList('category_item_ids', '',$category, array('class'=>'measure-select','prompt'=>'请选择服务类型'));
//			echo CHtml::button('添加', array('class'=>'measure-button', 'id'=>'measure-add'));?>
</div>
<!--	<div id="review" class="review_1"></div>-->

	<div class="whole-consult">预算：<?php echo $form->textField($model, 'total', array(
			'data-id' => 'total', 'size'=>10,'maxlength'=>10,
			'style'=>'height:34px;line-height:34px;margin-left:16px;margin-right:16px;')); ?>元</div>
	    </div>

<!--	预约时间-->
    <div class="measure-box measure-item-font container_24">预约时间</div>
    <div class="measure_form_margin">
	  <div style="margin: 30px 0 40px 0">
	    <div style="float: left; font-size: 14px"><span class="measure-time-label">服务日期：</span></div>
		  <?php
			  /*  $this->widget(
				  'ext.yiiwheels.widgets.datetimepicker.WhDateTimePicker',
				  array(
					  'id' => 'measure-time',
					  'name' => 'measureTime',
					  'pluginOptions' => array(
						  'format' => 'YYYY-MM-DD',
						  'language' => 'zh-CN',
					  )
				  )); */
				 $this->widget('zii.widgets.jui.CJuiDatePicker',array(
					'model'=>$model,
					'id' => 'measure-time',
					'name' => 'measureTime',
					'language'=>'zh_cn',
					'options'=>array(
					//	'showAnim'=>'fold',
					//	'showOn'=>'both',
					 //   'buttonImage'=>Yii::app()->request->baseUrl.'/images/calender.png',
					 //	'buttonImageOnly'=>true,
						'minDate'=>'new Date()',
						'dateFormat'=>'yy-mm-dd',
					),
					'htmlOptions'=>array(
					 'style'=>'height:27px;margin-left:0px;float:left;height:34px;font-size:14px',
					 'autocomplete'=>'off',
//					 'disabled'=>"disabled" ,
					 'readonly'=>"true"
					),
				));
		  ?>

<div style="float: left; font-size: 14px;"><span class="measure-time-label2" style="line-height: 34px;margin-left:40px;">时间段：</span></div>
	<select class="select-small" id="cc_start_time" style="padding-left: 3px;margin-left: 18px;font-size: 14px;height:34px;">
		<option value="09:00--12:00">09:00--12:00</option>
		<option value="12:00--16:00">12:00--16:00</option>
		<option value="16:00--20:00">16:00--20:00</option>
	</select>
 </div>
</div>





<!--客户留言框-->
<div class="measure-box measure-item-font container_24">给多彩饰家留言</div>
<div class="measure_form_margin">
<div >
<?php echo CHtml::textArea('client_memo','', array('data-id' => 'client_memo', 'class'=>'measure-box-content', 'rows' => '5', 'cols' => '60', 'placeholder' => '选填项，您可以留下补充说明...')) ?>
</div>
</div>

<!--确认订单按钮-->
<div class="order-confirm" style="margin: 0 auto;width: 1180px">
<?php echo CHtml::button('确定', array('data-id' => 'client_button', 'id' => 'confirmMeasure', 'class'=>"measure-confirm-button measure-item-font")) ?>
</div>
<div id="user_idd"  value="<?php if(isset(Yii::app()->user->user_id) ){echo  Yii::app()->user->user_id ;} ?>">
</div>

<?php $this->endWidget(); ?>

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
					<a href="<?php echo Yii::app()->createUrl('user/registration'); ?>" class="link" target="_blank"><u>免费注册</u></a>
<!--							<a href="javascript:void" class="link buy-without-login"-->
<!--							   id="buy-without-login"><u>免登陆直接购买</u></a>-->
				</div>
			</div>
		</form>
	</div>
</div>
</div>


</div>

<script type="text/javascript">
$(document).ready(function(){
var category_item_id = [];
var item_data = [];
var price_consult = 0;
var i=0;

$("#measure-add").click(function(){
	$(".category-error-message").html("");
	var category = $("#category_item_ids").val();
	if(category!="" && $.inArray(category, category_item_id) == -1){
//		category_item_id.push(category);
		$.post("/order/getMeasureItem", {id:category}, function(item){
			item_data.push(item);
			var html =
				'<div class="review-image">' +
				'<a href="javascript:back(0)" >' +
				'<img id="delete-pic" src="/images/delete.png" >' +
				'</a>' +
				'</div>'+
				'<div class="review-title">'+ item.name + '</div>'+
//						'<div class="review-pic">' +
//						'<img src="'+ item.pic + '" />' +
//						'</div>'+
//						'<div class="review-content">'+ item.desc + '</div>'+
//						'<div class="review-clear"></div>'+
//						'<hr>'+
//						'<div class="area">'+
//						'<input class="review-area" type="text" value="" name="area" id="area">'+
//						'</div>'+
//						'<div class="M-form">M<sup>2</sup></div>'+
//						'<div class="format-error-message"><span ></span></div>'+
//						'<div style="clear:both"></div>'+
//						'<textarea class="review-comment" rows="5" placeholder="选填项，您可以留下补充说明..." name="comment" id="comment"></textarea>'+
//						'<div class="measure-title-font item-consult">总计：' +
//						'<span class="measure-box-price">￥0.00元</span>' +
						'</div>';

					$('#review').append('<div class="review" id="review'+i+'">' + html + '</div>');

					i++;
				}, 'json');
			}
		});

		$('.review').click(function(){
			$(this).toggleClass('catlog_color');
		});

		$('#review').delegate('.review-area', 'keyup', function(){
			var $this = $(this);
			var reg=/^\d+(.\d+)?$/;
			if(reg.test($this.val()) || $this.val() == ""){
				$(".format-error-message span").text("");
				var item_i = parseInt($this.parents(".review").attr("id").match(/\d+/));
				var item = item_data[item_i];
				var price = $this.val()*item.price;
				price_consult = 0;
				$this.parent().siblings('.item-consult').children('.measure-box-price').text("￥"+price+".00元");
				$(".measure-box-price").each(function(){
								var area = $(this).text();
								var price_string = area.match(/\d+/);
								price_consult += parseInt(price_string);
							});
				$(".sum-price").text("￥"+price_consult+".00元");
			}else{
				$(".format-error-message span").text("格式不正确");
				$this.parent().siblings('.item-consult').children('.measure-box-price').text("￥0.00元");
				$(".sum-price").text("￥0.00元");
			}
		});

		$('#review').delegate('#delete-pic', 'click', function(){
			var $this = $(this);
			var item_i = parseInt($this.parents(".review").attr("id").match(/\d+/));
			$this.parents('.review').remove();
			category_item_id.splice(item_i,1);
			price_consult = 0;
			$(".measure-box-price").each(function(){
				var area = $(this).text();
				var price_string = area.match(/\d+/);
				price_consult += parseInt(price_string);
			});
			$(".sum-price").text("￥"+price_consult+".00元");
		});


$("#confirmMeasure").click(function () {

	var data = {};
	var rst_area = [];
	var rst_memo = [];
//	        console.log(category_item_id);
	$("input").each(function(){
		data[$(this).attr("data-id")] = $(this).val();
	});

	$(".review-area").each(function(){
		var reg=/^\d+(.\d+)?$/;
		if(reg.test($(this).val()) || $(this).val() == "")
			rst_area.push($(this).val());
		else
			rst_area.push("");
	});

	$(".review textArea").each(function(){
		rst_memo.push($(this).val());
	});

	$("select").each(function(){
		data[$(this).attr("data-id")] = $(this).val();
	});

	category_item_id = [];
	$(".catlog_color").each(function(){
		var catlog = $(this).attr('value');
		category_item_id.push(catlog);
	});

		    var memo_time = $("#measure-time").val();
			var period_time = $('#cc_start_time').val();
		    var measureBoxContent = $(".measure-box-content").val();

	data[$(".measure-box-content").data('id')] = measureBoxContent;

	if (memo_time) {
	   data[$(".measure-box-content").data('id')] = data[$(".measure-box-content").data('id')] + '; 服务日期： ' + memo_time+ '; 时间段: ' + period_time;
	}

	data['rst_area'] = rst_area;
	data['rst_memo'] = rst_memo;
	data['update_time'] = memo_time;
	data['user_id']  = $("#user_idd").attr("value");

	if(category_item_id.length > 0){
		data['category_item_ids'] = category_item_id;

<!--			    $.post("--><?php //echo Yii::app()->createUrl('user/user/isLogin'); ?><!--", function (response) {-->
<!--				    if (response.status == 'login') {-->
//				$.post("/orderApi/createMeasure", data, function(data){
		        $.post("/order/createMeasure", data, function(data){
					if(data.success)
						window.location.href = "/order/measureSuccess";
					else{
						var msg = data.msg.split(":")[1];
						$(".address-error-message").html('<p>'+msg+'</p>');
					}
				},"json");
//				    } else {
//					    $('#myModal').modal('show');
//				    }
//			    }, 'json');
//		        $.post("/orderApi/createMeasure", data, function(data){
//			        if(data.success)
//				        window.location.href = "/order/success";
//			        else{
//				        var msg = data.msg.split(":")[1];
//				        $(".address-error-message").html('<p>'+msg+'</p>');
//			        }
//		        },"json");
		//登陆
		$("#log-btn-div").click(function () {
			carLogin ();
		});

		function carLogin () {
			var data_login = {
				username: $("#user").val(),
				password: $("#password").val()
			};

			$.post("../user/login/llogin", data_login, function (response) {
				if (response.status == 'login') {
					$('#myModal').modal('hide');
					$.post('/cart/addToUser', {position: 1}, function (json) {
					   if (json.status == 'success') {
//						$.post("/orderApi/createMeasure", data, function(data){ //原来测量单创建是在接口中，现在迁移出去到order里
						$.post("/order/createMeasure", data, function(data){
								if(data.success)
									window.location.href = "/order/success";
								else{
									var msg = data.msg.split(":")[1];
									$(".address-error-message").html('<p>'+msg+'</p>');
								}
							},"json");
						} else {
							alert("登录失败！请重新登陆")
						}
					}, 'json');
				} else {
					alert("用户名或密码不对！");
				}
			}, 'json');
		}
	}
	else
		$(".category-error-message").html("<p>请选择预约类别</p>");
});

});

</script>



