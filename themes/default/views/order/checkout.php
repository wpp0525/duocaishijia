<?php
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/cart/core.css');
//Yii::app()->clientScript->registerCssFile(Yii::app()->theme->baseUrl . '/css/cart/box.css');
$this->breadcrumbs = array(
    '服务车' => array('/cart'),
    '确认订单'
);
//Yii::app()->clientScript->registerCoreScript('jquery');
?>
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl.'/css/cart/core.css' ?>">
<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->theme->baseUrl.'/css/cart/box.css' ?>">
<script>
    $(function(){
        var flag = 0;
        $("#confirmOrder").click(function () {
            if ($(this).hasClass('btn1')) {
                $("#orderForm").submit();
            }
        });
        $('.delivery-address').change(function(){
             if($(this).val()){
                 if($('[name="payment_method_id"]').prop('checked')){
                     $('#confirmOrder').removeClass("btn");
                     $('#confirmOrder').addClass("btn1");
                 }
             }else{
                 $('#confirmOrder').removeClass("btn1");
                 $('#confirmOrder').addClass("btn");
             }
        })
        $('[name="payment_method_id"]').change(function(){
            if($(this).val()){
                if($('.delivery-address').prop('checked')){
                    $('#confirmOrder').removeClass("btn");
                    $('#confirmOrder').addClass("btn1");
                }
            }else{
                $('#confirmOrder').removeClass("btn1");
                $('#confirmOrder').addClass("btn");
            }
        })
    });

</script>
    <div style="margin-top:10px"></div>

<?php if (Yii::app()->user->id) { ?>
    <div class="box address-panel" style="border: 1px solid #D6F5CF;">
        <div class="box-title container_24"><span
                style="float:right"><?php echo CHtml::link('管理服务地址', array('/member/delivery_address/admin'),array('target'=>'_blank')) ?></span>服务地址
        </div>
        <div class="box-content">
            <?php
            $cri = new CDbCriteria(array(
                'condition' => 'user_id = ' . Yii::app()->user->id
            ));
            $AddressResult = AddressResult::model()->findAll($cri);
	        echo '<ul id="address_list" style="padding-left: 0px" >';
            if ($AddressResult) {

                foreach ($AddressResult as $address) {
                    $default_address = $address->is_default == 1 ? 'default_address' : '';
                    echo '<li class=' . $default_address . '>' . CHtml::radioButton('delivery_address', $address->is_default == 1 ? TRUE : FALSE, array('value' => $address->contact_id, 'class' => 'delivery-address', 'id' => 'delivery_address' . $address->contact_id));
                    echo CHtml::tag('span', array(
                            'class' => 'buyer-address shop_selection',
                            'style'=>'margin-left:5px',
                        ),
                        $address->s->name . '&nbsp;' . $address->c->name . '&nbsp;' . $address->d->name . '&nbsp;' . $address->address . '&nbsp;(' . $address->contact_name . '&nbsp;收)&nbsp;' . $address->mobile_phone);

                    echo '</li>';

                }

            }
	            echo '</ul>';
            ?>
            <div style="margin-top: 15px;margin-bottom:20px;padding-left:0px">
                <a class="btn btn-primary" data-toggle="modal" data-target ='#addressModal' data-backdrop = "true"> 添加新服务地址</a>
            </div>
        </div>
    </div>

<?php } else {
    echo CHtml::beginForm(array('/order/create'), 'POST', array('id' => 'orderForm')) ;
    ?>
    <div class="box">
        <div class="box-title">服务地址：</div>
        <div class="box-content">
            <?php $model = new AddressResult;
            ?>

            <div style="width:600px">

                <p class="note">带<span class="required">*</span>必须填写！</p>
<!--                <form role="form" class="form-horizontal"id="addr-form">-->
                    <div class="form-group">
                        <label for="AddressResult_contact_name" class="col-xs-2 control-label">联系人：<span class="required">*</span></label>
                        <div class="col-xs-10">
                        <input class="form-control" name="AddressResult[contact_name]" id="AddressResult_contact_name"
                               type="text"/>
                        </div>
                    </div>
                    <div class="form-group" style="height:40px; overflow:hidden;border:1px solid white">
                        <lable class="col-xs-2 control-lable" style="width:120px;padding-top:10px;"><b>所在地区：</b><span class="required">*</span></lable>
                               <div style="width:480px;">
                            <div class="row" data-url="<?php echo Yii::app()->createUrl('order/getChildAreas'); ?>">
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
                                            )));
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
                                                'url' => CController::createUrl('/member/Delivery_address/DynamicDistrict'), //url to call
                                                'update' => '#AddressResult_district', //selector to update
                                                'data' => 'js:"AddressResult_city="+jQuery(this).val()',
                                            )));
                                $d_default = $model->isNewRecord ? '' : $model->district;
                                if (!$model->isNewRecord) {
                                    $district_data = Area::model()->findAll("parent_id=:parent_id",
                                        array(":parent_id" => $model->city));
                                    $district = CHtml::listData($district_data, "id", "name");
                                }
                                $district_update = $model->isNewRecord ? array() : $district;
                                echo '&nbsp;&nbsp;' . CHtml::dropDownList('AddressResult[district]', $d_default, $district_update,
                                        array(
                                            'empty' => '请选择地区',
                                        )
                                    );
                                ?>
                                </div>
                                </div>
                               </div>



