<?php

class OrderController extends Controller
{

    /**
     * @var string the default layout for the views. Defaults to '//layouts/column2', meaning
     * using two-column layout. See 'protected/views/layouts/column2.php'.
     */
//    public $layout = '//layouts/column2';

    /**
     * @return array action filters
     */
    public function filters()
    {
        return array(
            'accessControl', // perform access control for CRUD operations
        );
    }

    /**
     * Specifies the access control rules.
     * This method is used by the 'accessControl' filter.
     * @return array access control rules
     */
    public function accessRules()
    {
        return array(
            array('allow', // allow all users to perform 'index' and 'view' actions
                'actions' => array('index', 'view','checkout', 'create', 'update'),
                'users' => array('*'),
            ),
//            array('allow', // allow authenticated user to perform 'create' and 'update' actions
//                'actions' => array(),
//                'users' => array('@'),
//            ),
        );
    }

    /**
     * Displays a particular model.
     * @param integer $id the ID of the model to be displayed
     */
    public function actionView($id)
    {
        $this->render('view', array(
            'model' => $this->loadModel($id),
        ));
    }

    public function actionCheckout()
    {
        if (isset($_POST['position'])) {
            $keys = isset($_REQUEST['position']) ? (is_array($_REQUEST['position']) ? $_REQUEST['position'] : explode('_', $_REQUEST['position'])) : array();
            $this->render('checkout', array('keys' => $keys));
        } else {
            $item = $this->loadItem();
            $item->detachBehavior("CartPosition");
            $item->attachBehavior("CartPosition", new ECartPositionBehaviour());
            $item->setRefresh(true);
            $quantity = empty($_POST['qtys']) ? 1 : $_POST['qtys'];
            $item->setQuantity($quantity);

            $this->render('checkout', array('item' => $item));
        }
    }

    public function loadItem()
    {
	    //TODO $_POST['props'] change to array, Jianglong please try to fixed it.

//	    $props=implode('-',$_POST['props']);
        if (empty($_POST['item_id'])) {
            throw new CHttpException(400, 'Bad Request!.');
        }
        $item = CartItem::model()->with('skus')->findByPk(intval($_POST['item_id']));
        if (empty($item)) {
            throw new CHttpException(400, 'Bad Request!.');
        }
        $item->cartProps = empty($_POST['props']) ? '' : $_POST['props'];

        return $item;
    }

