<?php

 $this->beginContent('//layouts/main'); ?>

<div class="big-box container_24" >
    <div class="big-box-left grid_4">
        <div class="box my-sidenav" style="font:14px;">
            <div class="box-title">我的账户</div>
            <div class="box-content">
                <ul>
                    <li><span><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/profile1.png"></span><?php echo CHtml::link('个人信息', array('/user/profile/edit')) ?></li>
                    <li><span><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/profile2.png"></span><?php echo CHtml::link('服务地址', array('/member/delivery_address/admin')) ?></li>
                    <li><span><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/profile3.png"></span><?php echo CHtml::link('修改密码', array('/user/profile/changepassword')) ?></li>
                </ul>
            </div>
            <div class="box-title">我的交易</div>
            <div class="box-content">
                <ul>
                    <li><span><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/profile4.png"></span><?php echo CHtml::link('我的订单', array('/member/orderlist/admin')) ?></li>
                    <li><span><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/profile5.png"></span><?php echo CHtml::link('我的测量单', array('/member/MeasureOrderlist/admin')) ?></li>
                    <li><span><img src="<?php echo Yii::app()->theme->baseUrl; ?>/image/profile6.png"></span><?php echo CHtml::link('我的服务车', array('//cart')) ?></li>
                </ul>
            </div>
        </div>
    </div>
    <div class="big-box-right grid_20">
        <div id="content">
            <?php echo $content; ?>
        </div><!-- content -->
    </div>
    <div class="clr"></div>

</div>


<?php $this->endContent(); ?>