<!--                    <div class="form-group" style="height:35px; border:1px solid white">-->
<!--                        <label for="AddressResult_zipcode" class=" col-xs-2 control-label">邮政编号 <span class="required">*</span></label>-->
<!--                         <div class="col-xs-10">-->
<!--                         <input-->
<!--                            name="AddressResult[zipcode]" id="AddressResult_zipcode"class="form-control"  type="text"/>-->
<!--                         </div>-->
<!--                    </div>-->
                    <div class="form-group" style="height:35px; border:1px solid white">
                        <label for="AddressResult_address" class="col-xs-2 control-label">详细地址 <span class="required">*</span></label>
                         <div class="col-xs-10">
                         <input
                            name="AddressResult[address]" id="AddressResult_address" type="text" class="form-control" />
                            </div>
                    </div>

                    <div class="form-group" style="height:35px;border:1px solid white">

                        <label for="AddressResult_mobile_phone" class="col-xs-2 control-label">手机 <span class="required">*</span></label>
                        <div class="col-xs-10">
                        <input  name="AddressResult[mobile_phone]" id="AddressResult_mobile_phone" class="form-control"
                               type="text"/>
                               </div>
                               </div>
                    <div class="form-group" style="height:35px;border:1px solid white">
                        <label for="AddressResult_phone" class="col-xs-2 control-label">电话</label>
                         <div class=" col-xs-10">
                         <input   name="AddressResult[phone]" class="form-control"
                                                                           id="AddressResult_phone" type="text"/></div>
                          </div>
<!--                    <div class="form-group" >-->
<!--                        <label for="AddressResult_memo" class="col-xs-2 control-label">备注</label>-->
<!--                        <div class=" col-xs-10">-->
<!--                        <textarea rows="6" cols="50" name="AddressResult[memo]" class="form-control"-->
<!--                                                                             id="AddressResult_memo"></textarea></div>-->
<!--                         </div>-->


                </div>
<!--         </form>-->
        </div>
    </div>

