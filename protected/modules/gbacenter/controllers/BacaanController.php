<?php
class BacaanController extends AdminController {
	protected $menuname				 = 'bacaan';
	public $module						 = 'gbacenter';
	protected $pageTitle			 = 'Bacaan';
	public $wfname						 = '';
	public $sqldata						 = "select a0.bukubacaanid,a0.kodebuku,a0.namabuku,a0.jumlah, a0.total, a0.notes,a0.recordstatus,
	b0.bukubacaandetailid  idbacaandetail, b0.hari, b0.menuharian, b0.url
	from bukubacaan a0 
	join bukubacaandetail b0 on b0.bukubacaanid = a0.bukubacaanid
  ";
  
    //peserta
	public $sqldatagroupmenu	 = "select a0.bukubacaanid,a0.kodebuku,a0.namabuku,a0.jumlah, a0.total, a0.notes,a0.recordstatus,
	b0.bukubacaandetailid  idbacaandetail, b0.bukubacaanid as idbukubacaanid, b0.hari, b0.menuharian, b0.url
	from bukubacaan a0 
	join bukubacaandetail b0 on b0.bukubacaanid = a0.bukubacaanid
  ";

	public $sqlcount					 = "select count(1) 
	from bukubacaan a0 
	join bukubacaandetail b0 on b0.bukubacaanid = a0.bukubacaanid
  ";
  
    //count Menu Harian
	public $sqlcountgroupmenu	 = "select count(1) 
	from bukubacaan a0 
	join bukubacaandetail b0 on b0.bukubacaanid = a0.bukubacaanid
  ";

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
	$bukubacaanid = filterinput(1, 'bukubacaanid',FILTER_SANITIZE_NUMBER_INT);
	
    if ($bukubacaanid > 0) {
      $this->sqlcountgroupmenu .= ' where a0.bukubacaanid = '.$bukubacaanid;
      $this->sqldatagroupmenu	 .= ' where a0.bukubacaanid = '.$bukubacaanid;
    }
		$countgroupmenu				 = Yii::app()->db->createCommand($this->sqlcountgroupmenu)->queryScalar();
		$dataProvidergroupmenu = new CSqlDataProvider($this->sqldatagroupmenu,
			array(
			'totalItemCount' => $countgroupmenu,
			'keyField' => 'bukubacaanid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
        'attributes' => array(
					'bukubacaanid'
				),
				'defaultOrder' => array(
					'bukubacaanid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldatagroupmenu.' where a0.bukubacaanid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'bukubacaanid' => $model['bukubacaanid'],
				'kodebuku' => $model['kodebuku'],
				'namabuku' => $model['namabuku'],
				'jumlah' => $model['jumlah'],
				'total' => $model['total'],
				'notes' => $model['notes'],
				'hari' => $model['hari'],
				'menuharian' => $model['menuharian'],
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
				array(':kodebuku', 'kodebuku', PDO::PARAM_STR),
				array(':namabuku', 'namabuku', PDO::PARAM_STR),
				array(':jumlah', 'jumlah', PDO::PARAM_STR),
				array(':total', 'total', PDO::PARAM_STR),
				array(':notes', 'notes', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into bukubacaan (bukubacaanid,kodebuku,namabuku,jumlah,total,notes,recordstatus)
				values (:bukubacaanid,:kodebuku,:namabuku,:jumlah,:total,:notes,:recordstatus)'
			  ,
			  'update bukubacaan
			  	set kodebuku = :kodebuku,namabuku = :namabuku,jumlah = :jumlah,total = :total
			  	,notes = :notes,recordstatus = :recordstatus
			  	where bukubacaanid = :bukubacaanid'
			  );
		}
	}
	public function actionSavemenuharian() {
		parent::actionSave();
		$error = ValidateData(array(
			array('bukubacaanid', 'string', 'emptybukubacaanid'),
			array('kodebuku', 'string', 'emptykodebuku'),
			array('namabuku', 'string', 'emptynamabuku'),
		));
		if ($error == false) {
			
			ModifyCommand(1, $this->menuname, 'bukubacaanid',
				array(
				array(':bukubacaanid', 'bukubacaanid', PDO::PARAM_STR),
				array(':hari', 'hari', PDO::PARAM_STR),
				array(':menuharian', 'menuharian', PDO::PARAM_STR),
				array(':url', 'url', PDO::PARAM_STR),
				),
				'insert into bukubacaandetail (bukubacaanid,hari,menuharian,url)
			      values (:bukubacaanid,:hari,:menuharian,:url)',
				'insert into bukubacaandetail (bukubacaanid,hari,menuharian,url)
			      values (:bukubacaanid,:hari,:menuharian,:url)');
				
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
		$this->pdf->title					 = getCatalog('bacaan');
		$this->pdf->AddPage('L');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('bukubacaanid'), getCatalog('kodebuku'),
			getCatalog('namabuku'), getCatalog('jumlah'), getCatalog('notes'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(25, 30, 40, 20, 45, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['bukubacaanid'], $row1['kodebuku'], $row1['namabuku'],$row1['jumlah'],
			$row1['notes'],$row1['recordstatus']));
		}
		$this->pdf->Output();
	}
}