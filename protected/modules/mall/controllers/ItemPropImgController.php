<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 15-1-20
 * Time: 上午9:39
 */
class ItemPropImgController extends  MallBaseController{

	public function actionCreate()
	{
		$model = new ItemPropImg;
		if (isset($_POST['ItemPropImg'])) {
			$model->attributes = $_POST['ItemPropImg'];
			if ($model->save()) {
                if(isset($_POST['ItemPropImgs'])){

                    foreach($_POST['ItemPropImgs']['pic'] as $pic){
                        $itemprop = new  ItemPropImgs;
                        $itemprop->item_prop_img_id = $model->item_prop_img_id;
                        $itemprop -> pic = $pic;
                        if(!$itemprop->save()){
                            throw new Exception();
                        }
                    }
                }
				$this->redirect(array('view', 'id' => $model->item_prop_img_id));
			}
		}

		$this->render('create', array(
			'model' => $model,
		));
	}

	/**
	 * Manages all models.
	 */
	public function actionAdmin()
	{
		$model = new ItemPropImg('search');
		$model->unsetAttributes(); // clear any default values
		if (isset($_GET['ItemPropImg']))
			$model->attributes = $_GET['ItemPropImg'];

		$this->render('admin', array(
			'model' => $model,
		));
	}

	public function actionView($id)
	{
		$this->render('view', array(
			'model' => $this->loadModel($id),
		));
	}

	public function actionUpdate($id)
	{
		$model = $this->loadModel($id);
		if (isset($_POST['ItemPropImg'])) {
			$model->attributes = $_POST['ItemPropImg'];
			if ($model->save()) {
				if(isset($_POST['ItemPropImgs'])){
					if(
					!ItemPropImgs::model()->deleteAllByAttributes(array(
						'item_prop_img_id' => $model->item_prop_img_id,
					))){
						throwException(e);
					}
					foreach($_POST['ItemPropImgs']['pic'] as $pic){
						$itemprop = new  ItemPropImgs;
						$itemprop->item_prop_img_id = $model->item_prop_img_id;
						$itemprop -> pic = $pic;
						if(!$itemprop->save()){
							throw new Exception();
						}
					}
				}
				$this->redirect(array('view', 'id' => $model->item_prop_img_id));
			}
		}
		$this->render('update', array(
			'model' => $model,
		));
	}

	public  function actionDelete($id){
		ItemPropImgs::model()->deleteAllByAttributes(array('item_prop_img_id' =>$id));
		ItemPropImg::model()->deleteByPk($id);
		$model = new ItemPropImg('search');
		$model->unsetAttributes();
		$this->render('admin', array(
			'model' => $model,
		));
	}


	public function loadModel($id)
	{
		$model = ItemPropImg::model()->findByPk($id);
		if ($model === null)
			throw new CHttpException(404, 'The requested page does not exist.');
		return $model;
	}


	public function actionGetProps(){
		if (isset($_GET['item_id'])&& $_GET['item_id']!='') {
			$itemdata = array();
			$data = Item::model()->findAll("item_id=:item_id", array(":item_id" => $_GET['item_id']));
			$props = json_decode($data[0]->props);
			foreach ($props as $key => $values) {
				$itemProp = ItemProp::model()->findAllByPk($key);
				if ($itemProp[0]->is_color_prop == 1) {
					foreach ($values as $value) {
						$itemdata[$value] = Tbfunction::showProps($value);
					}
				}
			}
			echo CHtml::tag("option", array("value" => ''), '', true);
			foreach ($itemdata as $value => $name) {
				echo CHtml::tag("option", array("value" => $value), CHtml::encode($name), true);
			}
		}
	}

}
