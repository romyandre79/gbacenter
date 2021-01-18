<?php
class SiteController extends Controller {
	private $_identity;
	public $module = '';
	public function actionIndex() {
		parent::actionIndex();
    $this->pageTitle = getparameter('tagline');
    $this->description = getparameter('sitetitle').' '.getparameter('tagline');
    $citys = Yii::app()->db->createCommand("
      select cityid,cityname
      from city
      where recordstatus = 1 
      order by cityname asc
      ")->queryAll();
    $provinces = Yii::app()->db->createCommand("
      select provinceid,provincename
      from province
      where recordstatus = 1 
      order by provincename asc
      ")->queryAll();
    $countrys = Yii::app()->db->createCommand("
      select countryid,countryname
      from country
      where recordstatus = 1
      order by countryname asc 
      ")->queryAll();
    $this->render('index',array('citys'=>$citys,'provinces'=>$provinces,'countrys'=>$countrys));
	}
	public function actionError() {
		$error = Yii::app()->errorHandler->error;
		if ($error) {
			$this->render('error', array('error' => $error));
		}
	}
	public function actionLogin() {
		if (isset($_POST['pptt']) && (isset($_POST['sstt']))) {
			$this->_identity = new UserIdentity($_POST['pptt'], $_POST['sstt']);
			$this->_identity->authenticate();
			if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
				$rememberMe	 = isset($_POST['rrmm']) ? $_POST['rrmm'] : false;
				$duration		 = $rememberMe ? 3600 * 24 * 30 : 0; // 30 days
				Yii::app()->user->login($this->_identity, $duration);
				Yii::app()->db->createCommand("update useraccess set isonline = 1 where lower(username) = lower('".Yii::app()->user->id."')")->execute();
				getMessage('success', 'welcome');
			} else {
				getMessage('error', 'tryagain');
			}
		} else {
      Yii::app()->theme = "blue";
			$this->render('login');
		}
	}
	public function actionLogout() {
		Yii::app()->db->createCommand("update useraccess set isonline = 0 where lower(username) = lower('".Yii::app()->user->id."')")->execute();
		Yii::app()->user->logout();
		$this->redirect(Yii::app()->user->returnUrl);
	}
	public function actionSaveuser() {
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			if ($_POST['useraccessid'] > 0) {
				$sql = "update useraccess 
					set realname = '".$_POST['realname']."', 
					email = '".$_POST['email']."',
					phoneno = '".$_POST['phoneno']."',
					birthdate = '".$_POST['birthdate']."',
					useraddress = '".$_POST['useraddress']."' 
					where useraccessid = ".$_POST['useraccessid'];
				$connection->createCommand($sql)->execute();
				if ($_POST['password'] !== '') {
					$sql = "update useraccess 
						set password = md5('".$_POST['realname']."') 
						where useraccessid = ".$_POST['useraccessid'];
					$connection->createCommand($sql)->execute();
				}
			} else {
				$sql = "insert into useraccess (username,password,realname,email,phoneno,languageid,recordstatus,joindate,birthdate,useraddress) 
					values ('".$_POST['username']."',md5('".$_POST['password']."'),'".$_POST['realname']."','".$_POST['email']."',
					'".$_POST['phoneno']."',1,0,now(),'".$_POST['birthdate']."','".$_POST['useraddress']."')";
				$connection->createCommand($sql)->execute();
			}
			$transaction->commit();
			getMessage('success', 'alreadysaved');
		} catch (CdbException $e) {
			$transaction->rollBack();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionGetProfile() {
		$username	 = filter_input(INPUT_POST, 'username', FILTER_SANITIZE_STRING);
		$password	 = filter_input(INPUT_POST, 'password', FILTER_SANITIZE_STRING);
		if (($username == '') && ($password == '')) {
			$user = Yii::app()->db->createCommand("select *
				from useraccess
				where lower(username) = '".Yii::app()->user->id."'")->queryRow();
		} else if (($username !== '') && ($password !== '')) {
			$user = Yii::app()->db->createCommand("select *
				from useraccess
				where lower(username) = '".$username."' and password = md5('".$password."')"
				)->queryRow();
		} else {
			echo CJSON::encode(array(
				'status' => 'failure',
				'div' => getCatalog('invaliduser')
			));
			Yii::app()->end();
		}
		echo CJSON::encode(array(
			'status' => 'success',
			'useraccessid' => $user['useraccessid'],
			'username' => $user['username'],
			'realname' => $user['realname'],
			'email' => $user['email'],
			'phoneno' => $user['phoneno'],
		));
		Yii::app()->end();
  }
  public function actionMaintain() {
    if (Yii::app()->params['ismaintain'] == true) {
      Yii::app()->theme = 'blue';
      $this->renderPartial('maintain');
    } else {
      $this->redirect('index');
    }
  }
}