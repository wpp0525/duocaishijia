<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-12
	 * Time: 上午11:31 GMT+8.00
	 */

	class OrderApiController extends ApiController
	{
		/**
		 * @param CAction $action
		 */

		public function afterAction ($action)
		{
			if ($action->id == "create" || $action->id == 'createMeasure') {
				return;
			}

			/** @var DcsjHttpRequest $request */
			$request = Yii::app()->request;

			$model = new OrderLog();

			$flag = $request->getPost('order_id');
			$model->order_id = isset($flag) ? $flag : $request->getPost('measure_id');
			$model->op_name = $action->id;
			$model->result = 'success';
			$model->origin_body = $request->getRawBody();

			switch ($action->id) {
				case 'assign':
					$model->log_text = '指派门店';

					break;
				case 'pay':
					$model->log_text = '订单支付';

					break;
				case 'measureConfirm':
					$model->log_text = '订单测量确认';

					break;
				case 'measureUpdate':
					$model->log_text = '订单测量信息更新';

					break;
				case 'doConfirm':
					$model->log_text = '订单处理确认';

					break;
				case 'doConstruction':
					$model->log_text = '订单施工中';

					break;
				case 'doUpdate':
					$model->log_text = '订单实际施工面积更新';

					break;
				case 'doComplete':
					$model->log_text = '订单施工完成';

					break;
				case 'search':
					$model->log_text = '订单查询';

					break;
				default:
					break;
			}

			if ($model->validate()) {
				if (!$model->save()) {
					Yii::log('Fail to save order log', 'error');
				}
			}
		}

		public function writelog ($str){
			$file = Yii::app()->baseUrl."log.txt";

			if(!file_exists($file)){
				mkdir($file);
			}

			$fp = fopen($file, "a");
			$flag = fwrite($fp, var_export($str,true).";\n");
			fclose($fp);

		}

		/* 订单创建和更新， 订单创建，  测量单更新。
		  测量单更新以及更新时候，如果订单存在数据，更新，不存在，则插入到订单里
         */
		public function actionCreate ()
		{
			/** @var UserModule $webUser */
			$webUser = Yii::app()->user;

			/*post的数据写到日记里面*/
			$log = $_POST;
			$this->writelog($log);

			$prods = json_decode($_POST['prods']); //订单的信息
			$measureId = $_POST['measure_id'];  //$measureId 是所有的原始单号,如果$_POST['measure_id']为空则，根据属性新建订单，如果不为空，则更新order， $_POST['order_id'] 目前不使用
			$confirm_memo = $_POST['confirm_memo'];
			$measures = json_decode($_POST['result'],true); //预约单的信息

			/*如果测量单存在，则测量单更新，如果不存在则下面订单就不执行 */
			$orderMeasure = ArOrderMeasure::model()->findByAttributes(array(
				'erp_measure_id' => $measureId
			));
//			var_dump($orderMeasure->erp_measure_id);

			if(isset($orderMeasure->erp_measure_id) ){
				if(isset($measureId)){  //如果能查到单号，且传的不是空单的情况下，执行更新语句,同时把信息插入到订单中
					$orderMeasure->memo = $confirm_memo;
					$orderMeasure->update_time = date('Y-m-d H:i:s', time());
					$orderMeasure->status = 1;
					$orderMeasure->news = 1;
					$orderMeasure->news = 1;

					if($orderMeasure->save()) {
						/*下面是明细信息的处理，如果数据多了则删除，少了增加*/
						foreach ($measures as $measure) {
//     						var_dump($measure);
							$category  = category::model()->findByAttributes(array(
								'erpID' => $measure['category_item_id'],
							));
							$measureItemInfo = ArOrderMeasureItem::model()->findByAttributes(array(
								'measure_id'       => $orderMeasure->measure_id,
								'category_item_id' => $category->category_id,
							));

							if( isset($measureItemInfo) ==false){ //如果是新增行，则创建对象
								$measureItemInfo =  new ArOrderMeasureItem();
								$measureItemInfo->measure_id = $orderMeasure->measure_id;

								$measureItemInfo->rst_area = $measure->area;
								$measureItemInfo->rst_memo = $measure->rst_memo;
								$measureItemInfo->create_time = date('Y-m-d H:i:s', time());
								$measureItemInfo->update_time = date('Y-m-d H:i:s', time());
								$measureItemInfo->category_item_id = $category->category_id;
							}else{  //如果不是新增行，则创建修改
								$measureItemInfo->rst_area = $measure->area;
								$measureItemInfo->rst_memo = $measure->rst_memo;
								$measureItemInfo->update_time = date('Y-m-d H:i:s', time());
							}
								if ($measureItemInfo->validate()) {
									if (!$measureItemInfo->save()) {
										throw new DbSaveException(array(
											'code' =>'240',
											'success'  => false,
											'msg'      => $this->modelError($measureItemInfo),
											'measureId'      => $measureId,
											'categoryItmeId' => $measure['item_id'],
										));
									}else{   //保存成功打印信息
//										$json2 = array(
//											'code' =>'100',
//											'success'  => true,
//											'msg' => $measureId,
//										);
//										$json3 =$this->echoJson($json2);
										//echo  $json3;
									}
								}else{
									throw new InvalidDataException(array(
										'code' =>'250',
										'success'  => false,
										'msg' => $this->modelError($measureItemInfo),
									));
								}
//							}
						}
					}else{
						throw new DbSaveException(array(
							'code' =>'240',
							'success'  => false,
							'msg' => $this->modelError($orderMeasure),
							'measureId' => $measureId,
						));
					}
				}
			}

			/*订单的处理,如果$_POST['measure_id']为空则，根据属性新建订单，
			 * 如果不为空，则更新order，	$_POST['order_id'] 目前不使用
			 */

			$order = Order::model()->findByAttributes(array(
				'erp_order_id'  =>  $measureId,
				'receiver_mobile'  =>  $_POST['client_mobile'],  //手机号要唯一
			));
			$new  = 0;
			if(!count($order)){  //如果订单不存在，则产生新的订单号,把传过来的值作为erpid记录
				$order = new Order();
				$order->order_id = F::get_order_id();
				$order->erp_order_id = $measureId;
//				$order->status = Order::ORDER_CREATE;
				$order->status = Order::ORDER_MEASUREMENT;

			}else{
				$new =1;
				$order->status =Order::ORDER_MEASUREMENT;
			}

			$order->user_id = $_POST['client_id'] ? $_POST['client_id']:'999999'  ;
			$order->receiver_name = $_POST['client_name'];
			$order->receiver_mobile = $_POST['client_mobile']; //手机号是必填项
			$order->receiver_phone = $_POST['client_phone'];
			$order->receiver_state = $_POST['client_state'];
			$order->receiver_city = $_POST['client_city'];
			$order->receiver_district = $_POST['client_district'];
			$order->receiver_address = $_POST['client_address'];

			$order->memo = $_POST['order_memo'];
			$order->order_source_type = $_POST['order_source_type'];
			$order->order_source = $_POST['order_source_id'];
			$order->create_time = time();
			$order->news = 1;
			$order->pay_method_id = 1;
			$order->pay_fee = $_POST['pay_fee'];
			$order->total_fee = $_POST['total_fee'];

			$totalFee = 0 ;

			if ($order->validate()) {
				try{
					if ($order->save()){
						foreach($prods as $prod){ //如果产品明细
							/*考虑增加一行的情况*/
							$OrderItem2 =  OrderItem::model()->findByAttributes(array(
								'order_id' => $order->order_id,
								'line_no' => $prod->line_no,
							));

							if(count($OrderItem2) == 0){  // 如果是新增行，则新建model
								$OrderItem = new OrderItem();
							}else{
								//如果是更新行，更新价格，面积和金额
								$OrderItem =  OrderItem::model()->findByAttributes(array(
									'order_id' => $order->order_id,
									'line_no' => $prod->line_no,
								));
								if(!count($OrderItem)){
									throw new NotFoundException(array(
										'order_id' => $order->order_id,
										'line_no' => $prod->line_no,
									));
								}
							}

							/*如果是新数据，则把所有的值维护一下*/
							$item = Item::model()->findByAttributes(array(
								'erpID' =>$prod->prod_id,
							));
							if(!count($OrderItem)){
								throw new NotFoundException(array(
									'code' =>'240',
									'success'  => false,
									'msg' => $prod->prod_id,
								));
							}

							$OrderItem->order_id = $order->order_id;
							$OrderItem->item_id = $item->item_id;
							$OrderItem->title = $item->title;
							$OrderItem->desc = $item->desc;
//							$OrderItem->pic = $item->getMainPic();
							//$model->props = $order->props;  //字段需要维护 ,这个比较麻烦
							$OrderItem ->line_no = $prod ->line_no;
							$OrderItem ->price = $prod ->prod_price;
							$OrderItem->area = $prod->prop_area;
							//$model->total_price = $model->price * $model->area ;
							/* 结束测试*/

							/*保存 order_item的数据*/
							if ($OrderItem->validate()) {
								if (!$OrderItem->save()) {
									throw new DbSaveException(array(
										'msg' => $this->modelError($OrderItem),
									));
								}
								//$totalFee += $model->total_price;
							} else{
								throw new InvalidDataException(array(
									'msg' => $this->modelError($OrderItem),
								));
							}
						}

						/* 如果删除一行数据，金额需要重新考虑*/
						$arr22 = array();
						foreach($prods as $prod){
							$arr22[] = $prod->line_no;
						}
						$criteria = new CDbCriteria();
						$criteria->select = '* ';
						$criteria->distinct = true;
						$criteria->addNotInCondition('line_no',$arr22);
						$criteria->addCondition('order_id =:order_id');
						$criteria->params[':order_id'] = $order->order_id;
						$orderItems = OrderItem::model()->deleteAll($criteria);

						/*金额从新计算*/
						$OrderItemP=  OrderItem::model()->findByAttributes(array(
							'order_id' => $order->order_id,
						));
						foreach($OrderItemP as $OrderItem5 ){
							$totalFee += $OrderItem5->total_price;
						}

//						$order->total_fee = $totalFee;
						//保存order的数据
						if(!$order->save()){
							throw new DbSaveException(array(
								'code'  => '240',
								'success'  => false,
								'msg' => $this->modelError($OrderItem),
							));
						}

						$orderLog = new OrderLog();

						$orderLog->order_id = $order->order_id;
						$orderLog->op_name = 'create';
						$orderLog->result = 'success';
						$orderLog->origin_body = Yii::app()->request->getRawBody();
						$orderLog->log_text = '创建订单';

						if ($orderLog->validate()) {
							if (!$orderLog->save()){
								Yii::log('Fail to save order log', 'error');
							}
						}
						$response = array(
							'code'  => '100',
							'success'  => true,
							'msg'=> $order->order_id,
							'order_id' => $order->order_id,
						);
						$this->echoJson($response);
					}
				}catch (CException $error) {
					throw new DcsjException( Yii::t('orderApi', 'Fail to save order1. error: {error}',
					     	                   array('{error}' => $error->getMessage())), $error->getCode()
					                       );
				}
			} else {
				throw new InvalidDataException (array(
					'code' =>'250',
					'success'  => false,
					'msg' => $this->modelError($order),
				   ));
			}
		}

//		public function actionCreate ()
//		{
//			/** @var UserModule $webUser */
//			$webUser = Yii::app()->user;
//
//           /*post的数据写到日记里面*/
//     	   $log = $_POST;
//           $this->writelog($log);
//
//
//
//			$prods = json_decode($_POST['prods']);
//			$measureId = $_POST['measure_id']; //$measureId 是所有的原始单号,如果$_POST['measure_id']为空则，根据属性新建订单，如果不为空，则更新order， $_POST['order_id'] 目前不使用
//
//			$confirm_memo = $_POST['confirm_memo'];
//			$measures = json_decode($_POST['result'],true);
//
//			/*如果测量单存在，则测量单更新，如果不存在则下面订单就不执行 */
//		 	$orderMeasure = ArOrderMeasure::model()->findByAttributes(array(
//				'erp_measure_id' => $measureId
//			));
//			if(isset($orderMeasure)){
//			  if(isset($measureId)){ //如果能查到单号，且传的不是空单的情况下，执行更新语句
//				$orderMeasure->memo = $confirm_memo;
//				$orderMeasure->update_time = date('Y-m-d H:i:s', time());
//				$orderMeasure->status = 1;
//				if ($orderMeasure->save()) {
//					foreach ($measures as $measure) {
//						 $category  = category::model()->findByAttributes(array(
//							        'erpID' => $measure['category_item_id'],
//						        ));
//						var_dump($orderMeasure->measure_id);
//						$measureItemInfo = ArOrderMeasureItem::model()->findByAttributes(array(
//							'measure_id'       => $orderMeasure->measure_id,
//							'category_item_id' => $category->category_id,
//						));
//					 	var_dump($measureItemInfo);
//						if (isset($measureItemInfo)) {
//
//							$measureItemInfo->rst_area = $measure->area;
//							$measureItemInfo->rst_memo = $measure->rst_memo;
//
//							if ($measureItemInfo->validate()) {
//								if (!$measureItemInfo->save()) {
//									echo 'dd';
//									throw new DbSaveException(array(
//										'code' =>'240',
//										'success'  => false,
//										'measureId'      => $measureId,
//										'categoryItmeId' => $measure['item_id'],
//										'msg'      => $this->modelError($measureItemInfo),
//									));
//								}else{  //保存成功打印信息
//									$json2 = array(
//										    'code' =>'100',
//											'success'  => true,
//											'msg' => $measureId,
//										);
//									$json3 =$this->echoJson($json2);
//									echo  $json3;
//								}
//							}else{
//								throw new InvalidDataException(array(
//									'code' =>'250',
//									'success'  => false,
//									'msg' => $this->modelError($measureItemInfo),
//								));
//							}
//						}
//					}
//				}else{
//						throw new DbSaveException(array(
//							'code' =>'240',
//							'success'  => false,
//							'measureId' => $measureId,
//							'msg' => $this->modelError($orderMeasure),
//						));
//				}
// 			  }
//			}
//
//         /*订单的处理,如果$_POST['measure_id']为空则，根据属性新建订单，
//			如果不为空，则更新order，	$_POST['order_id'] 目前不使用 */
////           if(isset($measureId)&&isset($orderMeasure)){
////              }else{
//			$order = Order::model()->findByAttributes(array(
//				// 'erp_order_id'  => $_POST['order_id'],
//				 'erp_order_id'  =>  $measureId,
//				 'receiver_mobile'  =>  $_POST['client_mobile'],  //手机号要唯一
//			));
//			$new  = 0;
//			if(!count($order)){  //如果订单不存在，则产生新的订单号,把传过来的值作为erpid记录
//				$order = new Order();
//				$order->order_id = F::get_order_id();
//				//$order->erp_order_id = $_POST['order_id'];
//				$order->erp_order_id = $measureId;
//				$order->status = Order::ORDER_CREATE;
//
//			}else{
//				$new =1;
//				$order->status =Order::ORDER_MEASUREMENT ;
//			}
//
//			$order->user_id = $_POST['client_id'] ? $_POST['client_id']:'999999'  ;
//			$order->receiver_name = $_POST['client_name'];
//			$order->receiver_mobile = $_POST['client_mobile']; //手机号是必填项
//			$order->receiver_phone = $_POST['client_phone'];
//			$order->receiver_state = $_POST['client_state'];
//			$order->receiver_city = $_POST['client_city'];
//			$order->receiver_district = $_POST['client_district'];
//			$order->receiver_address = $_POST['client_address'];
//
//			$order->memo = $_POST['order_memo'];
//			$order->order_source_type = $_POST['order_source_type'];
//			$order->order_source = $_POST['order_source_id'];
//			$order->create_time = time();
//			$order->news = 1;
//			$order->pay_method_id = 1;
//
//			$totalFee = 0 ;
//
//			if ($order->validate()) {
//				try{
//					if ($order->save()){
//						foreach($prods as $prod){  //如果产品增加一行数据或者删除一条数据，没考虑到
//							/*考虑增加一行的情况*/
//							$model2 =  OrderItem::model()->findByAttributes(array(
//								'order_id' => $order->order_id,
//								'line_no' => $prod->line_no,
//							));
//
//							if(count($model2) == 0){  //如果是新增行，则新建model
//								$model = new OrderItem();
//							}else{
//								//如果是更新行，更新价格，面积和金额
//								$model =  OrderItem::model()->findByAttributes(array(
//									'order_id' => $order->order_id,
//									'line_no' => $prod->line_no,
//								));
//								if(!count($model)){
//									throw new NotFoundException(array(
//										'order_id' => $order->order_id,
//										'line_no' => $prod->line_no,
//									));
//								}
//							}
//
//							/*如果是新数据，则把所有的值维护一下*/
//							$item = Item::model()->findByAttributes(array(
//								'erpID' =>$prod->prod_id,
//							    ));
//							if(!count($item)){
//								throw new NotFoundException(array(
//									'itemID' => $prod->prod_id,
//								));
//							}
//
//							$model->order_id = $order->order_id;
//							$model->item_id = $item->item_id;
//							$model->title = $item->title;
//							$model->desc = $item->desc;
//							$model->pic = $item->getMainPic(); //字段需要维护
//							//$model->props = $order->props;  //字段需要维护 ,这个比较麻烦
//							$model ->line_no = $prod ->line_no;
//							$model ->price = $prod ->prod_price;
//							$model->area = $prod->prop_area;
//							//$model->total_price = $model->price * $model->area ;
//							/* 结束测试*/
//
//							/*保存 order_item的数据*/
//							if ($model->validate()) {
//								if (!$model->save()) {
//									throw new DbSaveException(array(
//										'msg' => $this->modelError($model),
//									));
//								}
//								//$totalFee += $model->total_price;
//							} else{
//								throw new InvalidDataException(array(
//									'msg' => $this->modelError($model),
//								));
//							}
//						}
//						/* 如果删除一行数据，金额需要重新考虑*/
//						$arr22 = array();
//						foreach($prods as $prod){
//							 $arr22[] = $prod->line_no;
//						}
//						$criteria = new CDbCriteria();
//						$criteria->select = '* ';
//						$criteria->distinct = true;
//						$criteria->addNotInCondition('line_no',$arr22);
//						$criteria->addCondition('order_id =:order_id');
//						$criteria->params[':order_id'] = $order->order_id;
//						$orderItems = OrderItem::model()->deleteAll($criteria);
//
//						/*金额从新计算*/
//						$OrderItemP=  OrderItem::model()->findByAttributes(array(
//							'order_id' => $order->order_id,
//						));
//						foreach($OrderItemP as $OrderItem ){
//							$totalFee += $model->total_price;
//						}
//
//						$order -> total_fee = $totalFee;
//						//保存order的数据
//						if(!$order->save()){
//							throw new DbSaveException(array(
//								'msg' => $this->modelError($model),
//							));
//						}
//
//						$orderLog = new OrderLog();
//
//						$orderLog->order_id = $order->order_id;
//						$orderLog->op_name = 'create';
//						$orderLog->result = 'success';
//						$orderLog->origin_body = Yii::app()->request->getRawBody();
//						$orderLog->log_text = '创建订单';
//
//						if ($orderLog->validate()) {
//							if (!$orderLog->save()){
//								Yii::log('Fail to save order log', 'error');
//							}
//						}
//						$response = array(
//
//							'success'  => true,
//							'order_id' => $order->order_id,
//						);
//						$this->echoJson($response);
//					}
//				} catch (CException $error) {
//					throw new DcsjException(Yii::t('orderApi', 'Fail to save order1. error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
//				}
//			} else {
//				throw new InvalidDataException (array(
//					'code' =>'250',
//					'success'  => false,
//					'msg2' => $this->modelError($order),
//				));
//			}
//		  }
//		}


		/**
		 * @throws NotFoundException
		 * @throws DbSaveException
		 * 订单分配  状态：2
		 */
		public function actionAssign ()
		{
			$log = $_POST;
			$this->writelog($log);

			$orderId = $_POST['order_id'];
			$storeId = $_POST['store_id'];

			$order = Order::model()->findByAttributes(array(
				'erp_order_id'  => $orderId,
			));

			if(!count($order)){
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}

			if (isset($order)) {
				$store = DcStore::model()->findByAttributes(array(
					'erpID' => $storeId,
				));

				if(!count($store)){
					throw new NotFoundException(array(
						'storeID' => $storeId,
					));
				}

				$order->store_id = $store->store_id;
				$order->update_time = time();
				$order->status = Order::ORDER_DISPATCH;

				if ($order->update()) {
					$this->echoJson(array(
						'success' => true,
					));
				} else {
					throw new DbSaveException(array(
						'orderId' => $orderId,
						'storeId' => $storeId,
						'msg' => $this->modelError($order),
					));
				}
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}

		}
         /* ERP post过来的数据
          * 状态为 8 .
          * 如果同时付款应该考虑到，比如线上付款了，线下付款了。
          * */
		public function actionPay ()
		{
			/** @var UserModule $webUser */
			$webUser = Yii::app()->user;

			$this->writelog('支付完成接口,doComplete');

			$log = $_POST;
			$this->writelog($log);

			$orderId = $_POST['order_id'];
			$paymentSn = Payment::model()->findByAttributes(array(
				'payment_sn' => $_POST['payment_sn'],
			));

			if(isset($paymentSn)) {
				$this->echoJson(array(
					'code'     =>'240',
					'success'   => false,
					'msg'       => 'Already exists！',
				));
			} else {
				$payment = new Payment();
				$order = Order::model()->findByAttributes(array(
					'erp_order_id'  => $orderId,
				));

				if(!count($order)){
					throw new NotFoundException(array(
						'code'     =>'250',
						'success'   => false,
						'erp_order_Id' => $orderId,
					));
				}

				if (isset($order)) {
					$payFee = $order->pay_fee;
					$status = $order->status;

					$order->pay_fee += $_POST['amount'];
					$order->update_time = time();

					if ($order->total_fee - $order->pay_fee == 0) {
						if ($status == Order::ORDER_ACCEPTANCE) {
							$order->status = Order::ORDER_COMPLETE;
						} else {
							$order->status = Order::ORDER_ACCOUNT;
						}

						$payment->status = Payment::PAY_FULL;
						$order->pay_status = 3;   // 付全款完毕，pay_status在 tbfunction 定义了已支付状态
					} else if ($payFee == 0) {
						$order->pay_time = time();
						$payment->status = Payment::PAY_ADVANCE;
						$order->pay_status = 1;   // 预付款完毕，pay_status在tbfunction定义了已支付状态 ，第一次付款付款状态为0
					} else {
						$payment->status = Payment::PAY_FINAL;
						$order->pay_status = 2;   //尾款，才算付款，pay_status在tbfunction定义了已支付状态
					}

					try {
						if (!$order->save()) {
							echo 'order save failed!';
								throw new DbSaveException(array(
									'orderId' => $orderId,
									'msg' => $this->modelError($order),
								));
						}

						$payment->order_id = $order->order_id;
						$payment->payment_sn = $_POST['payment_sn'];
						$payment->account = $_POST['account'];   //支付方法, 1代表支付宝,2代表微信,8代表pos结算,9代表现金
						$payment->money = $_POST['amount'];
						$payment->user_id = $order->user_id;    //默认是谁的订单谁付款
						$payment->create_time = time();
						$payment->pay_account = '123';
						if (!$payment->save()) {
						  echo 'payment save failed!';
							throw new DbSaveException(array(
								'orderId' => $orderId,
								'msg' => $this->modelError($payment),
							));
						}

						$this->echoJson(array(
							'code'   => 100,
							'success'   => true,
							'total_fee' => $order->total_fee,
							'pay_fee'   => $order->pay_fee,
						));
					} catch (CException $error) {
						throw new DcsjException(Yii::t('orderApi', 'Fail to save payment. error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
					}
				} else {
					throw new NotFoundException(array(
						'orderId' => $orderId,
					));
				}
			}
		}

		public function actionMeasureConfirm ()
		{
			$measureId = $_POST['measure_id'];

			$orderMeasure = ArOrderMeasure::model()->findByPk($measureId);

			if (isset($orderMeasure)) {
				$orderMeasure->memo = $_POST['confirm_memo'];
				$orderMeasure->update_time = date('Y-m-d H:i:s', time());

				if (!$orderMeasure->save()) {
					throw new DbSaveException(array(
						'measureId' => $measureId,
						'msg' => $this->modelError($orderMeasure),
					));
				} else {
					$this->echoJson(array(
						'success' => true
					));
				}
			} else {
				throw new NotFoundException(array(
					'measureId' => $measureId,
				));
			}
		}

		public function actionMeasureUpdate ()
		{
			$measureId = $_POST['measure_id'];
			$confirm_memo = $_POST['confirm_memo'];
			$measures = json_decode($_POST['result'],true);

			if (!empty($measures)) {
				foreach ($measures as $measure) {
					$orderMeasure = ArOrderMeasure::model()->findByPk($measureId);

					$measureItemInfo = ArOrderMeasureItem::model()->findByAttributes(array(
						'measure_id'       => $measureId,
						'category_item_id' => $measure['item_id'],
					));

					$orderMeasure->memo = $confirm_memo;
					$orderMeasure->update_time = date('Y-m-d H:i:s', time());
					if (!$orderMeasure->save()) {
						throw new DbSaveException(array(
							'measureId' => $measureId,
							'msg' => $this->modelError($orderMeasure),
						));
					}
					if (isset($measureItemInfo)) {
						$measureItemInfo->rst_area = $measure['area'];
						$measureItemInfo->rst_memo = $measure['rst_memo'];

						if ($measureItemInfo->validate()) {
							if (!$measureItemInfo->save()) {
								throw new DbSaveException(array(
									'measureId' => $measureId,
									'categoryItmeId' => $measure['item_id'],
									'msg' => $this->modelError($measureItemInfo),
								));
							}
						} else {
							throw new InvalidDataException(array(
								'measure_id' => $measureId,
								'category_item_id' =>$measure['item_id'],
								'msg' =>$this->modelError($measureItemInfo),
							));
						}
					} else {
						throw new NotFoundException(array(
							'$measureId' => $measureId,
							'measureId'      => $measureId,
							'categoryItemId' => $measure["item_id"]

						));
					}
				}
				$this->echoJson(array(
					'success' => true
				));
			}
		}

		/**
		 * @throws NotFoundException
		 * @throws DbSaveException
		 * 订单确认  状态 ：1
		 */
		public function actionDoConfirm ()
		{
			$orderId = $_POST['order_id'];

			$order = Order::model()->findByAttributes(array(
				'erp_order_id'  => $orderId,
			));
			if(!count($order)){
				throw new NotFoundException(array(
					'erp_order_Id' => $orderId,
				));
			}

			if (isset($order)) {
				$order->status = Order::ORDER_CONFIRM;
				$order->update_time = time();

				if (!$order->update()) {
					throw new DbSaveException(array(
						'orderId' => $orderId,
						'msg' => $this->modelError($order),
					));
				}

				$this->echoJson(array('success' => true));
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}
		}

		/**
		 * @throws NotFoundException
		 * @throws DbSaveException
		 * 施工中  状态：4
		 */
		public function actionDoConstruction(){
			$orderId = $_POST["order_id"];

			$order = Order::model()->findByAttributes(array(
				'erp_order_id'  => $orderId,
			));
			if(!count($order)){
				throw new NotFoundException(array(
					'erp_order_Id' => $orderId,
				));
			}
			if (isset($order)) {
				$order->status = Order::ORDER_UNDER_CONSTRUCTION;
				$order->update_time = time();

				if (!$order->update()) {
					throw new DbSaveException(array(
						'orderId' => $orderId,
						'msg' => $this->modelError($order),
					));
				}


				$this->echoJson(array('success' => true));
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}
		}

		/**
		 * @throws DcsjException
		 * 更新准确值  状态：已测量 3
		 */
		public function actionDoUpdate ()
		{
			$items = json_decode($_POST['result']);
			$measures = $items;
			$orderId = $_POST['order_id'];
			$measureId = $_POST['measure_id'];
			$confirm_memo = $_POST['confirm_memo'];

			$totalFee = 0;
			try {
				$order = Order::model()->findByAttributes(array(
					'erp_order_id' => $orderId,
				));
				if (!count($order)) {
					throw new NotFoundException(array(
						'erp_order_Id' => $orderId,
					));
				}

				if(is_array($items)){
				foreach ($items as $item) {

					$erpItem = Item::model()->findByAttributes(array(
						'erpID' => $item->item_id,
					));
					if(!count($erpItem)){
						throw new NotFoundException(array(
							'item' => $item->item_id,
						));
					}
					$orderItem = OrderItem::model()->findByAttributes(array(
						'order_id' => $order->order_id,
						'item_id'  => $erpItem->item_id,
					));

					if (isset($orderItem)) {
						$orderItem->area = $item->area;
						$orderItem->total_price = $orderItem->area * $orderItem->price;
						if (!$orderItem->save()) {
							throw new DbSaveException(array(
								'erporderId' => $orderId,
								'itemId' => $item->item_id,
								'msg' => $this->modelError($orderItem),
							));
						} else {
							$totalFee += $orderItem->total_price;
						}
					} else {
						throw new NotFoundException(array(
							'orderId' => $orderId,
						));
					}
				}
			}
					$order->total_fee = $totalFee;
					$order->update_time = time();
					$order->status = 3;

					if (!$order->save()) {
						throw new DbSaveException(array(
							'orderId' => $orderId,
							'msg' => $this->modelError($order),
						));
					}

					//测量单更新
				$orderMeasure = ArOrderMeasure::model()->findByAttributes(array(
					'erp_measure_id' => $measureId
				));
				if (isset($orderMeasure)) {
					$orderMeasure->memo = $confirm_memo;
					$orderMeasure->update_time = date('Y-m-d H:i:s', time());
					$orderMeasure->status = 1;
					if ($orderMeasure->save()) {
						foreach ($measures as $measure) {
							$measureItemInfo = ArOrderMeasureItem::model()->findByAttributes(array(
								'measure_id'       => $orderMeasure->measure_id,
								'category_item_id' => $measure->category_item_id,
							));
							if (isset($measureItemInfo)) {
								$measureItemInfo->rst_area = $measure->area;
								$measureItemInfo->rst_memo = $measure->rst_memo;

								if ($measureItemInfo->validate()) {
									if (!$measureItemInfo->save()) {
										throw new DbSaveException(array(
											'measureId'      => $measureId,
											'categoryItmeId' => $measure['item_id'],
											'msg'      => $this->modelError($measureItemInfo),
										));
									}
								}
							}
						}
					} else {
						throw new DbSaveException(array(
							'measureId' => $measureId,
							'msg' => $this->modelError($orderMeasure),
						));
					}
				}
					$response = array(
						'success'   => true,
						'totalFree' => $totalFee,
					);
					$this->echoJson($response);
			} catch (CException $error) {
				throw new DcsjException(Yii::t('orderApi', 'Fail to save order. error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
			}
		}

		/**
		 * @throws NotFoundException
		 * @throws DbSaveException
		 * 订单施工完成 状态：6
		 */
		public function actiondoComplete()
		{
			$log = $_POST;
			$this->writelog('施工完成接口,doComplete');
			$this->writelog($log);

			$orderId = $_POST["order_id"];

			$order = Order::model()->findByAttributes(array(
				'erp_order_id' => $orderId,
			));
			if (!count($order)) {
				new NotFoundException(array(
					'erp_order_Id' => $orderId,
				));
			}
			if (isset($order)) {
				$order->status = Order::ORDER_COMPLETE_CONSTRUCTION;
				$order->update_time = time();

				if (!$order->update()) {
					throw new DbSaveException(array(
						'orderId' => $orderId,
						'msg' => $this->modelError($order),
					));
				}

				$this->echoJson(array(
					'code' =>'100',
					'success' => true,
					'msg' => $orderId,
				));
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}

		}

		public function actionSearch ()
		{
			$orderId = $_POST['order_id'];

//			$model = Order::model()->findByPk($orderId);
			$model = Order::model()->findByAttributes(array(
				'erp_order_id' => $orderId,
			));
			if (!count($model)) {
				throw new NotFoundException(array(
					'erp_order_Id' => $orderId,
				));
			}
			if (isset($model)) {
				$model->with('orderItems');
				$model->with('orderLogs');
				$model->with('payments');
				$model->with('refunds');
				$model->with('users');

				$result = array(
					'user'      => array(
						'userId'   => $model->user_id,
						'userName' => $model->users->username,
					),
					'order'     => array(
						'orderId'    => $orderId,
						'userName'   => $model->receiver_name,
						'status'     => $model->getOrderStatus(),
						'totalFee'   => $model->total_fee,
						'payFee'     => $model->pay_fee,
						'state'      => $model->getArea($model->receiver_state),
						'city'       => $model->getArea($model->receiver_city),
						'district'   => $model->getArea($model->receiver_district),
						'address'    => $model->receiver_address,
						'zip'        => $model->receiver_zip,
						'mobile'     => $model->receiver_mobile,
						'phone'      => $model->receiver_phone,
						'memo'       => $model->memo,
						'sourceType' => $model->order_source_type,
						'source'     => $model->order_source,
						'payTime'    => date('Y-m-d H:i:s', $model->pay_time),
						'createTime' => date('Y-m-d H:i:s', $model->create_time),
					),
					'orderItem' => array(),
					'pay'       => array(),
					'refund'    => array(
						'sn'         => $model->refunds->refunds_sn,
						'money'      => $model->refunds->money,
						'status'     => $model->refunds->status,
						'createTime' => $model->refunds->create_time,
					),
					'orderLog'  => array(),
				);

				if (isset($model->orderItems)) {
					foreach ($model->orderItems as $item) {
						$result['orderItem'][] = array(
							'itemName'   => $item->title,
							'desc'       => $item->desc,
							'price'      => $item->price,
							'area'       => $item->area,
							'totalPrice' => $item->total_price,
						);
					}
				}

				if (isset($model->payments)) {
					foreach ($model->payments as $payment) {
						$result['pay'][] = array(
							'sn'         => $payment->payment_sn,
							'money'      => $payment->money,
							'status'     => $payment->getPayStatus(),
							'createTime' => date('Y-m-d H:i:s', $payment->create_time),
						);
					}
				}

				if (isset($model->orderLogs)) {
					foreach ($model->orderLogs as $log) {
						$result['orderLog'][] = array(
							'actionName' => $log->op_name,
							'text'       => $log->log_text,
							'originBody' => $log->origin_body,
							'actionTime' => $log->action_time,
						);
					}
				}


				$this->echoJson($result);
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}
		}

		/**
		 * @throws NotFoundException
		 * @throws DbSaveException
		 * 订单验收 状态：已验收：7 并且已结算 8  状态：完成：9
		 */

		public function actionUserAccept ()
		{
			$orderId = $_POST['order_id'];

//			$model = Order::model()->findByPk($orderId);

			$model = Order::model()->findByAttributes(array(
				'erp_order_id' => $orderId,
			));
			if (!count($model)) {
				throw new NotFoundException(array(
					'erp_order_Id' => $orderId,
				));
			}
			if (isset($model)) {
				if ($model->status == Order::ORDER_ACCOUNT) {
					$model->status = Order::ORDER_COMPLETE;
				} else {
					$model->status = Order::ORDER_ACCEPTANCE;
				}

				if (!$model->save()) {
					throw new DbSaveException(array(
						'orderId' => $orderId,
						'msg' => $this->modelError($model),
					));
				}

				$this->echoJson(array('success' => true));
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}
		}


		/**
		 * @throws CDbException
		 * 这个函数是处理由erp传递过来的线下预约订单，在多彩商城上创建的一条线下订单
		 */
		public function actionCreateMeasureOffline ()
		{

			/** @var UserModule $webUser */
			$log = $_POST;
			$this->writelog($log);

			$model = new ArOrderMeasure();

			$client_mobile = $_POST['client_mobile'];

 			$model->user_id = User::model()->MeasureCreateUser($client_mobile);

			$model->erp_measure_id = $_POST['erp_measure_id'];
			$model->client_name = $_POST['client_name'];
			$model->client_state = $_POST['client_state'];
			$model->client_city = $_POST['client_city'];
			// $model->client_district = $_POST['client_district'];
			$model->client_address = $_POST['client_address'];
			$model->client_mobile = $_POST['client_mobile'];
			$model->client_memo = $_POST['client_memo'];
			$model->total = $_POST['total'];
			$model->create_time = date('Y-m-d H:i:s', time());
			$model->news = 1;

			if ($model->validate()) {
				try {
					if ($model->save()) {
						$prods  = json_decode($_POST['prods']);

						foreach( $prods as $order_measure_item) {
							$measureItem = new ArOrderMeasureItem();
							$category  = category::model()->findByAttributes(array(
								'erpID' => $order_measure_item->category_item_id,
							));

							$measureItem->category_item_id = $category->category_id;
							$measureItem->rst_memo  = $order_measure_item->rst_memo;

							$measureItem->measure_id = $model->measure_id;
							$measureItem->create_time = $model->create_time;

							if ($measureItem->validate()) {
								if (!$measureItem->save()) {
										Yii::log('Fail to save order measure item2.', 'error');
										throw new DbSaveException(array(
											'code'     => '240',
											'success'  => false,
											'msg' => $this->modelError($measureItem),
									));
								}
							}else{
								throw new InvalidDataException(array(
									'code'     => '250',
									'success'  => false,
									'msg' => $this->modelError($measureItem),
								 ));
							}
						}

						$orderLog = new OrderLog();
						$orderLog->order_id = $model->measure_id;
						$orderLog->op_name = 'create';
						$orderLog->result = 'success';
						$orderLog->origin_body = Yii::app()->request->getRawBody();
						$orderLog->log_text = '线下erp创建预约单';

						if ($orderLog->validate()) {
							if (!$orderLog->save()) {
								Yii::log('Fail to save order log', 'error');
							}
						}

						$this->echoJson(array(
							'code'     => '100',
							'success'  => true,
							'msg'=>$model->measure_id,
							'measure_id' => $model->measure_id,
						));
					}
				} catch (CException $error) {
					throw new DcsjException(Yii::t('orderApi', 'Fail to save order measure3. Error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
				  }
			} else {   //数据校验失败
				throw new InvalidDataException(array(
					'code'     => '250',
					'success'  => false,
					'msg' => $this->modelError($model),
				));
			  }
		}
		/**
		 * @throws CDbException
		 * 多彩内部自己创建预约单，不能放在接口里，这个迁移出去
		 */
//		public function actionCreateMeasure ()
//		{
//
//			/** @var UserModule $webUser */
//			$webUser = Yii::app()->user;
//
//			$model = new ArOrderMeasure();
//
//			if($_POST['user_id'] == null){
//				$model->user_id =  '9998';
//			}else{
//				$model->user_id =   $_POST['user_id'] ;
//			};
////         	$model->user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '9999';
//			$model->client_name = $_POST['client_name'];
//			$model->client_state = $_POST['client_state'];
//			$model->client_city = $_POST['client_city'];
//		    $model->client_district = $_POST['client_district'];
//			$model->client_address = $_POST['client_address'];
//     		$model->client_zip = $_POST['client_zip'];
//			$model->client_mobile = $_POST['client_mobile'];
//  			$model->client_phone = $_POST['client_phone'];
//			$model->client_memo = $_POST['client_memo'];
//			$model->total = $_POST['total'];
//			$model->create_time = date('Y-m-d H:i:s', time());
//
//			$model->news = 1;//创建默认信息是 1
//			$model->measure_time = $_POST['update_time'];  //预约时间
//
//			if ($model->validate()) {
//				try {
//					if ($model->save()) {
//						$i=0;
//
//						foreach ($_POST['category_item_ids'] as $category_item_id) {
//							$measureItem = new ArOrderMeasureItem();
//
//							$rst_area = $_POST['rst_area'][$i];
//							$rst_memo = $_POST['rst_memo'][$i];
//
//							$measureItem->measure_id = $model->measure_id;
//							$measureItem->category_item_id = $category_item_id;
//							$measureItem->create_time = $model->create_time;
//							$measureItem->rst_area = $rst_area;
//							$measureItem->rst_memo = $rst_memo;
//							$i++;
//
//							if ($measureItem->validate()) {
//								if (!$measureItem->save()) {
//									Yii::log('Fail to save order measure item.', 'error');
//									throw new DbSaveException(array(
//										'msg' => $this->modelError($measureItem),
//									));
//								}
//
//							} else {
//								throw new InvalidDataException(array(
//									'msg' => $this->modelError($measureItem),
//								));
//							}
//						}
//
//						/*调用测量单的程序*/
//					   $orderID = $measureItem->measure_id;
//					   $this->postMeasureOrder($orderID);
//                        /*结束测试*/
//
//						$orderLog = new OrderLog();
//
//						$orderLog->order_id = $model->measure_id;
//						$orderLog->op_name = 'create';
//						$orderLog->result = 'success';
//						$orderLog->origin_body = Yii::app()->request->getRawBody();
//						$orderLog->log_text = '创建预约单';
//
//						if ($orderLog->validate()) {
//							if (!$orderLog->save()) {
//								Yii::log('Fail to save order log', 'error');
//							}
//						}
//
//						$this->echoJson(array(
//							'success'    => true,
//							'measure_id' => $model->measure_id,
//						));
//					}
//				} catch (CException $error) {
//					throw new DcsjException(Yii::t('orderApi', 'Fail to save order measure. Error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
//				}
//			} else {
//				throw new InvalidDataException(array(
//					'msg22' => $this->modelError($model),
//				));
//			}
//		}
//          /*post数据到erp系统中*/
//		public function  postMeasureOrder($measure_id){
//
//			/*订单创建完成后，把数据解析到相应的模块 ，预约单没有产品id*/
//			$order_measure = ArOrderMeasure::model()->findByAttributes( array("measure_id" => $measure_id ) );
//
//			$date_reserve =$order_measure->measure_time;   //预约时间目前需要从measure_time拿到
//			$user_id = $order_measure->user_id;
//			$user =   Users::model()->findByAttributes(array( "id" =>$user_id ) );
//			$name_member = $user->username;
//
//			$dec_budamt = $order_measure->total;
//			$var_tel = $order_measure->client_mobile;
//			$id_province = $order_measure->client_state;
//			$id_city = $order_measure->client_city;
//
//			$var_addr = $order_measure->client_address;
//			$var_remark = $order_measure->memo;
// 			$dec_samt = 0;
// 		    $dec_spayamt = 0;
//
////			$skus = sku::model()->findByAttributes(array('item_id' => $item_id ));
//			//  var_dump($receiver_mobile .' -- '. $dec_samt .' --'.$id_province);
//
//			/*主表信息*/
//			$orderss = array();
//			$orderss['date_reserve'] = $date_reserve;
//			$orderss['id_member'] = $user_id;
//			$orderss['name_member'] = $name_member;
//			$orderss['dec_budamt'] = $dec_budamt;
//			$orderss['var_tel'] = $var_tel;
//			$orderss['id_province'] = $id_province;
//			$orderss['id_city'] = $id_city;
//
//			$orderss['var_addr'] = $var_addr;
//			$orderss['var_remark'] = $var_remark;
//			$orderss['dec_samt'] = $dec_samt;
//			$orderss['dec_spayamt'] = $dec_spayamt;
//			$orderss['ori_no'] = $measure_id;
//			$orderss['id_ordsource'] = '002';
//
//			$OrderItem   = ArOrderMeasureItem::model()->findAllByAttributes( array("measure_id" => $measure_id ));
//			/*明细表信息*/
//			$OrderItems = array();
//			$line_no=1;
//			foreach($OrderItem as $OrderItemv){
//				$orders = array();
//
//				$category_id  =  $OrderItemv->category_item_id;
//				$category   =  category::model()->findByAttributes(array( 'category_id' => $category_id ));
//				$id_itemcate_erpid = $category->erpID;
//
//				$orders['line_no']  = $line_no;
//				$orders['id_itemcate']  = $id_itemcate_erpid;  //category表 产品分类 ， 默认是  1
//				$orders['id_series'] = "";
//				$orders['id_roomtype'] = "";     //默认为空
//
//				$orders['id_item']  =  "";       //默认为空
//				$orders['id_color'] =  "";       //默认为空
//				$orders['id_package'] = "";      //默认为空
//
//				$orders['dec_price']  =  0;
//				$orders['dec_measurearea'] = 0;
//				$orders['dec_amt']  = 0;
//				$orders['var_dremark'] = "预约单测试";
//				$orders['var_flag']  = "dsrvmeasure";
//				$OrderItems[] = $orders;
//				$line_no++;
//			}
//
//			$orderss['details'] =$OrderItems;
//			$json2    =  $this->JSON($orderss); // 自己使用的json，中文不转成 unicode码。
//			$json2    =   urlencode($json2);
//			//	$client = new SoapClient("http://183.81.182.11:8080/nerp/services/DCService?wsdl"); //服务器
//	   	    $client = new SoapClient(webservice_url);
//
//			$obj = $client->getOrder(array('paramJson'=>$json2) ) ;
//			var_dump(urldecode($obj->getOrderReturn));
//
//			/*  下面是保存数据 */
//			$returnResult = urldecode($obj->getOrderReturn);
//			$jsobj =	json_decode($returnResult);
//
//			if($jsobj->code == '100' ){
//				$order_measure->erp_measure_id = $jsobj->data[0]->ori_no ;
//				if( $order_measure->validate() ){
//					if( !$order_measure->save() ){
//						dump('保存erp单号失败');
//					}else{
//						echo	'保存成功2';
//					}
//				}
//			}
//			var_dump(json_decode(urldecode($json2)) );
//		}


		public	function arrayRecursive(&$array, $function, $apply_to_keys_also = false)
		{
			static $recursive_counter = 0;
			if (++$recursive_counter > 1000) {
				die('possible deep recursion attack');
			}
			foreach ($array as $key => $value) {
				if (is_array($value)) {
					$this->arrayRecursive($array[$key], $function, $apply_to_keys_also);
				} else {
					$array[$key] = $function($value);
				}
				if ($apply_to_keys_also && is_string($key)) {
					$new_key = $function($key);
					if ($new_key != $key) {
						$array[$new_key] = $array[$key];
						unset($array[$key]);
					}
				}
			}
			$recursive_counter--;
		}

		public 	function JSON($array) {
			$this->arrayRecursive($array, 'urlencode', true);
			$json = json_encode($array);
			return urldecode($json);
		}

		public function actionGetStatus(){
			$orderId = $_POST['order_id'];
			$status = "-1";

			$order = Order::model()->findAllByPk($orderId);

			if(count($order)){
				$status = $order[0]->status ;
			}

			$this->echoJson(array(
				'success'    => true,
				'status' => $status,
			));
		}
	}