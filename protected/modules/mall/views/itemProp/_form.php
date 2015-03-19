<?php
	// Yii::app()->clientScript->registerCoreScript('jquery');
 $form = $this->beginWidget('bootstrap.widgets.TbActiveForm', array(
    'id' => 'item-prop-form',
    'htmlOptions' => array(
        'class' => 'form-horizontal',
	    'enctype'=>'multipart/form-data',
    ),
     'enableAjaxValidation' => false,
));
$form = new TbActiveForm();

if ($model->hasErrors()): ?>
    <div class="control-group">
        <?php echo $form->errorSummary($model); ?>
    </div>
<?php endif; ?>
<div class="control-group"><p class="help-block">带 <span class="required">*</span> 的字段为必填项.</p></div>
<?php
echo $form->dropDownListControlGroup($model, 'category_id', $model->getCategory());
echo $form->dropDownListControlGroup($model, 'parent_prop_id', $props);
echo $form->textFieldControlGroup($model, 'prop_name');
echo $form->textFieldControlGroup($model, 'prop_alias');
echo $form->inlineRadioButtonListControlGroup($model, 'type', $model->allType());
foreach (array('is_key_prop' => 'allKey', 'is_sale_prop' => 'allSale', 'is_color_prop' => 'allColor','is_combo_prop' => 'allCombo','is_room_prop' => 'allRoom', 'must' => 'allMust', 'multi' => 'allMulti', 'status' => 'allStatus') as $k => $v) {
    echo $form->dropDownListControlGroup($model, $k, call_user_func(array($model, $v)));
}
echo $form->textFieldControlGroup($model, 'prop_title');
echo $form->textAreaControlGroup($model, 'prop_desc');
?>

