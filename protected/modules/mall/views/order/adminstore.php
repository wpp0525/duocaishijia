<h1><?php echo Yii::t('main','StoreDeliver Orders');?></h1>


<div class="search-form" style="display:none">
    <?php $this->renderPartial('select_user', array(
        'users' => $users,
    )); ?>
</div>

<?php $this->widget('bootstrap.widgets.TbGridView', array(
    'id' => 'order-grid',
    'dataProvider' => $model->searchQuery(),
    'filter' => $model,
    'enableHistory' => 'true',
    'columns' => array(
        'order_id',
        array(
            'name' => 'user_id',
            'header' => '会员账号',
            'value' => 'Tbfunction::getUser($data->user_id)',
        ),
        array(
            'name' => 'pay_status',
            'value' => 'Tbfunction::showPayStatus($data->pay_status)',
            //  'filter' => Tbfunction::ReturnPayStatus(),
        ),


        array(
            'name' => 'refund_status',
            'value' => 'Tbfunction::showRefundStatus($data->refund_status)',
            'filter' => Tbfunction::ReturnRefundStatus(),
        ),
//        array(
//            'name' => 'payment_method_id',
//            'value' => 'Tbfunction::showPayMethod($data->payment_method_id)',
//            'filter' => Tbfunction::ReturnPayMethod(),
//        ),
        'pay_fee',
//        'ship_fee',
        'total_fee',
//        array(
//            'name' => 'shipping_method_id',
//            'value' => 'Tbfunction::showShipMethod($data->shipping_method_id)',
//            'filter' => Tbfunction::ReturnShipMethod(),
//        ),
        array(
            'name' => 'create_time',
            'value' => 'date("Y-m-d H:i:s",$data->create_time)'
        ),
        array(
            'name' => 'receiver_name',
        ),
//        array(
//            'name' => 'ship_status',
//            'value' => 'Tbfunction::showShipStatus($data->ship_status)',
//            'filter' => Tbfunction::ReturnShipStatus(),
//        ),

        array(
            'class' => 'bootstrap.widgets.TbButtonColumnQuery',
            'template' => '{deliverstore}',
            'buttons' => array(
                'deliverstore' => array(
                    'label' => '分配门店',
               //    'icon' => 'plane',
                    'url' => 'Yii::app()->createUrl("mall/order/deliverstore", array("id"=>$data->order_id))',
                    'options' => array(
                        'class' => 'btn btn-info btn-lg btn-assign',
                        'data-target' => '#myModal',
                        'data-toggle' => 'modal',
                        'ajax' => array(
                            'type' => 'POST',
                            'url' => "js:$(this).attr('href')",
                            'success' => 'function(data) { $("#myModal .modal-body").html(data); $("#myModal").modal(); }',
                            'error' => 'function(XHR) {$("#myModal .modal-body").html("未知错误"); $("#myModal").modal();}',
                            'data' => array('fid' => 'js:this.value', 'YII_CSRF_TOKEN' => Yii::app()->request->csrfToken),
                        ),
                    ),
                ),
            ),
        ),
//
//         array(
//            'class' => 'bootstrap.widgets.TbButtonColumnQuery',
//        ),
    )

));
?>

<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title" id="myModalLabel">分配门店信息</h4>
            </div>
            <div class="modal-body">
                ...
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
</div><!-- /.modal -->



<script>
    $("#myModal").on("hidden", function () {
        $(this).removeData("modal");
    });
</script>
