<?php
	/**
	 * Created by PhpStorm.
	 * User: Administrator
	 * Date: 14-10-24
	 * Time: 下午1:06
	 */
	/**
	 * This is the model class for table "dc_store".
	 *
	 * The followings are the available columns in table 'dc_store':
	 * @property int        $store_id
	 * @property varchar    $store_name
	 * @property varchar    $state
	 * @property varchar    $city
	 * @property varchar    $district
	 * @property varchar    $address
	 * @property varchar    $phone
	 * @property timestamp  $create_time
	 */

	class DcStore extends CActiveRecord
	{

		//返回当前数据表名
		public function tableName ()
		{
			return '{{dc_store}}';
		}

		//设置标签的显示名字
		public function attributeLabels ()
		{
			return array(
				'store_id'    => '门店编号',
				'store_name'  => '门店名字',
				'erpID'       => 'erpID',
				'state'       => '省份',
				'city'        => '城市',
				'district'    => '地区',
				'address'     => '详细地址',
				'phone'       => '电话',
				'create_time' => '开店日期',
			);
		}

		//创建静态模型
		public static function model ($className = __CLASS__)
		{
			return parent::model($className);
		}

		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules ()
		{
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return array(
				array('store_name, state, city, address, phone,erpID', 'required'),

				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('store_id, store_name, state, city, district, address, phone, create_time', 'safe', 'on' => 'search'),);
		}


		/**
		 * @return array relational rules.
		 */
		public function relations ()
		{
			// NOTE: you may need to adjust the relation name and the related
			// class name for the relations automatically generated below.
			return array();
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

			$criteria->compare('store_id', $this->store_id, true);
			$criteria->compare('store_name', $this->store_name, true);
			$criteria->compare('state', $this->state, true);
			$criteria->compare('city', $this->city, true);
			$criteria->compare('district', $this->district, true);
			$criteria->compare('address', $this->address, true);
			$criteria->compare('phone', $this->phone, true);
			$criteria->compare('create_time', $this->create_time, true);
			$criteria->compare('erpID', $this->erpID, true);

			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
		}

		/**
		 * 得到HolderJs
		 * @param type $width
		 * @param type $height
		 * @return type
		 */
		public function getHolderJs($width = '150', $height = '150', $text = 'No Picture')
		{
			return 'holder.js/' . $width . 'x' . $height . '/text:' . $text;
		}



	}