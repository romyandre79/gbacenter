<?php
class CatalogsysController extends AdminController {
	protected $menuname	 = 'catalogsys';
	public $module			 = 'Admin';
	protected $pageTitle = 'Kamus';
	public $wfname			 = '';
	public $sqldata			 = "select a0.catalogsysid,a0.languageid,a0.catalogname,a0.description,a0.catalogval,a1.languagename as languagename 
    from catalogsys a0 
    left join language a1 on a1.languageid = a0.languageid
  ";
	public $sqlcount		 = "select count(1) 
    from catalogsys a0 
    left join language a1 on a1.languageid = a0.languageid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$catalogsysid	 = filterinput(2, 'catalogsysid');
		$catalogname	 = filterinput(2, 'catalogname');
		$description	 = filterinput(2, 'description');
		$catalogval		 = filterinput(2, 'catalogval');
		$languagename	 = filterinput(2, 'languagename');
		$where				 .= " where coalesce(a0.catalogname,'') like '%".$catalogname."%'
			and coalesce(a0.description,'') like '%".$description."%'
			and coalesce(a0.catalogval,'') like '%".$catalogval."%'
			and coalesce(a1.languagename,'') like '%".$languagename."%'";
		if (($catalogsysid != '') && ($catalogsysid != '0')) {
			$where .= " and a0.catalogsysid in (".$catalogsysid.")";
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
			'keyField' => 'catalogsysid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'catalogsysid', 'languageid', 'catalogname', 'description', 'catalogval'
				),
				'defaultOrder' => array(
					'catalogsysid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
	}
	public function actionCreate() {
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where catalogsysid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'catalogsysid' => $model['catalogsysid'],
				'languageid' => $model['languageid'],
				'catalogname' => $model['catalogname'],
				'description' => $model['description'],
				'catalogval' => $model['catalogval'],
				'languagename' => $model['languagename'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('languageid', 'string', 'emptylanguageid'),
			array('catalogname', 'string', 'emptycatalogname'),
			array('description', 'string', 'emptydescription'),
			array('catalogval', 'string', 'emptycatalogval'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'catalogsysid',
				array(
				array(':catalogsysid', 'catalogsysid', PDO::PARAM_STR),
				array(':languageid', 'languageid', PDO::PARAM_STR),
				array(':catalogname', 'catalogname', PDO::PARAM_STR),
				array(':description', 'description', PDO::PARAM_STR),
				array(':catalogval', 'catalogval', PDO::PARAM_STR),
				),
				'insert into catalogsys (languageid,catalogname,description,catalogval)
			      values (:languageid,:catalogname,:description,:catalogval)',
				'update catalogsys
			      set languageid = :languageid,catalogname = :catalogname,description = :description,catalogval = :catalogval
			      where catalogsysid = :catalogsysid');
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
        $sql = "delete from catalogsys where catalogsysid = ".$id;
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

		//masukkan judul
		$this->pdf->title					 = getCatalog('catalogsys');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('catalogsysid'), getCatalog('language'),
			getCatalog('catalogname'), getCatalog('description'), getCatalog('catalogval'));
		$this->pdf->setwidths(array(10, 40, 40, 40, 40));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L');

		foreach ($dataReader as $row1) {
			//masukkan baris untuk cetak
			$this->pdf->row(array($row1['catalogsysid'], $row1['languagename'], $row1['catalogname'],
				$row1['description'], $row1['catalogval']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}