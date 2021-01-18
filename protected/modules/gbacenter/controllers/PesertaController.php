<?php
class PesertaController extends AdminController {
	protected $menuname	 = 'peserta';
	public $module			 = 'Gbacenter';
	protected $pageTitle = 'Peserta';
	public $wfname			 = '';
	public $sqldata			 = "
	select a0.pesertaid,a0.idtelegram,a0.nohp,a0.nama,a0.aliasid,a0.alamat,
    a0.kota, a0.provinsi,a0.negara,a0.asalgereja,a0.jabatangereja,a0.tgllahir,a0.sexid,a0.statusbaca,
	a0.foto,a0.kontak,a0.note,a0.useraccessid,a0.recordstatus,
	b0.cityname namakota, c0.provincename namaprovinsi, d0.countryname namanegara
	from peserta a0 
	left join city b0 on b0.cityid = a0.kota
	left join province c0 on c0.provinceid = a0.provinsi
	left join country d0 on d0.countryid = a0.negara
  ";
	public $sqlcount		 = "select count(1) 
	from peserta a0 
	left join city b0 on b0.cityid = a0.kota
	left join province c0 on c0.provinceid = a0.provinsi
	left join country d0 on d0.countryid = a0.negara
  ";
  private $sqlinsert = "insert into peserta (idtelegram, nohp, nama, aliasid, alamat, kota, provinsi, negara
  , asalgereja, jabatangereja, tgllahir, sexid, statusbaca, foto, kontak, note
  ,recordstatus)
values (:idtelegram, :nohp, :nama, :aliasid, :alamat, :kota, :provinsi, :negara
    , :asalgereja, :jabatangereja, :tgllahir, :sexid, :statusbaca, :foto, :kontak, :note
    ,:recordstatus)";
  private $sqlupdate = "update peserta
  set idtelegram = :idtelegram,nohp = :nohp,nama = :nama,aliasid = :aliasid
  ,alamat = :alamat,kota = :kota ,provinsi = :provinsi,negara = :negara,asalgereja = :asalgereja
  ,jabatangereja = :jabatangereja,tgllahir = :tgllahir,sexid = :sexid,statusbaca = :statusbaca
  ,foto = :foto,kontak = :kontak,note = :note,recordstatus = :recordstatus
