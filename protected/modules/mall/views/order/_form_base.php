<?php
/**
 * Created by JetBrains PhpStorm.
 * User: User
 * Date: 14-1-13
 * Time: 下午1:57
 * To change this template use File | Settings | File Templates.
 */

?>

<div class="input-group space">
    <?php echo $form->labelEx($model, 'user_id', array('class' => 'input-group-addon')); ?>
    <?php echo $form->dropdownlist($model, 'user_id', Tbfunction::showUser(), array('class' => 'form-control form-control1')); ?>
</div>

<div class="input-group space">
    <?php echo $form->labelEx($model, 'total_fee', array('class' => 'input-group-addon')); ?>
    <?php echo $form->textField($model, 'total_fee', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control form-control1')); ?>
</div>

<div class="input-group space">
    <?php echo $form->labelEx($model, 'pay_status', array('class' => 'input-group-addon')); ?>
    <?php echo $form->dropdownlist($model, 'pay_status', array('0' => '未支付', '1' => '已付款'), array('class' => 'form-control form-control1',));
    ?>
</div>

<div class="input-group space">
    <?php echo $form->labelEx($model, 'pay_fee', array('class' => 'input-group-addon')); ?>
    <?php echo $form->textField($model, 'pay_fee', array('size' => 10, 'maxlength' => 10, 'class' => 'form-control form-control1')); ?>
</div>
