<script type="text/javascript" src="<?php echo Yii::app()->theme->baseUrl; ?>/js/jquery.js"></script>

<script type="text/javascript">

		function meas_chg(val){
			if(val=='.cel_title21'){
				$('#ce_button').css("display","block");
				$('.ce_area').css("display","none");
				$('.ce_updarea').css("display","inline");
				$(".ce_delete").each(function(){
					$(this).css("display","inline");
				});
				$(".able_input select").each(function(){
					$(this).css("display","inline");
					$(this).css("float", "left");
				});
				$(".able_input label").each(function(){
					$(this).css("display","none");
				})
			}else{
				$(val).hide();
				$(val+'1').show();
			}
		}

		$(document).ready(function(){
//			$(".ce_delete").click(function(){
//				$(this).parents(".ce_tr").remove();
//			});
			$("tbody").delegate(".ce_delete", "click", function(){
				$(this).parents(".ce_tr").remove();
			})
			$("#add_button").click(function(){
				var tr_pre_html = '<tr class="ce_tr">'+
					'<th class="able_input">'+
					'<div id="category-name-select">'+
					'<select style="display: inline; float: left;" name="category_id" id="category_id">';

				var tr_last_html =          '</select>'+
					'</div>'+
					'<label class="label_class"  style="display: none"> </label>'+
//					'<span class="ce_areamem"> 约： </span>'+
//					'<span class="ce_area ce_area_num" style="display: none"></span>'+
//					'<span class="ce_area" style="display: none">M<sup>2</sup></span>'+
//					'<span class="ce_updarea" style="display: inline"><input type="text" name="rst_area" id="rst_area">M<sup>2</sup></span>'+
					'<span class="ce_delete" style="display: inline"> <img src="/themes/default/image/ce_delete.png">删除</span>'+
					'</th>'+
					'</tr>';

				$.post("/order/getCategory",{}, function(data){
					var select_html = '<option value="" selected="selected">请选择</option>';
					$.each(data,function(index, val){
						select_html += '<option value="'+index+'">'+val+'</option>';
					});
					$('tbody').append(tr_pre_html + select_html + tr_last_html);
				},'json');
			});
			$("#client_button").click(function(){
				$(".able_input select").each(function(){
					$(this).css("display","none");
				});
				$('.ce_updarea').each(function(){
					$(this).css("display","none");
				});
				var index = 0;
				$(".able_input label").each(function(){
					$(this).css("display", "inline-block");
					$(this).text(category_text[index]);
					$(this).parent().children(".ce_area_num").text(rst_areas_text[index]);
					index++;
				});

			});
		});

		function meas_chged(val){
			if(val=='.cel_title21'){
//                $("select").each(function(){
//                    $("select").attr("disabled","disabled");
//                });
				$('#ce_button').css("display","none");
//				$('.ce_updarea').css("display","none");
				$('.ce_area').css("display","inline");
				$(".ce_delete").each(function(){
					$(this).css("display","none");
				});
				$(".able_input select").each(function(){
					$(this).css("display","none");
				});
//	            $(".able_input label").each(function(){
//		            $(this).css("display", "inline");
//	            });
//                var index = $('.able_input').size();
//                for(var i = 1;i<=index;i++){
//                    var v = $(".ce_tr"+i+' .ce_updarea input').val();
//                    $(".ce_tr"+i+' .ce_area_num').text(v);
//                }
				$(".ce_updarea input").each(function(){
					var v = $(this).val();
					$(this).parents(".ce_tr").children(".ce_area_num").text(v);
				});
			}else{
				$(val).show();
				$(val+'1').hide();
			}
		}

		function val_change(type,mea_div){
			var val = $('#client_memo').val();
			var cel_num = $('.cel_num').text();

			//获取地址和联系方式信息
			var client_name = $('#client_name').val();
			var client_address = $('#client_address').val();
			var client_zip = $('#client_zip').val();
			var client_mobile = $('#client_mobile').val();
			var client_phone = $('#client_phone').val();
			var client_state = $('#client_state').val();
			var client_district = $('#client_district').val();
			var client_city = $('#client_city').val();
			//获取时间
			var measure_time = $('#measure_time').val();
			var data = {};
			data['cel_num'] = cel_num;
			switch(type) {
				case "cate":
					var category_ids = '';
					var rst_areas = '';
					rst_areas_text =[];
					category_text = [];
					$(".ce_updarea :input").each(function(){
						if($(this).val()!=''){
							if(rst_areas == ''){
								rst_areas = $(this).val();
							}else{
								rst_areas = $(this).val()+','+rst_areas;
							}
							var text = $(this).val();
							rst_areas_text.push(text);
						}
					});
					$(".able_input select").each(function(){
						if($(this).val()!=''){
							if(category_ids == ''){
								category_ids = $(this).val();
							}else{
								category_ids = $(this).val()+','+category_ids;
							}
							var text = $(this).find("option:selected").text();
							category_text.push(text);
						}
					});
					if(category_ids==''){
						meas_chged('.cel_title21');
						return;
					}
					data['category_ids'] = category_ids;
					data['rst_areas'] = rst_areas;
					data['meas_type'] = 'cate';
					break;
				case "area":
					data['client_phone'] = client_phone;
					data['client_mobile'] = client_mobile;
					data['client_zip'] = client_zip;
					data['client_address'] = client_address;
					data['client_name'] = client_name;
					data['client_state'] = client_state;
					data['client_city'] = client_city;
					data['client_district'] = client_district;
					data['meas_type'] = 'area';
					break;
				case "time":
					data['measure_time'] = measure_time;
					alert(measure_time);
					data['meas_type'] = 'time';
					break;
				case "memo":
					data['client_memo'] = val;
					data['meas_type'] = 'memo';
					break;
				default:
					break;
			}
			$.post('/member/MeasureOrderlist/update', data, function(response) {
				if (response.status) {
//					alert("Save success!");
//                alert(response.measure_id);
					switch(type) {
						case "cate":
							meas_chged(mea_div);
							break;
						case "area":
							data['client_phone'] = client_phone;
							data['client_mobile'] = client_mobile;
							data['client_zip'] = client_zip;
							data['client_address'] = client_address;
							data['client_name'] = client_name;
							data['client_state'] = client_state;
							data['client_city'] = client_city;
							data['client_district'] = client_district;
							$('.client_phone').text(client_phone);
							$('.client_mobile').text(client_mobile);
							$('.client_zip').text(client_zip);
							$('.client_address').text(client_address);
							$('.client_name').text(client_name);
							$('.client_state').text(client_state);
							$('.client_city').text(client_city);
							$('.client_district').text(client_district);
							meas_chged(mea_div);
							break;
						case "time":
							$('.cel_title23 p').text(measure_time);
							meas_chged(mea_div);
							break;
						case "memo":
							$('.cel_memo').text(val);
							meas_chged(mea_div);
							break;
						default:
							break;
					}
				} else {
					alert("Save failed!")
				}
			}, 'json')
//        $.ajax({
//            type: 'POST',
//            url: "/member/MeasureOrderlist/update" ,
//            cache:false,
//            dataType:'json',
//            data: data,
//            success:function(data) {
//                alert(data); //如果成功，就打印相应信息。
//            },
//            error : function() {
//                alert("异常！");
//            }
//        });
		}


