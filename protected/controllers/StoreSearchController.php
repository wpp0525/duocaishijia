<?php

	class StoreSearchController extends YController
	{
		public function actionIndex()
		{
          $stores=DcStore::model()->findAll(array(
	          'order'=>'city asc',));
          $this->render('index',array('stores'=>$stores));
		}

		public function actionDetail($id){
			$store = DcStore::model()->findByPk($id);
			$this->render('detail',array('store'=>$store));
		}
	}
?>