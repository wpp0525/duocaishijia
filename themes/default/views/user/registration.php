
<script type="text/javascript">
	$(document).ready(function(){
		var click = true;
		var i = 0;
		$("#getCode").click(function(){

			if(click){
				var data = {mobile:$("#RegistrationForm_mobile").val()};

				$.post("registration/get", data, function(response){
					if(response.code==200){
						test.init($("#getCode"));
//						$('#errorCode').attr('color','red');
						$('#errorCode').text('信息发送成功，请注意查收短信！');
						$('#code').attr('disabled',false);
					}else if(response.code==400){
						$('#errorCode').text('请填写手机号！');
					}else if(response.code==300){
						$('#errorCode').text('信息发送失败，请重新获取！');
					}
				},'json');
			}
		});

		$("#code").change(function(){
			var data = {mcode : $("#code").val() , mobile:$("#RegistrationForm_mobile").val()};
			$.post("registration/check",data , function(response){
				if(response.code==200){
					$('#errorCode').text('验证码已过期，请重新获取！');
					$('#submit').attr('disabled',true);
				}else if(response.code==400){
					$('#errorCode').text('验证码错误，请重新输入！');
					$('#submit').attr('disabled',true);
				}else{
					$('#errorCode').text('验证码正确');
					$('#submit').attr('disabled',false);
				}
			},'json');
		});
		$("#code").keyup(function(){
			var data = {mcode : $("#code").val() , mobile:$("#RegistrationForm_mobile").val()};
			$.post("registration/check",data , function(response){
				if(response.code==200){
					$('#errorCode').text('验证码已过期，请重新获取！');
					$('#submit').attr('disabled',true);
				}else if(response.code==400){
					$('#errorCode').text('验证码错误，请重新输入！');
					$('#submit').attr('disabled',true);
				}else{
					$('#errorCode').text('验证码正确');
					$('#submit').attr('disabled',false);
				}
			},'json');
		});

		var test = {
			node:null,
			count:90,
			start:function(){
				if(this.count > 0){
					click = false;
					this.node.text(this.count+('秒后可重新获取'));
					this.count--;
					var _this = this;
					setTimeout(function(){
						_this.start();
					},1000);
				}else{
//					this.node.removeAttribute("disabled");
					click = true;
					this.node.text("获取验证码");
					this.count = 90;
				}
			},
			//初始化
			init:function(node){
				this.node = node;
//				this.node.setAttribute("disabled",true);
				this.start();
			}
		};
//		var btn1 = $("#getCode");
//		$("#getCode").click(function(){
//			if(click){
//				test.init(btn1);
//			}
//		});
	});
</script>
<?php
	/*
  if (!function_exists('getallheaders'))
{
	function getallheaders()
	{
		foreach ($_SERVER as $name => $value)
		{
			if (substr($name, 0, 5) == 'HTTP_')
			{
				$headers[str_replace(' ', '-', ucwords(strtolower(str_replace('_', ' ', substr($name, 5)))))] = $value;
			}
		}
		return $headers;
	}
}
	  print_r(getallheaders());*/
	$this->pageTitle=Yii::app()->name . ' - '.UserModule::t("Registration");
$this->breadcrumbs=array(
    UserModule::t("Registration"),
);
?>
<div class="login_box col-lg-12">
    <div class="login_tit">
        <a class="current">注册</a><span>（请正确填写您的基本信息）</span>
    </div>
    <div class="login_ct">

    <div class="login col-lg-6">
    <div class="login_form form">


<?php if(Yii::app()->user->hasFlash('registration')): ?>
    <div class="success">
        <?php echo Yii::app()->user->getFlash('registration'); ?>
    </div>
<?php else: ?>
    <?php CHtml::$afterRequiredLabel = '';?>
    <div class="form">
        <?php $form=$this->beginWidget('UActiveForm', array(
            'id'=>'registration-form',
            'enableAjaxValidation'=>true,
//            'disableAjaxValidationAttributes'=>array('RegistrationForm_verifyCode'),
            'clientOptions'=>array(
                'validateOnSubmit'=>true,
            ),
            'htmlOptions' => array('enctype'=>'multipart/form-data'),
        )); ?>

<!--        <p class="note">--><?php //echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?><!--</p>-->

        <?php echo $form->errorSummary(array($model,$profile)); ?>

        <div class="form_c">
            <div class="form_l"><?php echo $form->labelEx($model,'username'); ?></div>
            <?php echo $form->textField($model,'username',array('placeholder'=>'请输入4-12位字符，支持字母、数字组合')); ?>
          <div class="center" > <?php echo $form->error($model,'username'); ?></div>

        </div>