</script>


<div class="ce_box">

    <div class="login_tit">
        <a class="current">我的测量单号：</a><span class="cel_num"><?php echo $measureOrders->measure_id ?></span>
    </div>
    <div class="cel_title">
        <div class="cel_title1 measure-box measure-box-title">
            <span class="ce_lei">预约类别</span>
<!--        <span class="ce_leiimg">-->
<!--            <a onclick="meas_chg('.cel_title21')"><img src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/image/profile_edit.png">编辑</a>-->
<!--        </span>-->
        </div>
        <div class="cel_title21">
            <table  class="ce_table">

    <?php
    $measure_id = $measureOrders->measure_id;
    //            dump($measureOrders);
    $measureOrderItems = ArOrderMeasureItem::model()
        ->findAll("measure_id=:measure_id",
            array("measure_id"=>$measure_id));
    $path1 = ','.$measureOrders->client_state.','.$measureOrders->client_city.','.$measureOrders->client_district;
    $path2 = ','.$measureOrders->client_state.','.$measureOrders->client_city;
    $path3 = ','.$measureOrders->client_state;

//    echo '</br>'.$measure.'</br>';
    $area3 = Area::model()->findByAttributes(array('path' => $path3));
//    echo($area3->name);
    $area2 = Area::model()->findByAttributes(array('path' => $path2));
