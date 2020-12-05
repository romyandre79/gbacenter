<?php
class DefaultController extends AdminController {
	public $menuname = 'dashboard';
	public $module	 = 'admin';
	public function actionIndex() {
		parent::actionIndex();
		$this->pageTitle = "Dashboard";
		$sql						 = "select widgetname,widgettitle,widgeturl,dashgroup,webformat,position,(
			select count(1)
			from userdash d0
			where d0.dashgroup = a.dashgroup and d0.menuaccessid = a.menuaccessid and d0.groupaccessid = d.groupaccessid
			) dashcount 
			from userdash a 
			inner join menuaccess b on b.menuaccessid = a.menuaccessid 
			inner join widget c on c.widgetid = a.widgetid 
			inner join groupaccess d on d.groupaccessid = a.groupaccessid 
			inner join usergroup e on e.groupaccessid = d.groupaccessid 
			inner join useraccess f on f.useraccessid = e.useraccessid 
			where lower(menuname) = lower('".$this->menuname."') and f.username = '".Yii::app()->user->name."'
			order by dashgroup asc, position asc ";
		$dependency			 = new CDbCacheDependency('SELECT MAX(userdashid) FROM userdash');
		$datas					 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
		$this->render('index', array('datas' => $datas));
	}
}