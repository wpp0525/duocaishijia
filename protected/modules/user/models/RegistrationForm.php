<?php
/**
 * RegistrationForm class.
 * RegistrationForm is the data structure for keeping
 * user registration form data. It is used by the 'registration' action of 'UserController'.
 */
class RegistrationForm extends User {
	public $verifyPassword;
	public $verifyCode;

	public function rules() {
		$rules = array(
			array('username, password, verifyPassword,mobile', 'required'),
			//array('username', 'length', 'max'=>20, 'min' => 4,'message' => UserModule::t("Incorrect username (length between 4 and 20 characters).")),
			array('password', 'length', 'max'=>128, 'min' => 4,'message' => UserModule::t("Incorrect password (minimal length 4 symbols).")),
			array('mobile', 'length', 'max'=>11, 'min' => 11,'message' => '手机号码必须是11位'),

			array('mobile', 'match', 'pattern' => '/^0{0,1}(13[0-9]|14[0-9]|15[0-9]|16[0-9]|17[0-9]|18[0-9])[0-9]{8}/', 'message' => UserModule::t("Mobile is incorrect.")),
			array('username', 'unique', 'message' => UserModule::t("This user's name already exists.")),
//			array('email', 'unique', 'message' => UserModule::t("This user's email address already exists.")),

			array('mobile', 'unique', 'message' => UserModule::t("This user's mobile address already exists.")),
			//array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Retype Password is incorrect.")),
			array('username', 'match', 'pattern' => '/^[a-za-z]{1}([a-za-z0-9]|[._]){3,19}$/','message' => '用户名必须以字符开头，4到12位，不可包含汉字和特殊符号！'),
		);

//		if (!(isset($_POST['ajax']) && $_POST['ajax']==='registration-form')) {
//			array_push($rules,array('verifyCode', 'captcha', 'allowEmpty'=>!UserModule::doCaptcha('registration')));
//		}
		
		array_push($rules,array('verifyPassword', 'compare', 'compareAttribute'=>'password', 'message' => UserModule::t("Retype Password is incorrect.")));
		return $rules;
	}
}