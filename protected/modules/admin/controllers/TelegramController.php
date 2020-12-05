<?php
class TelegramController extends Controller {
  public $menuname = 'telegram';  
  public function actionReplyBot(){
    $content = file_get_contents("php://input");
    WriteTelegramLog("------------------------". PHP_EOL . $content);
    $update = json_decode($content, TRUE);
    if (!$update) {
      exit;
    }
    if (isset($update['message'])) {
      processMessage(Yii::app()->params['TelegramUrl'],Yii::app()->params['TelegramKey'],$update['message']);
    }
  }
  public function actionProses() {    
    try {
      $usertelegram = $_REQUEST['username'];
      $id = $_REQUEST['id'];
      $sql = "select ifnull(count(1),0) 
        from logchat 
        where isproses = 0 
        and logchatid = ".$id." 
        and idtelegram = '".$usertelegram."'";
      $k = Yii::app()->db->createCommand($sql)->queryScalar();
      if ($k == 0) {
        $template = getTemplateRespon('chatsudahproses');
        echo $template;
      } else 
      if ($k > 0) {
        $sql = "select logchatid,logtext,grupbaca
          from logchat 
          where isproses = 0 
          and logchatid = ".$id." 
          and idtelegram = '".$usertelegram."'";
        $logchat = Yii::app()->db->createCommand($sql)->queryRow();
        $contenfail = "";
        $contenok = '';

        $grupbaca = $logchat['grupbaca'];
        $logtext = $logchat['logtext'];
        $logchatid = $logchat['logchatid'];

        $handle = fopen($logtext, "r");
        if ($handle) {
          while (($line = fgets($handle, 4096)) !== false) {
            $line = str_replace('  ', ' ',$line);
            if (strpos($line, 'BESOK') !== false) {
            } else
            if (strpos($line, 'HARI') !== false) {
            } else
            if (strpos(strtolower($line), '# lapor') !== false) {
              $contenfail .= $line. " <b>--> *salah tulis #lapor/#report ( # lapor )*</b>\n";
            } else
            if (strpos(strtolower($line), '#lapor') !== false) {
              //format export WA: tgl jam - nohp: #lapor aliasid hari
              $rawdata = explode(' ',$line);
              if (is_array($rawdata)) {
                if ($rawdata >= 8) {
                  $tgljam = $rawdata[0].' '.str_replace('.',':',$rawdata[1]).':00';
                  $newdate = date_create_from_format('d/m/y H:i:s',$tgljam);
                  if ($newdate != false) {
                    $datetgljam = date_format($newdate,"Y/m/d H:i:s");
                    $nohp = str_replace('-','',str_replace(' ','',str_replace(':','',getStringBetween($line,'+',':'))));
                    $formatlapor = explode(' ',substr($line,strpos($line,'#')));
                    if (isDate($datetgljam) && ($nohp != '')) {
                      if (count($formatlapor) == 3) {
                        if (strpos($formatlapor[2],'-') === false) {
                          $jmlhari = 1;
                          $hari = intval($formatlapor[2]);
                        } else {
                          $arr = explode('-',preg_replace('/\s+/', '', $formatlapor[2]));
                          $jmlhari = intval($arr[1]);
                          $hari = intval($arr[0]);
                        }
                        $sql = "select ifnull(count(1),0)
                          from peserta a
                          join membergrupbaca b on b.pesertaid = a.pesertaid 
                          join grupbaca c on c.grupbacaid = b.grupbacaid 
                          where c.namagrup = '".$grupbaca."' and a.aliasid = '".$formatlapor[1]."' 
                        ";
                        $k = Yii::app()->db->createCommand($sql)->queryScalar();
                        if ($k > 0) {
                          $sql = "select a.pesertaid, c.grupbacaid 
                            from peserta a
                            join membergrupbaca b on b.pesertaid = a.pesertaid 
                            join grupbaca c on c.grupbacaid = b.grupbacaid 
                            where c.namagrup = '".$grupbaca."' and a.aliasid = '".$formatlapor[1]."'";
                          $data = Yii::app()->db->createCommand($sql)->queryRow();
                          $sql = "select ifnull(count(1),0)
                            from transharian z
                            where z.grupbacaid = ".$data['grupbacaid'];
                          $k = Yii::app()->db->createCommand($sql)->queryScalar();
                          if ($k == 0) {
                            $sql = "insert into transharian (grupbacaid) 
                              values (".$data['grupbacaid'].")";
                            Yii::app()->db->createCommand($sql)->execute();
                          }
                          $sql = "select z.transharianid
                            from transharian z
                            where z.grupbacaid = ".$data['grupbacaid'];
                          $transid = Yii::app()->db->createCommand($sql)->queryScalar();

                          $totalhari = $hari + $jmlhari;

                          for ($i = $hari; $i < $totalhari;$i++) {
                            $sql = "call inserttransharian (:vtransid,:vtgljam,:vpesertaid,:vhari,:vlogchatid)";
                            $command = Yii::app()->db->createCommand($sql);
                            $command->bindvalue(':vtransid', $transid, PDO::PARAM_STR);
                            $command->bindvalue(':vtgljam', $datetgljam, PDO::PARAM_STR);
                            $command->bindvalue(':vpesertaid', $data['pesertaid'], PDO::PARAM_STR);
                            $command->bindvalue(':vhari', $i, PDO::PARAM_STR);
                            $command->bindvalue(':vlogchatid', $id, PDO::PARAM_STR);
                            $command->execute();
                          }
                        } else {
                          $contenfail .= $line . " <b>--> User tidak terdaftar</b>\n";
                        }
                      } else {
                        $contenfail .= $line . " <b>--> Format Pelaporan tidak sesuai, #lapor AliasID HariBaca</b>\n";
                      }
                    }
                  } else {
                    $contenfail .= $line . " <b>--> Tanggal+Jam Gagal di Baca (Lihat 16 Huruf Pertama)</b>\n";
                  }
                }
              }
            }
          }
          fclose ($handle);
        } 

        $sql = "update logchat
          set isproses = 1
          where logchatid = ".$logchatid;
        Yii::app()->db->createCommand($sql)->execute();

        if ($contenfail != '') {
          $sql = "insert into logfail (logchatid,logtext,idtelegram) 
            values (:logchatid,:logtext,:idtelegram)";
          $command = Yii::app()->db->createCommand($sql);
          $command->bindvalue(':logchatid', $logchat['logchatid'], PDO::PARAM_STR);
          $command->bindvalue(':logtext', $contenfail, PDO::PARAM_STR);
          $command->bindvalue(':idtelegram', $usertelegram, PDO::PARAM_STR);
          $command->execute();
        }

        /*$template = getTemplateRespon('proses'); 
        $template = str_replace('[id]',$logchatid, $template);
        echo $template;*/
        echo 'ok';

      }
    } catch (Exception $ex) {
      WriteTelegramLog($ex->getMessage());
      echo $ex->getMessage();
    }
  }
  public function actionGetRekap() {

  }
  public function actionGetLogFail() {
    $id = (isset($_REQUEST['data'])?$_REQUEST['data']:0);
    if ($id > 0) {
      $sql = "select ifnull(count(1),0) 
        from logfail a
        join logchat b on b.logchatid = a.logchatid 
        where a.logchatid = ".$id;
      $k = Yii::app()->db->createCommand($sql)->queryScalar();
      if ($k > 0) {
        $sql = "select a.logchatid,a.transdate,a.idtelegram,a.logtext,b.grupbaca
          from logfail a
          join logchat b on b.logchatid = a.logchatid 
          where a.logchatid = ".$id;
        $datas = Yii::app()->db->createCommand($sql)->queryRow();
        $template = getTemplateRespon('logfail');
        $template = str_replace('[content]',$datas['logtext'],$template);
        $template = str_replace('[grupbaca]',$datas['grupbaca'],$template);
        echo $template;
      } else {
        echo 'id tidak tersedia';
      }
    } else {
      echo 'id harus diisi';
    }
  }
  public function actionRegisPeserta() {
    $datas = explode("\n",$_POST['data']);
    $transaction=Yii::app()->db->beginTransaction();
    try {
      $column = '';
      $values = '';
      $grupbaca = '';
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
            $column .= 'nama';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'NamaLengkap') {
            $column .= 'idtelegram';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'No.WHATSAPP') {
            $column .= 'nohp';
            $values .= "'".$expdata[1]."'";
          } else
          if ($expdata[0] == 'AliasID') {
            $column .= 'aliasid';
            $values .= "'".$expdata[1]."'";
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
  public function actionAmbilBuku(){
    $idtelegram = $_REQUEST['username'];
    $data = $_REQUEST['data'];
    $datas = explode(',',$data);

    $kodebuku = '';
    $hari = '';

    foreach ($datas as $datapeserta) {
      $expdata = explode('=',$datapeserta);
      if ($expdata[0] == "kodebuku") {
        $kodebuku = $expdata[1];
      }
      if ($expdata[0] == "hari") {
        $hari = $expdata[1];
      }
    }

    $sql = "select url 
      from bukubacaan a
      join bukubacaandetail b on b.bukubacaanid = a.bukubacaanid 
      where kodebuku = '".$kodebuku."' and hari = ".$hari;
    $url = Yii::app()->db->createCommand($sql)->queryScalar();

    echo $url;
  }
  public function actionGetWeather() {
    /*
    {"coord":{"lon":106.99,"lat":-6.23},
    "weather":{"id":801,"main":"Clouds","description":"few clouds","icon":"02d"},
    "base":"stations",
    "main":{"temp":308.22,"feels_like":307.33,"temp_min":307.15,"temp_max":309.26,"pressure":1008,"humidity":36},
    "visibility":6000,
    "wind":{"speed":5.1,"deg":50},
    "clouds":{"all":20},"dt":1598941073,"sys":{"type":1,"id":9383,"country":"ID","sunrise":1598914350,"sunset":1598957521},
    "timezone":25200,"id":1649378,"name":"Bekasi","cod":200}
    */
    $cityname  = $_REQUEST['city'];
    $url = "http://api.openweathermap.org/data/2.5/weather?q=".$cityname.",id&APPID=3356a431f18ceba2b1a8759b842ca0b3&units=metric";
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $output = curl_exec($ch);
    curl_close($ch);
    $output = json_decode($output,true);
    $return .= "<b>Kondisi Cuaca Kota ".strtoupper($cityname)."</b>\n\n";
    $return .= "<b>Koordinat</b>\n";
    $return .= '<b>Longitude :</b>'.$output['coord']['lon']."\n";
    $return .= '<b>Latitude :</b>'.$output['coord']['lat']."\n\n";
    $return .= "<b>Cuaca </b>\n";
    $return .= "<b>Awan </b> : ".$output['weather']['description']."\n";
    $return .= "<b>Kondisi </b> : ".$output['clouds']['all']."\n\n";
    $return .= "<b>Suhu </b>\n";
    $return .= "<b>Suhu (tercatat) : </b>".$output['main']['temp']." oC\n";
    $return .= "<b>Suhu (dirasakan) : </b>".$output['main']['feels_like']." oC\n";
    $return .= "<b>Suhu (Min) : </b>".$output['main']['temp_min']." oC\n";
    $return .= "<b>Suhu (Max) : </b>".$output['main']['temp_max']." oC\n";
    $return .= "<b>Tekanan : </b>".$output['main']['pressure']." hPa\n";
    $return .= "<b>Kelembaban : </b>".$output['main']['humidity']." %\n";
    $return .= "<b>Jangkauan Pandangan : </b>".$output['visibility']." m\n\n";
    $return .= "<b>Angin </b>\n";
    $return .= "<b>Kecepatan : </b>".$output['wind']['speed']." m/s\n";
    $return .= "<b>Arah : </b>".$output['wind']['deg']." derajat\n";
    echo $return;
  }
  public function actionCalc() {
    $hitung  = $_REQUEST['hitung'];
    $hitung = str_replace('x','*',$hitung);
    $hitung = eval('return '.$hitung.';');
    echo $hitung;
  }
}