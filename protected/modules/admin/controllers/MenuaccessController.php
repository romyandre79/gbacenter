<?php
class MenuaccessController extends AdminController {
	protected $menuname	 = 'menuaccess';
	public $module			 = 'Admin';
	protected $pageTitle = 'Akses Menu';
	public $wfname			 = '';
	public $sqldata			 = "select a0.menuaccessid,a0.menuname,a0.menutitle,a0.description,a0.moduleid,a0.parentid,a0.menuurl,a0.sortorder,a0.recordstatus,
	a1.modulename as modulename, a2.menuname as parentname
	from menuaccess a0
	left join modules a1 on a1.moduleid = a0.moduleid
	left join menuaccess a2 on a0.parentid = a2.menuaccessid";
	public $sqlcount		 = "select count(1)
    from menuaccess a0
    left join modules a1 on a1.moduleid = a0.moduleid
		left join menuaccess a2 on a0.parentid = a2.menuaccessid";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$menuaccessid	 = filterinput(2, 'menuaccessid', FILTER_SANITIZE_STRING);
		$menuname			 = filterinput(2, 'menuname', FILTER_SANITIZE_STRING);
		$menutitle		 = filterinput(2, 'menutitle', FILTER_SANITIZE_STRING);
		$menuurl			 = filterinput(2, 'menuurl', FILTER_SANITIZE_STRING);
		$modulename		 = filterinput(2, 'modulename', FILTER_SANITIZE_STRING);
		$parentname		 = filterinput(2, 'parentname', FILTER_SANITIZE_STRING);
		$where				 .= " where coalesce(a0.menuname,'') like '%".$menuname."%'
		and coalesce(a0.menutitle,'') like '%".$menutitle."%'
		and coalesce(a0.menuurl,'') like '%".$menuurl."%'
		and coalesce(a2.menuname,'') like '%".$parentname."%'
		and coalesce(a1.modulename,'') like '%".$modulename."%'";
		if (($menuaccessid !== '0') && ($menuaccessid !== '')) {
			$where .= " and a0.menuaccessid in (".$menuaccessid.")";
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
			'keyField' => 'menuaccessid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'menuaccessid', 'menuname', 'menutitle', 'description', 'moduleid',
					'parentid', 'menuurl', 'sortorder', 'recordstatus'
				),
				'defaultOrder' => array(
					'menuaccessid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.menuaccessid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'menuaccessid' => $model['menuaccessid'],
				'menuname' => $model['menuname'],
				'menutitle' => $model['menutitle'],
				'description' => $model['description'],
				'moduleid' => $model['moduleid'],
				'parentid' => $model['parentid'],
				'parentname' => $model['parentname'],
				'menuurl' => $model['menuurl'],
				'sortorder' => $model['sortorder'],
				'recordstatus' => $model['recordstatus'],
				'modulename' => $model['modulename'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('menuname', 'string', 'emptymenuname'),
			array('menutitle', 'string', 'emptymenutitle'),
			array('moduleid', 'string', 'emptymoduleid'),
			array('menuurl', 'string', 'emptymenuurl'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'menuaccessid',
				array(
				array(':menuaccessid', 'menuaccessid', PDO::PARAM_STR),
				array(':menuname', 'menuname', PDO::PARAM_STR),
				array(':menutitle', 'menutitle', PDO::PARAM_STR),
				array(':description', 'description', PDO::PARAM_STR),
				array(':moduleid', 'moduleid', PDO::PARAM_STR),
				array(':parentid', 'parentid', PDO::PARAM_STR),
				array(':menuurl', 'menuurl', PDO::PARAM_STR),
				array(':sortorder', 'sortorder', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into menuaccess (menuname,menutitle,description,moduleid,parentid,menuurl,sortorder,recordstatus)
			  values (:menuname,:menutitle,:description,:moduleid,:parentid,:menuurl,:sortorder,:recordstatus)',
				'update menuaccess
			  set menuname = :menuname,menutitle = :menutitle,description = :description,moduleid = :moduleid,parentid = :parentid,menuurl = :menuurl,sortorder = :sortorder,recordstatus = :recordstatus
			  where menuaccessid = :menuaccessid');
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
        $sql		 = "select recordstatus from menuaccess where menuaccessid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update menuaccess set recordstatus = 0 where menuaccessid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update menuaccess set recordstatus = 1 where menuaccessid = ".$id;
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
			if (isset($_POST['id'])) {
				$id = $_POST['id'];
				if (!is_array($id)) {
					$ids[] = $id;
					$id		 = $ids;
				}
				for ($i = 0; $i < count($id); $i++) {
					$sql = "delete from menuaccess where menuaccessid = ".$id[$i];
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
		$this->pdf->title					 = getCatalog('menuaccess');
		$this->pdf->AddPage('L');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('menuaccessid'), getCatalog('menuname'),
			getCatalog('menutitle'), getCatalog('description'), getCatalog('module'),
			getCatalog('menuurl'), getCatalog('sortorder'), getCatalog('recordstatus'));
		$this->pdf->setwidths(array(10, 40, 60, 60, 30, 40, 15, 15));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'R', 'L');

		foreach ($dataReader as $row1) {
			//masukkan baris untuk cetak
			$this->pdf->row(array($row1['menuaccessid'], $row1['menuname'], $row1['menutitle'],
				$row1['description'],
				$row1['modulename'], $row1['menuurl'], $row1['sortorder'], (($row1['recordstatus']
				== 1) ? 'Active' : 'NotActive')));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}