<?php } ?>
<?php echo
CHtml::beginForm(array('/order/create'), 'POST', array('id' => 'orderForm')) ?>
    <?php echo CHtml::hiddenField('delivery_address','address_value',array('id' => 'hide_address'))?>
    <div class="box" style=" border:1px solid #D6F5CF;">
        <div class="box-title container_24">支付方式</div>
        <div class="box-content" style="vertical-align:middle;">
	        <?php

		        $paymentMethod = PaymentMethod::model()->findAllByAttributes(array(
			        'enabled'           => '1',
			        'payment_method_id' => '1',
		        ));
		        $list = CHtml::listData($paymentMethod, 'payment_method_id', 'name');
		        echo CHtml::radioButtonList('payment_method_id', '1', $list);
	        ?>
        </div>
    </div>

    <?php $imageHelper=new ImageHelper(); ?>
    <div class="box" style=" border:1px solid #D6F5CF;">
        <div class="box-title container_24">服务列表</div>
        <div class="box-content cart container_24" style="padding-left:0px; margin-top: 0px;padding-top:0px;padding-right:0px; ">
            <table id="list-div-box" class="table">
                <tr>
                    <th class="col-xs-3">服务</th>
                    <th class="col-xs-3">名称</th>
                    <th class="col-xs-3">属性</th>
                    <th class="col-xs-1">价格</th>
                    <th class="col-xs-1">数量</th>
                    <th class="col-xs-1">小计</th>
                </tr>
                <?php

                $cart = Yii::app()->cart;
                if (isset($item)) {
                    $item->getId();
                    if($keys==null)
                    {
	                    foreach($item->sku as $sku1){
		                    $sku2[] = $sku1->sku_id;
	                    }
	                    foreach($item->sku as $sku1){
		                    $itemId[] = $sku1->item_id;
	                    }
	                    $sku2=implode(';',$sku2);
	                    $itemId = implode(';',$itemId);
                        echo CHtml::hiddenField('sku_id',$sku2);
                        echo CHtml::hiddenField('item_id',$itemId);
	                    echo CHtml::hiddenField('area',implode(';',$item->getQuantity()));
                        //echo CHtml::hiddenField('area', json_encode($item->getQuantity()));
                    }
                    ?>
                    <tr><?php
                        $itemUrl = Yii::app()->createUrl('item/view', array('id' => $item->item_id));
                        if($item->getMainPic()){
                            $picUrl=$imageHelper->thumb('70','70',$item->getMainPic());
                            $picUrl=Yii::app()->baseUrl.$picUrl;
                        ?>
                        <td>

                            <?php //echo CHtml::image($picUrl, $item->title, array('width' => '80px', 'height' => '80px'));
                                echo '<a href='.$itemUrl.'><img src="' .$picUrl . '" width="70px" height="70px"></a>';

                            } else {
                                echo Yii::t('leather','该服务没有上传图片');
                            } ?></td>
                        <td><?php echo '<a href='.$itemUrl.'>'.$item->title.'</a>' ?></td>
                        <td><?php foreach($item->sku as $sku1){
		                        echo  implode(',', json_decode($sku1->props_name, true));
		                        echo '<br/>' ;
	                        }
//		                    echo  empty($item->sku) ? '' : implode(',', json_decode($item->sku[0]->props_name, true));
	                        ?></td>
	                    <?php
		                    $area = array();
		                    $areall = $item->getQuantity();
		                    foreach($areall as $arekey=>$area1){
								$area[]= floatval($area1);
		                    }
		                    //var_dump($area);die;
	                    ?>
                        <td><?php foreach($item->getPrice() as $prices) {
		                        echo $prices.'<br/>';;
	                        } ?></td>
<!--                        <td>--><?php //echo $item->getQuantity(); ?><!--</td>-->
	                    <td><?php for($i =0 ;$i<count($area);$i++){
			                    echo $area[$i].'<br/>';
		                    }?></td>
                        <td><?php echo $item->getSumPrice() ?>元</td>
                        <?php $price += $item->getSumPrice() ?>
                    </tr>
                <?php  echo CHtml::hiddenField('total_fee', $price );
                } else {

                    $items = $cart->getPositions();
                    if (empty($items)) {
                        ?>
                        <tr>
                            <td colspan="6" style="padding:10px">您的服务车是空的!</td>
                        </tr>
                    <?php
                    } else {

	                    $defaultItemId = $price = 0;

                        foreach ($keys as $key) {
                            if (!isset($items[$key])) continue;

                            $item = $items[$key];


	                        $itemId = $item->item_id;

	                        $propnuber=count($item->cartProps);

	                        if ($itemId != $defaultItemId || $propnuber<=$i) {
		                        $i = 0;
		                        $defaultItemId = $itemId;
	                        }
	                        $keys1 = $item->getId();


	                        $keyId = $keys1[$i];

	                        while($keyId!=$key && $i<count($keys1)){
		                        $i++;
		                        $keyId = $keys1[$i];
	                        }

                            echo CHtml::hiddenField('keys[]', $key);
                            ?>
                            <tr><?php
                                ?>
                                <td><?php
                                    $itemUrl = Yii::app()->createUrl('item/view', array('id' => $item->item_id));
	                                if ($item->getMainPic()) {
		                                $picUrl = $imageHelper->thumb('70', '70', $item->getMainPic());
		                                $picUrl = Yii::app()->baseUrl.$picUrl;
	                                } else {
		                                $picUrl = $item->getHolderJs('70', '70');
	                                }
                                    echo '<a href='.$itemUrl.'><img src="' .$picUrl . '" width="80px" height="80px"></a>';
                                    //echo CHtml::image($picUrl, $item->title, array('width' => '80px', 'height' => '80px')); ?></td>
                                <td><?php echo '<a href='.$itemUrl.'>'.$item->title.'</a>' ?></td>
                                <!--<?php echo $item->title; ?></td>-->
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
	                                <?php
		                                $itemPrice = $item->getPrice();
		                                echo $itemPrice[$item->cartProps[$i]];
	                                ?>
                                </td>
                                <td><?php echo $item->getQuantity($key); ?></td>
                                <td><?php echo $item->getSumPrice(true, $item->cartProps[$i],$key) ?>元</td>
                                <?php
	                                $price += $item->getSumPrice(true, $item->cartProps[$i],$key);
	                                $i++;
                                ?>
                            </tr>
                        <?php
                        }
                    }
                }?>
                <tr>
                    <td colspan="6" style="padding:10px;text-align:right">总计：<?php echo $price; ?> 元</td>
	                <?php  echo CHtml::hiddenField('total_fee', $price );?>
                </tr>
            </table>

	        </div>

        </div>

		<div class="box" style=" border:1px solid #D6F5CF;">
			<div class="box-title container_24">付款方式</div>
			<div class="box-content" style="vertical-align:middle;">
				<?php
				//	echo CHtml::checkBoxList('payment_free_id', '', array('1' => '预付款'), array('style' => 'vertical-align: middle;margin-right: 3px;'));
				//	$free = $price * 0.1;
				//	echo CHtml::textField('pay_first', $free, array('disabled' => 'true','style' => 'height:34px;margin-left: 8px '));
				?>
			</div>

			<?php
				$model2 = new Area();
				$model2->setAttribute('language','Q');
				$sex_radiobuttonList = CHtml::activeRadioButtonList($model2,"language",
					array("Y"=>"预付款  100元","Q"=>"全款","D"=>"先下单后付款"),
					array("template"=>'<li style="display:inline-block;width:1080px;padding-left: 20px;padding-top: 4px">{input}{label}</li>',"separator"=>" ")
				);
				  $sex_radiobuttonList= str_replace("<label", "<span style=margin-left:5px", $sex_radiobuttonList);
				  $sex_radiobuttonList= str_replace("</label", "</span", $sex_radiobuttonList);
				echo $sex_radiobuttonList;
			?>
		</div>


		<div class="box" style=" border:1px solid #D6F5CF;">
			<div class="box-title container_24">预约上门测量/服务时间</div>

			<div class="box-content_1" style="vertical-align:middle;">
				<?php
					//echo CHtml::checkBoxList('measure_date', '', array('1' => '日期：'), array('style' => 'vertical-align: middle;margin-right: 3px;float:left;'));
					echo '<span id="measure_date">
						   <label for="measure_date_0">日期：</label>
							</span>';
				?>
