<?php
class GrupbacaController extends AdminController {
	protected $menuname				 = 'grupbaca';
	public $module						 = 'Admin';
	protected $pageTitle			 = 'Grup Baca';
	public $wfname						 = '';
	public $sqldata						 = "select a0.grupbacaid,a0.kodegrup,a0.namagrup,a0.startdate, a0.notes,a0.recordstatus,
	(select ifnull(count(1),0) from grupbacadetail z where z.grupbacaid = a0.grupbacaid ) as jumsub,
	c0.divisiid, c0.namadivisi, d0.bukubacaanid, d0.namabuku
	from grupbaca a0 
	left join divisi c0 on c0.divisiid = a0.divisiid
	left join bukubacaan d0 on d0.bukubacaanid = a0.bukubacaanid
  ";
	public $sqldatagroupmenu	 = "select 
	b0.grupbacadetailid, b0.grupbacaid, b0.hari, b0.menuharian, b0.menudate, b0.recordstatus
	from grupbacadetail b0 
  ";
	public $sqldatauserdash		 = "select a0.membergrupbacaid,a0.grupbacaid,a0.pesertaid,a0.recordstatus,a0.statusbaca,
	b0.nama, b0.aliasid, b0.nohp, b0.idtelegram
	from membergrupbaca a0 
	left join peserta b0 on b0.pesertaid = a0.pesertaid
  ";
	public $sqlcount					 = "select count(1) 
	from grupbaca a0 
	left join divisi c0 on c0.divisiid = a0.divisiid
	left join bukubacaan d0 on d0.bukubacaanid = a0.bukubacaanid 
  ";
	public $sqlcountgroupmenu	 = "select count(1) 
	from grupbacadetail b0 
  ";
	public $sqlcountuserdash	 = "select count(1) 
	from membergrupbaca a0 
	left join peserta b0 on b0.pesertaid = a0.pesertaid
  ";
  public $sqlinsertgrupbacadetail = 'insert into grupbacadetail (grupbacaid,hari,menuharian,menudate,recordstatus)
  values (:grupbacaid,:hari,:menuharian,:menudate,:recordstatus)';
  public $sqlupdategrupbacadetail = 'update grupbacadetail 
  set hari = :hari, menuharian = :menuharian, menudate = :menudate, recordstatus = :recordstatus
  where grupbacaid = :grupbacaid';
  public $sqlinsertmembergrupbaca = 'insert into membergrupbaca (grupbacaid,pesertaid,statusbaca,recordstatus)
  values (:grupbacaid,:pesertaid,:statusbaca,:recordstatus)';
  public $sqlupdatemembergrupbaca = 'update membergrupbaca
  set grupbacaid = :grupbacaid,pesertaid = :pesertaid,statusbaca = :statusbaca, recordstatus = :recordstatus
  where membergrupbacaid = :membergrupbacaid';

