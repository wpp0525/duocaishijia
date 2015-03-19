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
	 */

	class Dc_store extends CActiveRecord{

		//返回当前数据表名 ，默认加括号。
		public function tableName(){
			return '{{dc_store}}';
		}

		//设置标签的显示名字
		public function attributeLabels()
		{
			return array(
				'store_id' => '商户id',
				'store_name'=>'门店名字',
				'state'=>'省',
				'city' => '市',
				'district'=>'区',
				'create_time'=>'创建日期',
				'address'=>'详细地址',
				'phone'=>'电话',
			);
		}

		//创建静态模型
		public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}


		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules()
		{
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return array(

				array('store_id,store_name,,state,city,district,address', 'required'),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched. 查询条件

				array('store_id,store_name,,state,city,district,address', 'safe', 'on' => 'search'),);
		}


		public function search(){
			// Warning: Please modify the following code to remove attributes that
			// should not be searched.

			$criteria = new CDbCriteria;  //表中的所有字段都要填写
			$criteria->compare('store_id', $this->store_id, true);
			$criteria->compare('store_name', $this->store_name, true);
			$criteria->compare('state', $this->state, true);
			$criteria->compare('city', $this->city, true);
			$criteria->compare('district', $this->district, true);
			$criteria->compare('address', $this->address, true);
			$criteria->compare('create_time', $this->create_time, true);
			$criteria->compare('phone', $this->phone, true);

		 	$criteria->with = 'users';
			$criteria->order='store_id desc';  //订单序号倒序排列
			//  var_dump("criteria"); die;
			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,

			) );
		}


	}



?>