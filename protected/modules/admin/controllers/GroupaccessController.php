<?php
class GroupaccessController extends AdminController {
	protected $menuname				 = 'groupaccess';
	public $module						 = 'Admin';
	protected $pageTitle			 = 'Akses Grup';
	public $wfname						 = '';
	public $sqldata						 = "select a0.groupaccessid,a0.groupname,a0.description,a0.recordstatus,
		(select ifnull(count(1),0) from groupmenu z where z.groupaccessid = a0.groupaccessid ) as jumsub
    from groupaccess a0 
  ";
	public $sqldatagroupmenu	 = "select a0.groupmenuid,a0.groupaccessid,a0.menuaccessid,a0.isread,a0.iswrite,a0.ispost,a0.isreject,a0.ispurge,a0.isupload,a0.isdownload,a1.menuname as menuname 
    from groupmenu a0 
    left join menuaccess a1 on a1.menuaccessid = a0.menuaccessid
  ";
	public $sqldatauserdash		 = "select a0.userdashid,a0.groupaccessid,a0.widgetid,a0.menuaccessid,a0.position,a0.webformat,a0.dashgroup,a1.widgetname as widgetname,a2.menuname as menuname 
    from userdash a0 
    left join widget a1 on a1.widgetid = a0.widgetid
    left join menuaccess a2 on a2.menuaccessid = a0.menuaccessid
  ";
	public $sqlcount					 = "select count(1) 
    from groupaccess a0 
  ";
	public $sqlcountgroupmenu	 = "select count(1) 
    from groupmenu a0 
    left join menuaccess a1 on a1.menuaccessid = a0.menuaccessid
  ";
	public $sqlcountuserdash	 = "select count(1) 
    from userdash a0 
    left join widget a1 on a1.widgetid = a0.widgetid
    left join menuaccess a2 on a2.menuaccessid = a0.menuaccessid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$groupaccessid = filterinput(2, 'groupaccessid');
		$groupname		 = filterinput(2, 'groupname');
		$description	 = filterinput(2, 'description');
		$where				 .= " where a0.groupname like '%".$groupname."%' 
			and a0.description like '%".$description."%'";
		if (($groupaccessid !== '0') && ($groupaccessid !== '')) {
			$where .= " and a0.groupaccessid in (".$groupaccessid.")";
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
			'keyField' => 'groupaccessid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'groupaccessid', 'groupname', 'description', 'recordstatus'
				),
				'defaultOrder' => array(
					'groupaccessid' => CSort::SORT_DESC
				),
			),
		));
    $groupaccessid = filterinput(1, 'groupaccessid',FILTER_SANITIZE_NUMBER_INT);
    if ($groupaccessid > 0) {
      $this->sqlcountgroupmenu .= ' where a0.groupaccessid = '.$groupaccessid;
      $this->sqldatagroupmenu	 .= ' where a0.groupaccessid = '.$groupaccessid;
    }
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'groupmenuid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'groupmenuid', 'menuaccessid'
				),
				'defaultOrder' => array(
					'groupmenuid' => CSort::SORT_DESC
				),
			),
		));
		if ($groupaccessid > 0) {
			$this->sqlcountuserdash	 .= ' where a0.groupaccessid = '.$groupaccessid;
			$this->sqldatauserdash	 .= ' where a0.groupaccessid = '.$groupaccessid;
		}
		$countuserdash				 = Yii::app()->db->createCommand($this->sqlcountuserdash)->queryScalar();
		$dataProvideruserdash	 = new CSqlDataProvider($this->sqldatauserdash,
			array(
			'totalItemCount' => $countuserdash,
			'keyField' => 'userdashid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
         'attributes' => array(
					'userdashid', 'widgetid', 'menuaccessid', 'position', 'webformat', 'dashgroup'
				),
				'defaultOrder' => array(
					'userdashid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
			array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu,
			'dataProvideruserdash' => $dataProvideruserdash));
	}
	public function actionCreate() {
		parent::actionCreate();
		$groupaccessid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'groupaccessid' => $groupaccessid,
		));
	}
	public function actionCreategroupmenu() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
		));
	}
	public function actionCreateuserdash() {
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.groupaccessid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'groupaccessid' => $model['groupaccessid'],
				'groupname' => $model['groupname'],
				'description' => $model['description'],
				'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionUpdategroupmenu() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where groupmenuid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'groupmenuid' => $model['groupmenuid'],
				'groupaccessid' => $model['groupaccessid'],
				'menuaccessid' => $model['menuaccessid'],
				'isread' => $model['isread'],
				'iswrite' => $model['iswrite'],
				'ispost' => $model['ispost'],
				'isreject' => $model['isreject'],
				'ispurge' => $model['ispurge'],
				'isupload' => $model['isupload'],
				'isdownload' => $model['isdownload'],
				'menuname' => $model['menuname'],
			));
			Yii::app()->end();
		}
	}
	public function actionUpdateuserdash() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldatauserdash.' where userdashid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'userdashid' => $model['userdashid'],
				'groupaccessid' => $model['groupaccessid'],
				'widgetid' => $model['widgetid'],
				'menuaccessid' => $model['menuaccessid'],
				'position' => $model['position'],
				'webformat' => $model['webformat'],
				'dashgroup' => $model['dashgroup'],
				'widgetname' => $model['widgetname'],
				'menuname' => $model['menuname'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('groupname', 'string', 'emptygroupname'),
			array('description', 'string', 'emptydescription'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'groupaccessid',
				array(
				array(':groupaccessid', 'groupaccessid', PDO::PARAM_STR),
				array(':actiontype', 'actiontype', PDO::PARAM_STR),
				array(':groupname', 'groupname', PDO::PARAM_STR),
				array(':description', 'description', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call Insertgroupaccess (:actiontype
					,:groupaccessid
					,:groupname
					,:description
					,:recordstatus,:vcreatedby)',
				'call Insertgroupaccess (:actiontype
					,:groupaccessid
					,:groupname
					,:description
					,:recordstatus,:vcreatedby)');
		}
	}
	public function actionSavegroupmenu() {
		parent::actionSave();
		$error = ValidateData(array(
			array('groupaccessid', 'string', 'emptygroupaccessid'),
			array('menuaccessid', 'string', 'emptymenuaccessid'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'groupmenuid',
				array(
				array(':groupmenuid', 'groupmenuid', PDO::PARAM_STR),
				array(':groupaccessid', 'groupaccessid', PDO::PARAM_STR),
				array(':menuaccessid', 'menuaccessid', PDO::PARAM_STR),
				array(':isread', 'isread', PDO::PARAM_STR),
				array(':iswrite', 'iswrite', PDO::PARAM_STR),
				array(':ispost', 'ispost', PDO::PARAM_STR),
				array(':isreject', 'isreject', PDO::PARAM_STR),
				array(':ispurge', 'ispurge', PDO::PARAM_STR),
				array(':isupload', 'isupload', PDO::PARAM_STR),
				array(':isdownload', 'isdownload', PDO::PARAM_STR),
				),
				'insert into groupmenu (groupaccessid,menuaccessid,isread,iswrite,ispost,isreject,ispurge,isupload,isdownload)
			      values (:groupaccessid,:menuaccessid,:isread,:iswrite,:ispost,:isreject,:ispurge,:isupload,:isdownload)',
				'update groupmenu
			      set groupaccessid = :groupaccessid,menuaccessid = :menuaccessid,isread = :isread,iswrite = :iswrite,ispost = :ispost,isreject = :isreject,ispurge = :ispurge,isupload = :isupload,isdownload = :isdownload
			      where groupmenuid = :groupmenuid');
		}
	}
	public function actionSaveuserdash() {
		parent::actionSave();
		$error = ValidateData(array(
			array('groupaccessid', 'string', 'emptygroupaccessid'),
			array('widgetid', 'string', 'emptywidgetid'),
			array('menuaccessid', 'string', 'emptymenuaccessid'),
			array('position', 'string', 'emptyposition'),
			array('webformat', 'string', 'emptywebformat'),
			array('dashgroup', 'string', 'emptydashgroup'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'userdashid',
				array(
				array(':userdashid', 'userdashid', PDO::PARAM_STR),
				array(':groupaccessid', 'groupaccessid', PDO::PARAM_STR),
				array(':widgetid', 'widgetid', PDO::PARAM_STR),
				array(':menuaccessid', 'menuaccessid', PDO::PARAM_STR),
				array(':position', 'position', PDO::PARAM_STR),
				array(':webformat', 'webformat', PDO::PARAM_STR),
				array(':dashgroup', 'dashgroup', PDO::PARAM_STR),
				),
				'insert into userdash (groupaccessid,widgetid,menuaccessid,position,webformat,dashgroup)
			      values (:groupaccessid,:widgetid,:menuaccessid,:position,:webformat,:dashgroup)',
				'update userdash
			      set groupaccessid = :groupaccessid,widgetid = :widgetid,menuaccessid = :menuaccessid,position = :position,webformat = :webformat,dashgroup = :dashgroup
			      where userdashid = :userdashid');
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
        $sql		 = "select recordstatus from groupaccess where groupaccessid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update groupaccess set recordstatus = 0 where groupaccessid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update groupaccess set recordstatus = 1 where groupaccessid = ".$id;
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
        $sql = "delete from groupaccess where groupaccessid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionPurgegroupmenu() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql = "delete from groupmenu where groupmenuid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
      }
      $transaction->commit();
      getMessage('success', 'alreadysaved');
		} catch (CDbException $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionPurgeuserdash() {
		parent::actionPurge();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$ids = filterinput(4,'id');
      if ($ids == '') {
        GetMessage('error', 'chooseone');
      }
      foreach ($ids as $id) {
        $sql = "delete from userdash where userdashid = ".$id;
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
		$this->pdf->title					 = getCatalog('groupaccess');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('groupaccessid'), getCatalog('groupname'),
			getCatalog('description'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 50, 60, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['groupaccessid'], $row1['groupname'], $row1['description'],
				$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
}