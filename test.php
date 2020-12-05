<?php
function isDate($value) {
  if (!$value) {
    return false;
  } else {
    $date = date_parse($value);
    if($date['error_count'] == 0 && $date['warning_count'] == 0){
      return checkdate($date['month'], $date['day'], $date['year']);
    } else {
      return false;
    }
  }
}
function getStringBetween($string, $start, $end){
  $string = ' ' . $string;
  $ini = strpos($string, $start);
  if ($ini == 0) return '';
  $ini += strlen($start);
  $len = strpos($string, $end, $ini) - $ini;
  return substr($string, $ini, $len);
}
function GetLogFail($content){
  $contenok = '';
  $contenfail = '';
  foreach(preg_split("/((\r?\n)|(\r\n?))/", $content) as $line){
    if (strpos(strtolower($line), '#lapor') !== false) {
      //format export WA: tgl jam - nohp: #lapor aliasid hari
      $rawdata = explode(' ',$line);
      if (is_array($rawdata)) {
        if ($rawdata > 7) {
          $tgljam = $rawdata[0].' '.str_replace('.',':',$rawdata[1]).':00';
          $datetgljam = date_format(date_create_from_format('d/m/y H:i:s',$tgljam),"Y/m/d H:i:s");
          $nohp = str_replace('-','',str_replace(' ','',str_replace(':','',getStringBetween($line,'+',':'))));
          $formatlapor = explode(' ',substr($line,strpos($line,'#')));
          if (isDate($datetgljam) && (count($formatlapor) == 3) && ($nohp != '')) {
            $contenok .= $line. PHP_EOL;
          } else {
            $contenfail .= $line . PHP_EOL;
          }
        } else {
          $contenfail .= $line . PHP_EOL;
        }
      } else {
        $contenfail .= $line . PHP_EOL;
      }
    } else {
      $contenfail .= $line . PHP_EOL;
    }
  } 
  echo $contenok;
}

$content = '26/11/20 23.10 - Pesan dan panggilan terenkripsi secara end-to-end. Tidak seorang pun di luar chat ini yang dapat membaca atau mendengarkannya, bahkan WhatsApp. Ketuk untuk info selengkapnya.
25/10/20 10.20 - ‎+62 818-0722-8020 telah membuat grup "GBA GC D7/PB NORMAL 5PSL"
26/11/20 23.10 - ‎+62 821-6120-0555 menambahkan Anda
26/11/20 23.18 - +62 813-8900-1003: #lapor adelinass 30
26/11/20 23.47 - +60 19-293 8380: #lapor dennisw 30
27/11/20 00.01 - +62 878-7200-1228: #lapor wendrit 29-3
27/11/20 03.26 - +62 812-7915-940: #lapor Sungmielan 31
27/11/20 03.59 - +60 10-236 7488: #lapor mawarpi  31
27/11/20 03.59 - +62 813-9914-0368: Pesan ini telah dihapus
27/11/20 03.59 - +60 11-1477 5379: #lapor welnnyt 31
27/11/20 04.00 - +60 11-1481 7715: #lapor lennyta 31
27/11/20 04.00 - +62 852-1616-8895: #lapor yosanties 31
27/11/20 04.00 - +60 19-914 6021: #lapor jotimoie 31
27/11/20 04.00 - +60 13-854 7142: #lapor suzilahs 31
27/11/20 04.00 - +60 11-3614 9739: #lapor mainehk 31
27/11/20 04.00 - +60 19-704 5536: #lapor lorag 31
27/11/20 04.00 - +62 853-6177-3026: #lapor etikan 31
27/11/20 04.00 - +60 19-878 2764: #lapor dennahd 31
27/11/20 04.00 - +62 823-6069-2715: #lapor rezahg 31
27/11/20 04.00 - +60 16-575 0810: #lapor analizat 31
27/11/20 04.00 - +1 (240) 543-1474: #lapor arlindaa 31
27/11/20 04.00 - +62 813-9914-0368: Pesan ini telah dihapus
27/11/20 04.00 - +60 12-806 9472: Menunggu pesan ini
27/11/20 04.00 - +60 19-812 4967: #lapor sainik 31
27/11/20 04.04 - +62 813-3922-9674: #lapor lusiana2 31
27/11/20 04.04 - +62 813-9914-0368: #lapor claraad 31
27/11/20 04.04 - +62 821-1335-2115: #lapor tyasm 31
27/11/20 04.10 - +60 19-856 3205: #lapor rusinek 31
27/11/20 04.19 - +62 823-6612-7459: #lapor unimal 21
27/11/20 04.19 - +62 815-4243-1044: #lapor ruthsi 31
27/11/20 04.19 - +60 14-373 9706: #lapor julianasu 31
27/11/20 04.24 - +62 878-8361-8084: #lapor sriaton 31
27/11/20 04.28 - +60 19-705 7520: #lapor malamu 31
27/11/20 04.29 - +60 17-818 7677: #lapor lilianm 31
27/11/20 04.32 - +60 11-6460 1204: #lapor mariadalosin 31
27/11/20 04.32 - +60 10-948 9391: Pesan ini telah dihapus
27/11/20 04.33 - +60 10-948 9391: #lapor  caroline2  31
27/11/20 04.36 - +62 813-2543-3435: #lapor lidias 31
27/11/20 04.43 - +60 19-580 0326: #lapor judiths 31
27/11/20 04.44 - +60 11-3507 6910: #lapor margaritaa 31
27/11/20 04.48 - +60 14-918 6118: #lapor malindam 31
27/11/20 04.51 - +62 821-8167-8400: #lapor susiana 31
27/11/20 04.58 - +60 13-543 8352: #lapor brendasy 31
27/11/20 05.13 - +62 812-2949-3025: #Lapor sudarh 31
27/11/20 05.29 - +60 13-257 1904: Shalom..selamat pagi ,
Anak2 Tuhan yang setia.
Bangkitlah, menjadi teranglah sebab terangMu datang kemulian Tuhan terbit atasmu.
Jom lihar yg sudah lapor.

';

print_r(GetLogfail($content));