where pesertaid = :pesertaid";
  
  public function actionUploadData() {
		if (!file_exists(Yii::getPathOfAlias('webroot').'/uploads/')) {
			mkdir(Yii::getPathOfAlias('webroot').'/uploads/');
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
        $id = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
        $usertelegram = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
        $nohp = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
        $nama = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
        $aliasid = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
        $aliasid = $this->getAliasID($aliasid);
        $alamat = $objWorksheet->getCellByColumnAndRow(5, $row)->getValue();
        $kota = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
        $provinsi = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
        $negara = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
        $asalgereja = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
        $jabatan = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
        $tgllahir = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();
        $jeniskelamin = $objWorksheet->getCellByColumnAndRow(12, $row)->getValue();
        $statusbaca = $objWorksheet->getCellByColumnAndRow(13, $row)->getValue();
        $foto = $objWorksheet->getCellByColumnAndRow(14, $row)->getValue();
        $kontak = $objWorksheet->getCellByColumnAndRow(15, $row)->getValue();
        $notes = $objWorksheet->getCellByColumnAndRow(16, $row)->getValue();
        $recordstatus = $objWorksheet->getCellByColumnAndRow(17, $row)->getValue();   
        if ($id != '') {
          $command = $connection->createCommand($this->sqlupdate);
          $command->bindValue(':pesertaid', $id, PDO::PARAM_STR);
        } else {   
          $command = $connection->createCommand($this->sqlinsert);
        }
        $command->bindValue(':idtelegram', $usertelegram, PDO::PARAM_STR);
        $command->bindValue(':nohp', $nohp, PDO::PARAM_STR);
        $command->bindValue(':nama', $nama, PDO::PARAM_STR);
        $command->bindValue(':aliasid', $aliasid, PDO::PARAM_STR);
        $command->bindValue(':alamat', $alamat, PDO::PARAM_STR);
        $command->bindValue(':kota', $kota, PDO::PARAM_STR);
        $command->bindValue(':provinsi', $provinsi, PDO::PARAM_STR);
        $command->bindValue(':negara', $negara, PDO::PARAM_STR);
        $command->bindValue(':asalgereja', $asalgereja, PDO::PARAM_STR);
        $command->bindValue(':jabatangereja', $jabatangereja, PDO::PARAM_STR);
        $command->bindValue(':tgllahir', $tgllahir, PDO::PARAM_STR);
        $command->bindValue(':sexid', $jeniskelamin, PDO::PARAM_STR);
        $command->bindValue(':statusbaca', $statusbaca, PDO::PARAM_STR);
        $command->bindValue(':foto', $foto, PDO::PARAM_STR);
        $command->bindValue(':kontak', $kontak, PDO::PARAM_STR);
        $command->bindValue(':note', $notes, PDO::PARAM_STR);
        $command->bindValue(':recordstatus', $recordstatus, PDO::PARAM_STR);
        $command->execute();
      }
      $transaction->commit();
      echo getcatalog('insertsuccess');
    }
    catch (CDbException $e) {
      $transaction->rollBack();
      echo 'Error Line: '.$row.' ==> '.implode(" ",$e->errorInfo);
    }
	}
	public function getSQL() {
		$this->count = Yii::app()->db->createCommand($this->sqlcount)->queryScalar();
		$where			 = "";
		$pesertaid		 = filterinput(2, 'pesertaid', FILTER_SANITIZE_STRING);
		$nohp	 = filterinput(2, 'nohp', FILTER_SANITIZE_STRING);
        $nama	 = filterinput(2, 'nama', FILTER_SANITIZE_STRING);
		$aliasid	 = filterinput(2, 'aliasid', FILTER_SANITIZE_STRING);
        $where			 .= " where coalesce(a0.nama,'') like '%".$nama."%'
			and coalesce(a0.aliasid,'') like '%".$aliasid."%'
			and coalesce(a0.nohp,'') like '%".$nohp."%'";
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
                    'pesertaid', 'idtelegram', 'nohp', 'nama', 'aliasid', 'alamat', 'namakota', 'namaprovinsi', 'namanegara'
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
				'namakota' => $model['namakota'],
				'namanegara' => $model['namanegara'],
				'namaprovinsi' => $model['namaprovinsi'],
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
				$this->sqlinsert,
				$this->sqlupdate);
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
		$this->pdf->AddPage('L','A3');
		$this->pdf->colalign			 = array('C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C', 'C','C', 'C', 'C', 'C','C');
		$this->pdf->colheader			 = array(getCatalog('pesertaid'), getCatalog('idtelegram'),
            getCatalog('nohp'), getCatalog('nama'), getCatalog('aliasid'), getCatalog('grupbaca'),getCatalog('alamat'), getCatalog('kota')
            , getCatalog('provinsi'), getCatalog('negara'), getCatalog('asalgereja'), getCatalog('jabatangereja')
            , getCatalog('tgllahir'), getCatalog('sexid'), getCatalog('kontak')
            , getCatalog('note'));
		$this->pdf->setwidths(array(20, 25, 25, 30, 30, 30, 20, 30, 30, 20, 25, 20, 20, 20, 20, 30));
		$this->pdf->Rowheader();
		$this->pdf->coldetailalign = array('L', 'L', 'L', 'L');
		foreach ($dataReader as $row1) {
			$jk="";
			if($row1['sexid'] == "L") {
				$jk="Laki";
			} else {
				$jk="Perempuan";
			}
			$this->pdf->row(array($row1['pesertaid'], $row1['idtelegram'], $row1['nohp'],
                $row1['nama'], $row1['aliasid'], $row1['namagrup'],$row1['alamat'], $row1['namakota']
				, $row1['namaprovinsi'], $row1['namanegara'], $row1['asalgereja'], $row1['jabatangereja']
				, $row1['tgllahir'], $jk, $row1['kontak'], $row1['note']));
		}
		$this->pdf->Output();
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
			$sql		 = "select recordstatus from peserta where pesertaid = ".$id;
			$status	 = Yii::app()->db->createCommand($sql)->queryRow();
			if ($status['recordstatus'] == 1) {
			$sql = "update peserta set recordstatus = 0 where pesertaid = ".$id;
			} else
			if ($status['recordstatus'] == 0) {
			$sql = "update peserta set recordstatus = 1 where pesertaid = ".$id;
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

	public function actionRegister() {
		parent::actionSave();

      $nama = $_POST['nama'];
      $nohp = $_POST['nohp'];

			$error = false;
			if ( $_POST['aliasid'] !== '') {
				$valiasid = '';
				if ($valiasid =='') {
					$valiasid = str_replace(' ','.',$_POST['nama']);
				}
			}
			
			$sql = "select count(1)
					from peserta 
					where recordstatus = 1
					and aliasid = '".$valiasid."'";
			$cntpeserta = Yii::app()->db->createCommand($sql)->queryScalar();
			//$aliasid = $this->getAliasID($_POST['nama']);

			if($cntpeserta > 0 ) {
				$valiasid .= rand(0,1000000000);
      } 
      
      $alamat = $_POST['alamat'];
      $kota = $_POST['kota'];
      $provinsi = $_POST['provinsi'];
      $negara = $_POST['negara'];
      $asalgereja = $_POST['asalgereja'];
      $jabatangereja = $_POST['jabatangereja'];
      $tgllahir = $_POST['tgllahir'];
      $sexid = $_POST['sexid'];
				
			$error = ValidateData(array(
				array('nama', 'string', 'emptynama')
      ));
      
      
			if ($error == false) {
				ModifyCommand(1, $this->menuname, 'pesertaid',
					array(
					array(':pesertaid', 'pesertaid', PDO::PARAM_STR),	
					array(':nama', 'nama', PDO::PARAM_STR),	
					array(':nohp', 'nohp', PDO::PARAM_STR),
					array(':aliasid', 'aliasid', PDO::PARAM_STR),
					array(':alamat', 'alamat', PDO::PARAM_STR),
					array(':kota', 'kota', PDO::PARAM_STR),
					array(':provinsi', 'provinsi', PDO::PARAM_STR),
					array(':negara', 'negara', PDO::PARAM_STR),
					array(':asalgereja', 'asalgereja', PDO::PARAM_STR),
					array(':jabatangereja', 'jabatangereja', PDO::PARAM_STR),
					array(':tgllahir', 'tgllahir', PDO::PARAM_STR),
					array(':sexid', 'sexid', PDO::PARAM_STR),
					),
					'insert into peserta (nama, nohp, aliasid, alamat, kota, provinsi, negara, asalgereja, jabatangereja, tgllahir, sexid, recordstatus)
					values (:nama,:nohp,:aliasid,:alamat,:kota,:provinsi,:negara,:asalgereja,:jabatangereja,:tgllahir,:sexid,1)',
					'insert into peserta (nama, nohp, aliasid, alamat, kota, provinsi, negara, asalgereja, jabatangereja, tgllahir, sexid, recordstatus)
					values (:nama,:nohp,:aliasid,:alamat,:kota,:provinsi,:negara,:asalgereja,:jabatangereja,:tgllahir,:sexid,1)');
			}
    
    /*
    if ($error == false) {
      $sql = "insert into peserta (nama, nohp, aliasid, alamat, kota, provinsi, negara, asalgereja, jabatangereja, tgllahir, sexid, recordstatus)
      values (:nama,:nohp,:aliasid,:alamat,:kota,:provinsi,:negara,:asalgereja,:jabatangereja,:tgllahir,:sexid,1)";
      $command = Yii::app()->db->createCommand($sql);
      $command->bindvalue(':nama', $nama, PDO::PARAM_STR);	
      $command->bindvalue(':nohp', $nohp, PDO::PARAM_STR);
      $command->bindvalue(':aliasid', $valiasid, PDO::PARAM_STR);
      $command->bindvalue(':alamat', $alamat, PDO::PARAM_STR);
      $command->bindvalue(':kota', $kota, PDO::PARAM_STR);
      $command->bindvalue(':provinsi', $provinsi, PDO::PARAM_STR);
      $command->bindvalue(':negara', $negara, PDO::PARAM_STR);
      $command->bindvalue(':asalgereja', $asalgereja, PDO::PARAM_STR);
      $command->bindvalue(':jabatangereja', $jabatangereja, PDO::PARAM_STR);
      $command->bindvalue(':tgllahir', $tgllahir, PDO::PARAM_STR);
      $command->bindvalue(':sexid', $sexid, PDO::PARAM_STR);
      $command->bindvalue(':recordstatus', 1, PDO::PARAM_STR);
      $command->execute();
    }
    */
	}
  
  private function getAliasID($nama) {
    $result = str_replace(' ','.',$nama);
    $sql = "select ifnull(count(1),0)
      from peserta 
      where aliasid = '".$nama."'";
    $jumlah = Yii::app()->db->createCommand($sql)->queryScalar();
    if ($jumlah > 0) {
      $result .= rand(0,1000000000);
      $this->getAliasID($result);
    }
    return $result;
  }
  public function actionRegisPeserta() {
    $datas = explode("\n",$_POST['data']);
    $transaction=Yii::app()->db->beginTransaction();
    try {
      $column = '';
      $values = '';
      $grupbaca = '';
      $aliasid = '';
      foreach ($datas as $datapeserta) {
        if (strpos($datapeserta,':') > 0) {
          $expdata = explode(':',$datapeserta);
          $expdata[0] = str_replace(' ','',$expdata[0]);
          $expdata[1] = trim($expdata[1]);
          if (($column != '') && ($expdata[0] != 'GrupBaca')) {
            $column .= ',';
            $values .= ',';
          }
          if ($expdata[0] == 'UserTelegram') {
            $column .= 'idtelegram';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'NamaLengkap') {
            $column .= 'nama';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'No.WHATSAPP') {
            $column .= 'nohp';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'AliasID') {
            $column .= 'aliasid';
            if ($expdata[1] == '') {
              $expdata[1] .= str_replace(' ','.',$expdata[1]);
            }
            $values .= "'".$this->getAliasID($expdata[1])."'";
          } else
          if ($expdata[0] == 'Alamat')  {
            $column .= 'alamat';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'Kota')  {
            $column .= 'kota';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'Provinsi') {
            $column .= 'provinsi';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'Negara') {
            $column .= 'negara';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'BeribadahDi') {
            $column .= 'asalgereja';
            $values .= "'".$expdata[1]."'";
          }
          if ($expdata[0] == 'Jabatan') {
            $column .= 'jabatangereja';
            $values .= "'".$expdata[1]."'";
          }
          if ($expdata[0] == 'TanggalLahir')  {
            $column .= 'tgllahir';
            $values .= "'".$expdata[1]."'";
          }
          if ($expdata[0] == 'JenisKelamin') {
            $column .= 'sexid';
            $values .= "'".$expdata[1]."'";
          }
          if ($expdata[0] == 'Kontak') {
            $column .= 'kontak';
            $values .= "'".$expdata[1]."'";
          }
          if ($expdata[0] == 'Note') {
            $column .= 'note';
            $values .= "'".$expdata[1]."'";
          }
          if ($expdata[0] == 'GrupBaca') {
            $grupbaca = $expdata[1];
          }
        }
      }
      $grupbacaid = 0;

      if ($grupbaca != '') {
        $sql = "select ifnull(count(1),0) from grupbaca where kodegrup = '".$grupbaca."'";
        $k = Yii::app()->db->createCommand($sql)->queryScalar();
        if ($k > 0) {
          $sql = "select grupbacaid from grupbaca where kodegrup = '".$grupbaca."'";
          $grupbacaid = Yii::app()->db->createCommand($sql)->queryScalar();
        } else {
          echo 'Kode Grup Baca tidak sesuai, tolong dicek kembali, terima kasih';
          die();
        }
      }
      
      $sqlpeserta = "insert into peserta (".$column.") values (".$values.")";
      Yii::app()->db->createCommand($sqlpeserta)->execute();

      $sql = "select last_insert_id()";
      $pesertaid = Yii::app()->db->createCommand($sql)->queryScalar();

      if ($grupbacaid > 0) {
        $sql = "insert into membergrupbaca (grupbacaid,pesertaid,recordstatus) 
          values (:grupbacaid,:pesertaid,:recordstatus)";
        $command = Yii::app()->db->createCommand($sql);
        $command->bindvalue(':grupbacaid', $grupbacaid, PDO::PARAM_STR);
        $command->bindvalue(':pesertaid', $pesertaid, PDO::PARAM_STR);
        $command->bindvalue(':recordstatus', 1, PDO::PARAM_STR);
        $command->execute();
      }

      $transaction->commit();
      echo 'Data Tersimpan';

    } catch (Exception $ex) {
      $transaction->rollback();
      echo 'Error '.$ex->getMessage();
    }
  }
	
}