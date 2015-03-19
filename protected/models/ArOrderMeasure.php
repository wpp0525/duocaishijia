<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-12
	 * Time: 下午5:24 GMT+8.00
	 */

	/**
	 * This is the model class for table "order_measure".
	 *
	 * The followings are the available columns in table 'order_measure':
	 * @property bigint             $measure_id
	 * @property int                $user_id
	 * @property varchar            $client_name
	 * @property varchar            $client_state
	 * @property varchar            $client_city
	 * @property varchar            $client_district
	 * @property varchar            $client_address
	 * @property varchar            $client_zip
	 * @property varchar            $client_mobile
	 * @property varchar            $client_phone
	 * @property varchar            $client_memo
	 * @property varchar            $memo
	 * @property char               $status
	 * @property timestamp          $create_time
	 * @property timestamp          $update_time
	 * @property timestamp          $measure_time
	 * The followings are the available model relations:
	 * @property ArOrderMeasureItem $item
	 */
	class ArOrderMeasure extends CActiveRecord
	{
		const MEASURE_CREATE = 0;
		const MEASURE_COMPLETE = 1;
		/**
		 * Returns the static model of the specified AR class.
		 * @param string $className active record class name.
		 * @return ArOrderMeasure the static model class
		 */
		public static function model ($className = __CLASS__)
		{
			return parent::model($className);
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels ()
		{
			return array(
				'client_name'     => '姓名',
				'client_state'    => '省',
				'client_city'     => '市',
				'client_district' => '区',
				'client_address'  => '详细地址',
				'client_zip'      => '邮编',
				'client_mobile'   => '手机',
				'client_phone'    => '固定电话',
				'client_memo'     => '备注',
				'memo'            => '确认备注',
				'status'          => '状态',
				'total'           => '预算',
				'create_time'     => '创建时间',
				'update_time'     => '更新时间',
				'measure_time'     => '预约时间',
				'erp_measure_time'     => '预约erp单号',
			);
		}

		/**
		 * @return array relational rules.
		 */
		public function relations ()
		{
			// NOTE: you may need to adjust the relation name and the related
			// class name for the relations automatically generated below.
			return array(
				'item' => array(self::HAS_MANY, 'ArOrderMeasureItem', 'measure_id'),
			);
		}

		public function rules ()
		{
			return array(
				array('user_id, client_name, client_state, client_city, client_mobile,total', 'required'),

				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('measure_id, user_id, client_name, client_state, client_city, client_district, client_address, client_zip, client_mobile, client_phone, client_memo, memo, status, create_time, update_time，total,erp_measure_id,measure_time', 'safe', 'on' => 'search'),
                array('client_mobile', 'match', 'pattern' => '/^0{0,1}(13[0-9]|14[0-9]|15[0-9]|18[0-9])[0-9]{8}/', 'message' => '手机号不符合规则'),
                array('total', 'match', 'pattern' => '/^[0-9]+\.?[0-9]{0,2}$/', 'message' => '预算必须为数字')
            );
		}

		/**
		 * @return string the associated database table name
		 */
		public function tableName ()
		{
			return '{{order_measure}}';
		}


		/**
		 * Retrieves a list of models based on the current search/filter conditions.
		 * @return CActiveDataProvider the data provider that can return the models based on the search/filter conditions.
		 */
		public function search ()
		{
			// Warning: Please modify the following code to remove attributes that
			// should not be searched.

			$criteria = new CDbCriteria;

			$criteria->compare('measure_id', $this->measure_id, true);
			$criteria->compare('user_id', $this->user_id, true);
			$criteria->compare('client_name', $this->client_name, true);
			$criteria->compare('client_state', $this->client_state, true);
			$criteria->compare('client_city', $this->client_city, true);
			$criteria->compare('client_district', $this->client_district, true);
			$criteria->compare('client_address', $this->client_address, true);
			$criteria->compare('client_zip', $this->client_zip, true);
			$criteria->compare('client_mobile', $this->client_mobile, true);
			$criteria->compare('client_phone', $this->client_phone, true);
			$criteria->compare('client_memo', $this->client_memo, true);
			$criteria->compare('memo', $this->memo, true);
			$criteria->compare('status', $this->status, true);
			$criteria->compare('create_time', $this->create_time, true);
			$criteria->compare('update_time', $this->update_time, true);
			$criteria->compare('total', $this->total, true);
			$criteria->compare('total', $this->measure_time, true);
			$criteria->compare('erp_measure_id', $this->erp_measure_id, true);
			$criteria->compare('measure_time', $this->measure_time, true);

			$criteria->order = 'create_time desc';

			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
		}

		public function getMeasureStatus(){
			switch ($this->status) {
				case self::MEASURE_CREATE:
					return "未测量";

					break;
				case self::MEASURE_COMPLETE:
					return "已测量";

					break;
				default:
					break;
			}
		}

        protected function beforeSave()
        {
            // $this->create_time = date('Y-m-d', CDateTimeParser::parse($this->create_time, 'yyyy-MM-dd hh:mm'));
//            $this->create_time = strtotime($this->create_time);
            return parent::beforeSave();
        }

        protected function afterFind()
        {
            $this->create_time = Yii::app()->dateFormatter->format('yyyy-MM-dd hh:mm', $this->create_time);
            return parent::afterFind();
        }


		public function showDetailAddress ($model)
		{
			$data['client_country'] = 0;
			foreach (array('state', 'city') as $value) {
				$data['client_'.$value] = Area::model()->findByPk($model->{'client_'.$value})->name;
			}
			$data['client_address'] = $model->client_address;
			$detail_address = implode(' ', $data);

			return $detail_address;

		}

		public function getMeasureTime($memo){
			$time = substr($memo,stripos($memo,'服务日期： ')+16,10);

			$times = substr($memo,stripos($memo,'时间段:')+12);


			$client_time = $time .'日'. $times ;

			return $client_time;

		}
	}