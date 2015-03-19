<?php

	/**
	 * This is the model class for table "item_img".
	 *
	 * The followings are the available columns in table 'item_img':
	 * @property string $item_prop_imgs_id
	 * @property string $item_prop_img_id
	 * @property string $pic
	 *
	 * The followings are the available model relations:
	 * @property ItemPropImg   $itemPropImg
	 */
	class ItemPropImgs extends CActiveRecord
	{
		/**
		 * @return string the associated database table name
		 */
		public function tableName ()
		{
			return 'item_prop_imgs';
		}

		/**
		 * @return array validation rules for model attributes.
		 */
		public function rules ()
		{
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return array(
				array('item_prop_img_id, pic', 'required'),
				array('item_prop_img_id', 'length', 'max' => 10),
				array('pic', 'length', 'max' => 255),
				array('item_prop_imgs_id, item_prop_img_id, pic', 'safe', 'on' => 'search'),
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
				'itemPropImg' => array(self::BELONGS_TO, 'ItemPropImg', 'item_prop_img_id'),
			);
		}

		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels ()
		{
			return array(
				'item_prop_imgs_id' => '产品属性图片ID',
				'item_prop_img_id'  => '产品属性ID',
				'pic'               => '图片',
			);
		}

		/**
		 * Retrieves a list of models based on the current search/filter conditions.
		 *
		 * Typical usecase:
		 * - Initialize the model fields with values from filter form.
		 * - Execute this method to get CActiveDataProvider instance which will filter
		 * models according to data in model fields.
		 * - Pass data provider to CGridView, CListView or any similar widget.
		 *
		 * @return CActiveDataProvider the data provider that can return the models
		 * based on the search/filter conditions.
		 */
		public function search ()
		{
			// @todo Please modify the following code to remove attributes that should not be searched.

			$criteria = new CDbCriteria;

			$criteria->compare('item_prop_imgs_id', $this->item_prop_imgs_id, true);
			$criteria->compare('item_prop_img_id', $this->item_prop_img_id, true);
			$criteria->compare('pic', $this->pic, true);

			return new CActiveDataProvider($this, array(
				'criteria' => $criteria,
			));
		}

		/**
		 * Returns the static model of the specified AR class.
		 * Please note that you should have this exact method in all your CActiveRecord descendants!
		 * @param string $className active record class name.
		 * @return ItemImg the static model class
		 */
		public static function model ($className = __CLASS__)
		{
			return parent::model($className);
		}
	}