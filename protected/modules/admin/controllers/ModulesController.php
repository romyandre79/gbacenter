<?php
class ModulesController extends AdminController {
	protected $menuname	 = 'modules';
	public $module			 = 'admin';
	public $sqldata			 = "select a0.moduleid,a0.modulename,a0.description,a0.recordstatus,a0.createdby,a0.moduleversion,a0.installdate
    from modules a0 
  ";
	public $sqlcount		 = "select count(1) 
    from modules a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$modulename	 = filterinput(2, 'modulename', FILTER_SANITIZE_STRING);
		$description = filterinput(2, 'description', FILTER_SANITIZE_STRING);
		$createdby = filterinput(2, 'createdby', FILTER_SANITIZE_STRING);
		$moduleversion = filterinput(2, 'moduleversion', FILTER_SANITIZE_STRING);
		$where			 .= " where coalesce(a0.modulename,'') like '%".$modulename."%'
      and coalesce(a0.description,'') like '%".$description."%'
      and coalesce(a0.createdby,'') like '%".$createdby."%'
      and coalesce(a0.moduleversion,'') like '%".$moduleversion."%'"
      ;
		$this->sqldata = $this->sqldata.$where;
		$this->count	 = Yii::app()->db->createCommand($this->sqlcount.$where)->queryScalar();
	}
	public function getModuleRelation($moduleid) {
		$sql				 = "select a.relationid, b.modulename, b.description 
			from modulerelation a 
			inner join modules b on b.moduleid = a.relationid 
			where a.moduleid = ".$moduleid;
		$dependency	 = new CDbCacheDependency('SELECT MAX(moduleid) FROM modules');
		$datas			 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
		$s					 = "";
		foreach ($datas as $data) {
			if ($s == "") {
				$s = $data['modulename'];
			} else {
				$s = $s.','.$data['modulename'];
			}
		}
		return $s;
	}
	public function actionUninstall() {
		//TODO:
		//cek dependency
		//uninstall module
		$module			 = filterinput(1, 'module', FILTER_SANITIZE_STRING);
		$sql				 = "select ifnull(count(1),0) as jumlah
			from modulerelation a 
			where a.relationid = ".$module;
		$dependency	 = new CDbCacheDependency('SELECT MAX(moduleid) FROM modules');
		$datas			 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
		if ($datas['jumlah'] > 0) {
			getMessage('error', 'Your module has dependency to other');
		} else {
			$sql				 = "select modulename 
				from modules a 
				where a.moduleid = ".$module;
			$dependency	 = new CDbCacheDependency('SELECT MAX(moduleid) FROM modules');
			$datas			 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
			$a					 = $datas['modulename'];
			if (Yii::app()->hasModule($a)) {
				$s = Yii::app()->getModule($a)->uninstall();
				if ($s == "ok") {
					rrmdir(dirname('__FILES__').'/protected/modules/'.$datas['modulename']);
					getMessage('success', 'alreadysaved');
					$this->redirect(Yii::app()->user->returnUrl);
				} else {
					getMessage('error', $s);
				}
			} else {
				getMessage('error', 'alreadyuninstalled');
			}
		}
	}
	public function actionInstall() {
		if (!empty($_FILES)) {
			$storeFolder = dirname('__FILES__').'/uploads/';
			$tempFile		 = $_FILES['upload']['tmp_name'];
			$targetFile	 = $storeFolder.$_FILES['upload']['name'];
			move_uploaded_file($tempFile, $targetFile);
			$zip				 = new ZipArchive;
			if ($zip->open($storeFolder.$_FILES['upload']['name']) === TRUE) {
				$zip->extractTo(dirname('__FILES__').'/protected/modules/');
				$zip->close();
				$s = basename($_FILES['upload']['name'], ".zip");
				echo $s;
			}
			// unlink($storeFolder.$_FILES['upload']['name']);
		}
	}
	public function actionRunning() {
		try {
			$s = filterinput(1, 'id', FILTER_SANITIZE_STRING);
			$a = Yii::app()->getModule($s)->install();
			if ($a == "ok") {
				getMessage('success', 'alreadysaved');
			} else {
				getMessage('error', $a);
			}
		} catch (Exception $e) {
			getMessage('error', $e->getMessage());
		}
	}
	public function actionIndex() {
		parent::actionIndex();
    $this->pageTitle = "Modules";
    $this->getSQL();
		$dataProvider		 = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $this->count,
			'keyField' => 'moduleid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
					'moduleid', 'modulename', 'description',
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
	}
	public function actionUpdate() {
		parent::actionUpdate();
		$id = filterinput(1, 'id', FILTER_SANITIZE_NUMBER_INT);
		if ($id == '') {
			GetMessage('error', 'chooseone');
		}
		$model = Yii::app()->db->createCommand($this->sqldata.' where moduleid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'moduleid' => $model['moduleid'],
				'modulename' => $model['modulename'],
				'description' => $model['description'],
				'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
}