<?php
class UseraccessController extends AdminController {
	protected $menuname				 = 'useraccess';
	public $module						 = 'Admin';
	protected $pageTitle			 = 'Akses User';
	public $wfname						 = '';
	public $sqldata						 = "select a0.useraccessid,a0.username,a0.realname,a0.userphoto,a0.password,a0.email,a0.phoneno,a0.languageid,a0.recordstatus,a1.languagename as languagename,
			(select ifnull(count(1),0) from usergroup z where z.useraccessid = a0.useraccessid ) as jumsub
    from useraccess a0 
    left join language a1 on a1.languageid = a0.languageid
  ";
	public $sqldatausergroup	 = "select a0.usergroupid,a0.useraccessid,a0.groupaccessid,a1.groupname as groupname 
    from usergroup a0 
    left join groupaccess a1 on a1.groupaccessid = a0.groupaccessid
  ";
	public $sqlcount					 = "select count(1) 
    from useraccess a0 
    left join language a1 on a1.languageid = a0.languageid
  ";
	public $sqlcountusergroup	 = "select count(1) 
    from usergroup a0 
    left join groupaccess a1 on a1.groupaccessid = a0.groupaccessid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$useraccessid	 = filterinput(2, 'useraccessid', FILTER_SANITIZE_STRING);
		$username			 = filterinput(2, 'username', FILTER_SANITIZE_STRING);
		$realname			 = filterinput(2, 'realname', FILTER_SANITIZE_STRING);
		$password			 = filterinput(2, 'password', FILTER_SANITIZE_STRING);
		$email				 = filterinput(2, 'email', FILTER_SANITIZE_STRING);
		$phoneno			 = filterinput(2, 'phoneno', FILTER_SANITIZE_STRING);
		$languagename	 = filterinput(2, 'languagename', FILTER_SANITIZE_STRING);
		$where				 .= " where coalesce(a0.username,'') like '%".$username."%'
			and coalesce(a0.realname,'') like '%".$realname."%'
			and coalesce(a0.password,'') like '%".$password."%'
			and coalesce(a0.email,'') like '%".$email."%'
			and coalesce(a0.phoneno,'') like '%".$phoneno."%'
			and coalesce(a1.languagename,'') like '%".$languagename."%'";
		if (($useraccessid !== '0') && ($useraccessid !== '')) {
			$where .= " and a0.useraccessid in (".$useraccessid.")";
		}
		$this->sqldata = $this->sqldata.$where;
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount.$where)->queryScalar();
	}
	public function actionIndex() {
		parent::actionIndex();
		$this->getSQL();
		$dataProvider = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $this->count,
			'keyField' => 'useraccessid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'useraccessid', 'username', 'realname', 'password', 'email', 'phoneno', 'languageid',
					'recordstatus'
				),
				'defaultOrder' => array(
					'useraccessid' => CSort::SORT_DESC
				),
			),
		));
    $useraccessid = filterinput(1, 'useraccessid');
		if ($useraccessid > 0) {
			$this->sqlcountusergroup .= ' where a0.useraccessid = '.$useraccessid;
			$this->sqldatausergroup	 .= ' where a0.useraccessid = '.$useraccessid;
		}
		$countusergroup				 = Yii::app()->db->createCommand($this->sqlcountusergroup)->queryScalar();
		$dataProviderusergroup = new CSqlDataProvider($this->sqldatausergroup,
			array(
			'totalItemCount' => $countusergroup,
			'keyField' => 'usergroupid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'defaultOrder' => array(
					'usergroupid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
			array('dataProvider' => $dataProvider, 'dataProviderusergroup' => $dataProviderusergroup));
	}
	public function actionCreate() {
		parent::actionCreate();
		$useraccessid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'useraccessid' => $useraccessid,
		));
	}
	public function actionCreateusergroup() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
		));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.useraccessid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'useraccessid' => $model['useraccessid'],
				'username' => $model['username'],
				'realname' => $model['realname'],
				'password' => $model['password'],
				'email' => $model['email'],
				'phoneno' => $model['phoneno'],
				'languageid' => $model['languageid'],
				'recordstatus' => $model['recordstatus'],
				'languagename' => $model['languagename'],
				'userphoto' => $model['userphoto'],
			));
			Yii::app()->end();
		}
	}
	public function actionUpdateusergroup() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldatausergroup.' where usergroupid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'usergroupid' => $model['usergroupid'],
				'useraccessid' => $model['useraccessid'],
				'groupaccessid' => $model['groupaccessid'],
				'groupname' => $model['groupname'],
			));
			Yii::app()->end();
		}
	}
	public function actionUpload() {
		$this->storeFolder = dirname('__FILES__').'/images/useraccess/';
		parent::actionUpload();
		echo $_FILES['upload']['name'];
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('username', 'string', 'emptyusername'),
			array('username', 'space', 'usernotallowspace'),
			array('realname', 'string', 'emptyrealname'),
			array('password', 'string', 'emptypassword'),
			array('email', 'string', 'emptyemail'),
			array('phoneno', 'string', 'emptyphoneno'),
			array('languageid', 'string', 'emptylanguageid'),
			array('languageid', 'integer', 'mustintlanguageid')
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'useraccessid',
				array(
				array(':useraccessid', 'useraccessid', PDO::PARAM_STR),
				array(':actiontype', 'actiontype', PDO::PARAM_STR),
				array(':username', 'username', PDO::PARAM_STR),
				array(':realname', 'realname', PDO::PARAM_STR),
				array(':password', 'password', PDO::PARAM_STR),
				array(':email', 'email', PDO::PARAM_STR),
				array(':phoneno', 'phoneno', PDO::PARAM_STR),
				array(':userphoto', 'userphoto', PDO::PARAM_STR),
				array(':languageid', 'languageid', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call Insertuseraccess (:actiontype
					,:useraccessid
					,:username
					,:realname
					,:password
					,:email
					,:phoneno
					,:languageid
					,:userphoto
					,:recordstatus,:vcreatedby)');
		}
	}
  public function actionSaveProfile() {
		parent::actionSave();
		$error = ValidateData(array(
			array('username', 'string', 'emptyusername'),
			array('username', 'space', 'usernotallowspace'),
			array('realname', 'string', 'emptyrealname'),
			array('password', 'string', 'emptypassword'),
			array('email', 'string', 'emptyemail'),
			array('phoneno', 'string', 'emptyphoneno'),
		));
		if ($error == false) {
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
			try {
			$sql = "select `password` from useraccess where useraccessid = '".$_REQUEST['useraccessid']."'";
			$pass = Yii::app()->db->createCommand($sql)->queryScalar();
			if ($pass != $_REQUEST['password']) {
				$pass = md5($_REQUEST['password']);
			} 
			$sql = "update useraccess 
         set `password` = '".$pass."',
          `realname` = '".$_REQUEST['realname']."', `email` = '".$_REQUEST['email']."',
          `phoneno` = '".$_REQUEST['phoneno']."', `userphoto` = '".$_REQUEST['userphoto']."' 
         where useraccessid = ".$_REQUEST['useraccessid'];
				 $connection->createCommand($sql)->execute();
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
		}
	}
	public function actionSaveusergroup() {
		parent::actionSave();
		$error = ValidateData(array(
			array('useraccessid', 'string', 'emptyuseraccessid'),
			array('groupaccessid', 'string', 'emptygroupaccessid'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'usergroupid',
				array(
				array(':usergroupid', 'usergroupid', PDO::PARAM_STR),
				array(':useraccessid', 'useraccessid', PDO::PARAM_STR),
				array(':groupaccessid', 'groupaccessid', PDO::PARAM_STR),
				),
				'insert into usergroup (useraccessid,groupaccessid)
			      values (:useraccessid,:groupaccessid)',
				'update usergroup
			      set useraccessid = :useraccessid,groupaccessid = :groupaccessid
			      where usergroupid = :usergroupid');
		}
	}
	public function actionDelete() {
		parent::actionDelete();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
      $ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql		 = "select recordstatus from useraccess where useraccessid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update useraccess set recordstatus = 0 where useraccessid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update useraccess set recordstatus = 1 where useraccessid = ".$id;
        }
        $connection->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionPurge() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
      $ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql = "delete from useraccess where useraccessid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionPurgeusergroup() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
      $ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql = "delete from usergroup where usergroupid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionDownPDF() {
		parent::actionDownPDF();
		$this->getSQL();
		$dataReader = Yii::app()->db->createCommand($this->sqldata)->queryAll();
		$this->pdf->title					 = getCatalog('useraccess');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('useraccessid'), getCatalog('username'),
			getCatalog('realname'), getCatalog('email'),
			getCatalog('phoneno'), getCatalog('languagename'));
		$this->pdf->setwidths(array(10, 40, 40, 40, 40, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['useraccessid'], $row1['username'], $row1['realname'],
				$row1['email'], $row1['phoneno'], $row1['languagename']));
		}
		$this->pdf->Output();
	}
}