    /**
     * Creates a new model.
     * If creation is successful, the browser will be redirected to the 'view' page.
     */
    public function actionCreate()
    {

        $model = new Order;
            // Uncomment the following line if AJAX validation is needed
            // $this->performAjaxValidation($model);

            if (!$_POST['delivery_address'] && !Yii::app()->user->isGuest) {
                 echo '<script>alert("您还没有添加收货地址！")</script>';
                 echo '<script type="text/javascript">history.go(-1)</script>';
                 } else {
            if (isset($_POST)) {

                $transaction = $model->dbConnection->beginTransaction();
                try {
                    $cart = Yii::app()->cart;
                    if(!Yii::app()->user->isGuest) {
                        $model->attributes = $_POST;
                      /* 这段代码  ，是根据付款方式插入到相应的数据表中
	                    if(is_array($_POST['payment_free_id'])){
		                    $model->is_pay_first = $_POST['payment_free_id'][0];
	                    }

	                    if (isset($_POST['payment_free_id']) && isset($_POST['pay_first'])) {
		                    $payFirst = $_POST['pay_first'];
		                    $model->pay_first = $payFirst;
	                    } else {
		                    $model->pay_first = 0;
		                    $payFirst = $model->total_fee;

	                    } */
	                    if($_POST['Area']['language']=='Y'){ //如果是预付款,则首付款是 100元
		                    $model->is_pay_first = 1;
		                    $model->pay_first = 100;
		                    $payFirst=$model->pay_first;
	                    }
	                    else if ($_POST['Area']['language']=='Q'){ //如果是全款
		                    $model->pay_first = 0;
		                    $payFirst = $model->total_fee;
	                    }else if ($_POST['Area']['language']=='D'){  //如果是先下单后付款
		                    $model->pay_first = 0;
	                    }

	                    $model->pay_method_id =  $_POST['payment_method_id'];

	                    if (isset($_POST['measureTime'])) {
		                    $model ->memo = $_POST['memo']. ' 预约上门时间：'. $_POST['measureTime'].'日'.$_POST['cc_start_time'];
	                    }

                        $model->user_id = Yii::app()->user->id ? Yii::app()->user->id : '0';
                        $cri = new CDbCriteria(array(
                            'condition' => 'contact_id =' . $_POST['delivery_address'] . ' AND user_id = ' . Yii::app()->user->id
                        ));
                        $address = AddressResult::model()->find($cri);
                        $model->receiver_country = $address->country;
                        $model->receiver_name = $address->contact_name;
                        $model->receiver_state = $address->state;
                        $model->receiver_city = $address->city;
                        $model->receiver_district = $address->district;
                        $model->receiver_address = $address->address;
                        $model->receiver_zip = $address->zipcode;
                        $model->receiver_mobile = $address->mobile_phone;
                        $model->receiver_phone = $address->phone;
                    } else {
                        $address = $_POST['AddressResult'];
                        $model->user_id = '0';
                        $model->receiver_name = $address['contact_name'];
                        $model->receiver_state = $address['state'];
                        $model->receiver_city = $address['city'];
                        $model->receiver_district = $address['district'];
                        $model->receiver_address = $address['address'];
                        $model->receiver_zip = $address['zipcode'];
                        $model->receiver_mobile = $address['mobile_phone'];
                        $model->receiver_phone = $address['phone'];

	                    if (isset($_POST['measure_date']) && isset($_POST['measureTime'])) {
		                    $model ->memo = $_POST['memo']. ' 预约上门时间：'. $_POST['measureTime'];
	                    } else {
		                    $model ->memo = $_POST['memo'];
	                    }
                    }

                    $model->create_time = time();
                    $model->order_id = F::get_order_id();
                    $model->total_fee = 0;

	                $model->news = 1;
	                $model->status = 0;

                    if(isset($_POST['keys'])) {
//                        foreach ($_POST['keys'] as $key){
//                            $item= $cart->itemAt($key);
//                            $model->total_fee += $item['quantity'] * $item['price'];
//                        }
	                    $model->total_fee=$_POST['total_fee'];
                    } else {
                        $item = Item::model()->findBypk($_POST['item_id']);
                        $model->total_fee = $_POST['total_fee']; //$item->price * $_POST['area'];

                    }

                    if ($model->save()) {
                        if(empty($_POST['keys']))
                        {
	                        $sku1 = explode(';' ,$_POST['sku_id']);
	                        $area = explode(';' ,$_POST['area']);
	                        $item1 = explode(';' ,$_POST['item_id']);
	                        $i = 0;
	                        $line_no2=1;

	                        foreach ($sku1 as $sku){ //如果两行明细

                            $item = Item::model()->findBypk($item1[$i]);
                            $sku = Sku::model()->findByPk($_POST['sku_id']);
//                            if($sku->stock < $_POST['area'])
//                            {
//                                throw new Exception('stock is not enough!');
//                            }
                            $OrderItem = new OrderItem;

//                            $OrderItem->order_id = $model->order_id;
//                            $OrderItem->item_id = $item->item_id;
//                            $OrderItem->title = $item->title;
//                            $OrderItem->desc = $item->desc;
//                            $OrderItem->pic = $item->getMainPic();
//                            $OrderItem->props_name = $sku->props_name;
//                            $OrderItem->price = $item->price;
//                            $OrderItem->area = $_POST['area'];
//                            $OrderItem->total_price = $OrderItem->area * $OrderItem->price;


                            if (!$OrderItem::model()->saveOrderItem($OrderItem,$model->order_id,$item,$sku->props_name, $area[$i],$sku->price,$line_no2,$sku->props)) {
                                throw new Exception('save order item fail');
                            }
		                        $i++;
		                        $line_no2++;
                          }
                        }
                        else  // 插入操作，值会变换
                        {
	                         $line_no = 1;
                             foreach ($_POST['keys'] as $key){
	                             $item= $cart->itemAt($key);

	                             $skuId = str_replace('Sku', '', $key);
                                 $sku=Sku::model()->findByPk($skuId);

//                                if($sku->stock < $item->quantity){
//                                 throw new Exception('stock is not enough!');
//                                }
                                 //$sku->stock -= $item->quantity;
                                if(!$sku->save()) {
                                 throw new Exception('cut down stock fail');
                                 }

                                $OrderItem = new OrderItem;
                                $OrderItem->order_id = $model->order_id;
                                $OrderItem->item_id = $item['item_id'];
                                $OrderItem->title = $item['title'];
                                $OrderItem->desc = $item['desc'];
                                $OrderItem->pic = $item->getMainPic();

	                            $OrderItem->line_no = $line_no ;
	                            $OrderItem->props = $sku['props']; //相应的post 值拿到
                                $OrderItem->props_name = $sku['props_name'];
                                $OrderItem->price = $sku->price;
                                $OrderItem->area = floatval($item->getQuantity($key));
                                $OrderItem->total_price = $OrderItem->area * $OrderItem->price;

                                if (!$OrderItem->save()) {
                                    throw new Exception('save order item fail');
                                }
	                           $line_no++;
                               $cart->remove($key);
                             }
                        }
                    } else {
                        throw new Exception();
                    }
	                $transaction->commit();

	                /*订单创建完成后，把数据传送到erp系统中 */
	                 $orderID = $model->order_id;
	                 $this->postOrder($orderID);
	                /*拿到返回值，根据返回的值更新同一个订单号*/

	                //跳转支付宝  如果不是 先下订单后付款，则调到支付宝
	                if($_POST['Area']['language']!='D'){
                    $this->redirect(array('alipay/default/alipayapi',
                        'order_id'      => $model->order_id,
                        'amount'        => $payFirst,
                        'pay_method_id' => $_POST['payment_method_id'],
                    ));
	                }else{
		                $this->redirect(
			                array('success',)
		                );
	                }
	               /*支付宝支付成功后.把数据post到erp系统中 */
	               $this->PostOrderPayment($orderID);

                } catch (Exception $e) {
                    $transaction->rollBack();
                     $this->redirect(array('fail'));
	                 dump($e);
                     }
                }
            }
    }
     /*如果选择了支付宝支付，则会post订单的支付数据到erp系统，不选择则不post数据  */

