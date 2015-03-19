<?php

/**
 * UserIdentity represents the data needed to identity a user.
 * It contains the authentication method that checks if the provided
 * data can identity the user.
 */
class UserIdentity extends CUserIdentity
{
	private $_id;
	const ERROR_MOBILE_INVALID=211;
	const ERROR_EMAIL_INVALID=3;
	const ERROR_STATUS_NOTACTIV=4;
	const ERROR_STATUS_BAN=5;
	/**
	 * Authenticates a user.
	 * The example implementation makes sure if the username and password
	 * are both 'demo'.
	 * In practical applications, this should be changed to authenticate
	 * against some persistent user identity storage (e.g. database).
	 * @return boolean whether authentication succeeds.
	 */
	public function authenticate()
	{
	//	if (strpos($this->username,"@")) { //判断是否是邮箱登陆
        if( substr($this->username,0,1) =='1' || substr($this->username,0,1) =='0' ){ //判断是否是手机登陆,截取字符串的第一个值是否是 1
		 //	$user=User::model()->notsafe()->findByAttributes(array('email'=>$this->username));
			$user=User::model()->notsafe()->findByAttributes(array('mobile'=>$this->username));
		} else {
			$user=User::model()->notsafe()->findByAttributes(array('username'=>$this->username));
		}

		if($user===null)
		//	if (strpos($this->username,"@")) {  //判断是否是邮箱登陆
		    if( substr($this->username,0,1) =='1' || substr($this->username,0,1) =='0' ){ //判断是否是手机登陆,截取字符串的第一个值是否是 1
				// $this->errorCode=self::ERROR_EMAIL_INVALID;
				$this->errorCode=self::ERROR_MOBILE_INVALID;
			} else {
				$this->errorCode=self::ERROR_USERNAME_INVALID;
			}
		else if(Yii::app()->getModule('user')->encrypting($this->password)!==$user->password)
			$this->errorCode=self::ERROR_PASSWORD_INVALID;
		else if($user->status==0&&Yii::app()->getModule('user')->loginNotActiv==false)
			$this->errorCode=self::ERROR_STATUS_NOTACTIV;
		else if($user->status==-1)
			$this->errorCode=self::ERROR_STATUS_BAN;
		else {
			$this->_id=$user->id;
			$this->username=$user->username;
			$this->errorCode=self::ERROR_NONE;
		}
		return !$this->errorCode;
	}
    
    /**
    * @return integer the ID of the user record
    */
	public function getId()
	{
		return $this->_id;
	}
}