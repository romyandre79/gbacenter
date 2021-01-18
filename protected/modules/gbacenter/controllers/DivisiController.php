<?php
class DivisiController extends AdminController {
	protected $menuname				 = 'divisi';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Divisi';
	public $wfname						 = '';
	public $sqldata						 = "select a0.divisiid,a0.kodedivisi,a0.namadivisi, a0.parentid, a0.notes,a0.recordstatus,a1.kodedivisi as parentdivisi, 
	b0.namateam
  from divisi a0 
  left join divisi a1 on a1.divisiid = a0.parentid
  left join team b0 on b0.teamid = a0.teamid
  ";
    //peserta
	public $sqldatagroupmenu	 = "select a0.divisidetailid, a0.divisiid, a0.pesertaid, a0.jabatanid,
	b0.idtelegram, b0.nohp, b0.nama, b0.aliasid, 
	c0.namajabatan, c0.kodejabatan, c0.jobdesk
	from divisidetail a0 
	left join peserta b0 on b0.pesertaid = a0.pesertaid
	left join jabatan c0 on c0.jabatanid = a0.jabatanid
  ";
    //jabatan
	public $sqldatauserdash		 = "select a0.jabatanid,a0.kodejabatan,a0.namajabatan,a0.jobdesk,a0.istelegram
    from jabatan a0 
  ";
	public $sqlcount					 = "select count(1) 
	from divisi a0 
	left join divisi a1 on a1.divisiid = a0.parentid
	left join team b0 on b0.teamid = a0.teamid
  ";
    //count data peserta
	public $sqlcountgroupmenu	 = "select count(1) 
    from divisidetail a0 
	left join peserta b0 on b0.pesertaid = a0.pesertaid
	left join jabatan c0 on c0.jabatanid = a0.jabatanid
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
		$namateam	 = filterinput(2, 'namateam');
		$where				 .= " where a0.kodedivisi like '%".$kodedivisi."%' 
			and a0.namadivisi like '%".$namadivisi."%'";
		if ($namateam !== '') {	
			$where .= " and b0.namateam like '%".$namateam."%'";
		}
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
					'divisiid', 'kodedivisi', 'namadivisi', 'parentid','namateam','notes','recordstatus'
				),
				'defaultOrder' => array(
					'divisiid' => CSort::SORT_DESC
				),
			),
    ));
    if (isset($_REQUEST['divisiid'])) {
      $divisiid = $_REQUEST['divisiid'];
      $this->sqlcountgroupmenu .= ' where a0.divisiid = '.$divisiid;
      $this->sqldatagroupmenu	 .= ' where a0.divisiid = '.$divisiid;
      $countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
    }
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'divisidetailid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'divisidetailid','divisiid','nama','aliasid','namajabatan','jobdesk'
				),
				'defaultOrder' => array(
					'divisidetailid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
			array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu));
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
				'parentdivisi' => $model['parentdivisi'],
				'parentid' => $model['parentid'],
				'teamid' => $model['teamid'],
				'namateam' => $model['namateam'],
				'notes' => $model['notes'],
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where a0.divisidetailid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'divisidetailid' => $model['divisidetailid'],
				'divisiid' => $model['divisiid'],
				'pesertaid' => $model['pesertaid'],
				'jabatanid' => $model['jabatanid'],
				'namajabatan' => $model['namajabatan'],
				'nama' => $model['nama']
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
				array(':actiontype', 'actiontype', PDO::PARAM_STR),
				array(':kodedivisi', 'kodedivisi', PDO::PARAM_STR),
				array(':namadivisi', 'namadivisi', PDO::PARAM_STR),
				array(':parentid', 'parentid', PDO::PARAM_STR),
				array(':teamid', 'teamid', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call InsertDivisi (:actiontype
					,:divisiid
					,:kodedivisi
					,:namadivisi
					,:parentid
					,:teamid
					,:notes
					,:recordstatus,:vcreatedby)',
				'call InsertDivisi (:actiontype
				,:divisiid
				,:kodedivisi
				,:namadivisi
				,:parentid
				,:teamid
				,:notes
				,:recordstatus,:vcreatedby)');
		}
	}
	public function actionSavepeserta() {
		parent::actionSave();
		$error = ValidateData(array(
			array('divisidetailid', 'string', 'divisidetailid'),
			array('divisiid', 'string', 'emptydivisiid'),
			array('pesertaid', 'string', 'emptypesertaid'),
			array('jabatanid', 'string', 'emptyjabatanid'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'divisidetailid',
				array(
				array(':divisidetailid', 'divisidetailid', PDO::PARAM_STR),
				array(':divisiid', 'divisiid', PDO::PARAM_STR),
				array(':pesertaid', 'pesertaid', PDO::PARAM_STR),
				array(':jabatanid', 'jabatanid', PDO::PARAM_STR),
				),
				'insert into divisidetail (divisiid,pesertaid,jabatanid)
			      values (:divisiid,:pesertaid,:jabatanid)',
				'update divisidetail  set divisiid = :divisiid, pesertaid = :pesertaid, jabatanid = :jabatanid
				where divisidetail = :divisidetailid');
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
        $sql		 = "select recordstatus from divisi where divisiid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update divisi set recordstatus = 0 where divisiid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update divisi set recordstatus = 1 where divisiid = ".$id;
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
        $sql = "delete from divisidetail where divisidetailid = ".$id;
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
		
		foreach ($dataReader as $row) {
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->colheader			 = array(getCatalog('divisiid'), getCatalog('kodedivisi'),
				getCatalog('namadivisi'), getCatalog('namateam'),getCatalog('notes'), getCatalog('recordstatus'));
			$this->pdf->setwidths(array(20, 30, 40, 50, 15));
			$this->pdf->Rowheader();
			$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L');
			$this->pdf->row(array($row['divisiid'], $row['kodedivisi'], $row['namadivisi'],$row['namateam'],$row['notes'],
				$row['recordstatus']));

				$sql2 = "select a0.divisidetailid,
				b0.nama, b0.aliasid,
				c0.kodejabatan, c0.namajabatan, c0.jobdesk
				from divisidetail a0 
				left join peserta b0 on b0.pesertaid = a0.pesertaid
				left join jabatan c0 on c0.jabatanid = a0.jabatanid
				where divisiid = ".$row['divisiid'];
				$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
				$this->pdf->sety($this->pdf->gety() + 7);
				$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
				$this->pdf->colheader			 = array(getCatalog('nama'), getCatalog('aliasid'), getCatalog('kodejabatan')
				, getCatalog('namajabatan'), getCatalog('jobdesk'));
				$this->pdf->setwidths(array(30, 30, 30, 30, 30, 30));
				$this->pdf->Rowheader();
				$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
				foreach ($dataReader2 as $row2) {
					$this->pdf->row(array($row2['nama'], $row2['aliasid'], $row2['kodejabatan'], $row2['namajabatan'], $row2['jobdesk']));
				}	
				
		}
		$this->pdf->Output();
	}
}