<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-13
	 * Time: 下午1:59 GMT+8.00
	 */

	class ApiController extends YController
	{
		/**
		 * @param $action
		 * @return bool
		 */
		public function beforeAction ($action)
		{
			Yii::app()->getEventHandlers('onException')->insertAt(0, array($this, 'handleExceptionEvent'));
			Yii::app()->getEventHandlers('onError')->insertAt(0, array($this, 'handleErrorEvent'));

			/** @var UserModule $webUser */
			$webUser = Yii::app()->user;

			$filter = new IpFilter();

//			if ($webUser->isGuest && !$filter->isAllow()) {
//			if ($webUser->isGuest && !$filter->isAllow()) {
//					echo "你的没有权限调用这个接口";
//
//					return false;
//				} else {
					return true;
//				}

		}

		public function echoJson (array $attributes)
		{
			echo json_encode($attributes);

			$this->cleanEnd();
		}

		public function handleExceptionEvent (CExceptionEvent $event)
		{
			$exception = $event->exception;

			$this->echoJson(array(
				'success' => false,
				'code'    => $exception->getCode(),
				'msg'     => $exception->getMessage()
			));
		}

		public function handleErrorEvent ()
		{

		}

		public function cleanEnd ()
		{
			ob_start();
			Yii::app()->end(0, false);
			ob_end_clean();
			Yii::app()->end();
		}

		/**
		 * Return the first occurred validation error.
		 *
		 * @param CModel $model
		 * @return string false will be returned if the model has no error
		 */
		public function modelError($model) {

			foreach ($model->getErrors() as $attr_errs) {
				foreach ($attr_errs as $msg) {
					return $msg;
				}
			}
			return false;
		}

	}