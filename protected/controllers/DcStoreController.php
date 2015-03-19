<?php

	class DcStoreController extends YController {

		public function actionSearchStores(){
			$stores = DcStore::model()->findAll();
			$storesInfo = array(
				'address' => array(),
			);
				foreach ($stores as $store) {
					//门店地址
					$state = Area::model()->findByAttributes(array('area_id' => $store->state))->name;
					$city = Area::model()->findByAttributes(array('area_id' => $store->city))->name;
					$district = Area::model()->findByAttributes(array('area_id' => $store->district))->name;
					$address = $store->address;
					$storeName = $store->store_name;
					$phone = $store->phone;

					$storesInfo['address'][] = $state.$city.$district.$address;
					$storesInfo[$state.$city.$district.$address]['storeName'] = $storeName;
					$storesInfo[$state.$city.$district.$address]['phone'] = $phone;
				}
				$this->renderPartial('map', array('storesInfo' => $storesInfo));
		}
	}