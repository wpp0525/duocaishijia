<?php

	/**
	 * This is the model class for table "order".
	 *
	 * The followings are the available columns in table 'order':
	 * @property string  $order_id
	 * @property string  $user_id
	 * @property integer $status
	 * @property integer $pay_status
	 * @property integer $refund_status
	 * @property string  $total_fee
	 * @property string  $pay_fee
	 * @property string  $pay_method
	 * @property string  $receiver_name
	 * @property string  $receiver_country
	 * @property string  $receiver_state
	 * @property string  $receiver_city
	 * @property string  $receiver_district
	 * @property string  $receiver_address
	 * @property string  $receiver_zip
	 * @property string  $receiver_mobile
	 * @property string  $receiver_phone
	 * @property string  $memo
	 * @property string  $pay_time
	 * @property string  $create_time
	 * @property string  $update_time
	 * @property string  $order_source_type
	 * @property string  $order_source
	 * @property string  $measure_id
	 * @property string  $store_id
	 * @property string  pay_first
	 * @property string  pay_method_id
	 * @property integer  is_pay_first
	 * @property string  erp_order_id
	 * @property integer news
	 */
	class Order extends CActiveRecord
	{

		//已创建
		const ORDER_CREATE = 0;
		//已确认
		const ORDER_CONFIRM = 1;
		//已分配门店
		const ORDER_DISPATCH = 2;
		//已测量
		const ORDER_MEASUREMENT = 3;
		//施工中
		const ORDER_UNDER_CONSTRUCTION = 4;
		//施工中
//		const ORDER_CONSTRUCTION = 5;
		//施工完成
		const ORDER_COMPLETE_CONSTRUCTION = 6;
		//已验收
		const ORDER_ACCEPTANCE = 7;
		//已结算
		const ORDER_ACCOUNT = 8;
		//已完成
		const ORDER_COMPLETE = 9;

		private $orderLog;
		public $id;

		/**
		 * Returns the static model of the specified AR class.
		 * @param string $className active record class name.
		 * @return Order the static model class
		 */

		public static function model ($className = __CLASS__)
		{
			return parent::model($className);
		}

		/**
		 * @return string the associated database table name
		 */
		public function tableName ()
		{
			return 'order';
		}

		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules ()
		{
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return array(
				array('receiver_name, receiver_state, receiver_city, receiver_address, receiver_mobile', 'required'),
				array('status, pay_status, refund_status, comment_status', 'numerical', 'integerOnly' => true),
				array('user_id, pay_method_id, total_fee,pay_first, pay_fee, pay_time, create_time, update_time', 'length', 'max' => 10),
				array('receiver_name, receiver_state, receiver_city, receiver_mobile, receiver_phone', 'length', 'max' => 45),
				array('receiver_address', 'length', 'max' => 255),
				array('memo', 'safe'),
				//array('receiver_mobile', 'match', 'pattern' => '/^0{0,1}(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8}/', 'message' => 'Incorrect symbols (0-9).'),
				array('store_id', 'validateStore', 'on' => 'update'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('order_id, user_id, news,status, pay_status, refund_status, comment_status, total_fee, pay_fee, receiver_name, receiver_country, receiver_state, receiver_city, receiver_district, receiver_address, receiver_zip, receiver_mobile, receiver_phone, memo, create_time, update_time, order_source_type, order_source, measure_id, store_id', 'safe', 'on' => 'search'),);
		}


		/**
		 * @return array relational rules.
		 */
		public function relations ()
		{
			// NOTE: you may need to adjust the relation name and the related
			// class name for the relations automatically generated below.
			return array(
				'orderItems' => array(self::HAS_MANY, 'OrderItem', 'order_id'),
				'orderLogs'  => array(self::HAS_MANY, 'OrderLog', 'order_id'),
				'payments'   => array(self::HAS_MANY, 'Payment', 'order_id'),
				'refunds'    => array(self::HAS_MANY, 'Refund', 'order_id'),
				'users'      => array(self::BELONGS_TO, 'Users', 'user_id'),
				'stores'     => array(self::HAS_ONE, 'DcStore', 'store_id'),
			);
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels ()
		{
			return array(
				'order_id'          => '订单号',
				'user_id'           => '会员',
				'status'            => '订单状态',
				'pay_status'        => '状态',
				'refund_status'     => '退款状态',
				'total_fee'         => '服务总金额',
				'pay_fee'           => '实付款',
				'pay_method'        => '付款方式',
				'receiver_name'     => '收货人',
				'receiver_country'  => '国家',
				'receiver_state'    => '省',
				'receiver_city'     => '市',
				'receiver_district' => '区',
				'receiver_address'  => '详细地址',
				'receiver_zip'      => '邮政编码',
				'receiver_mobile'   => '手机',
				'receiver_phone'    => '电话',
				'memo'              => '备注',
				'pay_time'          => '付款时间',
				'create_time'       => '下单时间',
				'update_time'       => '更新时间',
				'comment_status'    => '评论状态',
				'detail_address'    => '具体地址',
				'order_source_type' => '订单来源类型',
				'order_source'      => '订单来源',
				'measure_id'        => '预约单ID',
				'store_id'          => '店面ID',
				'title'             => '服务',
				'is_pay_first'      => '是否首付款',
				'pay_method_id'     => '付款方式',
				'pay_first'         => '首付金额',
				'erp_order_id'      => 'erpID',
			);
		}

		/**
		 * Retrieves a list of models based on the current search/filter conditions.
		 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
		 */
		public function search ($order = null)
		{
			// Warning: Please modify the following code to remove attributes that
			// should not be searched.

			$criteria = new CDbCriteria;

			$criteria->compare('order_id', $this->order_id, true);
			$criteria->compare('user_id', $this->user_id, true);
			$criteria->compare('status', $this->status);
			$criteria->compare('pay_status', $this->pay_status);
			$criteria->compare('refund_status', $this->refund_status);
			$criteria->compare('comment_status', $this->comment_status);
			$criteria->compare('total_fee', $this->total_fee, true);
			$criteria->compare('pay_fee', $this->pay_fee, true);
			$criteria->compare('receiver_name', $this->receiver_name, true);
			$criteria->compare('receiver_country', $this->receiver_country, true);
			$criteria->compare('receiver_state', $this->receiver_state, true);
			$criteria->compare('receiver_city', $this->receiver_city, true);
			$criteria->compare('receiver_district', $this->receiver_district, true);
			$criteria->compare('receiver_address', $this->receiver_address, true);
			$criteria->compare('receiver_zip', $this->receiver_zip, true);
			$criteria->compare('receiver_mobile', $this->receiver_mobile, true);
			$criteria->compare('receiver_phone', $this->receiver_phone, true);
			$criteria->compare('memo', $this->memo, true);
			$criteria->compare('pay_time', $this->pay_time, true);
			$criteria->compare('create_time', $this->create_time, true);
			$criteria->compare('update_time', $this->update_time, true);
			$criteria->compare('order_source_type', $this->order_source_type, true);
			$criteria->compare('order_source', $this->order_source, true);
			$criteria->compare('measure_id', $this->measure_id, true);
			$criteria->compare('store_id', $this->store_id, true);
			$criteria->compare('erp_order_id', $this->erp_order_id, true);
			$criteria->compare('news', $this->news, true);
			$criteria->with = 'users';
			if($order){
				$criteria->order = $order.'desc ,'.'create_time desc ,order_id desc ';
			}else{
				$criteria->order = 'create_time desc ,order_id desc';
			}
			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
		}

		public function validateStore ()
		{
			if (!$this->hasErrors('store') && isset($this->store_id)) {
				$store = DcStore::model()->findByPk($this->store_id);

				if (!$store) {
					$this->addError('store', Yii::t('order', 'Store not find. StoreId: {storeId}', array('{storeId}' => $this->store_id)));
				}
			}
		}

		public function showDetailAddress ($model)
		{
			$data['receiver_country'] = $model->receiver_country;
			foreach (array('state', 'city', 'district') as $value) {
				$data['receiver_'.$value] = Area::model()->findByPk($model->{'receiver_'.$value})->name;
			}
			$data['receiver_address'] = $model->receiver_address;
			$detail_address = implode(' ', $data);

			return $detail_address;

		}
		public function searchQuery ()
		{
			// Warning: Please modify the following code to remove attributes that
			// should not be searched.

			$criteria = new CDbCriteria;

			$criteria->compare('order_id', $this->order_id, true);
			$criteria->compare('user_id', $this->user_id, true);
			$criteria->compare('status', $this->status);
			$criteria->compare('pay_status', $this->pay_status);
			$criteria->compare('refund_status', $this->refund_status);
			$criteria->compare('comment_status', $this->comment_status);
			$criteria->compare('total_fee', $this->total_fee, true);
			$criteria->compare('pay_fee', $this->pay_fee, true);
			$criteria->compare('receiver_name', $this->receiver_name, true);
			$criteria->compare('receiver_country', $this->receiver_country, true);
			$criteria->compare('receiver_state', $this->receiver_state, true);
			$criteria->compare('receiver_city', $this->receiver_city, true);
			$criteria->compare('receiver_district', $this->receiver_district, true);
			$criteria->compare('receiver_address', $this->receiver_address, true);
			$criteria->compare('receiver_zip', $this->receiver_zip, true);
			$criteria->compare('receiver_mobile', $this->receiver_mobile, true);
			$criteria->compare('receiver_phone', $this->receiver_phone, true);
			$criteria->compare('memo', $this->memo, true);
			$criteria->compare('pay_time', $this->pay_time, true);
			$criteria->compare('create_time', $this->create_time, true);
			$criteria->compare('update_time', $this->update_time, true);
			$criteria->compare('order_source_type', $this->order_source_type, true);
			$criteria->compare('order_source', $this->order_source, true);
			$criteria->compare('measure_id', $this->measure_id, true);
			$criteria->compare('store_id', $this->store_id, true);

			$criteria->with = 'users';
			$criteria->order = 'order_id desc';
			$criteria->addCondition(" store_id is null"); //等于空的时候查询

			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
		}

		public function MyOrderSearch ()
		{
			// Warning: Please modify the following code to remove attributes that
			// should not be searched.
			$criteria = new CDbCriteria;

			$criteria->compare('order_id', $this->order_id, true);
			$criteria->compare('user_id', Yii::app()->user->id, true);
			$criteria->compare('t.status', $this->status);
			$criteria->compare('pay_status', $this->pay_status);
			$criteria->compare('refund_status', $this->refund_status);
			$criteria->compare('comment_status', $this->comment_status);
			$criteria->compare('total_fee', $this->total_fee, true);
			$criteria->compare('pay_fee', $this->pay_fee, true);
			$criteria->compare('receiver_name', $this->receiver_name, true);
			$criteria->compare('receiver_country', $this->receiver_country, true);
			$criteria->compare('receiver_state', $this->receiver_state, true);
			$criteria->compare('receiver_city', $this->receiver_city, true);
			$criteria->compare('receiver_district', $this->receiver_district, true);
			$criteria->compare('receiver_address', $this->receiver_address, true);
			$criteria->compare('receiver_zip', $this->receiver_zip, true);
			$criteria->compare('receiver_mobile', $this->receiver_mobile, true);
			$criteria->compare('receiver_phone', $this->receiver_phone, true);
			$criteria->compare('memo', $this->memo, true);
			$criteria->compare('pay_time', $this->pay_time, true);
			$criteria->compare('create_time', $this->create_time, true);
			$criteria->compare('update_time', $this->update_time, true);
			$criteria->compare('order_source_type', $this->order_source_type, true);
			$criteria->compare('order_source', $this->order_source, true);
			$criteria->compare('measure_id', $this->measure_id, true);
			$criteria->compare('store_id', $this->store_id, true);

			$criteria->with = 'users';
			$criteria->order = 'create_time desc';

			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
                'pagination' =>array(
	                'pageSize' => 5,
                ),
			));
		}

		public function getOrderStatus ()
		{
			switch ($this->status) {
				case self::ORDER_CREATE:
					return "已创建";

					break;
				case self::ORDER_CONFIRM:
					return "已确认";

					break;
				case self::ORDER_DISPATCH:
					return "已指派";

					break;
				case self::ORDER_MEASUREMENT:
					return "已测量";

					break;
				case self::ORDER_UNDER_CONSTRUCTION:
					return "施工中";

					break;
//				case self::ORDER_CONSTRUCTION:
//					return "已施工";
//
//					break;
				case self::ORDER_COMPLETE_CONSTRUCTION:
					return "施工完成";

					break;
				case self::ORDER_ACCEPTANCE:
					return "已验收";

					break;
				case self::ORDER_ACCOUNT:
					return "已结算";

					break;
				case self::ORDER_COMPLETE:
					return "已完成";

					break;
				default:
					break;
			}
		}

		public function getOrderNextStatus ()
		{
			switch ($this->status) {
				case self::ORDER_CREATE:
					return "待确认";

					break;
				case self::ORDER_CONFIRM:
					return "待派单";

					break;
				case self::ORDER_DISPATCH:
					return "待测量";

					break;
				case self::ORDER_MEASUREMENT:
					return "待施工";

					break;
				case self::ORDER_UNDER_CONSTRUCTION:
					return "待施工完成";

					break;
				case self::ORDER_COMPLETE_CONSTRUCTION:
					return "待验收";

					break;
				case self::ORDER_ACCEPTANCE:
					return "待支付";

					break;
				default:
					break;
			}
		}

		public function getOrderWholeStatus ()
		{
			if ($this->getOrderNextStatus())
				return $this->getOrderStatus().", ".$this->getOrderNextStatus();
			else
				return $this->getOrderStatus();
		}

		public function getArea ($areaId)
		{
			$area = Area::model()->findByPk($areaId);

			return $area->name;
		}

		public function getTitle ()
		{
			$title = '';
			$orderItems = OrderItem::model()->findAll("order_id = :order_id", array(":order_id" => $this->order_id));
			foreach ($orderItems as $orderItem) {
				if (empty($title)) {
					$title = $orderItem->title;
				} else {
					$title .= "; ".$orderItem->title;
				}
			}

			return $title;
		}
	}
