<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-17
	 * Time: 下午1:26 GMT+8.00
	 */

	class InvalidDataException extends DcsjException
	{
//		const ERROR_CODE = 2003;
		const ERROR_CODE = 250;
		const ERROR_MESSAGE = 'Invalid failed.';

		private $errorData = array();

		public function __construct(array $data) {
			$this->errorData = $data;

			parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE, $this->errorData);
		}
	}