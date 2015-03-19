<?php
$this->pageTitle = Yii::app()->name . ' - ' . UserModule::t("Login");
$this->breadcrumbs = array(
    UserModule::t("Login"),
);
?>
<style>
    div.form label {
        font-weight: bold;
        display: initial !important;
    }
</style>


<div class="login_box col-lg-12">
    <div class="login_tit">
        <a class="current">登录</a><span>（请正确填写您的用户名和密码）<span>
    </div>
    <?php if(Yii::app()->user->hasFlash('hint')): ?>
        <div class="success" style="width:100%;height:24px;font-size:16px;text-align:center;">
            <?php echo Yii::app()->user->getFlash('hint'); ?>
        </div>
    <?php endif; ?>
    <?php if (Yii::app()->user->hasFlash('loginMessage')): ?>

        <div class="success" style="width:100%;height:24px;font-size:16px;text-align:center;">
            <?php echo Yii::app()->user->getFlash('loginMessage'); ?>
        </div>

    <?php endif; ?>

    <div class="login_ct">
        <div class="login col-lg-6" id="log-box-div">

            <div class="login_form form">
                <?php echo CHtml::beginForm(); ?>

                <p class="note"><?php echo UserModule::t('Fields with <span class="required">*</span> are required.'); ?>

                    <?php echo CHtml::errorSummary($model); ?>

                <div class="form_c">
                    <div class="form_l"> <?php echo CHtml::activeLabelEx($model, 'username'); ?></div>
                    <?php echo CHtml::activeTextField($model, 'username',array('placeholder'=>' 请输入用户名或手机号...')) ?>
                </div>

                <div class="form_c">
                    <div class="form_l"><?php echo CHtml::activeLabelEx($model, 'password'); ?></div>
                    <?php echo CHtml::activePasswordField($model, 'password',array('placeholder'=>' 请输入正确的密码...')) ?>
                </div>

                <div class="form_c">
                    <div class="form_tip">
                        <?php echo CHtml::activeCheckBox($model, 'rememberMe',array('style'=>'float:left;')); ?>
                        <?php echo CHtml::activeLabelEx($model, 'rememberMe'); ?>
                        <span class="cor_gray" style="margin-left: 10px">使用公用电脑请勿勾选</span>
                    </div>

                </div>

                <div class="form_cc">
                    <div class="form_submit">
                        <?php echo CHtml::submitButton(UserModule::t("Login")) . "&nbsp&nbsp&nbsp&nbsp" . CHtml::link(UserModule::t("Lost Password?"), Yii::app()->getModule('user')->recoveryUrl) . "&nbsp;|&nbsp;&nbsp;" . CHtml::link(UserModule::t("Register"), Yii::app()->getModule('user')->registrationUrl); ?>

                    </div>
                </div>

                <?php echo CHtml::endForm(); ?>


            </div>
        </div>

        <div class="logo_b col-lg-6">
<!--            <img alt="" src="--><?php //echo Yii::app()->request->baseUrl; ?><!--/images/login_logo.png" width="257" height="152"/>-->
        </div>
    </div>
</div>
<?php
$form = new CForm(array(
    'elements' => array(
        'username' => array(
            'type' => 'text',
            'maxlength' => 32,
        ),
        'password' => array(
            'type' => 'password',
            'maxlength' => 32,
        ),
        'rememberMe' => array(
            'type' => 'checkbox',
        )
    ),
    'buttons' => array(
        'login' => array(
            'type' => 'submit',
            'label' => 'Login',
        ),
    ),
), $model);
?>
<div class="clear"></div>