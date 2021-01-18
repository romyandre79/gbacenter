<?php
class BacaanController extends AdminController {
	protected $menuname				 = 'bacaan';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Bacaan';
	public $wfname						 = '';
	public $sqldata						 = "select a0.bukubacaanid,a0.kodebuku,a0.namabuku,a0.jumlah, a0.total, a0.notes,a0.recordstatus
	from bukubacaan a0 
  ";  
	public $sqldatagroupmenu	 = "select b0.bukubacaandetailid, b0.bukubacaanid, b0.hari, b0.menuharian, b0.url
	from bukubacaandetail b0 
  ";
	public $sqlcount					 = "select count(1) 
	from bukubacaan a0 
  ";
	public $sqlcountgroupmenu	 = "select count(1) 
	from bukubacaandetail b0 
  ";
  public $sqlinsertdetail = "insert into bukubacaandetail (bukubacaanid,hari,menuharian,url)
    values (:bukubacaanid,:hari,:menuharian,:url)";
  public $sqlupdatedetail = 'update bukubacaandetail  set bukubacaanid = :bukubacaanid, hari = :hari , menuharian = :menuharian, url = :url
  where bukubacaandetailid = :bukubacaandetailid';

  public function actionUploadDetail() {
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
        $hari = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
        $menubacaan = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
        $url = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
        $command = $connection->createCommand($this->sqlinsertdetail);
        $command->bindValue(':bukubacaanid', $_POST['grupbacaid'], PDO::PARAM_STR);
        $command->bindValue(':hari', $hari, PDO::PARAM_STR);
        $command->bindValue(':menuharian', $menubacaan, PDO::PARAM_STR);
        $command->bindValue(':url', $url, PDO::PARAM_STR);
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
		$bukubacaanid = filterinput(2, 'bukubacaanid');
		$kodebuku		 = filterinput(2, 'kodebuku');
		$namabuku	 = filterinput(2, 'namabuku');
		$where				 .= " where a0.kodebuku like '%".$kodebuku."%' 
			and a0.namabuku like '%".$namabuku."%'";
		if (($bukubacaanid !== '0') && ($bukubacaanid !== '')) {
			$where .= " and a0.bukubacaanid in (".$bukubacaanid.")";
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
			'keyField' => 'bukubacaanid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'bukubacaanid', 'kodebuku', 'namabuku', 'recordstatus'
				),
				'defaultOrder' => array(
					'bukubacaanid' => CSort::SORT_DESC
				),
			),
		));

	if (isset($_REQUEST['bukubacaanid'])) {
		$bukubacaanid = $_REQUEST['bukubacaanid'];
		$this->sqlcountgroupmenu .= ' where b0.bukubacaanid = '.$bukubacaanid;
		$this->sqldatagroupmenu	 .= ' where b0.bukubacaanid = '.$bukubacaanid;
    }
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'bukubacaandetailid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'bukubacaandetailid','bukubacaanid','hari','menuharian','url'
				),
				'defaultOrder' => array(
					'bukubacaandetailid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index',
		array('dataProvider' => $dataProvider, 'dataProvidergroupmenu' => $dataProvidergroupmenu));
		
	}
	public function actionCreate() {
		parent::actionCreate();
		$bukubacaanid = rand(-1, -1000000000);
		echo CJSON::encode(array(
			'status' => 'success',
			'bukubacaanid' => $bukubacaanid,
		));
	}
	public function actionCreatebacaan() {
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.bukubacaanid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'bukubacaanid' => $model['bukubacaanid'],
				'kodebuku' => $model['kodebuku'],
				'namabuku' => $model['namabuku'],
				'jumlah' => $model['jumlah'],
				'total' => $model['total'],
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where b0.bukubacaandetailid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'bukubacaandetailid' => $model['bukubacaandetailid'],
				'bukubacaanid' => $model['bukubacaanid'],
				'hari' => $model['hari'],
				'menuharian' => $model['menuharian'],
				'url' => $model['url'],
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
			array('kodebuku', 'string', 'emptykodebuku'),
			array('namabuku', 'string', 'emptynamabuku'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'bukubacaanid',
				array(
				array(':bukubacaanid', 'bukubacaanid', PDO::PARAM_STR),
				array(':actiontype', 'actiontype', PDO::PARAM_STR),
				array(':kodebuku', 'kodebuku', PDO::PARAM_STR),
				array(':namabuku', 'namabuku', PDO::PARAM_STR),
				array(':jumlah', 'jumlah', PDO::PARAM_STR),
				array(':total', 'total', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				array(':vcreatedby', 'vcreatedby', PDO::PARAM_STR),
				),
				'call InsertBacaan (:actiontype
					,:bukubacaanid
					,:kodebuku
					,:namabuku
					,:jumlah
					,:total
					,:notes
					,:recordstatus,:vcreatedby)',
				'call InsertBacaan (:actiontype
				,:bukubacaanid
				,:kodebuku
				,:namabuku
				,:jumlah
				,:total
				,:notes
				,:recordstatus,:vcreatedby)');
		}
	}
	public function actionSavemenuharian() {
		parent::actionSave();
		$error = ValidateData(array(
			array('bukubacaandetailid', 'string', 'emptybukubacaandetailid'),
			array('bukubacaanid', 'string', 'emptybukubacaanid'),
			array('kodebuku', 'string', 'emptykodebuku'),
			array('namabuku', 'string', 'emptynamabuku'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'bukubacaandetailid',
				array(
				array(':bukubacaandetailid', 'bukubacaandetailid', PDO::PARAM_STR),
				array(':bukubacaanid', 'bukubacaanid', PDO::PARAM_STR),
				array(':hari', 'hari', PDO::PARAM_STR),
				array(':menuharian', 'menuharian', PDO::PARAM_STR),
				array(':url', 'url', PDO::PARAM_STR),
				),
				$this->sqlinsertdetail,
				$this->sqlupdatedetail);				
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
        $sql		 = "select recordstatus from bukubacaan where bukubacaanid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update bukubacaan set recordstatus = 0 where bukubacaanid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update bukubacaan set recordstatus = 1 where bukubacaanid = ".$id;
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
        $sql = "delete from bukubacaandetail where bukubacaanid = ".$id;
        Yii::app()->db->createCommand($sql)->execute();
	  }
	  
	  foreach ($ids as $id) {
        $sql = "delete from bukubacaan where bukubacaanid = ".$id;
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
        $sql = "delete from bukubacaandetail where bukubacaandetailid = ".$id;
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
		$this->pdf->title					 = getCatalog('bacaan');
		$this->pdf->AddPage('L');

		foreach ($dataReader as $row1) {
			//var_dump($row);die();
			$this->pdf->sety($this->pdf->gety() + 5);
			$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C');
			$this->pdf->colheader			 = array(getCatalog('bukubacaanid'), getCatalog('kodebuku'),
			getCatalog('namabuku'), getCatalog('jumlah'), getCatalog('notes'), getCatalog('recordstatus'));
			$this->pdf->setwidths(array(25, 30, 40, 20, 45, 15));
			$this->pdf->Rowheader();
			$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
			$this->pdf->row(array($row1['bukubacaanid'], $row1['kodebuku'], $row1['namabuku'],$row1['jumlah'],
			$row1['notes'],$row1['recordstatus']));

				$sql2 = "select b0.bukubacaanid, b0.hari, b0.menuharian, b0.url
				from bukubacaandetail b0 
				where b0.bukubacaanid = ".$row1['bukubacaanid'];
				$dataReader2 = Yii::app()->db->createCommand($sql2)->queryAll();
				$this->pdf->sety($this->pdf->gety() + 7);
				$this->pdf->colalign			 = array('C', 'C', 'C');
				$this->pdf->colheader			 = array(getCatalog('hari'), getCatalog('menuharian'), getCatalog('url')
				);
				$this->pdf->setwidths(array(30, 30, 30, 30));
				$this->pdf->Rowheader();
				$this->pdf->coldetailalign = array('L', 'L', 'L', 'L',);
				foreach ($dataReader2 as $row2) {
					$this->pdf->row(array($row2['hari'], $row2['menuharian'], $row2['url']));
				}	
				
		}
		$this->pdf->Output();
  }
  
  public function actionAmbilBuku(){
    $data = (isset($_REQUEST['data'])?$_REQUEST['data']:0);
    if ($data != 0) {
      $datas = explode(',',$data);

      $kodebuku = '';
      $hari = '';

      foreach ($datas as $datapeserta) {
        $expdata = explode('=',$datapeserta);
        if ($expdata[0] == "kodebuku") {
          $kodebuku = $expdata[1];
        }
        if ($expdata[0] == "hari") {
          $hari = $expdata[1];
        }
      }

      $sql = "select url 
        from bukubacaan a
        join bukubacaandetail b on b.bukubacaanid = a.bukubacaanid 
        where kodebuku = '".$kodebuku."' and hari = ".$hari;
      $url = Yii::app()->db->createCommand($sql)->queryScalar();

      echo $url;
    } else {
      echo 'Kesalahan penulisan Format Perintah: <b>ambilbuku kodebuku=,hari=</b>';
    }
  }
}