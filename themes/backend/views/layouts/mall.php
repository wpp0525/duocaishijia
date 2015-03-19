<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

    <div id="sidebar-nav">
        <?php
        $this->widget('bootstrap.widgets.TbNav', array(
            'type' => TbHtml::NAV_TYPE_LIST,
            'items' => array_merge(array(
                array('label' => '主菜单', 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
//                array('label' => '控制面板', 'icon' => 'home', 'url' => array('/site/index')),
                array('label' => '商品', 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
                array('label' => '商品分类', 'icon' => 'bookmark', 'url' => array('/mall/itemCategory/admin'), 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
                array('label' => '商品属性', 'icon' => 'list-alt', 'url' => array('/mall/itemProp/admin'), 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
                array('label' => '商品列表', 'icon' => 'list-alt', 'url' => array('/mall/item/admin'), 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
                array('label' => '图片空间', 'icon' => 'list-alt', 'url' => array('/mall/elfinder/admin'), 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
				array('label' => '商品颜色图组', 'icon' => 'list-alt','url' => array('/mall/itemPropImg/admin'), 'visible' => !Yii::app()->user->isGuest),
	            array('label' => '支付', 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
//                array('label' => '支付方式', 'icon' => 'magnet', 'url' => array('/mall/paymentMethod/admin'), 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
//                array('label' => '配送方式', 'icon' => 'plane', 'url' => array('/mall/shippingMethod/admin'), 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
                array('label' => '订单', 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
                array('label' => '订单列表', 'icon' => 'shopping-cart', 'url' => array('/mall/order/admin'), 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
//                array('label' => '资金列表', 'icon' => 'align-justify', 'url' => array('/mall/payment/admin'), 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
//                array('label' => '订单日志', 'icon' => 'film', 'url' => array('/mall/orderLog/admin'), 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
//                array('label' => '服务', 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
//                array('label' => '发货单', 'icon' => 'list-alt', 'url' => array('/mall/shipping/admin'), 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
//                array('label' => '退货单', 'icon' => 'list-alt', 'url' => array('/mall/refund/admin'), 'visible' => !Yii::app()->user->isGuest&& !Yii::app()->user->isCustomService()),
                array('label' => '子目录', 'visible' => !Yii::app()->user->isGuest),//&& !Yii::app()->user->isCustomService()),
            ), $this->menu),
        ));
        ?>
    </div>
    <div id="sidebar-content">
        <div class="row-fluid">
            <div class="span12">
                <?php if (isset($this->breadcrumbs)): ?>
                    <?php
                    $this->widget('bootstrap.widgets.TbBreadcrumb', array(
                        'links' => $this->breadcrumbs,
                    ));
                ?><!-- breadcrumbs -->
                <?php endif ?>
                <?php echo $content; ?>
            </div>
        </div>
    </div>
<?php $this->endContent(); ?>