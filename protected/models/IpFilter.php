<?php

	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-12
	 * Time: 下午1:53 GMT+8.00
	 */

	class IpFilter extends CActiveForm
	{
		private function getSafeIpArray ()
		{
			return Yii::app()->params->trustedIps;
		}

		public function isAllow ()
		{
			/** @var DcsjHttpRequest $request */
			$request = Yii::app()->request;
			$ip = $request->userHostAddress;
			$SafeIpArray = $this->getSafeIpArray();

			return in_array($ip, $SafeIpArray);
		}
	}