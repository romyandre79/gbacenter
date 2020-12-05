<?php
class PesertaImportController extends AdminController {
	protected $menuname	 = 'peserta';
	public $module			 = 'Gbacenter';
	protected $pageTitle = 'Peserta';
    public $wfname			 = '';
	
	
    public function actionIndex() {
		//parent::actionIndex();
		/*$this->getSQL();
		$dataProvider = new CSqlDataProvider($this->sqldata,
			array(
			'totalItemCount' => $this->count,
			'keyField' => 'pesertaid',
			'pagination' => array(
				'pageSize' => getparameter('DefaultPageSize'),
				'pageVar' => 'page',
			),
			'sort' => array(
				'attributes' => array(
                    'pesertaid', 'idtelegram', 'nohp', 'nama', 'aliasid', 'alamat', 'kota', 'provinsi', 'negara'
                    , 'asalgereja', 'jabatangereja', 'tgllahir', 'sexid', 'statusbaca', 'foto', 'kontak', 'note'
                    , 'useraccessid', 'recordstatus'
				),
				'defaultOrder' => array(
					'pesertaid' => CSort::SORT_DESC
				),
			),
		));
		$this->render('index', array('dataProvider' => $dataProvider));
		*/
		$model=new CobaExcel;
		if(isset($_POST['CobaExcel']))
		{
			$model->attributes=$_POST['CobaExcel'];
			$itu=CUploadedFile::getInstance($model,'filee');
			$path='/../jadwal_keg.xls';
			$itu->saveAs($path);
			$data = new Spreadsheet_Excel_Reader($path);
			$id=array();
			$nama=array();
			for ($j = 2; $j <= $data->sheets[0]['numRows']; $j++) 
			{
				$id[$j]=$data->sheets[0]['cells'][$j][1];
				$nama[$j]=$data->sheets[0]['cells'][$j][2];
			}
		
			for($i=0;$i<count($id);$i++)
			{
				$model=new CobaExcel;

				$model->id=$id[$i];
				$model->nama=$keg[$i];
				$model->save();
                       }
                        unlink($path);
			$this->redirect(array('index'));
		}
		$this->render('excel',array('model'=>$model));

	}

}
?>