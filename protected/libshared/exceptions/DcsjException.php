<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-17
	 * Time: 下午1:24 GMT+8.00
	 */

	class DcsjException extends \CException
	{
		// Redefine the exception so message isn't optional
		public function __construct ($message, $code = 0, $data = array(), Exception $previous = NULL)
		{
			foreach ($data as $key => $value) {
				$message .= " ".$key.": ".$value;
			}

			// some code
			// make sure everything is assigned properly
			parent::__construct($message, $code, $previous);
		}

		// custom string representation of object
		public function __toString ()
		{
			return '['.$this->getMessage().'] '.parent::__toString();
		}
	}