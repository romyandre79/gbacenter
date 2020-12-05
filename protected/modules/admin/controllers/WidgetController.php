<?php
class WidgetController extends AdminController {
	protected $menuname	 = 'widget';
	public $module			 = 'Admin';
	protected $pageTitle = 'Widget';
	public $wfname			 = '';
	public $sqldata			 = "select a0.widgetid,a0.widgetname,a0.widgettitle,a0.widgetversion,a0.widgetby,a0.description,a0.widgeturl,a0.moduleid,a0.installdate,a0.recordstatus,a1.modulename as modulename 
    from widget a0 
    left join modules a1 on a1.moduleid = a0.moduleid
  ";
	public $sqlcount		 = "select count(1) 
    from widget a0 
    left join modules a1 on a1.moduleid = a0.moduleid
  ";
	public function getSQL() {
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where				 = "";
		$widgetid			 = filterinput(2, 'widgetid', FILTER_SANITIZE_STRING);
		$widgetname		 = filterinput(2, 'widgetname', FILTER_SANITIZE_STRING);
		$widgettitle	 = filterinput(2, 'widgettitle', FILTER_SANITIZE_STRING);
		$widgetversion = filterinput(2, 'widgetversion', FILTER_SANITIZE_STRING);
		$widgetby			 = filterinput(2, 'widgetby', FILTER_SANITIZE_STRING);
		$widgeturl		 = filterinput(2, 'widgeturl', FILTER_SANITIZE_STRING);
		$modulename		 = filterinput(2, 'modulename', FILTER_SANITIZE_STRING);
		$where				 .= " where a0.widgetname like '%".$widgetname."%'
			and a0.widgettitle like '%".$widgettitle."%'
			and a0.widgetversion like '%".$widgetversion."%'
			and a0.widgetby like '%".$widgetby."%'
			and a0.widgeturl like '%".$widgeturl."%'
			and a1.modulename like '%".$modulename."%'";
		if (($widgetid !== '0') && ($widgetid !== '')) {
			$where .= " and a0.widgetid in (".$widgetid.")";
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
			'keyField' => 'widgetid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'widgetid', 'widgetname', 'widgettitle', 'widgetversion', 'widgetby', 'description',
					'widgeturl', 'moduleid', 'installdate', 'recordstatus'
				),
				'defaultOrder' => array(
					'widgetid' => CSort::SORT_DESC
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where a0.widgetid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'widgetid' => $model['widgetid'],
				'widgetname' => $model['widgetname'],
				'widgettitle' => $model['widgettitle'],
				'widgetversion' => $model['widgetversion'],
				'widgetby' => $model['widgetby'],
				'description' => $model['description'],
				'widgeturl' => $model['widgeturl'],
				'moduleid' => $model['moduleid'],
				'installdate' => $model['installdate'],
				'recordstatus' => $model['recordstatus'],
				'modulename' => $model['modulename'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('widgetname', 'string', 'emptywidgetname'),
			array('widgettitle', 'string', 'emptywidgettitle'),
			array('widgetversion', 'string', 'emptywidgetversion'),
			array('widgetby', 'string', 'emptywidgetby'),
			array('description', 'string', 'emptydescription'),
			array('widgeturl', 'string', 'emptywidgeturl'),
			array('moduleid', 'string', 'emptymoduleid'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'widgetid',
				array(
				array(':widgetid', 'widgetid', PDO::PARAM_STR),
				array(':widgetname', 'widgetname', PDO::PARAM_STR),
				array(':widgettitle', 'widgettitle', PDO::PARAM_STR),
				array(':widgetversion', 'widgetversion', PDO::PARAM_STR),
				array(':widgetby', 'widgetby', PDO::PARAM_STR),
				array(':description', 'description', PDO::PARAM_STR),
				array(':widgeturl', 'widgeturl', PDO::PARAM_STR),
				array(':moduleid', 'moduleid', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into widget (widgetname,widgettitle,widgetversion,widgetby,description,widgeturl,moduleid,recordstatus)
			      values (:widgetname,:widgettitle,:widgetversion,:widgetby,:description,:widgeturl,:moduleid,:recordstatus)',
				'update widget 
			      set widgetname = :widgetname,widgettitle = :widgettitle,widgetversion = :widgetversion,widgetby = :widgetby,description = :description,widgeturl = :widgeturl,moduleid = :moduleid,recordstatus = :recordstatus 
			      where widgetid = :widgetid');
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
        $sql		 = "select recordstatus from widget where a0.widgetid = ".$id;
        $status	 = Yii::app()->db->createCommand($sql)->queryRow();
        if ($status['recordstatus'] == 1) {
          $sql = "update widget set recordstatus = 0 where a0.widgetid = ".$id;
        } else
        if ($status['recordstatus'] == 0) {
          $sql = "update widget set recordstatus = 1 where a0.widgetid = ".$id;
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
        $sql = "delete from widget where widgetid = ".$id;
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
		$this->pdf->title					 = getCatalog('widget');
		$this->pdf->AddPage('P');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C');
		$this->pdf->colheader			 = array(getCatalog('widgetid'), getCatalog('widgetname'),
			getCatalog('widgettitle'), getCatalog('modules'));
		$this->pdf->setwidths(array(10, 40, 50, 30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L', 'L', 'L', 'L', 'L', 'L',
			'L');

		foreach ($dataReader as $row1) {
			//masukkan baris untuk cetak
			$this->pdf->row(array($row1['widgetid'], $row1['widgetname'], $row1['widgettitle'],
				$row1['modulename']));
		}
		// me-render ke browser
		$this->pdf->Output();
	}
}