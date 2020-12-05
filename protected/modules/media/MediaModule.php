<?php

class MediaModule extends CWebModule
{
	public function init()
	{
		// this method is called when the module is being created
		// you may place code here to customize the module or the application

		// import the module-level models and components
		$this->setImport(array(
			'media.models.*',
			'media.components.*',
		));
	}

	public function beforeControllerAction($controller, $action)
	{
		if(parent::beforeControllerAction($controller, $action))
		{
			// this method is called before any module controller action is performed
			// you may place customized code here
			return true;
		}
		else
			return false;
	}
	
	public function Install()
	{
		$this->UnInstall();
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$sql = "insert into modules (modulename,description,createdby,moduleversion,installdate,recordstatus) 
				values ('media','Media Module','Prisma Data Abadi','0.1',now(),1)";
			$connection->createCommand($sql)->execute();
			
			$sql = "select moduleid from modules where lower(modulename) = 'media'";
			$moduleid = Yii::app()->db->createCommand($sql)->queryRow();
			
			$sql = "replace into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,recordstatus) 
				values ('media','Media','Media',".$moduleid['moduleid'].",null,'',1)";
			$connection->createCommand($sql)->execute();
			
			$sql = "select menuaccessid from menuaccess where lower(menuname) = 'media'";
			$menuparent = $connection->createCommand($sql)->queryRow();
			
			$sql = "replace into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,recordstatus) 
				values ('mediamgr','Media Manager','Media Manager',".$moduleid['moduleid'].",".$menuparent['menuaccessid'].",'mediamgr',1)";
			Yii::app()->db->createCommand($sql)->execute();
			
			$transaction->commit();
			return "ok";
		}
		catch(CdbException $e) // an exception is raised if a query fails
		{
			$transaction->rollback();
			return $e->getmessage();
		}
	}
	
	public function UnInstall()
	{
		$connection = Yii::app()->db;
		$transaction=$connection->beginTransaction();
		try
		{
			$sql = "select moduleid from modules where lower(modulename) = 'media'";
			$moduleid = Yii::app()->db->createCommand($sql)->queryRow();
			
			$sql = "delete from menuaccess where moduleid = ".$moduleid['moduleid'];
			$connection->createCommand($sql)->execute();
			
			$sql = "delete from modules where moduleid = ".$moduleid['moduleid'];
			$connection->createCommand($sql)->execute();
			
			$transaction->commit();
			return "ok";
		}
		catch(CdbException $e) // an exception is raised if a query fails
		{
			$transaction->rollback();
			return $e->getmessage();
		}
	}
}
