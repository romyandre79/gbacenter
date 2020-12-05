<?php
class Controller extends CController {
	//layout
	public $layout				 = '//layouts/column1';
	//seo
	protected $pageTitle	 = '';
	protected $metatag;
	protected $description = '';
	//internal
	protected $pdf;
	protected $menuname		 = '';
	protected $module			 = '';
	protected $sqldata		 = '';
	protected $sqlcount		 = '';
	protected $count			 = 0;
	protected $storeFolder = '';
	protected $wfname			 = '';
	protected $phpExcel;
	public $options				 = array(
		'addRemoveLinks' => true,
	);
	public $dict					 = array(
		'dictDefaultMessage' => 'Drop files here or click to Upload',
		'dictFallbackMessage' => 'Your Browser doesn\'t support',
		'dictInvalidFileType' => 'File Type not allowed (only zip)',
		'dictFileTooBig' => 'Your File Too Big',
		'dictResponseError' => 'Oops! something wrong',
		'dictCancelUpload' => 'Cancelled',
		'dictCancelUploadConfirmation' => 'Are you sure to cancel this upload ?',
		'dictRemoveFile' => 'Delete',
		'dictMaxFilesExceeded' => 'Maximum file exceeded',
	);
	protected function getFooterXLS($excel) {
		header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
		header('Content-Disposition: attachment;filename="'.getCatalog($this->menuname).'.xlsx"');
		header('Cache-Control: max-age=0');
		header('Cache-Control: max-age=1');
		header('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
		header('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
		header('Cache-Control: cache, must-revalidate');
		header('Pragma: public');
		$objWriter = PHPExcel_IOFactory::createWriter($excel, 'Excel2007');
		$objWriter->save('php://output');
		unset($excel);
	}
	public function actionIndex() {
    $this->description = getparameter('sitetitle').' '.$this->pageTitle;
		if (Yii::app()->params['install'] == true) {
			Yii::app()->theme = 'startup';
			$this->redirect(Yii::app()->baseUrl.'/install');
    } else 
    if (Yii::app()->params['ismaintain'] == true) { 
      Yii::app()->theme = 'maintain';
			$this->redirect('site/maintain');
    } else {
			getTheme(false, $this->module);
		}
		if (isset($_REQUEST['name'])) {
			$sql = 'delete from usertodo where usertodoid = '.$_REQUEST['name'];
			Yii::app()->db->createCommand($sql)->execute();
		}
	}
	public function actionCreate() {
		
	}
	public function actionUpdate() {
		
	}
	public function actionSave() {
		
	}
	public function actionDelete() {
		
	}
	public function actionPurge() {
		
	}
	public function actionDownPDF() {
		//require_once("pdf.php");
		$this->pdf = new pdf();
	}
	public function actionDownXLS() {
		Yii::import('ext.phpexcel.XPHPExcel');
		$this->phpExcel	 = XPHPExcel::createPHPExcel();
		$this->phpExcel->getProperties()->setCreator("Prisma Data Abadi")
			->setLastModifiedBy("Prisma Data Abadi")
			->setCompany("Prisma Data Abadi")
			->setTitle("Capella CMS")
			->setSubject("Capella CMS")
			->setDescription("Capella CMS")
			->setManager("Romy Andre")
			->setKeywords("capella cms php yii framework")
			->setCategory("Capella CMS");
		$objReader			 = PHPExcel_IOFactory::createReader('Excel2007');
		$filename				 = "";
		if (!file_exists(Yii::getPathOfAlias('webroot')."/protected/modules/".$this->menuname.".xlsx")) {
			$filename = Yii::getPathOfAlias('webroot')."/protected/modules/template.xlsx";
		} else {
			$filename = Yii::getPathOfAlias('webroot')."/protected/modules/".$this->menuname.".xlsx";
		}
		$this->phpExcel = $objReader->load($filename);
		$this->phpExcel->setActiveSheetIndex(0)->setCellValue('A1',
			getCatalog($this->menuname));
	}
	public function actionPost() {
		
	}
	public function actionUpload() {
		if (!empty($_FILES)) {
			if ($this->storeFolder === '') {
				$this->storeFolder = dirname('__FILES__').'/uploads/';
			}
			$tempFile		 = $_FILES['upload']['tmp_name'];
			$targetFile	 = $this->storeFolder.$_FILES['upload']['name'];
			move_uploaded_file($tempFile, $targetFile);
		}
	}
}