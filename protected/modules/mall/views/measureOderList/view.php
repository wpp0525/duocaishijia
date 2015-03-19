
    <link type="text/css" rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/css/order.css"/>


<div class="tabs-container" id="J_TabView">
<ul class="tabs-nav">
    <li class="current ks-switchable-trigger-internal164"><a name="tab0">预约单信息</a></li>
</ul>
<div class="tabs-panels">
    <div class="info-box order-info ks-switchable-panel-internal165" style="display: block;">
        <h2>预约单信息</h2>
        <div class="bd">
            <div class="addr_and_note">
                <dl>
                    <dt>
                        服务地址
                        ：
                    </dt>
                    <dd>
                        <?php
                            echo $orderMeasure->client_name.'&nbsp;&nbsp;'.$orderMeasure->client_mobile.'&nbsp;&nbsp;';
                            echo $orderMeasure::model()->showDetailAddress($orderMeasure);
                        ?>
                    </dd>
	                <dt>
	                预约时间
	                ：
	                </dt>
	                <dd>
		                <?php
			                echo $orderMeasure::model()->getMeasureTime($orderMeasure->client_memo);
		                ?>
	                </dd>
                    <dt>备注：</dt>
                    <dd>
                        <?php
                           echo $orderMeasure->client_memo;
                        ?>
                    </dd>
                </dl>
            </div>

            <hr>
            <!-- 订单信息 -->
            <div class="misc-info">
                <h1>预约单信息</h1>
                <dl>
                    <dt>预约单编号：</dt>
                    <dd>
                        <?php
                            echo $orderMeasure->measure_id;
                        ?>
                    </dd>
                    <dt>网上预约时间：</dt>
                    <dd>
                        <?php
                        if($orderMeasure->create_time){
                            echo $orderMeasure->create_time;
                        }
                        ?>
                    </dd></dl>
                <div class="clearfix"></div>
            </div>

            <hr>
            <div class="misc-info">
                <h1>预约详情</h1>
<!--                <dl>-->
<!--                    <dt>预约状态：</dt>-->
<!--                    <dd>-->
<!--                        --><?php
//                        echo Tbfunction::showStatus($orderMeasure->status);
//                        ?>
<!--                    </dd>-->
<!--                   -->
<!--                <dl>-->
                <div class="clearfix"></div>
            </div>
            <!-- 订单信息 -->
            <table id="table-margin">
                <colgroup>
                    <col class="item">
                    <col class="sku">
                    <col class="price">
                </colgroup>
                <tbody class="order">

                <tr class="order-hd">
                    <th class="item col-xs-3">服务</th>
                    <th class="sku col-xs-3">面积</th>
                    <th class="price col-xs-2">备注</th>
                </tr>

                <?php
                    foreach($orderMeasure_items as $orderMeasure_item){
                 ?>
                <tr class="order-item">
                    <td class="item">
<!--                        <div class="pic-info">-->
<!--                            <div class="pic s50">-->
<!--                                <a target="_blank" href="javascript:void(0)" title="商品图片">-->
<!--                                    <img alt="查看宝贝详情" src="--><?php //echo $orderItems->pic ?><!--" />-->
<!--                                </a>-->
<!--                            </div>-->
<!--                        </div>-->
                        <div class="txt-info">

                                <span class="name"><a href="#" title="" target="_blank"><?php
			                                $category = Category::model()->model()->findByPk($orderMeasure_item->category_item_id);
			                                echo $category->name;
		                                ?></a></span>
                                <br>

                        </div>
                    </td>
<!--                    <td class="price">-->
<!--                        --><?php
//                            echo $orderItems->price;
//                        ?>
<!--                    </td>-->
                    <td class="num">
                        <?php
                            echo $orderMeasure_item->rst_area;
                        ?>
                    </td>
	                <td class="totalfee">
		                <?php
			                echo $orderMeasure_item->rst_memo;
		                ?>
	                </td>
                </tr>

                <?php
                    }
                ?>

                </tbody>

            </table>
        </div>
    </div><!-- end order-info -->


