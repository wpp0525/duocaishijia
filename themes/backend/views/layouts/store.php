<?php /* @var $this Controller */ ?>
<?php $this->beginContent('//layouts/main'); ?>

	<div id="sidebar-nav">
		<?php $this->widget('bootstrap.widgets.TbNav', array(
			'type' => TbHtml::NAV_TYPE_LIST,
			'items' => array_merge(array(
				array('label' => '主菜单'),
				array('label' => '查看门店', 'icon' => 'list', 'url' => array('/mall/store/admin')),
//				array('label' => '分配订单', 'icon' => 'leaf', 'url' => array('/mall/order/adminStore')),
				array('label' => '添加门店', 'icon' => 'list', 'url' => array('/mall/store/create')),
				array('label' => '子目录'),
			), $this->menu),
		)); ?>
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