<!--				--><?php	//$this->widget(
//						'ext.yiiwheels.widgets.datetimepicker.WhDateTimePicker',
//						array(
//							'id' => 'measure-time',
//							'name' => 'measureTime',
//						)
//					); 	?

				$this->widget('zii.widgets.jui.CJuiDatePicker',array(
					        'model'=>$model,
				      	    'id' => 'measure-time',
							'name' => 'measureTime',
							'language'=>'zh_cn',
							'options'=>array(
								'showAnim'=>'fold',
								'showOn'=>'both',
								'buttonImage'=>Yii::app()->request->baseUrl.'/images/calender.png',
								'buttonImageOnly'=>true,
								'minDate'=>'new Date()',
								'dateFormat'=>'yy-mm-dd',
						     ),
							 'htmlOptions'=>array(
							 'style'=>'height:34px;float:left;margin-left:25px',
							 'autocomplete'=>'off',
//							 'disabled'=>"disabled" ,
							 'readonly'=>"true"
							),
				));
			  ?>

                <div style="float: left; font-size: 14px;"><span class="measure-time-label2" style="line-height: 34px;margin-left:40px;">时间段：</span></div>
                <select class="select-small" name="cc_start_time" id="cc_start_time" style="padding-left: 3px;margin-left: 18px;font-size: 12px;height:34px;">
                    <option value="09:00--12:00">09:00--12:00</option>
                    <option value="12:00--16:00">12:00--16:00</option>
                    <option value="16:00--20:00">16:00--20:00</option>
                </select>
		   </div>
		</div>

        <div class="box-content" style="padding:0px" >
            <div class="memo" style="float:left"><h3>
                    给多彩饰家留言：</h3>
                    <textarea id="memo" name="memo" placeholder="您可以告诉多彩饰家您对服务的特殊要求，如：颜色、房间等" rows="5"></textarea>
            </div>
        </div>
            <!--跳转到付款的界面-->
            <div>
	            <button id="checkout" class="btn btn-danger pull-left" style="line-height:20px;margin-top:30px;margin-right:150px;margin-bottom:60px;background-color: #49a01d;width:120px" href="">确定</button>
            </div>

    </div>