//    echo($area2->name);
    $area1 = Area::model()->findByAttributes(array('path' => $path1));
    //跟踪循环次数
    $index = 0;
    foreach($measureOrderItems as $measure_Order_Item){
        $category_item = Category::model()->findAll("category_id=:category_id",
            array(":category_id"=>$measure_Order_Item->category_item_id));
            $rst_area = $measure_Order_Item -> rst_area;
        foreach($category_item as $category){
            $name = $category->name;
            $category_id = $category -> category_id;
            $index = $index + 1;
        }
    ?>
                <tr class="ce_tr">
<!--                    <th class="dis_input">--><?php //echo $category_id ?><!--</th>-->
<!--                    <th class="dis_input">--><?php //echo CHtml::textField('dis_name',$name); ?><!--</th>-->
                    <th class="able_input">
	                    <div id="category-name-select">
                        <?php
                        $category_data = Category::model()->findAll('level=:level and root =:root',array(':level'=>3,':root'=>3));
                        $category = CHtml::listData($category_data, "category_id", 'name');
                        echo CHtml::dropDownList('category_id', $category_id,$category, array('style'=>"display:none"));
                        ?>
	                    </div>
	                    <label class="label_class"><?php echo $name ?></label>
<!--                        <span class="ce_areamem">约：</span>-->
<!--	                    <span class="ce_area ce-area_num">--><?php //echo $rst_area ?><!--</span>-->
<!--                        <span class="ce_area">M<sup>2</sup></span>-->
<!--                        <span class="ce_updarea">--><?php //echo CHtml::textField('rst_area',$rst_area); ?><!--M<sup>2</sup></span>-->
                        <span class="ce_delete"><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/ce_delete.png"><?php echo 删除 ?></span>
                    </th>
<!--                    <th class="able_input">--><?php //echo CHtml::textField('dis_name',$name,array("disabled"=>"disabled")); ?><!--</th>-->
<!--                    <td class="ce_delete"><img src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/image/ce_delete.png">--><?php //echo 删除 ?><!--</td>-->
                </tr>
    <?php } ?>
            </table>
            <div id="ce_button" style="display: none">
	            <?php echo CHtml::button('添加',
		            array('id'=>'add_button')) ?>
                <?php echo CHtml::button('确定',
                    array('data-id' => 'client_button',
	                    'id'=>'client_button',
	                    'style'=>'margin-left:15px',
                        "onclick" => "val_change('cate','.cel_title21')")) ?>
            </div>
        </div>
    </div>
    <div class="cel_title">
        <div class="cel_title1 measure-box">
            <span class="ce_lei">我的地址和联系方式</span>
<!--            <span class="ce_leiimg">-->
<!--                 <a onclick="meas_chg('.cel_title22')"><img src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/image/profile_edit.png">编辑</a>-->
<!--            </span>-->
        </div>
        <div class="cel_title22">
            <p><?php echo CHtml::activeLabelEx($model, 'client_name').'：'; ?>
                <span class="client_name"><?php  echo($measureOrders->client_name); ?></span>
            </p>
            <p><span>所在地区：</span>
                <span class="client_state"><?php  echo($area3->name); ?></span>
                <span class="client_city"><?php  echo($area2->name); ?></span>

            </p>

            <p><?php echo CHtml::activeLabelEx($model, 'client_address').'：'; ?>
                <span class="client_address">
                    <?php echo $measureOrders->client_address ?>
                </span>
            </p>
            <p><?php echo CHtml::activeLabelEx($model, 'client_mobile').'：'; ?>
                <span class="client_mobile">
                    <?php echo $measureOrders->client_mobile ?>
                </span>
            </p>
            <p><?php echo CHtml::activeLabelEx($model, 'client_phone').'：'; ?>
                <span class="client_phone">
                    <?php echo $measureOrders->client_phone ?>
                </span>
            </p>
        </div>
        <div class="cel_title221">
<!--            详细信息-->
            <div class="form-group">
                <p><?php echo CHtml::activeLabelEx($model, 'client_name'); ?>
                   <?php echo CHtml::textField('client_name',$measureOrders->client_name); ?>
                </p>
            </div>
	        <div class="form-group" style="height:40px; overflow:hidden;border:1px solid white">
		        <label style="float: left; line-height: 34px" >所在地区<span class="required">*</span></label>
		        <div style="margin-left: 80px">
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
//				        $state_data=  Area::model()->findAll("grade=:grade",
//					        array(":grade"=>1));
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
						        )));

				        //empty since it will be filled by the other dropdown
				        $c_default = $model->isNewRecord ? '' : $model->client_city;
				        if(!$model->isNewRecord){
					        $city_data=Area::model()->findAll("parent_id=:parent_id",
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
								        'url'=>CController::createUrl('/member/Delivery_address/dynamicdistrict'), //url to call
								        'update'=>'#client_district', //selector to update
								        'data'  => 'js:"AddressResult_city="+jQuery(this).val()',
							        )));
