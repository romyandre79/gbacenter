<?php
class Userprofile extends Portlet {
	public $sqldata = 'select a.useraccessid,a.username, a.password, a.realname, a.email, a.phoneno, a.userphoto
					from useraccess a 
          left join language b on b.languageid = a.languageid ';
	protected function renderContent() {
		$this->sqldata = $this->sqldata." where username = '".Yii::app()->user->id."'";
		$db						 = Yii::app()->db->createCommand($this->sqldata)->queryRow();
		$this->render('userprofile',
			array('useraccessid' => $db['useraccessid'], 'username' => $db['username'],
			'password' => $db['password'], 'realname' => $db['realname'], 'email' => $db['email'],
			'phoneno' => $db['phoneno'], 'userphoto' => $db['userphoto']));
	}
}