<!--        <div class="form_c">-->
<!--            <div class="form_l">--><?php //echo $form->labelEx($model,'email'); ?><!--</div>-->
<!--            --><?php //echo $form->textField($model,'email'); ?>
<!--            <div class="center" > --><?php //echo $form->error($model,'email'); ?><!--</div>-->
<!--        </div>-->

	    <div class="form_c">
		    <div class="form_l"><?php echo $form->labelEx($model,'mobile'); ?></div>
		    <?php echo $form->textField($model,'mobile',array('placeholder'=>'请输入11位手机号')); ?>
		    <div class="center" > <?php echo $form->error($model,'mobile'); ?></div>
	    </div>

        <div class="form_c">
            <div class="form_l"><?php echo $form->labelEx($model,'password'); ?></div>
            <?php echo $form->passwordField($model,'password',array('placeholder'=>'请输入4-20位密码')); ?>
            <div class="center" >  <?php echo $form->error($model,'password'); ?></div>

<!--            <p class="hint">-->
<!--                --><?php //echo UserModule::t("Minimal password length 4 symbols."); ?>
<!--            </p>-->
        </div>

        <div class="form_c">
            <div class="form_l">密码强度</div>
            <div class="psw_safe">
                <span class="safe_d">弱</span>
                <span class="safe_c">中</span>
                <span class="safe_h">强</span>
            </div>
        </div>

        <div class="form_c">
            <div class="form_l"><?php echo $form->labelEx($model,'verifyPassword'); ?></div>
            <?php echo $form->passwordField($model,'verifyPassword'); ?>
            <div class="center" >  <?php echo $form->error($model,'verifyPassword'); ?></div>
        </div>

	    <div class="form_c">
			<div class="form_l">验证码</div>
<!--		--><?php //echo $form->hiddenField($model,'code'); ?>
	        <input type="text" name="code" value="" id="code" style="width: 128px" disabled="true"/>
			<div class="codeTel" style=" background-color: #f7f7f7; border: 1px solid #d0d0d0;color: #404040;float: left;text-align:center;font-size: 13px;margin-left: 15px;width: 128px;">
		        <div style="padding:5px"> <a id="getCode" style="  cursor:pointer">获取验证码</a></div>
			</div>

		</div>
	    <div class="center" style="height: 20px" ><span id="errorCode" style="margin-left: 100px; "></span></div>

        <?php
        $profileFields=Profile::getFields();
        if ($profileFields) {
            foreach($profileFields as $field) {
                ?>
                <div class="form_c">
                    <?php echo $form->labelEx($profile,$field->varname); ?>
                    <?php
                    if ($widgetEdit = $field->widgetEdit($profile)) {
                        echo $widgetEdit;
                    } elseif ($field->range) {
                        echo $form->dropDownList($profile,$field->varname,Profile::range($field->range));
                    } elseif ($field->field_type=="TEXT") {
                        echo$form->textArea($profile,$field->varname,array('rows'=>6, 'cols'=>50));
                    } else {
                        echo $form->textField($profile,$field->varname,array('size'=>60,'maxlength'=>(($field->field_size)?$field->field_size:255)));
                    }
                    ?>
                    <?php echo $form->error($profile,$field->varname); ?>
                </div>
            <?php
            }
        }
        ?>
<!--        --><?php //if (UserModule::doCaptcha('registration')): ?>
<!--                   <div class="verifyOneDiwCode">-->
<!--                                          <div class="form_c">-->
<!--                                          <div class="form_l"> --><?php //echo $form->labelEx($model,'verifyCode'); ?><!--</div>-->
<!--                                          --><?php //echo $form->textField($model,'verifyCode'); ?>
<!--                                          </div>-->
<!--                                         <div id="code-div">-->
<!--                                          --><?php //$this->widget('CCaptcha'); ?>
<!---->
<!--	                                         --><?php //echo $form->error($model,'verifyCode'); ?>
<!---->
<!--	                                         <p class="hint">--><?php //echo UserModule::t("Please enter the letters as they are shown in the image above."); ?>
<!--		                                         <br/>--><?php //echo UserModule::t("Letters are not case-sensitive."); ?><!--</p>-->
<!--                                         </div>-->
<!--                                      </div>-->
<!--               --><?php //endif; ?>

        <div class="form_cc" id="submit-div">
        <div class="form_submit">
<!--            --><?php //echo CHtml::submitButton(UserModule::t("Register"),array('id'=>'submit')); ?>
	        <input id="submit" type="submit" value="马上注册" name="yt0" disabled="true">
            <span><font class="cor_gray">已注册请</font><?php echo CHtml::link(UserModule::t("Logining"),Yii::app()->getModule('user')->loginUrl);?>
        </div>
        </div>

        <?php $this->endWidget(); ?>
    </div><!-- form -->
<?php endif; ?>
  </div>


  </div>
        <div class="logo_b col-lg-6">
<!--            <img alt="" src="--><?php //echo Yii::app()->theme->baseUrl; ?><!--/image/logo_b.png" width="257" height="152"/>-->
        </div>
</div>
    </div>
<div class="clear"></div>
<div style="padding-bottom: 20px"></div>