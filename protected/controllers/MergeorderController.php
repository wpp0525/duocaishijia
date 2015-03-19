<?php

class MergeorderController extends Controller
{
	public function actionIndex()
	{
		$this->render('index');
	}


	public function actionDealOrder()
	{
        /*拿到要支付的金额*/
		$totalfee=0;

		if(empty($_POST['order']) ){
			$this->echoJson(array(
				'total_fee' => $totalfee,
			));
		}else{
			foreach($_POST['order'] as $key => $value){
		         $orders = order::model()->findByAttributes(array('order_id'=>$value));
				 $totalfee = $totalfee + $orders->total_fee - $orders->pay_fee;
			}
			$this->echoJson(array(
				'total_fee' => $totalfee,
			));
		}
	}

	public function actionDealOrderss()
	{

		$criteria = new CDbCriteria;
		$criteria->select = 'merge_order_id';//默认*
		$criteria->order = 'merge_order_id DESC' ;//排序条件
		$criteria->limit = 1;
		$maxmerge_order_id = Mergeorder::model()->find($criteria)->merge_order_id;//拿到最大的合并单号

		/*先创建订单到相应的表中*/
		foreach($_POST['order'] as $key => $value){
			$mergerorders =	new  Mergeorder;
			$mergerorders->merge_order_id = $maxmerge_order_id+1;
			$mergerorders->order_id= $value;

			if($mergerorders->validate()){
				if($mergerorders->save()){
					//    echo 'save success';
				}else{
					//  echo 'save fail!';
				}
			}
		}

		/*拿到要支付的金额*/
		$totalfee=0;
		foreach($_POST['order'] as $key => $value){
			$orders = order::model()->findByAttributes(array('order_id'=>$value));
			$totalfee = $totalfee + $orders->total_fee - $orders->pay_fee;
		}

		$this->echoJson(array(
			'success'   => true,
			'merge_order_ids' => $maxmerge_order_id+1,
			'total_fee' => $totalfee,
		));
	}

	public function cleanEnd ()
	{
		ob_start();
		Yii::app()->end(0, false);
		ob_end_clean();
		Yii::app()->end();
	}
	public function echoJson (array $attributes)
	{
		echo json_encode($attributes);

		$this->cleanEnd();
	}
	// Uncomment the following methods and override them if needed
	/*
	public function filters()
	{
		// return the filter configuration for this controller, e.g.:
		return array(
			'inlineFilterName',
			array(
				'class'=>'path.to.FilterClass',
				'propertyName'=>'propertyValue',
			),
		);
	}

	public function actions()
	{
		// return external action classes, e.g.:
		return array(
			'action1'=>'path.to.ActionClass',
			'action2'=>array(
				'class'=>'path.to.AnotherActionClass',
				'propertyName'=>'propertyValue',
			),
		);
	}
	*/



}