<?php
/**
* This is the model class for table "item_prop".
*
* The followings are the available columns in table 'item_prop_img':
* @property string $item_prop_img_id
* @property string $item_id
* @property string $item_props
 *
* @property ItemPropImgs[] itemPropImgs
*/
class ItemPropImg extends YActiveRecord{
/**
* @return string the associated database table name
*/
	public function tableName()
	{
		return '{{item_prop_img}}';
	}

	/**
	 * @return array validation rules for model attributes.
	 */
	public function rules()
	{
		// NOTE: you should only define rules for those attributes that
		// will receive user inputs.
		return array(
			array('item_id, item_props' ,'required'),
			array('item_props', 'length', 'max'=> 255),
		);
	}
	/**
	 * @return array customized attribute labels (name=>label)
	 */
	public function attributeLabels()
	{
		return array(
			'item_prop_img_id' => '产品属性编码',
			'item_id' => '产品Id',
			'item_props' => '产品颜色',
		);
	}

	/**
	 * @return array relational rules.
	 */
	public function relations()
	{
		// NOTE: you may need to adjust the relation name and the related
		// class name for the relations automatically generated below.
		return array(
			'itemPropImgs' => array(self::HAS_MANY, 'ItemPropImgs', 'item_prop_img_id'),
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

		$criteria = new CDbCriteria;

		$criteria->compare('item_prop_img_id', $this->item_prop_img_id, true);
		$criteria->compare('item_id', $this->item_id, true);
		$criteria->compare('item_props', $this->item_props, true);

		return new CActiveDataProvider($this, array(
			'criteria' => $criteria,
		));
	}

	public  function  getItems(){
		$itemsData = array();
		$Items = Item::model()->findAll();
		$itemsData[] = CMap::mergeArray(array('0' => ''), CHtml::listData($Items, 'item_id', 'title'));
		return $itemsData;
	}

	/**
	 * Returns the static model of the specified AR class.
	 * Please note that you should have this exact method in all your CActiveRecord descendants!
	 * @param string $className active record class name.
	 * @return ItemProp the static model class
	 */
	public static function model($className = __CLASS__)
	{
		return parent::model($className);
	}
}