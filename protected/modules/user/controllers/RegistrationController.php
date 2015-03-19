<?php

class RegistrationController extends Controller
{
	public $defaultAction = 'registration';
	
	/**
	 * Declares class-based actions.
	 */
	public function actions()
	{
		return array(
			'captcha'=>array(
				'class'=>'CCaptchaAction',
				'backColor'=>0xFFFFFF,
				'minLength'=>4,  //最短为4位
				'maxLength'=>4,   //是长为4位
			),
		);
	}
	/**
	 * Registration user
	 */
	public function actionRegistration() {
        Profile::$regMode = true;
        $model = new RegistrationForm;
        $profile=new Profile;

        // ajax validator
        if(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')
        {
            echo UActiveForm::validate(array($model,$profile));
            Yii::app()->end();
        }
        if (Yii::app()->user->id) {
            $this->redirect(Yii::app()->controller->module->profileUrl);
        } else {
            if(isset($_POST['RegistrationForm'])) {
                $model->attributes=$_POST['RegistrationForm'];
                $profile->attributes=((isset($_POST['Profile'])?$_POST['Profile']:array()));
	            $profile->phone = $model->mobile;
                if($model->validate()&&$profile->validate())
                {
                    $soucePassword = $model->password;
                    $model->activkey=UserModule::encrypting(microtime().$model->password);
                    $model->password=UserModule::encrypting($model->password);
                    $model->verifyPassword=UserModule::encrypting($model->verifyPassword);
                    $model->superuser=0;
                    //$model->status=((Yii::app()->controller->module->activeAfterRegister)?User::STATUS_ACTIVE:User::STATUS_NOACTIVE);
				    $model->status=1;
					
                    if ($model->save()) {
                        $profile->user_id=$model->id;
                        $profile->save();
		                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
                        Yii::app()->user->setFlash('hint','你已注册成功，请登录');
                        $this->redirect(array('/user/login'));
				/* 下面注释的代码是通过邮箱激活的，目前我是直接把状态给激活了 */
                 /*
                        if (Yii::app()->controller->module->sendActivationMail) {
                            $activation_url = $this->createAbsoluteUrl('/user/activation/activation',array("activkey" => $model->activkey, "email" => $model->email));
                            UserModule::sendMail($model->email,UserModule::t("You registered from {site_name}",array('{site_name}'=>Yii::app()->name)),UserModule::t("Please activate you account go to {activation_url}",array('{activation_url}'=>$activation_url)));
                        }

                        if ((Yii::app()->controller->module->loginNotActiv||(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false))&&Yii::app()->controller->module->autoLogin) {
                                $identity=new UserIdentity($model->username,$soucePassword);
                                $identity->authenticate();
                                Yii::app()->user->login($identity,0);
                                $this->redirect(Yii::app()->controller->module->returnUrl);
                        } else {
                            if (!Yii::app()->controller->module->activeAfterRegister&&!Yii::app()->controller->module->sendActivationMail) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Contact Admin to activate your account."));
                            } elseif(Yii::app()->controller->module->activeAfterRegister&&Yii::app()->controller->module->sendActivationMail==false) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please {{login}}.",array('{{login}}'=>CHtml::link(UserModule::t('Login'),Yii::app()->controller->module->loginUrl))));
                            } elseif(Yii::app()->controller->module->loginNotActiv) {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email or login."));
                            } else {
                                Yii::app()->user->setFlash('registration',UserModule::t("Thank you for your registration. Please check your email."));
                            }
                            $this->refresh();
                        }    */
                    }
                } else $profile->validate();
            }
            $this->render('//user/registration',array('model'=>$model,'profile'=>$profile));
        }
	}
	public function actionGet()
	{
		$code = $this->getCode();
		session_start(); //开启缓存
		$_SESSION['time'] = date("Y-m-d H:i:s");
		$_SESSION['mcode'] = $code; //将content的值保存在session中
		$mobile = $_POST['mobile'];
		if ($mobile) {
//			echo json_encode(array(
//				'success' => true,
//				'code' => 300,
//				'mcode' =>$code,
//				'message' => '验证码发送成功',
//			));
			//					$post_data = array();
			//					$post_data['username'] = "test"; //用户名
			//					$post_data['password'] = "test"; //密码
			//					$post_data['mobile'] = $mobile; //手机号，多个号码以分号分隔，如：13407100000;13407100001;13407100002
			//					$post_data['content'] = urlencode("您本次的验证码是：".$mcode); //内容，如为中文一定要使用一下urlencode函数
			//					$post_data['extcode'] = ""; //扩展号，可选
			//					$post_data['senddate'] = ""; //发送时间，格式：yyyy-MM-dd HH:mm:ss，可选
			//					$post_data['batchID'] = ""; //批次号，可选
			//					$url = 'http://116.213.72.20/SMSHttpService/send.aspx';
			//					$o = "";
			//					foreach ($post_data as $k => $v) {
			//						$o .= "$k=".$v."&";
			//					}
			//					$post_data = substr($o, 0, -1);
			//					$this_header = array("content-type: application/x-www-form-urlencoded;charset=UTF-8");
			//					$ch = curl_init();
			//					curl_setopt($ch, CURLOPT_POST, 1);
			//					curl_setopt($ch, CURLOPT_HTTPHEADER, $this_header);
			//					curl_setopt($ch, CURLOPT_HEADER, 0);
			//					curl_setopt($ch, CURLOPT_URL, $url);
			//					curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
			//					curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			//					$result = curl_exec($ch); //返回相应的标识，具体请参考我方提供的短信API文档
			//					curl_close($ch);

			$username = 'liuxl';
			$psw = strtoupper(md5('liuxl2015'));
			$massage = "欢迎注册多彩饰家，您的验证码是:".$code;
			$massages = iconv('utf-8','GBK',$massage);
			$result = $this->sendSMS($username, $psw, $mobile, $massages);
			$result = substr($result,0,stripos($result,'_'));
			if($result==0){
				echo json_encode(array(
				'success' => true,
				'code' => 200,
				'message' => '验证码发送成功',));
			}else{
				echo json_encode(array(
					'success' => true,
					'code' => 300,
					'message' => '验证码发送失败',));
			}
		}else{
			echo json_encode(array(
				'success' => false,
				'code' => 400,
				'message' => '请填写手机号',
			));
		}
	}


	public function actionCheck(){

		session_start(); //开启缓存
		$mcode = $_SESSION['mcode'];
		$pcode = $_POST['mcode'];
		if ((strtotime($_SESSION['time']) + 90) < strtotime(date("Y-m-d H:i:s"))) { //将获取的缓存时间转换成时间戳加上60秒后与当前时间比较，小于当前时间即为过期
			echo json_encode(array(
				'success'=>false,
				'code' => 200,
				'message' => '验证码已过期，请重新获取！',
			));
		}else if($mcode == $pcode){
			echo json_encode(array(
				'sucess' => true,
				'code' =>300,
				'message' => '成功'
			));
		}else{
			echo json_encode(array(
				'sucess' => false,
				'code' =>400,
				'message' => '失败'
			));
		}
	}

	public function  getCode(){
		$str = '0123456789';
		$code = '';
		for($i=0;$i<4;$i++){
			$code .= $str{mt_rand(0, 9)};
		}
		return $code;
	}


	//发送短信接口方式
	function smsSend($acount, $passwd , $mobiles , $smsContent, $extCode){
		/**
		 * 调用的HTTP接口说明：
		 * 	URI : 		http://www.hjhz.net/MessagePlat/smsSendServlet.htm
		 * 	command:	传固定值sendMD5  ，大小写敏感
		username:	短信发送账号
		pwd:		登录密码的MD5串
		mobiles:	手机号码(多个号码用英文逗号或者分号隔开)，不能超过100个号码
		content:	1、短信内容(全英文字符短信含签名每140个字符拆分为一条短信，中文短信含签名每70个字符拆分为一条短信)；
		2、短信内容中的中文字符统一采用GBK进行URL编码。
		(java进行编码的方法为：java.net.URLEncoder.encode("短信内容","GBK"))
		3、如果长度太大建议彩信post方式；
		rstype:		可选参数，“text”，返回结果为普通字符串格式,
		“xml”,返回结果为xml格式。不填该参数，则默认为text
		pfex:		短信发送时遇到违禁词时处理开关：0，1二个参数值,默认值为：0
		1：系统自动在违词中间添加空格符并下发短信（短信条件可能会增加）
		0：系统停止短信下发动作并返回违禁词信息，其格式下看下表说明
		extCode:	1、[可选参数]
		2、传1到4位任意组合的数字作为特服号后继扩展码。
		3、扩展码参数只允许数字字符。
		4、扩展码传入位数超过4位，系统会自己动截去后面部份保留前面4位
		 */
		//接口的uri
		$http_URI = 'http://www.hjhz.net/MessagePlat/smsSendServlet.htm';
		//构造参数
		$data = array ('command' => 'sendMD5',
		               'username' => $acount,
		               'pwd' => $passwd,
		               'mobiles' => $mobiles,
		               'content' => $smsContent,
		               'restype' => 'text',
		               'pfex' => '1',
		               'extCode' => $extCode);

		echo "\r\n 参数内容： $data";
		$data = http_build_query($data);

		$opts = array (
			'http' => array (
				'method' => 'POST',
				'header'=> "Content-type: application/x-www-form-urlencoded\r\n" .
					"Content-Length:" . strlen($data) . "\r\n",
				'content' => $data
			)
		);

		$context = stream_context_create($opts);

		echo "发送链接：$http_URI ; 内容: $context";

		$result = file_get_contents($http_URI, false, $context);

		return $result;

	}


	public function sendSMS($username,$psw1,$mobile,$code){
		$url = 'http://www.qymas.com/smsSendServlet.htm?command=sendSMSMD5&username='.$username.'&pwd='.$psw1.'&mobiles='.$mobile.'&content='.$code.'&incode=GBK&outcode=GBK&rstype=text';
		//			echo $url;
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);
		return $result;
	}
}