<?php echo CHtml::endForm() ?>

<div class="modal fade" id="addressModal" tabindex="-1" role="dialog" aria-labelledby="addressModalLabel"  aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">添加地址</h4>
            </div>
            <div class="modal-body">
                <div class="form">
                    <?php
                    $model = new AddressResult;
                    $form=$this->beginWidget('CActiveForm', array(
                        'id'=>'address-form',
                        'enableClientValidation'=>true,
                    ));
                    ?>

                    <p class="note">带<span class="required"> * </span>的必须填写。</p>
                    <div id="error-div" class="text-center" style="color:red">

                    </div>
                    <div class="form-horizontal">
                        <div class="form-group">
                            <?php echo $form->labelEx($model,'contact_name',array('class'=>'col-xs-2 control-label')); ?>
                            <div class="col-xs-7">
                                <?php echo $form->textField($model,'contact_name',array('size'=>45,'maxlength'=>45,'class'=>'form-control')); ?>
                            </div>
                            <?php echo $form->error($model,'contact_name'); ?>
                        </div>

                        <div class="form-group" style="height:40px; overflow:hidden;border:1px solid white;margin-top:-15px" >
                            <label class="col-xs-2 control-label" style="width:120px;padding-top:10px;margin-left: 45px;margin-right: 20px;"><b>所在地区</b><span class="required">*</span></label>

                                <div class="row" data-url="<?php echo Yii::app()->createUrl('order/getChildAreas'); ?>">
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
//                                    $state_data = Area::model()->findAll("grade=:grade",
//                                        array(":grade" => 1));
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
                                                )));
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
                                                    'url' => CController::createUrl('/member/Delivery_address/DynamicDistrict'), //url to call
                                                    'update' => '#AddressResult_district', //selector to update
                                                    'data' => 'js:"AddressResult_city="+jQuery(this).val()',
                                                )));
                                    $d_default = $model->isNewRecord ? '' : $model->district;
                                    if (!$model->isNewRecord) {
                                        $district_data = Area::model()->findAll("parent_id=:parent_id",
                                            array(":parent_id" => $model->city));
                                        $district = CHtml::listData($district_data, "id", "name");
                                    }
                                    $district_update = $model->isNewRecord ? array() : $district;
                                    echo '&nbsp;&nbsp;' . CHtml::dropDownList('AddressResult[district]', $d_default, $district_update,
                                            array(
                                                'empty' => '请选择地区',
                                            )
                                        );
                                    ?>
                                </div>

                        </div>

