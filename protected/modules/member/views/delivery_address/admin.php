<?php
$this->breadcrumbs = array(
'服务地址' => array('admin'),
'管理',
);
?>
<div class="my_info">
    <a class="current">我的服务地址</a>
</div>
<div class="profile_info1">
    <div class="profile_info2">
<!--    <div class="box-title" style="font-size: 14px">服务地址</div>-->
    <div class="box-content">
      <?php //$this->widget('bootstrap.widgets.TbGridView', array(
//        'dataProvider'=>$dataProvider,
//        'type' => 'striped bordered condensed',
//        'columns'=>array(
//            'contact_name',
//            's.name',
//            'c.name',
//            'd.name',
//            'address',
//            'zipcode',
//            'phone' ,
//            'mobile_phone' ,
//            'memo' ,
//            array(
//            'name' => 'is_default',
//            'value' => 'Tbfunction::ShowYesOrNo($data->is_default)',
//            ),
//            array(
//            'name' => 'create_time',
//            'value' => 'date("Y-m-d", $data->create_time)',
//            'htmlOptions' => array('style'=>'width:100px')
//            ),
//            array(
//            'name' => 'update_time',
//            'value' => 'date("Y-m-d", $data->update_time)',
//            'htmlOptions' => array('style'=>'width:100px')
//            ),
//            array(
//                'class' => 'bootstrap.widgets.TbButtonColumn',
//            ),
//        ),
//        )); ?>

         <span id="item" style="margin-left: 20px">已保存有效的地址:</span>

            <?php $this->widget('zii.widgets.grid.CGridView', array(
                'dataProvider'=>$dataProvider,
                'columns'=>array(
                    'contact_name',
                    's.name',
                    'c.name',
                    'd.name',
                    'address',
//                    'zipcode',
                    'phone' ,
                    'mobile_phone' ,
                    'memo' ,
                    array(
                        'name' => 'is_default',
                        'value' => 'Tbfunction::ShowYesOrNo($data->is_default)',
                    ),
                    array(
                        'name' => 'create_time',
                        'value' => 'date("Y-m-d", $data->create_time)',
                        'htmlOptions' => array('style'=>'width:100px')
                    ),
                    array(
                        'name' => 'update_time',
                        'value' => 'date("Y-m-d", $data->update_time)',
                        'htmlOptions' => array('style'=>'width:100px')
                    ),
                    array(
                        'class' => 'CButtonColumn',
                        'template' => '{view}{update}{delete}',
                        'viewButtonUrl' => 'Yii::app()->createUrl("/member/delivery_address/view/id/".$data->contact_id)',
                    ),
                ),
            )); ?>

        <span id="item" style="margin-left:20px" >新建服务地址：</span>
        <div style="width:80%" class="form-horizontal">
            <?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
        </div>

    </div>

    </div>
</div>