	public function  PostOrderPayment($orderId){
		/*订单创建完成后，把数据解析到相应的模块*/
//		$orderId = 'SC201501000130';
		$Orders =	Order::model()->findByAttributes(array('erp_order_id' => $orderId ) );

	    $payments =	payment::model()->findByAttributes(array('order_id' => $Orders->order_id ) );

		$orders = array();
		$orders['ori_no']  =  $Orders->erp_order_id; //erp系统订单
		$orders['id_setmth']  =  $payments->account; //结算方式
		$orders['dec_payamt']  =  $payments->money;

		$orders['var_billno']  =  $payments->payment_sn; //第三方单号
		$orders['id_bank'] =  $payments->bank;       //银行
		$orders['id_ordsource']  =  "L";           //支付来源
		$orders['var_remark']  =  '支付单测试';       //	备注
         /*循环遍历数组*/
//		$orderss = array();
//		foreach($payments as $OrderItemv){   //$item_erpid  与$id_itemcate_erpid 这两个需要调整
//			$orders = array();
//			$orders['ori_no']  =  $Orders->erp_order_id; //erp系统订单
//			$orders['id_setmth']  =  $OrderItemv->account; //结算方式
//			$orders['dec_payamt']  =  $OrderItemv->money;
//
//			$orders['var_billno']  =  $OrderItemv->payment_sn; //第三方单号
//			$orders['id_bank'] =  $OrderItemv->account;    //银行
//			$orders['id_ordsource']  =  "L";  //支付来源
//			$orders['var_remark']  =  $OrderItemv->money;  //	备注
//			$OrderItems[] =$orders;
//		}
 //          $orderss[] =$OrderItems;


		$json2    =  $this->JSON($orders); // 先转换成json，在转换成urlencode码。
		$json2    =   urlencode($json2);

    	$client = new SoapClient(webservice_url);
		$obj = $client->getPayment(array('paramJson'=>$json2) ) ; //传送过去有相应的函数
		var_dump(urldecode($obj->getOrderReturn));                //传送回来也有相应的函数
		/*  下面是如果成功后，则保存erp传过来的单号到本机 */
		$returnResult = urldecode($obj->getOrderReturn);
		$jsobj =	json_decode($returnResult);

        var_dump($jsobj);
		if($jsobj->code == '100' ){  //如果成功答应相应的信息
			  echo   $jsobj->dsarcv_no;
			  echo   $jsobj->data;
              echo   $jsobj->msg;
		}
//		dump(json_decode(urldecode($json2)));
	}

