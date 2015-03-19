<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-18
	 * Time: 上午10:14
	 */

	class NotFoundException extends DcsjException
	{
//		const ERROR_CODE = 1004;
		const ERROR_CODE = 260;
		const ERROR_MESSAGE = 'Data not found.';

		private $errorData = array();

		public function __construct(array $data) {
			$this->errorData = $data;

			parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE, $this->errorData);
		}
	}