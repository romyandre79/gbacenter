<?php
class UserIdentity extends CUserIdentity {
	const ERROR_USER_PASSWORD_INVALID = 3;
	public function authenticate() {
		$connection	 = Yii::app()->db;
		$sql				 = 'select username,ifnull(count(1),0) as jumlah from useraccess 
			where lower(username) = :username and password = md5(:password)';
		$command		 = $connection->createCommand($sql);
		$command->bindvalue(':username', $this->username, PDO::PARAM_STR);
		$command->bindvalue(':password', $this->password, PDO::PARAM_STR);
		$user				 = $command->queryRow();
		if ($user['jumlah'] > 0) {
      Yii::app()->user->useraccessid = $user['useraccessid'];
			Yii::app()->user->username = $user['username'];
			Yii::app()->user->realname = $user['realname'];
			Yii::app()->user->password = $user['password'];
			Yii::app()->user->email = $user['email'];
			Yii::app()->user->phoneno = $user['phoneno'];
			Yii::app()->user->languageid = $user['languageid'];
			Yii::app()->user->languagename = $user['languagename'];
			Yii::app()->user->themeid = $user['themeid'];
			Yii::app()->user->themename = $user['themename'];
			Yii::app()->user->isonline = $user['isonline'];
			Yii::app()->user->token = $user['authkey'];
			Yii::app()->user->wallpaper = $user['wallpaper'];
			$this->errorCode = self::ERROR_NONE;
		} else {
			$this->errorCode = self::ERROR_USER_PASSWORD_INVALID;
		}
		return $this->errorCode == self::ERROR_NONE;
	}
}