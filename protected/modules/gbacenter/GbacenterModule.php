<?php
require_once('gbacenterhelper.php');

class GbacenterModule extends CWebModule {
	public function init() {
		$this->setImport(array(
			'gbacenter.components.*',
		));
	}
	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			return true;
		} else return false;
	}
	public function Install() {
	}
	public function UnInstall() {
	}
}