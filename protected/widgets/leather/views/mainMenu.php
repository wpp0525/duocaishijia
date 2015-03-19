<?php
$menu = Menu::model()->findByPk(4);
$descendants = $menu->children()->findAll();

	/*$categories = Category::model()->new()->level(2)->findAll();

	$childItems = array();
	foreach ($categories as $category) {
		$childItems[] = array(
			'label'       => F::t($category->name),
			'url'         => Yii::app()->request->baseUrl.'catalog/index?cat='.$category->category_id,
			'itemOptions' => array(
				'class' => 'category-item',
				'role'  => 'categoryItem',
			)
		);
	}

	$items = array();

	$items[] = array(
		'label'          => F::t('全部商品分类'),
		'url'            => '#',
		'linkOptions'    => array(
			'class'       => 'dropdown-toggle',
			'data-toggle' => 'dropdown',
		),
		'items'          => $childItems,
		'itemOptions'    => array(
			'class' => 'dropdown all-category',
			'role'  => 'categoryItem'
		),
		'submenuOptions' => array(
			'class' => 'dropdown-menu',
			'role'  => 'menu'
		)
	);*/

/*
 * 单级菜单
 */
	$i=0;
foreach($descendants as $model){
    $items[] = array(
	    'itemOptions' => array(
	    'class' => 'menu_'.$i++,
    ),
        'linkOptions' =>array(
	        'style' => 'background:url('. Yii::app()->baseUrl.$model->pic.') no-repeat; background-position:center 25%'
        ),'label'=>strtoupper($model->name),'url'=>$model->url ? Yii::app()->request->baseUrl.'/'.$model->url : '#','active'=>Tbfunction::mainMenu(Yii::app()->baseUrl.'/'.$model->url));
}
$this->widget('zii.widgets.CMenu', array(
    'htmlOptions' => array('class' => 'nav_list','style' => 'padding-left: 0px;'),
    'activeCssClass'=>'current',
    'items'=>$items
));

/*
 * 多级菜单
 */
//foreach ($descendants as $model) {
//
//    if ($model->getChildCount() > 0) {
//        $items[] = array('label' => F::t($model->name), 'url' => $model->url ? Yii::app()->request->baseUrl . '/' . $model->url : '#',
//            'items' => $model->getChildMenu(),
//            'itemOptions' => array('class' => 'dropdown'), 'submenuOptions' => array('class' => 'dropdown'));
//    } else {
//        $items[] = array('label' => F::t($model->name), 'url' => $model->url ? Yii::app()->request->baseUrl . '/' . $model->url : '#');
//    }
//}
////
////print_r($items);
//$this->widget('zii.widgets.CMenu', array(
//    'htmlOptions' => array('class' => 'nav_list'),
//    'activeCssClass'=>'active',
//    'activateParents'=>true,
//    'items' => $items,
//));
//?>
