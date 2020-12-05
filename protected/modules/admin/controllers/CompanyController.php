<?php
class CompanyController extends AdminController {
	protected $menuname	 = 'company';
	public $module			 = 'Admin';
	protected $pageTitle = 'Perusahaan';
	public $wfname			 = '';
	public $sqldata			 = "select a0.companyid,a0.companyname,a0.companycode,a0.address,a0.cityid,a0.zipcode,a0.taxno,a0.currencyid,a0.faxno,a0.phoneno,a0.webaddress,a0.email,a0.leftlogofile,a0.rightlogofile,a0.isholding,a0.billto,a0.lat,a0.lng,a0.filelayout,a0.recordstatus,a1.cityname as cityname,a2.currencyname as currencyname 
    from company a0 
    left join city a1 on a1.cityid = a0.cityid
    left join currency a2 on a2.currencyid = a0.currencyid
  ";
	public $sqlcount		 = "select count(1) 
    from company a0 
    left join city a1 on a1.cityid = a0.cityid
    left join currency a2 on a2.currencyid = a0.currencyid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$companyid		 = filterinput(2, 'companyid');
		$companyname	 = filterinput(2, 'companyname');
		$companycode	 = filterinput(2, 'companycode');
		$zipcode			 = filterinput(2, 'zipcode');
		$taxno				 = filterinput(2, 'taxno');
		$faxno				 = filterinput(2, 'faxno');
		$phoneno			 = filterinput(2, 'phoneno');
		$webaddress		 = filterinput(2, 'webaddress');
		$email				 = filterinput(2, 'email');
		$cityname			 = filterinput(2, 'cityname');
		$currencyname	 = filterinput(2, 'currencyname');
		$where				 .= " where coalesce(a0.companyname,'') like '%".$companyname."%'
			and coalesce(a0.companycode,'') like '%".$companycode."%'
			and coalesce(a0.zipcode,'') like '%".$zipcode."%'
			and coalesce(a0.taxno,'') like '%".$taxno."%'
			and coalesce(a0.faxno,'') like '%".$faxno."%'
			and coalesce(a0.phoneno,'') like '%".$phoneno."%'
			and coalesce(a0.webaddress,'') like '%".$webaddress."%'
			and coalesce(a0.email,'') like '%".$email."%'
			and coalesce(a1.cityname,'') like '%".$cityname."%'
			and coalesce(a2.currencyname,'') like '%".$currencyname."%'";
		if (($companyid !== '0') && ($companyid !== '')) {
			$where .= " and a0.companyid in (".$companyid.")";
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
			'keyField' => 'companyid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'companyid', 'companyname', 'companycode', 'address', 'cityid', 'zipcode', 'taxno',
					'currencyid', 'faxno', 'phoneno', 'webaddress', 'email', 'leftlogofile', 'rightlogofile',
					'isholding', 'billto', 'lat', 'lng', 'filelayout', 'recordstatus'
				),
				'defaultOrder' => array(
					'companyid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
  }
  public function actionUpload() {
		if (!file_exists(Yii::getPathOfAlias('webroot').'/images/')) {
			mkdir(Yii::getPathOfAlias('webroot').'/images/');
		}
		$this->storeFolder = dirname('__FILES__').'/images/';
		parent::actionUpload();
		echo $_FILES['upload']['name'];
	}
	public function actionCreate() {
		parent::actionCreate();
		echo CJSON::encode(array(
			'status' => 'success',
			'currencyid' => getparameter("basecurrencyid"),
			'currencyname' => getparameter("basecurrency")
		));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldata.' where companyid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'companyid' => $model['companyid'],
				'companyname' => $model['companyname'],
				'companycode' => $model['companycode'],
				'address' => $model['address'],
				'cityid' => $model['cityid'],
				'zipcode' => $model['zipcode'],
				'taxno' => $model['taxno'],
				'currencyid' => $model['currencyid'],
				'faxno' => $model['faxno'],
				'phoneno' => $model['phoneno'],
				'webaddress' => $model['webaddress'],
				'email' => $model['email'],
				'leftlogofile' => $model['leftlogofile'],
				'rightlogofile' => $model['rightlogofile'],
				'isholding' => $model['isholding'],
				'billto' => $model['billto'],
				'lat' => $model['lat'],
				'lng' => $model['lng'],
				'filelayout' => $model['filelayout'],
				'recordstatus' => $model['recordstatus'],
				'cityname' => $model['cityname'],
				'currencyname' => $model['currencyname'],
			));
			Yii::app()->end();
		}
	}
	public function actionGetCompany() {
		$id = filterinput(1, 'companyid', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldata.' where companyid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'companyid' => $model['companyid'],
				'companyname' => $model['companyname'],
				'companycode' => $model['companycode'],
				'address' => $model['address'],
				'cityid' => $model['cityid'],
				'zipcode' => $model['zipcode'],
				'taxno' => $model['taxno'],
				'currencyid' => $model['currencyid'],
				'faxno' => $model['faxno'],
				'phoneno' => $model['phoneno'],
				'webaddress' => $model['webaddress'],
				'email' => $model['email'],
				'leftlogofile' => $model['leftlogofile'],
				'rightlogofile' => $model['rightlogofile'],
				'isholding' => $model['isholding'],
				'billto' => $model['billto'],
				'lat' => $model['lat'],
				'lng' => $model['lng'],
				'filelayout' => $model['filelayout'],
				'recordstatus' => $model['recordstatus'],
				'cityname' => $model['cityname'],
				'currencyname' => $model['currencyname'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('companyname', 'string', 'emptycompanyname'),
			array('companycode', 'string', 'emptycompanycode'),
			array('cityid', 'string', 'emptycityid'),
			array('currencyid', 'string', 'emptycurrencyid'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'companyid',
				array(
				array(':companyid', 'companyid', PDO::PARAM_STR),
				array(':companyname', 'companyname', PDO::PARAM_STR),
				array(':companycode', 'companycode', PDO::PARAM_STR),
				array(':address', 'address', PDO::PARAM_STR),
				array(':cityid', 'cityid', PDO::PARAM_STR),
				array(':zipcode', 'zipcode', PDO::PARAM_STR),
				array(':taxno', 'taxno', PDO::PARAM_STR),
				array(':currencyid', 'currencyid', PDO::PARAM_STR),
				array(':faxno', 'faxno', PDO::PARAM_STR),
				array(':phoneno', 'phoneno', PDO::PARAM_STR),
				array(':webaddress', 'webaddress', PDO::PARAM_STR),
				array(':email', 'email', PDO::PARAM_STR),
				array(':leftlogofile', 'leftlogofile', PDO::PARAM_STR),
				array(':rightlogofile', 'rightlogofile', PDO::PARAM_STR),
				array(':isholding', 'isholding', PDO::PARAM_STR),
				array(':billto', 'billto', PDO::PARAM_STR),
				array(':lat', 'lat', PDO::PARAM_STR),
				array(':lng', 'lng', PDO::PARAM_STR),
				array(':filelayout', 'filelayout', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into company (companyname, companycode, address, cityid, zipcode, taxno, currencyid, faxno, phoneno, webaddress, email, leftlogofile, rightlogofile, isholding, billto, lat, lng, filelayout, recordstatus)
				values (:companyname, :companycode, :address, :cityid, :zipcode, :taxno, :currencyid, :faxno, :phoneno, :webaddress, :email, :leftlogofile, :rightlogofile, :isholding, :billto, :lat, :lng, :filelayout, :recordstatus)',
				'update company
				set companyname = :companyname, companycode = :companycode, address = :address, cityid
				= :cityid, zipcode = :zipcode, taxno = :taxno, currencyid = :currencyid, faxno
				= :faxno, phoneno = :phoneno, webaddress = :webaddress, email = :email, leftlogofile
				= :leftlogofile, rightlogofile = :rightlogofile, isholding = :isholding, billto
				= :billto, lat = :lat, lng = :lng, filelayout = :filelayout, recordstatus = :recordstatus
				where companyid = :companyid');
		}
	}
	public function actionDelete() {
		parent::actionDelete();
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			if (isset($_POST['id'])) {
				$id = $_POST['id'];
				if (!is_array($id)) {
					$ids[] = $id;
					$id		 = $ids;
				}
				for ($i = 0; $i < count($id); $i++) {
					$sql		 = "select recordstatus from company where companyid = ".$id[$i];
					$status	 = Yii::app()->db->createCommand($sql)->queryRow();
					if ($status['recordstatus'] == 1) {
						$sql = "update company set recordstatus = 0 where companyid = ".$id[$i];
					} else
					if ($status['recordstatus'] == 0) {
						$sql = "update company set recordstatus = 1 where companyid = ".$id[$i];
					}
					$connection->createCommand($sql)->execute();
				}
				$transaction->commit();
				getMessage('success', 'alreadysaved');
			} else {
				getMessage('success', 'chooseone');
			}
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
			if (isset($_POST['id'])) {
				$id = $_POST['id'];
				if (!is_array($id)) {
					$ids[] = $id;
					$id		 = $ids;
				}
				for ($i = 0; $i < count($id); $i++) {
					$sql = "delete from company where companyid = ".$id[$i];
					Yii::app()->db->createCommand($sql)->execute();
				}
				$transaction->commit();
				getMessage('success', 'alreadysaved');
			} else {
				getMessage('success', 'chooseone');
			}
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
		$this->pdf->title					 = getCatalog('company');
		$this->pdf->AddPage('L', 'LEGAL');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C',
			'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('companyid'), getCatalog('companyname'),
			getCatalog('companycode'), getCatalog('address'), getCatalog('city'),
			getCatalog('zipcode'), getCatalog('taxno'), getCatalog('currency'),
			getCatalog('faxno'), getCatalog('phoneno'), getCatalog('billto'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 40, 20, 40, 40, 20, 30, 20, 20, 30, 40, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L',
			'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L');

		foreach ($dataReader as $row1) {
			//masukkan baris untuk cetak
			$this->pdf->row(array($row1['companyid'], $row1['companyname'], $row1['companycode'],
				$row1['address'], $row1['cityname'], $row1['zipcode'], $row1['taxno'], $row1['currencyname'],
				$row1['faxno'], $row1['phoneno'], $row1['billto'],
				$row1['recordstatus']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}