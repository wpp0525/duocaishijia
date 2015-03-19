<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-12
	 * Time: 下午2:07 GMT+8.00
	 */

	class DcsjHttpRequest extends CHttpRequest
	{

		/**
		 * Returns whether this is an mobile request.
		 * @return boolean whether this is an mobile request.
		 */
		public function getIsMobileRequest ()
		{

			return isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iPad|iPhone|iPod|Android|Windows Phone)/i', $_SERVER['HTTP_USER_AGENT']) !== 0;
		}

		/**
		 * Returns whether this is an IOS request.
		 * @return boolean whether this is an IOS request.
		 */
		public function getIsIOSRequest ()
		{

			return isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/(iPad|iPhone|iPod)/i', $_SERVER['HTTP_USER_AGENT']) !== 0;
		}

		/**
		 * Returns whether this is an Android request.
		 * @return boolean whether this is an Android request.
		 */
		public function getIsAndroidRequest ()
		{

			return isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'Android') !== false;
		}

		/**
		 * Returns whether this is an Windows Phone request.
		 * @return boolean whether this is an Windows Phone request.
		 */
		public function getIsWindowsPhoneRequest ()
		{

			return isset($_SERVER['HTTP_USER_AGENT']) && stripos($_SERVER['HTTP_USER_AGENT'], 'Windows Phone') !== false;
		}

		/**
		 * @param $key
		 * @return string
		 */
		public function getHeader ($key)
		{
			$key = strtoupper($key);

			return isset($_SERVER['HTTP_'.$key]) ? $_SERVER['HTTP_'.$key] : '';
		}

		/**
		 * Check if the given host name is an IP address.
		 * It will NOT check the validation of format, but only
		 * checks if there is letter that is not number or dot.
		 *
		 * @param string $host
		 * @return bool
		 */
		public function isIpAddress ($host)
		{
			return 1 == preg_match('/^[\d\.]+$/', $host);
		}

		/**
		 * Shortcut method, send a success response and terminate the current process. array('state' => 1, 'data' => $data)
		 * @param mixed $data the data
		 */
		public function ajaxSuccess ($data = '')
		{
			$this->sendResponseInJson(array(
				'success' => true,
				'state'   => 1,
				'data'    => $data
			));
		}

		/**
		 * Shortcut method, send a error response and terminate the current process. array('state' => 0, 'msg' => $msg)
		 * @param string $primaryMessage
		 * @param string $secondaryMessage
		 */
		public function ajaxError ($primaryMessage, $secondaryMessage = '')
		{

			$this->sendResponseInJson(array(
				'success' => false,
				'state'   => 0,
				'msg'     => $primaryMessage,
				'desc'    => $secondaryMessage,
			));
		}

		public function calculateContentType (array $options)
		{

			if (!count($options)) {
				throw new InvalidArgumentException('Options should not be empty.');
			}

			$priorityGroups = explode(';', $this->getHeader('Accept'));
			foreach ($priorityGroups as $types) {
				$types = preg_split('/[, ]/', $types, -1, PREG_SPLIT_NO_EMPTY);
				$interset = array_intersect($types, $options);
				if (count($interset)) {
					return array_shift($interset);
				}
			}

			return array_pop($options);
		}

		/**
		 * Send response in JSON and terminate current process (by default)..
		 * @param mixed $data
		 * @param bool  $endRequest
		 */
		public function sendResponseInJson ($data = '', $endRequest = true)
		{
			if (!empty($data) || $data == 0) {
				if (!headers_sent()) {
					header('Content-Type:'.$this->calculateContentType(array(
							'application/json',
							'text/json',
							'text/plain',
							'text/html',
						)));
				}
				echo json_encode($data);

			}
			if ($endRequest) {
				$this->cleanEnd();
			}
		}

		/**
		 * Send response in plain text and terminate current process (by default).
		 * @param string $content
		 * @param bool   $endRequest
		 */
		public function sendResponseInText ($content = '', $endRequest = true)
		{

			if (!headers_sent()) {
				header('Content-Type:'.$this->calculateContentType(array(
						'text/plain',
						'text/html',
					)));
			}
			echo $content;
			if ($endRequest) {
				$this->cleanEnd();
			}
		}

		protected function cleanEnd ()
		{
			ob_start();
			Yii::app()->end(0, false);
			ob_get_clean();
			exit;
		}

	}