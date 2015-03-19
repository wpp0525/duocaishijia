<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-1-8
 * Time: 下午2:31
 */
class MeasureOderListController extends MallBaseController{


	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new ArOrderMeasure('search');
		$model->unsetAttributes(); // clear any default values
		$this->render('admin', array(
			'model' => $model,
		));
	}

	public  function actionView($id){

		$orderMeasure = ArOrderMeasure::model()->findByPk($id);
		$orderMeasure_items = $orderMeasure->item;
		$this->render('view', array(
			'orderMeasure' => $orderMeasure, 'orderMeasure_items' => $orderMeasure_items
		));
	}

}