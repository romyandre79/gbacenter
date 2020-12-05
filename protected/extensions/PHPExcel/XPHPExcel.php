<?php
class XPHPExcel extends CComponent {
	private static $_isInitialized = false;
	public static function init() {
		if (!self::$_isInitialized) {
			spl_autoload_unregister(array('YiiBase', 'autoload'));
			require(dirname(__FILE__).DIRECTORY_SEPARATOR.'vendor'.DIRECTORY_SEPARATOR.'PHPExcel.php');
			spl_autoload_register(array('YiiBase', 'autoload'));
			self::$_isInitialized = true;
		}
	}
	public static function createPHPExcel() {
		self::init();
		return new PHPExcel;
	}
}