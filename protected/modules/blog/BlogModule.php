<?php
require_once('bloghelper.php');

class BlogModule extends CWebModule {
	public function init() {
		$this->setImport(array(
			'blog.components.*',
		));
	}
	public function beforeControllerAction($controller, $action) {
		if (parent::beforeControllerAction($controller, $action)) {
			return true;
		} else return false;
	}
	public function Install() {
		$connection	 = Yii::app()->db;
		$sql				 = "select moduleid from modules where lower(modulename) = 'admin'";
		$adminid		 = $connection->createCommand($sql)->queryScalar();
		if ($adminid > 0) {
			$this->UnInstall();
			$sql = "insert into modules (modulename,description,createdby,moduleversion,installdate,themeid,recordstatus) 
				values ('blog','Blog Module','Prisma Data Abadi','1.0',now(),1,1)";
			$connection->createCommand($sql)->execute();

			$sql			 = "select moduleid from modules where lower(modulename) = 'blog'";
			$moduleid	 = $connection->createCommand($sql)->queryScalar();

			$sql = "replace into modulerelation (moduleid,relationid) 
				values (".$moduleid.",".$adminid.")";
			$connection->createCommand($sql)->execute();

			$sql = "replace into widget (widgetname,widgettitle,widgetversion,widgetby,description,widgeturl,moduleid,recordstatus) 
				values ('recentcomment','Recent Comment','0.1','Prisma Data Abadi','Recent Comment List','recentcomment',".$moduleid.",1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into widget (widgetname,widgettitle,widgetversion,widgetby,description,widgeturl,moduleid,recordstatus) 
				values ('recenttag','Recent Tag','0.1','Prisma Data Abadi','Recent Tag List','recenttag',".$moduleid.",1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into widget (widgetname,widgettitle,widgetversion,widgetby,description,widgeturl,moduleid,recordstatus) 
				values ('recentpost','Recent Post','0.1','Prisma Data Abadi','Recent Post List','recentpost',".$moduleid.",1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into widget (widgetname,widgettitle,widgetversion,widgetby,description,widgeturl,moduleid,recordstatus) 
				values ('topcategory','Top Category','0.1','Prisma Data Abadi','Top Category List','topcategory',".$moduleid.",1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into widget (widgetname,widgettitle,widgetversion,widgetby,description,widgeturl,moduleid,recordstatus) 
				values ('searchpost','Search','0.1','Prisma Data Abadi','Search','search',".$moduleid.",1)";
			$connection->createCommand($sql)->execute();

			$sql			 = "select max(sortorder) from menuaccess where menuurl is null";
			$sortorder = $connection->createCommand($sql)->queryScalar() + 1;

			$sql = "replace into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,sortorder,recordstatus) 
				values ('blog','Blog','Blog',".$moduleid.",null,null,".$sortorder.",1)";
			$connection->createCommand($sql)->execute();

			$sql				 = "select menuaccessid from menuaccess where lower(menuname) = 'blog'";
			$menuparent	 = $connection->createCommand($sql)->queryScalar();

			$sql = "replace into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,sortorder,recordstatus) 
				values ('category','Category','Category',".$moduleid.",".$menuparent.",'category',1,1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,sortorder,recordstatus) 
				values ('post','Post','Post',".$moduleid.",".$menuparent.",'post',2,1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,sortorder,recordstatus) 
				values ('slideshow','Slide Show','Slide Show',".$moduleid.",".$menuparent.",'slideshow',2,1)";
			$connection->createCommand($sql)->execute();

			$sql = "
				replace into groupmenu (groupaccessid,menuaccessid,isread,iswrite,ispost,isreject,ispurge,isupload,isdownload)
				select 1,menuaccessid,1,1,1,1,1,1,1
				from menuaccess 
				where moduleid = ".$moduleid."
				";
			$connection->createCommand($sql)->execute();

			$sql = "replace into groupaccess (groupname,description,recordstatus) 
				values ('editor','Editor',1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into groupaccess (groupname,description,recordstatus) 
				values ('author','Author',1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into groupaccess (groupname,description,recordstatus) 
				values ('contributor','Contributor',1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into groupaccess (groupname,description,recordstatus) 
				values ('subscriber','Subscriber',1)";
			$connection->createCommand($sql)->execute();

			$sql = "replace into parameter (paramname,paramvalue,description) 
				values ('allowedcomment','0','Allowed Comment')";
			$connection->createCommand($sql)->execute();

			$sql = "replace into parameter (paramname,paramvalue,description) 
				values ('maxrecentpost','5','Max Recent Post')";
			$connection->createCommand($sql)->execute();

			$sql = "replace into parameter (paramname,paramvalue,description) 
				values ('maxrecentcomment','5','Max Recent Comment')";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE IF NOT EXISTS `category` (
						`categoryid` INT(11) NOT NULL AUTO_INCREMENT,
						`title` VARCHAR(50) NOT NULL,
						`parentid` INT(11) NULL DEFAULT NULL,
						`description` TEXT NOT NULL,
						`slug` VARCHAR(50) NOT NULL,
						`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
						PRIMARY KEY (`categoryid`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE IF NOT EXISTS `post` (
						`postid` INT(11) NOT NULL AUTO_INCREMENT,
						`useraccessid` INT(11) NOT NULL,
						`title` VARCHAR(200) NOT NULL,
						`description` TEXT NOT NULL,
						`metatag` TEXT NOT NULL,
						`slug` VARCHAR(50) NOT NULL,
						`postupdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
						`created` DATETIME NULL DEFAULT NULL,
						`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
						PRIMARY KEY (`postid`),
						UNIQUE INDEX `uq_post_title` (`title`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE IF NOT EXISTS `postcategory` (
						`postcategoryid` INT(11) NOT NULL AUTO_INCREMENT,
						`postid` INT(11) NOT NULL,
						`categoryid` INT(11) NOT NULL,
						PRIMARY KEY (`postcategoryid`)
					)
					COLLATE='utf8_general_ci'
					ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE IF NOT EXISTS `postcomment` (
					`postcommentid` INT(11) NOT NULL AUTO_INCREMENT,
					`postid` INT(11) NOT NULL,
					`useraccessid` INT(11) NOT NULL,
					`comment` TEXT NOT NULL,
					`commentdate` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
					`recordstatus` TINYINT(4) NOT NULL DEFAULT '0',
					PRIMARY KEY (`postcommentid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();

			$sql = "CREATE TABLE IF NOT EXISTS `slideshow` (
				`slideshowid` INT(11) NOT NULL AUTO_INCREMENT,
				`slidepic` VARCHAR(50) NOT NULL,
				`slidetitle` VARCHAR(50) NOT NULL,
				`slidedesc` TEXT NOT NULL,
				PRIMARY KEY (`slideshowid`)
				)
				COLLATE='utf8_general_ci'
				ENGINE=InnoDB;";
			$connection->createCommand($sql)->execute();
			return "ok";
		} else {
			return "Need module Admin to be installed";
		}
	}
	public function UnInstall() {
		$connection	 = Yii::app()->db;
		$sql				 = "select moduleid from modules where modulename = 'blog'";
		$module			 = $connection->createCommand($sql)->queryScalar();

		if ($module > 0) {
			$sql = "delete from menuaccess where moduleid = ".$module;
			$connection->createCommand($sql)->execute();

			$sql = "delete from modulerelation where moduleid = ".$module;
			$connection->createCommand($sql)->execute();

			$sql = "delete from widget where moduleid = ".$module;
			$connection->createCommand($sql)->execute();

			$sql = "delete from modules where modulename = 'blog'";
			$connection->createCommand($sql)->execute();

			$sql = "drop table if exists postcomment";
			$connection->createCommand($sql)->execute();

			$sql = "drop table if exists postcategory";
			$connection->createCommand($sql)->execute();

			$sql = "drop table if exists posts";
			$connection->createCommand($sql)->execute();

			$sql = "drop table if exists category";
			$connection->createCommand($sql)->execute();

			$sql = "drop table if exists slideshow";
			$connection->createCommand($sql)->execute();
		}
		return "ok";
	}
}