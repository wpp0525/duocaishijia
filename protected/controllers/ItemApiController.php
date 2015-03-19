<?php

	/**
	 * Created by PhpStorm.
	 * User: Administrator
	 * Date: 14-12-23
	 * Time: 上午11:29
	 */
	class ItemApiController extends ApiController
	{
		public function  actionItemCreate ()
		{
			$model = new Item();

			$model->title = $_POST['title'];
			$model->min_number = $_POST['min_number'];
			$model->price = $_POST['price'];
			$model->currency = $_POST['currency'];
			$model->outer_id = $_POST['outer_id'];
			$model->country = $_POST['country'];
			$model->state = $_POST['state'];
			$model->city = $_POST['city'];
			$model->desc = $_POST['desc'];
			$model->is_show = $_POST['is_show'];
			$model->is_promote = $_POST['is_promote'];
			$model->is_new = $_POST['is_new'];
			$model->is_promote = $_POST['is_promote'];
			$model->is_hot = $_POST['is_hot'];
			$model->is_best = $_POST['is_best'];
			$model->category_id = $_POST['category_id'];
			$model->item_id = $_POST['item_id'];
			$model->attributes = $_POST;

			$itemsku = json_decode($_POST['sku']);
			$props = array();
			foreach ($itemsku as $pid => $sku1) {
				foreach ($sku1->props as $key=>$skuprops){
					$props[$skuprops] = $key;
				}
				list($sku['props'], $sku['props_name']) = $this->handleItemProps($sku1->props);

				$sku['price'] = $sku1->price;
				$sku['outer_id'] = $sku1->outer_id;
				$skus[] = $sku;
			}
			$_POST['skus'] = $skus;
			$itemprops = array();

			foreach($props as $key=>$prop1){
				$prop = array();
				$prop[] = $key;
				if(isset($itemprops[$prop1])){
					$prop = $itemprops[$prop1];
					$prop[] = $key;
				}
				$itemprops[$prop1] = $prop;
			}

			list($_POST['props'], $_POST['props_name']) = $this->handleItemProps($itemprops);

			$model->skus = $_POST['skus'];
			$model->props = $_POST['props'];
			$model->props_name = $_POST['props_name'];
			$model->create_time = time();


				if ($model->save()) {
					echo "true";
				} else {
					echo "false";
				}
			}



		protected function handleItemProps ($itemProps)
		{

			$props = array();
			$props_name = array();
			foreach ($itemProps as $pid => $vid) {
				$itemProp = ItemProp::model()->findByPk($pid);
				$pname = $itemProp->prop_name;
				if (is_array($vid)) {
					$props[$pid] = array();
					$props_name[$pname] = array();
					foreach ($vid as $v) {
						$props[$pid][] = $pid.':'.$v;
						$propValue = PropValue::model()->findByPk($v);
						$vname = $propValue ? $propValue->value_name : $v;
						$props_name[$pname][] = $pname.':'.$vname;

					}
				} else {
					$props[$pid] = $pid.':'.$vid;
					$propValue = PropValue::model()->findByPk($vid);
					$vname = $propValue ? $propValue->value_name : $vid;
					$props_name[$pname] = $pname.':'.$vname;
				}
			}

			return array(json_encode($props), json_encode($props_name));
		}

		public function actionHandProps(){

			}



	}
