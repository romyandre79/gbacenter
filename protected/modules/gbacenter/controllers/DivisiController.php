<?php
class DivisiController extends AdminController {
	protected $menuname				 = 'divisi';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Divisi';
	public $wfname						 = '';
	public $sqldata						 = "select a0.divisiid,a0.kodedivisi,a0.namadivisi,a0.parentid, a0.notes,a0.recordstatus,
	b0.divisiid  iddivisidetail, b0.pesertaid, b0.jabatanid, 
	c0.kodejabatan,c0.namajabatan,c0.jobdesk,c0.istelegram,
	d0.idtelegram,d0.nohp,d0.nama,d0.aliasid, d0.asalgereja, d0.jabatangereja, d0.sexid, d0.foto
	from divisi a0 
	join divisidetail b0 on b0.divisiid = a0.divisiid
	LEFT JOIN jabatan c0 ON c0.jabatanid = b0.jabatanid
	LEFT JOIN peserta d0 ON d0.pesertaid = b0.pesertaid
  ";
    //peserta
	public $sqldatagroupmenu	 = "select a0.pesertaid, a0.idtelegram,a0.nohp,a0.nama,a0.aliasid, a0.asalgereja, a0.jabatangereja, a0.sexid, a0.foto
    from peserta a0 
  ";
    //jabatan
	public $sqldatauserdash		 = "select a0.jabatanid,a0.kodejabatan,a0.namajabatan,a0.jobdesk,a0.istelegram
    from jabatan a0 
  ";
	public $sqlcount					 = "select count(1) 
	from divisi a0 
	join divisidetail b0 on b0.divisiid = a0.divisiid
	LEFT JOIN jabatan c0 ON c0.jabatanid = b0.jabatanid
	LEFT JOIN peserta d0 ON d0.pesertaid = b0.pesertaid
  ";
    //count data peserta
	public $sqlcountgroupmenu	 = "select count(1) 
    from peserta a0 
  ";
    //count data jabatan
	public $sqlcountuserdash	 = "select count(1) 
    from jabatan a0 
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$divisiid = filterinput(2, 'divisiid');
		$kodedivisi		 = filterinput(2, 'kodedivisi');
		$namadivisi	 = filterinput(2, 'namadivisi');
		$where				 .= " where a0.kodedivisi like '%".$kodedivisi."%' 
			and a0.namadivisi like '%".$namadivisi."%'";
		if (($divisiid !== '0') && ($divisiid !== '')) {
			$where .= " and a0.divisiid in (".$divisiid.")";
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
			'keyField' => 'divisiid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'divisiid', 'kodedivisi', 'namadivisi', 'recordstatus'
				),
				'defaultOrder' => array(
					'divisiid' => CSort::SORT_DESC
				),
			),
		));
    $divisiid = filterinput(1, 'divisiid',FILTER_SANITIZE_NUMBER_INT);
    if ($divisiid > 0) {
      $this->sqlcountgroupmenu .= ' where a0.divisiid = '.$divisiid;
      $this->sqldatagroupmenu	 .= ' where a0.divisiid = '.$divisiid;
    }
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'pesertaid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'pesertaid'
				),
				'defaultOrder' => array(
					'pesertaid' => CSort::SORT_DESC
				),
			),
		));
		if ($divisiid > 0) {
			$this->sqlcountuserdash	 .= ' where a0.divisiid = '.$divisiid;
			$this->sqldatauserdash	 .= ' where a0.divisiid = '.$divisiid;
		}
		$countuserdash				 = Yii::app()->db->createCommand($this->sqlcountuserdash)->queryScalar();
		$dataProvideruserdash	 = new CSqlDataProvider($this->sqldatauserdash,
			array(
			'totalItemCount' => $countuserdash,
			'keyField' => 'jabatanid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
         'attributes' => array(
					'jabatanid', 'kodejabatan', 'namajabatan', 'jobdesk', 'istelegram'
				),
				'defaultOrder' => array(
					'jabatanid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
			array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu,
			'dataProvideruserdash' => $dataProvideruserdash));
	}
	public function actionCreate() {
		parent::actionCreate();
		$divisiid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'divisiid' => $divisiid,
		));
	}
	public function actionCreatepeserta() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
		));
	}
	public function actionCreatejabatan() {
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.divisiid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'divisiid' => $model['divisiid'],
				'kodedivisi' => $model['kodedivisi'],
				'namadivisi' => $model['namadivisi'],
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where pesertaid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'pesertaid' => $model['pesertaid'],
				'idtelegram' => $model['idtelegram'],
				'nohp' => $model['menuaccessid'],
				'nama' => $model['isread'],
				'aliasid' => $model['iswrite'],
				'asalgereja' => $model['ispost'],
				'jabatangereja' => $model['isreject'],
				'sexid' => $model['ispurge'],
				'foto' => $model['isupload'],
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
		$model = Yii::app()->db->createCommand($this->sqldatauserdash.' where jabatanid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'jabatanid' => $model['jabatanid'],
				'kodejabatan' => $model['kodejabatan'],
				'namajabatan' => $model['namajabatan'],
				'jobdesk' => $model['jobdesk'],
				'istelegram' => $model['istelegram'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('kodedivisi', 'string', 'emptykodedivisi'),
			array('namadivisi', 'string', 'emptynamadivisi'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'divisiid',
				array(
				array(':divisiid', 'divisiid', PDO::PARAM_STR),
				array(':kodedivisi', 'kodedivisi', PDO::PARAM_STR),
				array(':namadivisi', 'namadivisi', PDO::PARAM_STR),
				array(':parentid', 'parentid', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into divisi (divisiid,kodedivisi,namadivisi,parentid,notes,recordstatus)
				values (:divisiid,:kodedivisi,:namadivisi,:parentid,:notes,:recordstatus)'
			  ,
			  'insert into divisi (divisiid,kodedivisi,namadivisi,parentid,notes,recordstatus)
			  values (:divisiid,:kodedivisi,:namadivisi,:parentid,:notes,:recordstatus)');
		}
	}
	public function actionSavepeserta() {
		parent::actionSave();
		$error = ValidateData(array(
			array('divisiid', 'string', 'emptydivisiid'),
			array('pesertaid', 'string', 'emptypesertaid'),
			array('jabatanid', 'string', 'emptyjabatanid'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'divisiid',
				array(
				array(':divisiid', 'divisiid', PDO::PARAM_STR),
				array(':pesertaid', 'pesertaid', PDO::PARAM_STR),
				array(':jabatanid', 'jabatanid', PDO::PARAM_STR),
				),
				'insert into divisidetail (divisiid,pesertaid,jabatanid)
			      values (:divisiid,:pesertaid,:jabatanid)',
				'insert into divisidetail (divisiid, pesertaid,jabatanid)
				values (:divisiid,:pesertaid,:jabatanid)');
				
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
        $sql = "delete from divisidetail where divisiid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
	  }
	  
	  foreach ($ids as $id) {
        $sql = "delete from divisi where divisiid = ".$id;
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
		$this->pdf->title					 = getCatalog('divisi');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('divisiid'), getCatalog('kodedivisi'),
			getCatalog('namadivisi'), getCatalog('notes'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(20, 30, 40, 50, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['divisiid'], $row1['kodedivisi'], $row1['namadivisi'],$row1['notes'],
				$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
}