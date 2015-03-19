<?php

	/**
	 * Created by PhpStorm.
	 * User: Administrator
	 * Date: 14-11-12
	 * Time: 上午9:04
	 */
	class UserApiController extends ApiController
	{
		//this api use for check a user is existence or not by @client_mobile
		public function actionCheck ($client_mobile)
		{
			$user = User::model()->findByAttributes(array('mobile' => $client_mobile));
			$usersInfo = array();
			$user = array(
				"result" => 0
			);

			if (isset($user)) {
				$usersInfo["client_id"] = $user->id;
				$usersInfo["client_name"] = $user->username;
				$usersInfo["client_mobile"] = $user->mobile;

				$user = array(
					"result" => 1,
					"user"   => $usersInfo,
				);
			}

			$this->echoJson($user);
		}

		//this api can reate user when post @mobile , @username
		public function actionCreate ()
		{
			$mobile = $_POST['client_mobile'];
			$name = $_POST['client_name'];

			$user = User::model()->findByAttributes(array('mobile' => $mobile));
			if (isset($user)) {
				$this->echoJson(array(
					'success' => flase,
					'msg'     => 'user is existence',
				));
				return false;
			}
			$user = new User();
			$profile = new Profile();

			$password = $this->get_password(8);

			$userInfo = array(
				"username" => $mobile,
				"mobile"   => $mobile,
			);

			$user->attributes = $userInfo;
			$user->password = UserModule::encrypting($password);
			$user->superuser = 0;
			$user->activkey = UserModule::encrypting(microtime().$user->password);
			$user->status = 1;

			if ($user->validate()) {
				try {
					if ($user->save()) {
						$profileInfo = array(
							'truename' => $name,
							'phone'    => $user->mobile,
						);

						$profile->attributes = $profileInfo;
						$profile->user_id = $user->id;

						if ($profile->validate()) {
							if ($profile->save()) {
								$userOut = array(
									'client_id'  => $user->id,
									'client_pwd' => $password,
								);
								$this->echoJson($userOut);
							} else {
								throw new DbSaveException(array(
									'errorInfo' => $this->modelError($profile),
								));
							}
						} else {
							throw new InvalidDataException(array(
								'errorInfo' => $this->modelError($profile),
							));
						}
					} else {
						throw new DbSaveException(array(
							'errorInfo' => $this->modelError($user),
						));
					}
				} catch (CException $error) {
					throw new DcsjException(Yii::t('userApi', 'Fail to save user. Error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
				}
			} else {
				throw new InvalidDataException(array(
					'errorInfo' => $this->modelError($user),
				));
			}
		}

		//get a random password
		function get_password ($length)
		{
			$str = '';
			$pattern = '1234567890qwertyuioplkjhgfdsazxcvbnm';

			for ($rand = 0; $rand < $length; $rand++) {
				$str .= $pattern{mt_rand(0, 35)};
			}

			return $str;
		}
	}