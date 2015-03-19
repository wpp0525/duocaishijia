<?php
	/**
	 * Created by PhpStorm.
	 * User: Dove
	 * Date: 14-11-13
	 * Time: 上午11:04 GMT+8.00
	 */

	/**
	 * This is the model class for table "order_measure_item".
	 *
	 * The followings are the available columns in table 'order_measure_item':
	 * @property bigint             $measure_id
	 * @property int                $category_item_id
	 * @property decimal            $rst_area
	 * @property varchar            $rst_memo
	 * @property timestamp          $create_time
	 *
	 * The followings are the available model relations:
	 * @property Category           $cate
	 */
	class ArOrderMeasureItem extends CActiveRecord
	{
		/**
		 * Returns the static model of the specified AR class.
		 * @param string $className active record class name.
		 * @return ArOrderMeasureItem the static model class
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
				'measure_id'       => '预约编号',
				'category_item_id' => '类别编号',
				'rst_area'         => '测量结果',
				'rst_memo'         => '测量备注',
				'create_time'      => '创建时间',
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
				'cate' => array(self::HAS_MANY, 'Category', 'category_item_id'),
			);
		}

		public function rules ()
		{
			return array(
				array('measure_id, category_item_id', 'required'),

				// The following rule is used by search().
				// Please remove those attributes that should not be searched.
				array('measure_id, category_item_id, rst_area, rst_memo, create_time', 'safe', 'on'=>'search'),
			);
		}

		/**
		 * @return string the associated database table name
		 */
		public function tableName ()
		{
			return '{{order_measure_item}}';
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
			$criteria->compare('category_item_id', $this->category_item_id, true);
			$criteria->compare('rst_area', $this->rst_area, true);
			$criteria->compare('rst_memo', $this->rst_memo, true);
			$criteria->compare('create_time', $this->create_time, true);

			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
		}
	}