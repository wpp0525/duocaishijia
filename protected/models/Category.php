<?php

/**
 * This is the model class for table "category".
 *
 * The followings are the available columns in table 'category':
 * @property string $category_id
 * @property string $left
 * @property string $right
 * @property string $root
 * @property string $level
 * @property string $name
 * @property integer $label
 * @property string $url
 * @property string $pic
 * @property integer $is_show
 * @property string $desc
 * @property string $is_now_cat
 * @property string $erpID
 *
 * The followings are the available model relations:
 * @property Item[] $items
 * @property ItemProp[] $itemProps
 */
class Category extends CActiveRecord
{
    /**
     * @return string the associated database table name
     */
    public function tableName()
    {
        return 'category';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules()
    {
        // NOTE: you should only define rules for those attributes that
        // will receive user inputs.
        return array(
            array('name', 'required'),
            array('label, is_show', 'numerical', 'integerOnly' => true),
            array('left, right, root, level, is_now_cat,', 'length', 'max' => 10),
            array('name, url', 'length', 'max' => 200),
            array('pic,erpID', 'length', 'max' => 255),
            array('left, right, root, level,pic', 'safe'),
            // The following rule is used by search().
            // @todo Please remove those attributes that should not be searched.
            array('category_id, left, right, root, level, name, label, url, pic, is_show', 'safe', 'on' => 'search'),
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
            'items' => array(self::HAS_MANY, 'Item', 'category_id'),
            'itemProps' => array(self::HAS_MANY, 'ItemProp', 'category_id'),
        );
    }

    /**
     * @return array customized attribute labels (name=>label)
     */
    public function attributeLabels()
    {
        return array(
            'category_id' => '分类编码',
            'left' => '左节点',
            'right' => '右节点',
            'root' => '根节点',
            'level' => '级别',
            'name' =>Yii::t('backend','名字'),
            'label' => '标签',
            'url' => 'Url地址',
            'pic' => '图片',
            'is_show' => '显示',
	        'desc' => '描述',
	        'is_now_cat' =>'是否开通',
	        'erpID' =>'erpID',
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

        $criteria = new CDbCriteria;

        $criteria->compare('category_id', $this->category_id, true);
        $criteria->compare('left', $this->left, true);
        $criteria->compare('right', $this->right, true);
        $criteria->compare('root', $this->root, true);
        $criteria->compare('level', $this->level, true);
        $criteria->compare('name', $this->name, true);
        $criteria->compare('label', $this->label);
        $criteria->compare('url', $this->url, true);
        $criteria->compare('pic', $this->pic, true);
        $criteria->compare('is_show', $this->is_show);
        $criteria->compare('desc', $this->desc);
	    $criteria->compare('erpID', $this->erpID);

        return new CActiveDataProvider($this, array(
            'criteria' => $criteria,
        ));
    }

    /**
     * Returns the static model of the specified AR class.
     * Please note that you should have this exact method in all your CActiveRecord descendants!
     * @param string $className active record class name.
     * @return Category the static model class
     */
    public static function model($className = __CLASS__)
    {
        return parent::model($className);
    }

    public function behaviors()
    {
        return array(
            'NestedSetBehavior' => array(
                'class' => 'ext.nested-set-behavior.NestedSetBehavior',
                'leftAttribute' => 'left',
                'rightAttribute' => 'right',
                'levelAttribute' => 'level',
                'hasManyRoots' => true,
            ),
            'NestedSetExtBehavior' => array(
                'class' => 'ext.NestedSetExtBehavior',
                'id' => 'category_id',
            )
        );
    }

    /**
     * get label view
     * @return string
     * @author Lujie.Zhou(gao_lujie@live.cn, qq:821293064).
     */
    public function getLabel()
    {
        switch ($this->label) {
            case 1:
                return '<span class="label label-info" style="margin-right:5px">New</span>' . $this->name;
            case 2:
                return '<span class="label label-important" style="margin-right:5px">Hot!</span>' . $this->name;
            default:
                return $this->name;
                break;
        }
    }



    /**
     * get label list, use for editing category
     * @return array
     * @author Lujie.Zhou(gao_lujie@live.cn, qq:821293064).
     */
    public function getLabelList()
    {
        return array(
            '0' => '<span class="label label-success">None</span>',
            '1' => '<span class="label label-info">新品</span>',
            '2' => '<span class="label label-important">热卖!</span>',
        );
    }

    public function getUrl()
    {
        return $this->url ? $this->url : $this->category_id;
    }

    public function scopes()
    {
        return array(
            'new' => array(
                'condition' => 't.label = 1'
            ),
            'hot' => array(
                'condition' => 't.label = 2'
            ),
        );
    }

    public function limit($limit = 3)
    {
        $this->getDbCriteria()->mergeWith(array(
            'limit' => $limit,
        ));
        return $this;
    }

    public function level($level = 2)
    {
        $this->getDbCriteria()->mergeWith(array(
            'condition' => 't.level = ' . $level
        ));
        return $this;
    }

	public function getAllTree(){
		$catlog1 = Category::model()->findAllByAttributes(array(
			'level' => 1,
		));

		$attr = array();
		foreach($catlog1 as $catlog){
			$attr1 = array();
			$attr1['id'] = $catlog->category_id;
			$attr1['name'] = $catlog->name;
			$attr1['level'] = $catlog->level ;
			$attr1['list'] = $this->getTree1($attr1['id']);
			$attr[$attr1['id']] = $attr1;
		}
//		$file="./file.cache";
//		file_put_contents($file,serialize($attr));//写入缓存
		return $attr;
	}

	public function getTree1($id = 0){
		$catlog = Category::model()->findByPk($id);
		$catlogs = $catlog->children()->findAll();
		$attr = array();
		if(is_array($catlogs)){
			foreach($catlogs as $cat){
				$attes = array();
				$attes['id'] = $cat->category_id ;
				$attes['name'] = $cat->name;
				$attes['level'] = $catlog->level ;
				$attes['list'] = $this->getTree1($cat->category_id);
				$attr[$attes['id'] ] = $attes;
			}
		}
		return $attr;
	}

	public function getCatlog($attr = array(),$val=1){
		$tree = array();
		foreach ($attr as $k => $v){
			$tree = $v;
			$tree1 = $v['list'];
			if ($k == $val) {
				return $tree;
			}else{
				if(is_array($v))
					$tree =  $this->getCatlog($tree1, $val);
				return $tree;
			}
		}
		return  $tree;
	}
}