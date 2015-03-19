<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-18
	 * Time: 下午5:54 GMT+8.00
	 */

	class DbSaveException extends DcsjException
	{
//		const ERROR_CODE = 3003;
		const ERROR_CODE = 240;
		const ERROR_MESSAGE = 'DB save failed.';

		private $errorData = array();

		public function __construct (array $data)
		{
			$this->errorData = $data;

			parent::__construct(self::ERROR_MESSAGE, self::ERROR_CODE, $this->errorData);
		}
	}