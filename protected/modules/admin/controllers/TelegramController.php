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