<h2><a id="add-row" href="#">添加属性值</a></h2>
<fieldset>
<!--    <legend>属性值</legend>-->
    <div class="PropValues">
        <table id="add_prop" class="example" width="800px">
            <tr>
                <th>移动</th>
                <th>属性值名称</th>
                <th>克隆</th>
                <th>删除</th>
	            <th>图片url</th>
	            <th>erpID </th>
            </tr>
            <?php if ($model->isNewRecord) {  ?>
                <tr id="add-template">
                    <td class="icons">
                        <img class="drag-handle"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/drag.png"
                             alt="click and drag to rearrange"/>
                    </td>
                    <td>
                        <input id="tf1" type="text" name="PropValue[value_name][]" style='width:150px;height:28px'/>
                    </td>
                    <td class="icons">
                        <img class="row-cloner"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/clone.png"
                             alt="Clone Row"/>
                    </td>
                    <td class="icons">
                        <img class="row-remover"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/remove.png"
                             alt="Remove Row"/>
                    </td>
	                <td>
		                <input id="tf2" type="text" name="PropValue[pic][]" style='width:260px;height:28px'/>
	                </td>
	                <td>
		                <input id="tf3" type="text" name="PropValue[erpID][]" style='width:100px;height:28px'/>
	                </td>

<!--	                <td class="controls">-->
<!--		                <div class="controls2">-->
<!--		                --><?php //$this->widget('ext.elFinder.ServerFileInput', array( //内部嵌入上传按钮不成功
//				                'model' => $model,
//				                'attribute' => 'item_prop_id',
//				                'filebrowserBrowseUrl' => Yii::app()->createUrl('mall/elfinder/view'),
//			                )); ?>
<!--		                </div>-->
<!--	                </td>-->
	                <div class="controls1" style="display: inline;">
		                <?php
			                $this->widget('ext.elFinder.ServerFileInput', array(//外部上传按钮
				                'model' => $model,
				                'attribute' => 'item_prop_id',
				                'filebrowserBrowseUrl' => Yii::app()->createUrl('mall/elfinder/view'),
			                ));  ?>
		                <button id="bt" name="button" type="button" class="btn" style=" margin-top: -40px;">获得url值</button>
	                </div>
                </tr>
            <?php
              } else {
                $cri = new CDbCriteria(array(
                    'condition' => 'item_prop_id =' . $model->item_prop_id,
                    'order' => 'sort_order asc, prop_value_id asc'
                ));
                $propValues = PropValue::model()->findAll($cri);

                foreach ($propValues as $k => $sv) {
                    ?>
                    <tr id="update-template">
                        <td class="icons">
                            <img class="drag-handle"
                                 src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/drag.png"
                                 alt="click and drag to rearrange"/>
                        </td>
                        <td>
                            <input type="hidden" name="PropValue[prop_value_id][]"
                                   value="<?php echo $sv->prop_value_id;  //遍历拿到数据库的id，但是隐藏，用于保存数据库 ?>"/>
                            <input id="tf1" type="text" name="PropValue[value_name][]"  style='width:150px;height:28px'
                                   value="<?php echo $sv->value_name  //遍历拿到数据库的name值, 显示出来 ?> "/>
                        </td>
                        <td class="icons">
                            <img class="row-cloner"
                                 src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/clone.png"
                                 alt="Clone Row"/>
                        </td>
                        <td class="icons">
                            <img class="row-remover"
                                 src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/remove.png"
                                 alt="Remove Row"/>
                        </td>
	                    <td>
		                    <input id="tf2" type="text" name="PropValue[pic][]" style='width:260px;height:28px'
		                           value="<?php echo $sv->pic   //遍历拿到数据库的pic值, 如果是删除的需要加一个js进行判断， ?>"/>
	                    </td>

	                    <td>
		                    <input id="tf3" type="text" name="PropValue[erpID][]" style='width:100px;height:28px'
		                           value="<?php echo $sv->erpID   //遍历拿到数据库的pic值, 如果是删除的需要加一个js进行判断， ?>"/>
	                    </td>
                    </tr>
                <?php } ?>

	            <div class="controls1" style="display: inline;">
		            <?php
			            $this->widget('ext.elFinder.ServerFileInput', array(//外部上传按钮
				            'model' => $model,
				            'attribute' => 'item_prop_id',
				            'filebrowserBrowseUrl' => Yii::app()->createUrl('mall/elfinder/view'),
			            ));  ?>
		            <button id="bt" name="button" type="button" class="btn" style=" margin-top: -40px">获得url值</button>
	            </div>

                <tr id="add-template">
                    <td class="icons">
                        <img class="drag-handle"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/drag.png"
                             alt="click and drag to rearrange"/>
                    </td>
                    <td>
                        <input type="hidden" name="PropValue[prop_value_id][]"/>
                        <input id="tf1" type="text" name="PropValue[value_name][]" style='width:150px;height:28px' />
                    </td>
                    <td class="icons">
                        <img class="row-cloner"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/clone.png"
                             alt="Clone Row"/>
                    </td>
                    <td class="icons">
                        <img class="row-remover"
                             src="<?php echo Yii::app()->theme->baseUrl ?>/images/small_icons/remove.png"
                             alt="Remove Row"/>
                    </td>
	                <td>
		                <input id="tf2" type="text" name="PropValue[pic][]" style='width:260px;height:28px'/>
	                </td>
	                <td>
		                <input id="tf3" type="text" name="PropValue[erpID][]" style='width:100px;height:28px'/>
	                </td>
                </tr>
            <?php } ?>
        </table>
    </div>
</fieldset>

<?php

if (!$is_view) {
    echo TbHtml::formActions(array(
        TbHtml::submitButton('Submit', array('color' => TbHtml::BUTTON_COLOR_PRIMARY)),
        TbHtml::resetButton('Reset'),
    ));
}
$this->endWidget();
?>

<script type="text/javascript">
	$(function(){
		$('#bt').click(function() {
    	$('#tf2__c').val( $('#ItemProp_item_prop_id').val());
		})
	});

	//radio默认选中
	$(function initradio(){
		$('[name="ItemProp[type]"]')[2].checked=true;
	})
</script>