	/* post订单详细数据到erp系统  */
	public function  postOrder($orderId){

		/*订单创建完成后，把数据解析到相应的模块*/
	    $order_id = $orderId;
		$order = order::model()->findByAttributes( array("order_id" => $order_id ) );
		$date_reserve = date( 'Y-m-d h:m:s',$order->create_time);
		$user_id = $order->user_id;
		$user =   Users::model()->findByAttributes(array( "id" =>$user_id ) );
		$name_member = $user->username;

		$dec_budamt = 0; //这个预约金额为 0
		$var_tel = $order->receiver_mobile;
		$id_province = $order->receiver_state;
		$id_city = $order->receiver_city;

		$var_addr = $order->receiver_address;
		$var_remark = $order->memo;
		$dec_samt = $order->total_fee;
		$dec_spayamt = $order->pay_fee;

		$OrderItem   = OrderItem::model()->findAllByAttributes( array('order_id' => $order_id ));

		//$skus = sku::model()->findByAttributes(array('item_id' => $item_id ));
        //var_dump($receiver_mobile .' -- '. $dec_samt .' --'.$id_itemcate_erpid.' --'.$order_id);

        /*主表信息*/
		$orderss = array();
		$orderss['date_reserve'] = $date_reserve;
		$orderss['id_member'] = $user_id;
		$orderss['name_member'] = $name_member;
		$orderss['dec_budamt'] = $dec_budamt;
		$orderss['var_tel'] = $var_tel;
		$orderss['id_province'] = $id_province;
		$orderss['id_city'] = $id_city;

		$orderss['var_addr'] = $var_addr;
		$orderss['var_remark'] = $var_remark;
		$orderss['dec_samt'] = $dec_samt;
		$orderss['dec_spayamt'] = $dec_spayamt;
		$orderss['ori_no'] = $order_id;
		$orderss['id_ordsource'] = 'L';

		/*明细表信息*/
		$OrderItems = array();
		foreach($OrderItem as $OrderItemv){ //$item_erpid  与$id_itemcate_erpid 这两个需要调整
			$orders = array();

			$item = item::model()->findByAttributes(array('item_id'  => $OrderItemv->item_id) );
			$item_erpid  =  $item->erpID;

			$category_id  =  $item->category_id; //如果有两条信息
			$category   =  category::model()->findByAttributes(array( 'category_id' => $category_id ));
			$id_itemcate_erpid = $category->erpID;

			$orders['line_no']  = $OrderItemv->line_no;
			$orders['id_itemcate']  = $id_itemcate_erpid;  //category表 产品分类 ， 默认是  1 快捷刷新，如果有产品id会有问题？？
			//$orders['id_itemcate']  = "1";  //category表 产品分类 ， 默认是 1
			$orders['id_series'] = "";        //目前没有，传空值
			$orders['id_item']  =  $item_erpid; //item 表，默认是 Q00101
			$orders['id_color'] = "";
			$orders['id_roomtype']= "";
			$orders['id_package'] = "";


          /*  循环拿到propvalue表中的erpID */
			$props = $OrderItemv->props;
			$sku2 = json_decode($props);
			foreach($sku2 as $sku3){
				var_dump($sku3);
				$sku4 = explode(':' ,$sku3);
				$item_prop_id = $sku4[0] ;
				$prop_value_id = $sku4[1] ;
				$ItemProp = ItemProp::model()->findByAttributes(array(
					'item_prop_id'  => $item_prop_id ,
				));

				if($ItemProp ->is_room_prop == 1  ){  //如果是房间类型，
					$PropValue =  PropValue::model()->findByAttributes(array(
						'item_prop_id'  => $item_prop_id ,
						'prop_value_id' => $prop_value_id ,
					));
					$orders['id_roomtype'] = $PropValue->erpID;
					var_dump($orders['id_roomtype']);
				}else if($ItemProp ->is_color_prop == 1  ){ //颜色属性
					$PropValue =  PropValue::model()->findByAttributes(array(
						'item_prop_id'  => $item_prop_id ,
						'prop_value_id' => $prop_value_id ,
					 ));
//					$orders['id_color'] = "0001";
					$orders['id_color'] = $PropValue->erpID;;
					var_dump($orders['id_color']);
				}else if($ItemProp ->is_combo_prop == 1  ){  //套餐属性
					$PropValue =  PropValue::model()->findByAttributes(array(
						'item_prop_id'  => $item_prop_id ,
						'prop_value_id' => $prop_value_id ,
					));
					$orders['id_package'] = $PropValue->erpID;
					var_dump($PropValue->erpID);
				}
			}
//		    $orders['id_roomtype'] = "F001";  //propvalue ,如果根据订单，找到相应的 propvalue的字段， 默认是F001
//			$orders['id_color'] =  "0001";        //颜色属性
//			$orders['id_package'] = "P001";      //套餐

			$orders['dec_price']  =  $OrderItemv->price;
			$orders['dec_measurearea'] = $OrderItemv->area;
			$orders['dec_amt']  = $OrderItemv ->total_price;
			$orders['var_dremark'] = "测试1";
			$orders['var_flag']  = "dsaord";

			$OrderItems[] = $orders;
		}

		$orderss['details'] =$OrderItems;
		$json2    =  $this->JSON($orderss); // 自己使用的json，中文不转成 unicode码。
 	    $json2    =   urlencode($json2);
//		$client = new SoapClient("http://183.81.182.4:8090/nerp/services/DCService?wsdl");//李峰
		$client = new SoapClient(webservice_url);
		$obj = $client->getOrder(array('paramJson'=>$json2) ) ;

		var_dump(urldecode($obj->getOrderReturn));

		/*  下面是如果成功后，则保存erp传过来的单号到本机 */
		$returnResult = urldecode($obj->getOrderReturn);
		$jsobj =	json_decode($returnResult);

		if($jsobj->code == '100' ){
			$order->erp_order_id = $jsobj->data[0]->ori_no ;
			if( $order->validate() ){
				if( !$order->save() ){
					dump('保存erp单号失败');
				}
				else{
				    echo	'保存成功2';
				}
			}
		}
//		 dump(json_decode(urldecode($json2)) );
	}


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

	public function  actionPay ()
	{
		$orderId = $_GET['order_id'];
		$amount = $_GET['amount'];
		$pay_method_id = $_GET['pay_method_id'];

		$this->render('pay', array(
			'orderId'       => $orderId,
			'amount'        => $amount,
			'pay_method_id' => $pay_method_id,
		));
	}
	/* cleanEnd ，payfinish方法是 对apiControllers里缺少的方法的补充 */
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

