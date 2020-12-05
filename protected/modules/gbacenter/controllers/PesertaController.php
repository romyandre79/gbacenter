<?php
class PesertaController extends AdminController {
	protected $menuname	 = 'peserta';
	public $module			 = 'Gbacenter';
	protected $pageTitle = 'Peserta';
	public $wfname			 = '';
    public $sqldata			 = "select a0.pesertaid,a0.idtelegram,a0.nohp,a0.nama,a0.aliasid,a0.alamat,
    a0.kota, a0.provinsi,a0.negara,a0.asalgereja,a0.jabatangereja,a0.tgllahir,a0.sexid,a0.statusbaca,
    a0.foto,a0.kontak,a0.note,a0.useraccessid,a0.recordstatus
    from peserta a0 
  ";
	public $sqlcount		 = "select count(1) 
    from peserta a0 
  ";
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$pesertaid		 = filterinput(2, 'pesertaid', FILTER_SANITIZE_STRING);
		$idtelegram	 = filterinput(2, 'idtelegram', FILTER_SANITIZE_STRING);
        $nama	 = filterinput(2, 'nama', FILTER_SANITIZE_STRING);
        $aliasid	 = filterinput(2, 'aliasid', FILTER_SANITIZE_STRING);
        $asalgereja	 = filterinput(2, 'asalgereja', FILTER_SANITIZE_STRING);
        $jabatangereja	 = filterinput(2, 'jabatangereja', FILTER_SANITIZE_STRING);
        $where			 .= " where a0.asalgereja like '%".$asalgereja."%'
            or a0.jabatangereja like '%".$jabatangereja."%'
			or a0.idtelegram like '%".$idtelegram."%'
			or a0.nama like '%".$nama."%'";
		if (($pesertaid !== '0') && ($pesertaid !== '')) {
			$where .= " and a0.pesertaid in (".$pesertaid.")";
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
		$model = Yii::app()->db->createCommand($this->sqldata.' where pesertaid = '.$id)->queryRow();
		if ($model !== null) {
			echo CJSON::encode(array(
				'status' => 'success',
				'pesertaid' => $model['pesertaid'],
				'idtelegram' => $model['idtelegram'],
				'nohp' => $model['nohp'],
                'nama' => $model['nama'],
                'aliasid' => $model['aliasid'],
                'alamat' => $model['alamat'],
                'kota' => $model['kota'],
                'provinsi' => $model['provinsi'],
                'negara' => $model['negara'],
                'asalgereja' => $model['asalgereja'],
                'jabatangereja' => $model['jabatangereja'],
                'tgllahir' => $model['tgllahir'],
                'sexid' => $model['sexid'],
                'statusbaca' => $model['statusbaca'],
                'foto' => $model['foto'],
                'kontak' => $model['kontak'],
                'note' => $model['note'],
                'useraccessid' => $model['useraccessid'],
                'recordstatus' => $model['recordstatus'],
			));
			Yii::app()->end();
		}
	}
	public function actionSave() {
		parent::actionSave();
		$error = ValidateData(array(
			array('idtelegram', 'string', 'emptyidtelegram'),
			array('nohp', 'string', 'emptynohp'),
            array('nama', 'string', 'emptynama'),
		));
		if ($error == false) {
			ModifyCommand(1, $this->menuname, 'pesertaid',
				array(
				array(':pesertaid', 'pesertaid', PDO::PARAM_STR),
				array(':idtelegram', 'idtelegram', PDO::PARAM_STR),
				array(':nohp', 'nohp', PDO::PARAM_STR),
				array(':nama', 'nama', PDO::PARAM_STR),
				array(':aliasid', 'aliasid', PDO::PARAM_STR),
				array(':alamat', 'alamat', PDO::PARAM_STR),
				array(':kota', 'kota', PDO::PARAM_STR),
				array(':provinsi', 'provinsi', PDO::PARAM_STR),
				array(':negara', 'negara', PDO::PARAM_STR),
				array(':asalgereja', 'asalgereja', PDO::PARAM_STR),
				array(':jabatangereja', 'jabatangereja', PDO::PARAM_STR),
				array(':tgllahir', 'tgllahir', PDO::PARAM_STR),
				array(':sexid', 'sexid', PDO::PARAM_STR),
				array(':statusbaca', 'statusbaca', PDO::PARAM_STR),
				array(':foto', 'foto', PDO::PARAM_STR),
				array(':kontak', 'kontak', PDO::PARAM_STR),
				array(':note', 'note', PDO::PARAM_STR),
				array(':recordstatus', 'recordstatus', PDO::PARAM_STR),
				),
				'insert into peserta (idtelegram, nohp, nama, aliasid, alamat, kota, provinsi, negara
                , asalgereja, jabatangereja, tgllahir, sexid, statusbaca, foto, kontak, note
                ,recordstatus)
			      values (:idtelegram, :nohp, :nama, :aliasid, :alamat, :kota, :provinsi, :negara
                  , :asalgereja, :jabatangereja, :tgllahir, :sexid, :statusbaca, :foto, :kontak, :note
                  ,:recordstatus)',
				'update peserta
                  set idtelegram = :idtelegram,nohp = :nohp,nama = :nama,aliasid = :aliasid
                  ,alamat = :alamat,kota = :kota ,provinsi = :provinsi,negara = :negara,asalgereja = :asalgereja
                  ,jabatangereja = :jabatangereja,tgllahir = :tgllahir,sexid = :sexid,statusbaca = :statusbaca
                  ,foto = :foto,kontak = :kontak,note = :note,recordstatus = :recordstatus
			      where pesertaid = :pesertaid');
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
        $sql = "delete from peserta where pesertaid = ".$id;
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
		$this->pdf->title					 = getCatalog('peserta');
		$this->pdf->AddPage('L');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C','C');
		$this->pdf->colheader			 = array(getCatalog('pesertaid'), getCatalog('idtelegram'),
            getCatalog('nohp'), getCatalog('nama'), getCatalog('aliasid'), getCatalog('alamat'), getCatalog('kota')
            , getCatalog('provinsi'), getCatalog('negara'), getCatalog('asalgereja'), getCatalog('jabatangereja')
            , getCatalog('tgllahir'), getCatalog('sexid'), getCatalog('statusbaca'), getCatalog('kontak')
            , getCatalog('note'));
		$this->pdf->setwidths(array(20, 30, 30, 30, 30, 30, 30, 30, 30, 30, 30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$this->pdf->row(array($row1['pesertaid'], $row1['idtelegram'], $row1['nohp'],
                $row1['nama'], $row1['aliasid'], $row1['alamat'], $row1['kota']
				, $row1['provinsi'], $row1['negara'], $row1['asalgereja'], $row1['jabatangereja']
				, $row1['tgllahir'], $row1['sexid'], $row1['kontak'], $row1['note']));
		}
		$this->pdf->Output();
	}
}