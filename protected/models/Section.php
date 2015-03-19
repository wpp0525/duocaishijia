<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 14-12-25
 * Time: 下午2:29
 */
	/**
	 * This is the model class for table "section".
	 *
	 * The followings are the available columns in table 'area':
	 * @property string section_id
	 * @property string section
	 */
	class Section extends CActiveRecord
	{
		/**
		 * @return string the associated database table name
		 */
		public function tableName()
		{
			return 'section';
		}
		public function rules()
		{
			// NOTE: you should only define rules for those attributes that
			// will receive user inputs.
			return array(
				array('section_id, section', 'required'),
				array('section_id', 'length', 'max'=>10),
				array('section', 'length', 'max'=>255),
				// The following rule is used by search().
				// @todo Please remove those attributes that should not be searched.
				array('section, section_id', 'safe', 'on'=>'search'),
			);
		}
		/**
		 * @return array customized attribute labels (name=>label)
		 */
		public function attributeLabels()
		{
			return array(
				'section' => 'Section',
				'section_id' => 'SectionId',
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
		public function search()
		{
			// @todo Please modify the following code to remove attributes that should not be searched.

			$criteria=new CDbCriteria;

			$criteria->compare('section',$this->section_id,true);
			$criteria->compare('section_id',$this->section_id,true);

			return new CActiveDataProvider($this, array(
				'criteria'=>$criteria,
			));
		}

		/**
		 * Returns the static model of the specified AR class.
		 * Please note that you should have this exact method in all your CActiveRecord descendants!
		 * @param string $className active record class name.
		 * @return Area the static model class
		 */
		public static function model($className=__CLASS__)
		{
			return parent::model($className);
		}
	}