<!--                        <div class="form-group">-->
<!--                            --><?php //echo $form->labelEx($model,'zipcode',array('class'=>'col-xs-2 control-label pull-left')); ?>
<!--                            <div class="col-xs-7">-->
<!--                                --><?php //echo $form->textField($model,'zipcode',array('size'=>45,'maxlength'=>45,'class'=>'form-control')); ?>
<!--                            </div>-->
<!--                            --><?php //echo $form->error($model,'zipcode'
//                            ); ?>
<!---->
<!--                        </div>-->

                        <div class="form-group">
                            <?php echo $form->labelEx($model,'address',array('class'=>'col-xs-2 control-label')); ?>
                            <div class="col-xs-7">
                                <?php echo $form->textField($model,'address',array('size'=>45,'maxlength'=>45,'class'=>'form-control')); ?>
                            </div>
                            <?php echo $form->error($model,'address'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model,'mobile_phone',array('class'=>'col-xs-2 control-label')); ?>
                            <div class="col-xs-7">
                                <?php echo $form->textField($model,'mobile_phone',array('size'=>45,'maxlength'=>45,'class'=>'form-control')); ?>
                            </div>
                            <?php echo $form->error($model,'mobile_phone'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model,'phone',array('class'=>'col-xs-2 control-label')); ?>
                            <div class="col-xs-7">
                                <?php echo $form->textField($model,'phone',array('size'=>45,'maxlength'=>45,'class'=>'form-control')); ?>
                            </div>
                            <?php echo $form->error($model,'phone'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model,'is_default',array('class'=>'col-xs-2 control-label')); ?>
                            <div class="col-xs-7">
                                <?php echo $form->dropDownlist($model,'is_default', array('0'=>'否', '1'=>'是'),array('class'=>'form-control')); ?>
                            </div>
                            <?php echo $form->error($model,'is_default'); ?>
                        </div>

                        <div class="form-group">
                            <?php echo $form->labelEx($model,'memo',array('class'=>'col-xs-2 control-label')); ?>
                            <div class="col-xs-7">
                                <?php echo $form->textArea($model,'memo',array('rows'=>6, 'cols'=>50,'class'=>'form-control')); ?>
                            </div>
                            <?php echo $form->error($model,'memo'); ?>
                        </div>


                        <div class="row buttons " style="margin-left: 35px;">
                            <?php echo
                            CHtml::ajaxSubmitButton( '创建', CController::createUrl('addAddressByAjax'),array(
                                'type' => 'POST',
                                'success' => 'function(data){
                                    if(data.indexOf("success") != 0)
                                    {
                                        $("#error-div").html(data)
                                    }
                                    else
                                    {
                                       var addressArray = data.split("@");
                                       data = addressArray[2];
                                       $("#address_list").append(data);
                                       $("#addressModal").on("hidden.bs.modal", function(){
                                             $(this).removeData("bs.modal");
                                        })
                                       $("#addressModal").modal("hide");
                                       data = addressArray[1];
                                       $("#hide_address").val(data);
                                    }
                                }'
                            ),array('class'=>'btn btn-success')); ?>
                        </div>
                        <?php $this->endWidget(); ?>

                    </div>
                </div><!-- form -->
            </div>
        </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
</div><!-- /.modal -->
<script type="text/javascript">
    $(function() {
        if($("input:radio[name='delivery_address']").is(":checked")) {
            $("#hide_address").val($("input:radio[name='delivery_address']:checked").val());
            $("#checkout").removeAttr('disabled');
        } else {
            $('input:radio:first').attr('checked', true);
            $("#hide_address").val($('input:radio:first').val());
        }

//	    $("#measure-time").attr('disabled', true);
    });
    $('#AddressResult_state, #AddressResult_city').change(function() {
        var url = $(this).parent('.row').data('url');
        var select = '';
        if (this.id == 'AddressResult_state') {
            select = '#AddressResult_city';
        } else {
            select = '#AddressResult_district';
        }
        $.get(url,{'parent_id': $(this).val()},function(response){
            var html = '';
            for (var i in response) {
                var option = '<option value="'+i+'">'+response[i]+'</option>';
                html += option;
            }
            $(select).html(html);
        },'json');
    });

    $("#address_list li").click(function(){
        $("#hide_address").val($(this).children('input').val());
    });

	$("#measure_date_0").change(function () {
		var $this = $(this);
		var checked = $this.attr('checked');

		$("#measure-time").attr('disabled', !checked);
	});

    $('[name="Area[language]"]').change(function (){
           //  alert("gaibing");
    }) ;

    $('#payment_free_id_0').change(function () {
	    //获得选择的值
	    var radionum=  $('[name="Area[language]"]') ;
	    for(var i=0;i<radionum.length;i++){
		    if(radionum[i].checked){
			   userid2 = radionum[i].value
			 }
        }
	    alert(userid2);

	    var $this = $(this);
	    var checked = $this.attr('checked');
	    $("#pay_first").attr('disabled', !checked);
    });

    $('#checkout').click(function () {

	    var $payFirst = $("#pay_first");
	    var pay = $payFirst.val();
	    var payment_id = $("#payment_free_id_0").attr("checked");
	    var total = <?php echo $price; ?>;

	    var measure = $('[name="measureTime"]').val();
	    var address = $('#hide_address').val();
	    
	    if(measure==''){
		    showPopup("请填写服务日期！");
		    return false;
	    }
	    if(address == '1'){
		    showPopup("请填写收货地址！");
		    return false;
	    }else if (payment_id && (pay < total * 0.1)) {
		    showPopup("预付款金额不能小于总计10%！");
		    return false;

	    } else if (payment_id && (pay > total)) {
		    showPopup("预付款金额不能大于总计！");
		    return false;
	    }
    });
</script>