  public function actionUploadMember() {
		if (!file_exists(Yii::getPathOfAlias('webroot').'/uploads')) {
			mkdir(Yii::getPathOfAlias('webroot').'/uploads');
		}
		$this->storeFolder = dirname('__FILES__').'/uploads/';
    parent::actionUpload();
    $target_file = dirname('__FILES__').'/uploads/' . basename($_FILES['upload']['name']);
    Yii::import('ext.PHPExcel.XPHPExcel');
		$phpExcel = XPHPExcel::createPHPExcel();
    $objReader = PHPExcel_IOFactory::createReader('Excel2007');
    $objPHPExcel = $objReader->load($target_file);
    $objWorksheet = $objPHPExcel->getActiveSheet();
    $highestRow = $objWorksheet->getHighestRow(); 
    $highestColumn = $objWorksheet->getHighestColumn();
    $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn); 
    $connection  = Yii::app()->db;
    $transaction = $connection->beginTransaction();
    try {
      for ($row = 2; $row <= $highestRow; ++$row) {
        $nama = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
        $aliasid = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
        $pesertaid = Yii::app()->db->createCommand("select pesertaid from peserta where aliasid = '".$aliasid."'")->queryScalar();
        $command = $connection->createCommand($this->sqlinsertmembergrupbaca);
        $command->bindValue(':grupbacaid', $_POST['grupbacaid'], PDO::PARAM_STR);
        $command->bindValue(':pesertaid', $pesertaid, PDO::PARAM_STR);
        $command->bindValue(':statusbaca', 1, PDO::PARAM_STR);
        $command->bindValue(':recordstatus', 1, PDO::PARAM_STR);
        $command->execute();
      }
      $transaction->commit();
      echo getcatalog('insertsuccess');
    }
    catch (CDbException $e) {
      $transaction->rollBack();
      echo 'Error Line: '.$row.' ==> '.$e->getMessage();
    }
	}
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$grupbacaid = filterinput(2, 'grupbacaid');
		$kodegrup		 = filterinput(2, 'kodegrup');
		$namagrup	 = filterinput(2, 'namagrup');
		$where				 .= " where a0.kodegrup like '%".$kodegrup."%' 
			and a0.namagrup like '%".$namagrup."%'";
		if (($grupbacaid !== '0') && ($grupbacaid !== '')) {
			$where .= " and a0.grupbacaid in (".$grupbacaid.")";
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
			'keyField' => 'grupbacaid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'grupbacaid', 'namagrup', 'kodegrup', 'namadivisi','startdate', 'namabuku','notes','recordstatus'
				),
				'defaultOrder' => array(
					'grupbacaid' => CSort::SORT_DESC
				),
			),
		));
	$grupbacaid = filterinput(1, 'grupbacaid',FILTER_SANITIZE_NUMBER_INT);
	
	if (isset($_REQUEST['grupbacaid'])) {
		$grupbacaid = $_REQUEST['grupbacaid'];
		$this->sqlcountgroupmenu .= ' where b0.grupbacaid = '.$grupbacaid;
		$this->sqldatagroupmenu	 .= ' where b0.grupbacaid = '.$grupbacaid;
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
    }
		
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'grupbacadetailid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'grupbacadetailid', 'grupbacaid' , 'hari', 'menuharian', 'menudate'
				),
				'defaultOrder' => array(
					'grupbacadetailid' => CSort::SORT_DESC
				),
			),
		));
		if (isset($_REQUEST['grupbacaid'])) {
			$grupbacaid = $_REQUEST['grupbacaid'];
			$this->sqlcountuserdash	 .= ' where a0.grupbacaid = '.$grupbacaid;
			$this->sqldatauserdash	 .= ' where a0.grupbacaid = '.$grupbacaid;
			$countuserdash				 = Yii::app()->db->createCommand($this->sqlcountuserdash)->queryScalar();
		}
		
		$dataProvideruserdash	 = new CSqlDataProvider($this->sqldatauserdash,
			array(
			'totalItemCount' => $countuserdash,
			'keyField' => 'membergrupbacaid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
         'attributes' => array(
					 'membergrupbacaid', 'grupbacaid', 'pesertaid', 'nama','aliasid','nohp','idtelegram', 'statusbaca','recordstatus',
				),
				'defaultOrder' => array(
					'membergrupbacaid' => CSort::SORT_DESC
				),
			),
		));

		$this->render('index',
			array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu,
			'dataProvideruserdash' => $dataProvideruserdash));
	}
	public function actionCreate() {
		parent::actionCreate();
		$grupbacaid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'grupbacaid' => $grupbacaid,
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.grupbacaid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'grupbacaid' => $model['grupbacaid'],
				'kodegrup' => $model['kodegrup'],
				'namagrup' => $model['namagrup'],
				'divisiid' => $model['divisiid'],
				'startdate' => $model['startdate'],
				'bukubacaanid' => $model['bukubacaanid'],
				'notes' => $model['notes'],
				'namadivisi' => $model['namadivisi'],
				'namabuku' => $model['namabuku'],
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where b0.grupbacadetailid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'grupbacadetailid' => $model['grupbacadetailid'],
				'grupbacaid' => $model['grupbacaid'],
				'kodegrup' => $model['kodegrup'],
				'namagrup' => $model['namagrup'],
				'divisiid' => $model['divisiid'],
				'startdate' => $model['startdate'],
				'bukubacaanid' => $model['bukubacaanid'],
				'notes' => $model['notes'],
				'hari' => $model['hari'],
				'menuharian' => $model['menuharian'],
				'menudate' => $model['menudate'],
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
		$model = Yii::app()->db->createCommand($this->sqldatauserdash.' where membergrupbacaid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'membergrupbacaid' => $model['membergrupbacaid'],
				'grupbacaid' => $model['grupbacaid'],
				'pesertaid' => $model['pesertaid'],
				'nama' => $model['nama'],
				'aliasid' => $model['aliasid'],
				'statusbaca' => $model['statusbaca'],
				'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('kodegrup', 'string', 'emptykodegrup'),
			array('namagrup', 'string', 'emptynamagrup'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'grupbacaid',
				array(
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':actiontype', 'actiontype', PDO::PARAM_STR),
				array(':kodegrup', 'kodegrup', PDO::PARAM_STR),
				array(':namagrup', 'namagrup', PDO::PARAM_STR),
				array(':divisiid', 'divisiid', PDO::PARAM_STR),
				array(':startdate', 'startdate', PDO::PARAM_STR),
				array(':bukubacaanid', 'bukubacaanid', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call InsertGrupbaca (:actiontype
					,:grupbacaid
					,:kodegrup
					,:namagrup
					,:divisiid
					,:startdate
					,:bukubacaanid
					,:notes
					,:recordstatus,:vcreatedby)',
					'call InsertGrupbaca (:actiontype
					,:grupbacaid
					,:kodegrup
					,:namagrup
					,:divisiid
					,:startdate
					,:bukubacaanid
					,:notes
					,:recordstatus,:vcreatedby)');
		}
	}
	public function actionSavegrupbacaharian() {
		parent::actionSave();
		$error = ValidateData(array(
			array('grupbacadetailid', 'string', 'emptygrupbacadetailid'),
			array('grupbacaid', 'string', 'emptygrupbacaid'),
			array('hari', 'string', 'emptyhari'),
			array('menuharian', 'string', 'emptymenuharian'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'grupbacadetailid',
				array(
				array(':grupbacadetailid', 'grupbacaid', PDO::PARAM_STR),
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':hari', 'hari', PDO::PARAM_STR),
				array(':menuharian', 'menuharian', PDO::PARAM_STR),
				array(':menudate', 'menudate', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
        $this->sqlinsertgrupbacadetail,
        $this->sqlupdategrupbacadetail
        );
				
		}
		
	}
	public function actionSaveuserdash() {
		parent::actionSave();
		$error = ValidateData(array(
			array('groupbacaid', 'string', 'emptygroupbacaid'),
			array('pesertaid', 'string', 'emptypesertaid'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'membergrupbacaid',
				array(
				array(':membergrupbacaid', 'membergrupbacaid', PDO::PARAM_STR),	
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),	
				array(':pesertaid', 'pesertaid', PDO::PARAM_STR),
				array(':statusbaca', 'statusbaca', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				$this->sqlinsertmembergrupbaca,
				$this->sqlupdatemembergrupbaca);
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
        $sql		 = "select recordstatus from grupbaca where grupbacaid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update grupbaca set recordstatus = 0 where grupbacaid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update grupbaca set recordstatus = 1 where grupbacaid = ".$id;
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
        $sql = "delete from grupbacadetail where grupbacaid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
	  }
	  
	  foreach ($ids as $id) {
        $sql = "delete from grupbaca where grupbacaid = ".$id;
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
        $sql = "delete from grupbacadetail where grupbacadetailid = ".$id;
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
        $sql = "delete from membergrupbaca where membergrupbacaid = ".$id;
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
		$this->pdf->title					 = getCatalog('grupbaca');
		$this->pdf->AddPage('L');


		foreach ($dataReader as $row1) {
			//var_dump($row);die();
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->colheader			 = array(getCatalog('grupbacaid'), getCatalog('kodegrup'),
				getCatalog('namagrup'), getCatalog('namadivisi'), getCatalog('startdate'), getCatalog('namabuku'), getCatalog('notes'),getCatalog('recordstatus'));
			$this->pdf->setwidths(array(20, 20, 60, 40, 20, 40, 25, 20));
			$this->pdf->Rowheader();
			$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L');
			$this->pdf->row(array($row1['grupbacaid'], $row1['kodegrup'], $row1['namagrup'],$row1['namadivisi'],
			$row1['startdate'],$row1['namabuku'],$row1['notes'],$row1['recordstatus']));

				$sql2 = "select a0.hari, a0.menuharian, a0.menudate
				from  grupbacadetail a0 
				where a0.grupbacaid = ".$row1['grupbacaid'];
				$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
				$this->pdf->sety($this->pdf->gety() + 7);
				$this->pdf->colalign			 = array('C', 'C', 'C');
				$this->pdf->colheader			 = array(getCatalog('hari'), getCatalog('menuharian'), getCatalog('menudate')
				);
				$this->pdf->setwidths(array(30, 30, 30));
				$this->pdf->Rowheader();
				$this->pdf->coldetailalign = array('L', 'L', 'L');
				foreach ($dataReader2 as $row2) {
					$this->pdf->row(array($row2['hari'], $row2['menuharian'], $row2['menudate']));
				}	
				
		}


		$this->pdf->Output();
	}


	public function actionProsesGrupBaca() {
		parent::actionSave();
		$error = ValidateData(array(
			array('grupbacaid', 'string', 'emptygrupbacaid'),
			array('bukubacaanid', 'string', 'emptybukubacaanid'),
			array('startdate', 'string', 'emptystartdate'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'grupbacaid',
				array(
				array(':grupbacaid', 'grupbacaid', PDO::PARAM_STR),
				array(':bukubacaanid', 'bukubacaanid', PDO::PARAM_STR),
				array(':startdate', 'startdate', PDO::PARAM_STR),
				),
				'call generategrupbaca(:grupbacaid,:bukubacaanid,:startdate)',
				'call generategrupbaca(:grupbacaid,:bukubacaanid,:startdate)');
				
		}
		
	}
}