//				        $d_default = $model->isNewRecord ? '' : $model->client_district;
//				        if(!$model->isNewRecord){
//					        $district_data=Area::model()->findAll("parent_id=:parent_id",
//						        array(":parent_id"=>$model->client_city));
//					        $district=CHtml::listData($district_data,"area_id","name");
//				        }
//				        $district_update = $model->isNewRecord ? array() : $district;
//				        echo '&nbsp;&nbsp;'.CHtml::dropDownList('client_district', $d_default, $district_update,
//						        array(
//							        'class'=>'selected-form',
//							        'data-id' => 'client_district',
//							        'empty'=>'请选择地区',
//						        )
//					        );
			        ?>
		        </div>
	        </div>

	        <div class="form-group">
                <p><?php echo CHtml::activeLabelEx($model, 'client_address'); ?>
                    <?php echo CHtml::textField('client_address',$measureOrders->client_address); ?>
                </p>
            </div>
<!--            <div class="form-group">-->
<!--                <p>--><?php //echo CHtml::activeLabelEx($model, 'client_zip'); ?>
<!--                    --><?php //echo CHtml::textField('client_zip',$measureOrders->client_zip); ?>
<!--                </p>-->
<!--            </div>-->
            <div class="form-group">
                <p><?php echo CHtml::activeLabelEx($model, 'client_mobile'); ?>
                    <?php echo CHtml::textField('client_mobile',$measureOrders->client_mobile); ?>
                </p>
            </div>
            <div class="form-group">
                <p><?php echo CHtml::activeLabelEx($model, 'client_phone'); ?>
                    <?php echo CHtml::textField('client_phone',$measureOrders->client_phone); ?>
                </p>
            </div>

            <?php echo CHtml::button('确定',
                array('data-id' => 'client_button',
                    "onclick" => "val_change('area','.cel_title22')")) ?>
        </div>
    </div>
    <div class="cel_title">
        <div class="cel_title1 measure-box">
            <span class="ce_lei">预约时间</span>
<!--        <span class="ce_leiimg">-->
<!--            <a onclick="meas_chg('.cel_title23')"><img src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/image/profile_edit.png">编辑</a>-->
<!--        </span>-->

        </div>
        <div class="cel_title23">
	        <?php $time = date('Y-m-d h:i', CDateTimeParser::parse($measureOrders->create_time, 'yyyy-MM-dd HH:mm'));?>

            <p>时间：<?php echo date("Y-m-d H:i",strtotime($time)) ?></p>
        </div>
        <div class="cel_title231">

            <p style="float: left">时间</p>
	        <?php
		        $this->widget(
			        'ext.yiiwheels.widgets.datetimepicker.WhDateTimePicker',
			        array(
				        'id' => 'measure-time',
				        'name' => 'measureTime',
			        )
		        );
		    ?>

			<div style="clear: both"></div>
            <?php echo CHtml::button('确定',
                array('data-id' => 'client_button',
	                'values'=>$measureOrders->create_time,
                    "onclick" => "val_change('time','.cel_title23')")) ?>

        </div>
    </div>
    <div class="cel_title">
        <div class="cel_title1 measure-box">
            <span class="ce_lei">我的留言</span>
<!--        <span class="ce_leiimg">-->
<!--            <a  onclick="meas_chg('.cel_title24')"><img src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/image/profile_edit.png">编辑</a>-->
<!--        </span>-->

        </div>
        <div class="cel_title24">
            <p class="cel_memo"><?php echo $measureOrders->memo ?></p>

        </div>
	    <div class="go_back">
		    <?php echo CHtml::button('返回',
			    array('id' => 'go_back','onclick'=>"history.go(-1)")) ?>

	    </div>
        <div class="cel_title241">
                <?php echo CHtml::textArea('client_memo',$measureOrders->memo, array('data-id' => 'client_memo', 'class'=>'measure-box-content', 'rows' => '5', 'cols' => '60', 'placeholder' => '选填项，您可以留下补充说明...')) ?>
            <br/><p></p>
                <?php echo CHtml::button('确定',
            array('data-id' => 'client_button',
                "onclick" => "val_change('memo','.cel_title24')")) ?>

        </div>
    </div>

</div>