	/*这个模块是多彩 单笔订单支付调用的功能*/
	public function actionPayFinish ()
	{
		/** @var UserModule $webUser */
		$webUser = Yii::app()->user;
		$orderId = $_POST['order_id'];

		$paymentSn = Payment::model()->findByAttributes(array(
			'payment_sn' => $_POST['payment_sn'],
		));

		if (isset($paymentSn)) {
			$this->echoJson(array(
				'success'   => false,
				'msg'       => '请勿重复提交！',
			));
		} else {
			$payment = new Payment();
			$order = Order::model()->findByPk($orderId);

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
					$order->pay_status = 3; // 付全款完毕，pay_status在tbfunction定义了已支付状态
				} else if ($payFee == 0) {
					$payment->status = Payment::PAY_ADVANCE;
					$order->pay_time = time();
					$order->pay_status = 1; // 预付款完毕，pay_status在tbfunction定义了已支付状态
				} else {
					$payment->status = Payment::PAY_FINAL;
					$order->pay_status = 2; //尾款，才算付款，pay_status在tbfunction定义了已支付状态
				}

				try {
					if (!$order->save()) {
						echo 'order save failed!';
						throw new DbSaveException(array(
							'orderId' => $orderId,
							'errorInfo' => $this->modelError($order),
						));
					}

					$payment->order_id = $orderId;
					$payment->payment_sn = $_POST['payment_sn'];
					//$payment->pay_account = $_POST['pay_method_id'];
					//$payment->pay_account = $_POST['pay_account'];
					$payment->pay_account = 'test1_1';
					$payment->account = $_POST['pay_type'];
					$payment->money = $_POST['amount'];
					$payment->user_id = $order->user_id;
					$payment->create_time = time();
					if (!$payment->save()) {
						echo 'payment save failed!';
						throw new DbSaveException(array(
							'orderId' => $orderId,
							'errorInfo' => $this->modelError($payment),
						));
					}
					$this->echoJson(array(
						'success'   => true,
						'total_fee' => $order->total_fee,
						'pay_fee'   => $order->pay_fee,
					));
				} catch (CException $error) {
					throw new DcsjException(Yii::t('orderApi', 'Fail to save payment. error22: {error}', array('{error}' => $error->getMessage())), $error->getCode());
				}
			} else {
				throw new NotFoundException(array(
					'orderId' => $orderId,
				));
			}
		}
	}


	/*这个模块是多彩 多笔订单支付调用的功能 ，返回的结果拆分成相应的数据，分部插入 */
	public function actionPayFinishs ()
	{
		/** @var UserModule $webUser */

		// 根据返回的类型进行订单的 mergeorder拆分

		$webUser = Yii::app()->user;
		$orderIds = $_POST['order_id'];

		$paymentSn = Payment::model()->findByAttributes(array(
			'payment_sn' => $_POST['payment_sn'],
		));

		if (isset($paymentSn)) {
			$this->echoJson(array(
				'success'   => false,
				'msg'       => '请勿重复提交！',
			));
		} else {


		$payment = new Payment();
		$payment->order_id = $orderIds;
		$payment->payment_sn = $_POST['payment_sn'];
		$payment->pay_account = 'test1_1';
		$payment->account = $_POST['pay_type']; //支付方法, 1代表支付宝,2代表微信,8代表pos结算,9代表现金
		$payment->money = $_POST['amount'];
		$payment->create_time = time();
		$payment->user_id = $webUser->id;

		if($payment->validate()){
			if (!$payment->save()) {
//				echo 'payment save failed!';
				throw new DbSaveException(array(
//	                'orderId' => $orderId,
					'errorInfo' => $this->modelError($payment),
				));
		    }else{
		//		echo 'payment save success!';
			}
		}else{
			$this->echoJson(array(
				'success'   => false,
				'msg'   => 'payment validate not success!',
			));
		};

		$mergeorders = mergeorder::model()->findAllByAttributes(array('merge_order_id' => $orderIds ));

		foreach($mergeorders as  $mergeorder){
			$orderId = $mergeorder->order_id;

			$order = Order::model()->findByPk($orderId);

			if (count($order) !=0 ) {
				$order->status = Order::ORDER_COMPLETE;
				$order->pay_fee = $order->total_fee; //多单支付，付款金额，一定是订单的金额
				$order->pay_status = 2;     //尾款，才算付款，pay_status在tbfunction定义了已支付状态
				$order->update_time = time();

//				if ($order->total_fee - $order->pay_fee == 0) {
//					if ($status == Order::ORDER_ACCEPTANCE) {
//						$order->status = Order::ORDER_COMPLETE;
//					} else {
//						$order->status = Order::ORDER_ACCOUNT;
//					}
//
//					$payment->status = Payment::PAY_FULL;
//					$order->pay_status = 3; // 付全款完毕，pay_status在tbfunction定义了已支付状态
//				} else if ($payFee == 0) {
//					$payment->status = Payment::PAY_ADVANCE;
//					$order->pay_time = time();
//					$order->pay_status = 1; // 预付款完毕，pay_status在tbfunction定义了已支付状态
//				} else {
//					$payment->status = Payment::PAY_FINAL;
//					$order->pay_status = 2; //尾款，才算付款，pay_status在tbfunction定义了已支付状态
//				}

			try {
					if (!$order->save()) {
						throw new DbSaveException(array(
							'success'   => false,
							'orderId' => $orderId,
							'errorInfo' => $this->modelError($order),
							'msg' =>'order save failed!',
						));
					}

				 } catch (CException $error) {
					   throw new DcsjException(Yii::t('orderApi',
						                            'Fail to save payment. error22: {error}',
						                            array('{error}' => $error->getMessage())),
						                            $error->getCode()
					                            );
				    }
			} else{
					throw new NotFoundException(array(
						'success'   => false,
						'errorInfo' => $this->modelError($order),
						'orderId' => $orderId,
					));
			  }
		 }
	  }
							$this->echoJson(array(
								'success'   => true,
								'total_fee' => $order->total_fee,
								'pay_fee'   => $order->pay_fee,
							));
	}

	public function modelError($model) {

		foreach ($model->getErrors() as $attr_errs) {
			foreach ($attr_errs as $msg) {
				return $msg;
			}
		}
		return false;
	}

    public function actionFail()
    {
        $this->render('fail');
    }

    public function actionSuccess()
    {
        $this->render('success');
    }

	public function actionMeasureSuccess()
	{
		$this->render('measureSuccess');
	}
    /**
     * Updates a particular model.
     * If update is successful, the browser will be redirected to the 'view' page.
     * @param integer $id the ID of the model to be updated
     */
    public function actionUpdate($id)
    {
        $model = $this->loadModel($id);

        // Uncomment the following line if AJAX validation is needed
        // $this->performAjaxValidation($model);

        if (isset($_POST['Order'])) {
            $model->attributes = $_POST['Order'];
            if ($model->save())
                $this->redirect(array('view', 'id' => $model->order_id));
        }

        $this->render('update', array(
            'model' => $model,
        ));
    }

    /**
     * Deletes a particular model.
     * If deletion is successful, the browser will be redirected to the 'admin' page.
     * @param integer $id the ID of the model to be deleted
     */
    public function actionDelete($id)
    {
        if (Yii::app()->request->isPostRequest) {
            // we only allow deletion via POST request
            $this->loadModel($id)->delete();

            // if AJAX request (triggered by deletion via admin grid view), we should not redirect the browser
            if (!isset($_GET['ajax']))
                $this->redirect(isset($_POST['returnUrl']) ? $_POST['returnUrl'] : array('admin'));
        } else
            throw new CHttpException(400, 'Invalid request. Please do not repeat this request again.');
    }

    /**
     * Lists all models.
     */
    public function actionIndex()
    {
        $dataProvider = new CActiveDataProvider('Order');
        $this->render('index', array(
            'dataProvider' => $dataProvider,
        ));
    }

    /**
     * Manages all models.
     */
    public function actionAdmin()
    {
        $model = new Order;
        $model->unsetAttributes(); // clear any default values
        if (isset($_GET['Order']))
            $model->attributes = $_GET['Order'];
        $this->render('admin', array(
            'model' => $model
        ));
    }

    /**
     * Returns the data model based on the primary key given in the GET variable.
     * If the data model is not found, an HTTP exception will be raised.
     * @param integer the ID of the model to be loaded
     */
    public function loadModel($id)
    {
        $model = Order::model()->findByPk($id);
        if ($model === null)
            throw new CHttpException(404, 'The requested page does not exist.');
        return $model;
    }

    /**
     * Performs the AJAX validation.
     * @param CModel the model to be validated
     */
    protected function performAjaxValidation($model)
    {
        if (isset($_POST['ajax']) && $_POST['ajax'] === 'order-form') {
            echo CActiveForm::validate($model);
            Yii::app()->end();
        }
    }

    public function actionGetChildAreas($parent_id)
    {
        $areas = Area::model()->findAllByAttributes(array('parent_id' => $parent_id));
        $areasData = CHtml::listData($areas, 'area_id', 'name');
        echo json_encode(CMap::mergeArray(array('0' => ''), $areasData));
    }

    /**
     * name: actionAddAddressByAjax
     * function:add address when buy product var use new address
     * @author: shuai.du@jago-ag.cn
     */
    public function actionAddAddressByAjax(){
        $model = new AddressResult;
        if (isset($_POST['AddressResult']) && Yii::app()->request->isAjaxRequest) {
            $model->attributes = $_POST['AddressResult'];
            $result = array();
            if ($model->save())
            {
                echo 'success@';
                echo $model->contact_id.'@';
//                echo $model->contact_id;
//                echo '<li>'. CHtml::radioButton('delivery_address',true,array('value' => $model->contact_id,'id' => 'delivery_address'.$model->contact_id)).CHtml::tag('span', array(
//                            'class' => 'buyer-address shop_selection'),
//                        $model->s->name . '&nbsp;' . $model->c->name . '&nbsp;' . $model->d->name . '&nbsp;' . $model->address . '&nbsp;(' . $model->contact_name . '&nbsp;收)&nbsp;' . $model->mobile_phone).'</li>';
                $this->renderPartial('address',array('model' => $model));

            }
            else
            {
                $errors = $model->getErrors();
                foreach($errors as  $error):
                    ?>
                    <li><?php echo $error[0];?></li>
            <?php
               endforeach;
            }
        };
    }

	public function actionMeasureCheckouttd(){
		if($_POST['dd']== 12334){
			$baseurl2 =	Yii::app()->request->hostInfo;
			$baseurlxml = $baseurl2 ."/DCService.xml";
			echo $baseurlxml;
		}else{
			echo 'dd';
		}
	}

	public function actionMeasureCheckout(){
		$model = new ArOrderMeasure();
		$measureItem = new ArOrderMeasureItem();
		$this->render("measureCheckout", array('model'=>$model, 'measureItem'=>$measureItem));
	}



	/* 预约单订单页面创建*/
	public function actionCreateMeasure ()
	{
		/** @var UserModule $webUser */

		$model = new ArOrderMeasure();

		$client_mobile = $_POST['client_mobile'];
		$model->user_id = User::model()->MeasureCreateUser($client_mobile);//创建用户表

		$model->client_name = $_POST['client_name'];
		$model->client_state = $_POST['client_state'];
		$model->client_city = $_POST['client_city'];
		$model->client_district = $_POST['client_district'];
		$model->client_address = $_POST['client_address'];
		$model->client_zip = $_POST['client_zip'];
		$model->client_mobile = $_POST['client_mobile'];
		$model->client_phone = $_POST['client_phone'];
		$model->client_memo = $_POST['client_memo'];
		$model->total = $_POST['total'];
		$model->create_time = date('Y-m-d H:i:s', time());

		$model->news = 1;
		$model->measure_time = $_POST['update_time'];  //预约时间

		if ($model->validate()) {
			try {
				if ($model->save()) {
					$i=0;

					foreach ($_POST['category_item_ids'] as $category_item_id) {
						$measureItem = new ArOrderMeasureItem();

						$rst_area = $_POST['rst_area'][$i];
						$rst_memo = $_POST['rst_memo'][$i];

						$measureItem->measure_id = $model->measure_id;
						$measureItem->category_item_id = $category_item_id;
						$measureItem->create_time = $model->create_time;
						$measureItem->rst_area = $rst_area;
						$measureItem->rst_memo = $rst_memo;
						$i++;

						if ($measureItem->validate()) {
							if (!$measureItem->save()) {
								Yii::log('Fail to save order measure item.', 'error');
								throw new DbSaveException(array(
									'errorInfo' => $this->modelError($measureItem),
								));
							}

						} else {
							throw new InvalidDataException(array(
								'errorInfo' => $this->modelError($measureItem),
							));
						}
					}

					/*调用测量单的程序*/
				   $orderID = $measureItem->measure_id;
 			 	   $post = $this->postMeasureOrder($orderID);
					/*结束测试*/

					$orderLog = new OrderLog();

					$orderLog->order_id = $model->measure_id;
					$orderLog->op_name = 'create';
					$orderLog->result = 'success';
					$orderLog->origin_body = Yii::app()->request->getRawBody();
					$orderLog->log_text = '创建预约单';

					if ($orderLog->validate()) {
						if (!$orderLog->save()) {
							Yii::log('Fail to save order log', 'error');
						}
					}

					if($post){
						echo json_encode(array(
							'success'    => true,
							'measure_id' => $model->measure_id,
						));
					}else{
						echo json_encode(array(
							'success'    => false,
							'measure_id' => $model->measure_id,
						));
					}

				}
			} catch (CException $error) {
				throw new DcsjException(Yii::t('orderApi', 'Fail to save order measure. Error: {error}', array('{error}' => $error->getMessage())), $error->getCode());
			}
		} else {
			throw new InvalidDataException(array(
				'errorInfo' => $this->modelError($model),
			));
		}
	}

	 /*post数据到erp系统中  */
	public function  postMeasureOrder($measure_id){

		/*订单创建完成后，把数据解析到相应的模块 ，预约单没有产品id*/
		$order_measure = ArOrderMeasure::model()->findByAttributes( array("measure_id" => $measure_id ) );

		$date_reserve =$order_measure->measure_time;   //预约时间目前需要从update_time拿到
		$user_id = $order_measure->user_id;
		$user =   Users::model()->findByAttributes(array( "id" =>$user_id ) );
//		$name_member = $user->username;
		$name_member = $order_measure->client_name;  //传给他的是预约的用户真实名字，但是登陆使用的还是 J加上手机号

		$dec_budamt = $order_measure->total;
		$var_tel = $order_measure->client_mobile;
		$id_province = $order_measure->client_state;
		$id_city = $order_measure->client_city;

		$var_addr = $order_measure->client_address;
		$var_remark = $order_measure->memo;
		$dec_samt = 0;
		$dec_spayamt = 0;

		/*主表信息*/
		$orderss = array();
		$orderss['date_reserve'] = $date_reserve;
		$orderss['id_member'] = $user_id;
		$orderss['name_member'] = $name_member;
		$orderss['dec_budamt'] = $dec_budamt;
		$orderss['var_tel'] = $var_tel;
		$orderss['id_province'] = $id_province;
		$orderss['id_city'] = $id_city;

		$orderss['var_addr'] = $var_addr;
		$orderss['var_remark'] = $var_remark;
		$orderss['dec_samt'] = $dec_samt;
		$orderss['dec_spayamt'] = $dec_spayamt;
		$orderss['ori_no'] = $measure_id;
		$orderss['id_ordsource'] = 'L';

		$OrderItem   = ArOrderMeasureItem::model()->findAllByAttributes( array("measure_id" => $measure_id ));
		/*明细表信息*/
		$OrderItems = array();
		$line_no=1;
		foreach($OrderItem as $OrderItemv){
			$orders = array();

			$category_id  =  $OrderItemv->category_item_id;
			$category   =  category::model()->findByAttributes(array( 'category_id' => $category_id ));
			$id_itemcate_erpid = $category->erpID;

			$orders['line_no']  = $line_no;
			$orders['id_itemcate']  = $id_itemcate_erpid;  //category表 产品分类 ， 默认是  1
			$orders['id_series'] = "";
			$orders['id_roomtype'] = "";     //默认为空

			$orders['id_item']  =  "";       //默认为空
			$orders['id_color'] =  "";       //默认为空
			$orders['id_package'] = "";      //默认为空

			$orders['dec_price']  =  0;
			$orders['dec_measurearea'] = 0;
			$orders['dec_amt']  = 0;
			$orders['var_dremark'] = "预约单测试";
			$orders['var_flag']  = "dsrvmeasure";
			$OrderItems[] = $orders;
			$line_no++;
		}

		$orderss['details'] =$OrderItems;
		$json2    =  $this->JSON($orderss); // 自己使用的json，中文不转成 unicode码。
		$json2    =   urlencode($json2);

 		$client = new SoapClient(webservice_url);
 		$obj = $client->getOrder(array('paramJson'=>$json2) );
// 		var_dump(urldecode($obj->getOrderReturn));

		/*  下面是保存数据在多彩系统中 */
		$returnResult = urldecode($obj->getOrderReturn);
		$jsobj =	json_decode($returnResult);

		if($jsobj->code == '100' ){
			$order_measure->erp_measure_id = $jsobj->data[0]->ori_no ;
			if( $order_measure->validate() ){
				if( !$order_measure->save() ){
					//echo  '保存erp单号失败';
					return false;
				}
				else{
					return true;
					//echo	'保存成功2';
				}
			}
		}
  		//var_dump(json_decode(urldecode($json2)) );
	}

	public function actionGetMeasureItem(){
		$id = $_POST["id"];
		$measureItem = Category::model()->findByPk($id);
		$price = $this->GetItemPrice($id);
		echo json_encode(CMap::mergeArray(array('price' => $price), $measureItem));
	}

	public function GetItemPrice($id){
		$item = Item::model()->find('category_id=:category_id', array(':category_id'=>$id));
		return $item->price;
	}

	public function actionGetCategory(){
		$category_data = Category::model()->findAll('level=:level and root=:root',array(':level'=>3, ':root'=>3));
		$category = CHtml::listData($category_data, "category_id", "name");
		echo json_encode($category);
	}

	public function actionDeleteNews(){
		$id = $_POST["Id"];
		$order = Order::model()->findByPk($id);
		$order->news = 0;
		if($order->save()){
			$success = array('success'=>true);
			echo json_encode($success);
		}else{
			$success = array('success'=>false);
			echo json_encode($success);
		}

	}

	public function actionDeleteMeasureNews(){
		$id = $_POST["Id"];
		$order = ArOrderMeasure::model()->findByPk($id);
		$order->news = 0;
		if($order->save()){
			$success = array('success'=>true);
			echo json_encode($success);
		}else{
			$success = array('success'=>false);
			echo json_encode($success);
		}

	}

	public function actionGetNewsCount(){

		$counts = 0;

		$countOrder = Order::model()->countByAttributes(array(
			'news' => 1,
		));

		$countMeasure = ArOrderMeasure::model()->countByAttributes(array(
			'news' => 1,
		));

		$counts = $countOrder + $countMeasure;

		echo json_encode(array(
			'counts' => $counts,
			'countOrder' => $countOrder,
			'countMeasure' =>$countMeasure,
		));
	}
}
