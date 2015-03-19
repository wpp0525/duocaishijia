<?php

/**
 * Yii-Plugin module
 * 
 * @author Viking Robin <healthlolicon@gmail.com> 
 * @link https://github.com/health901/yii-plugin
 * @license https://github.com/health901/yii-plugins/blob/master/LICENSE
 * @version 1.0
 */
class AlipayModule extends CWebModule {

	public $pluginRoot = 'application.default';
	public $layout = '//layouts/main';
	public $moduleDir;

	public function init() {
		$this->moduleDir = dirname(__FILE__);
		Yii::setPathOfAlias('alipayModule', $this->moduleDir);
		Yii::import('alipayModule.lib.*');
		//require_once($this->moduleDir . DIRECTORY_SEPARATOR . 'lib' . DIRECTORY_SEPARATOR . '');
	}

}
