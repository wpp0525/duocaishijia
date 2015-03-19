<div class="my_info">
    <a class="current">我的预约测量单</a>
</div>
<div class="profile_info1">
<!--	<div class="box-title">我的预约测量单</div>-->
<!--        <div style="margin: 30px 0 0 20px">-->
	<div class="profile_info1_form">
		<div class="measure-list">
			<div class="measure-list-title">名称</div>
			<div class="measure-list-title">时间</div>
			<div class="measure-list-title">状态</div>
			<div style="clear: left"></div>
		</div>

			<?php
				foreach($measureOrders as $measure_Order){
					$name = null;
					$measureOrderItem = ArOrderMeasureItem::model()
						->findAll("measure_id=:measure_id",
							array("measure_id"=>$measure_Order->measure_id));
					foreach($measureOrderItem as $measure_Order_Item){
						$category_item = Category::model()->findAll("category_id=:category_id",
							array(":category_id"=>$measure_Order_Item->category_item_id));
						foreach($category_item as $category){
							if(empty($name))
								$name = $category->name;
							else
								$name .= "，".$category->name;
						}
					}
					?>
					<div class="measure-list-content-whole">
						<div class="measure-list-content">
							<?php echo CHtml::link($name, array('/member/MeasureOrderlist/view','measureId'=>$measure_Order->measure_id)) ?>
						</div>
						<div class="measure-list-content">
							<?php echo $measure_Order->create_time ?>
						</div>
						<div class="measure-list-content">
							<?php $status = $measure_Order->status; ?>
							<?php   if($status == 0) {?>
										<span style="color: #FF4F03"><?php echo $measure_Order->getMeasureStatus(); ?></span>
							<?php }
									if($status == 1){
							?>
										<span style="color: #47A11B"><?php echo $measure_Order->getMeasureStatus(); ?></span>
							<?php }?>

						</div>
						<div style="clear: left"></div>
					</div>
				<?php
				}
			?>

	</div>

	<div class="pager">
		<ul class="yiiPager">
			<li class="page">
				<?php echo CHtml::link("首页", array("/member/measureOrderlist/admin?index=1"));
				?>
			</li>
			<li class="page">
				<?php
					$previousPage = $currentPage-1;
					if($previousPage > 0){
						echo CHtml::link("上一页", array("/member/measureOrderlist/admin?index=".$previousPage));
					}
					else{
						?>
						上一页
					<?php } ?>
			</li>
			<?php
				for($i=1; $i<=$count; $i++){
					if($currentPage == $i){
						?>
						<li class="page selected">
							<?php
								echo CHtml::link($i, array("/member/measureOrderlist/admin?index=".$i))
							?>
						</li>
					<?php
					}
					else{
						?>
						<li class="page">
							<?php
								echo CHtml::link($i, array("/member/measureOrderlist/admin?index=".$i))
							?>
						</li>
					<?php
					}
				}
			?>
			<li class="page">
				<?php $nextPage = $currentPage+1;
					if($nextPage <= $count)
						echo CHtml::link("下一页", array("/member/measureOrderlist/admin?index=".$nextPage));
					else{
						?>
						下一页
					<?php }?>
			</li>
			<li class="page">
				<?php
					echo CHtml::link("末页", array("/member/measureOrderlist/admin?index=".$count));
				?>
			</li>
		</ul>
	</div>

</div>

