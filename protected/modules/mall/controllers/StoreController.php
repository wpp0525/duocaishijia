<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-12-30
 * Time: 上午10:03
 */
class StoreController extends MallBaseController{
	public $layout = '//layouts/store';

	/**
	 * Specifies the access control rules.
	 * This method is used by the 'accessControl' filter.
	 * @return array access control rules
	 */
	public function accessRules()
	{
		return array_merge(array(
				array('allow',
					'users' => array('@'),
				)
			), parent::accessRules()
		);
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new DcStore('search');

		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['dcStore']))
			$model->attributes = $_GET['dcStore'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function actionCreate(){
		$model = new DcStore();

		if(isset($_POST['DcStore']))
		{
//			dump($_POST);
//			$model->attributes=$_POST['Store'];
			$model->store_name = $_POST['DcStore']['store_name'];
			$model->address = $_POST['DcStore']['address'];
			$model->phone = $_POST['DcStore']['phone'];
			$model->state = $_POST['state'];
			$model->city = $_POST['city'];
 			$model->district = $_POST['district'];
			$model->erpID = $_POST['DcStore']['erpID'];
			$model->create_time = date("Y-m-d H:i",time());
//			dump($model);

			if($model->save())
				$this->redirect(array('view','id'=>$model->store_id));
		}
		$this->render('create',array(
			'model'=>$model,
		));
	}

	public function actionUpdate($id){
		$model = $this->loadModel($id);

		if(isset($_POST['DcStore']))
		{
			//			dump($_POST);
			//			$model->attributes=$_POST['Store'];
			$model->store_name = $_POST['DcStore']['store_name'];
			$model->address = $_POST['DcStore']['address'];
			$model->erpID = $_POST['DcStore']['erpID'];
			$model->phone = $_POST['DcStore']['phone'];
			$model->state = $_POST['state'];
			$model->city = $_POST['city'];
			$model->district = $_POST['district'];
			$model->create_time = date("Y-m-d H:i",time());
			//			dump($model);

			if($model->save())
				$this->redirect(array('view','id'=>$model->store_id));
		}
		$this->render('update',array(
			'model'=>$model,
		));
	}

	public function actionDelete($id)
	{
		$this->loadModel($id)->delete();
		$this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
	}


	public function loadModel($id)
	{
		$model = DcStore::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}




}