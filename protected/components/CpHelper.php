<?php
define('PICTURE_HAND_UP','👆');
define('PICTURE_CHAMPION','🏆⁠⁠⁠⁠');
define('PICTURE_FINISH','✅⁠⁠⁠⁠');
define('PICTURE_NOT_FINISH','🏃⁠⁠⁠⁠');
define('PICTURE_PESERTA','👨‍👩‍👧‍👦⁠⁠⁠⁠');
define('PICTURE_FLAG_NOT_ACTIVE','🏳️⁠⁠⁠⁠');
define('PICTURE_BOOK','📖⁠⁠⁠⁠');
define('PICTURE_LIGHT','🚥⁠⁠⁠⁠');
define('PICTURE_FLAG_FINISH','🏁⁠⁠⁠⁠');
define('PICTURE_100','💯⁠⁠⁠⁠');
define('PICTURE_WOMAN','👩⁠⁠⁠⁠');
define('PICTURE_MAN','👨⁠⁠⁠⁠');
define('PICTURE_LAST_FINISH','☑⁠⁠⁠⁠');
define('PICTURE_LATE','🏃⁠⁠⁠⁠');
define('PICTURE_HAND_LEFT','👉🏽⁠⁠⁠⁠');
define('PICTURE_LOVE','💖');
define('PICTURE_FIRE','🔥');
define('PICTURE_CLAP','👏');
define('PICTURE_CROWN','👑');
define('PICTURE_PRAY','🤲');
define('PICTURE_SUN','☀️');
define('PICTURE_STAR','🌟');
define('PICTURE_VIA_HP','📲');
define('PICTURE_RAINBOW','🌈');
define('PICTURE_DRINK','🍷');
define('PICTURE_FLOWER','💐');
define('PICTURE_PLEASE','🙏');
define('PICTURE_ANGEL','😇');
define('PICTURE_GOOD_JOB','👍');
define('PICTURE_SMILE','😊');
define('PICTURE_SMILE_TEETH','😁');

define('ARRAY_KEY_FC_LOWERCASE', 25); //FOO => fOO
define('ARRAY_KEY_FC_UPPERCASE', 20); //foo => Foo
define('ARRAY_KEY_UPPERCASE', 15); //foo => FOO
define('ARRAY_KEY_LOWERCASE', 10); //FOO => foo
define('ARRAY_KEY_USE_MULTIBYTE', true); //use mutlibyte functions
/**
 * Function for Input Filtering from POST, GET, or Ordinary Variable
 * @param type $datatype
 * 0 : Ordinary Variable
 * 1 : Input POST Single Data
 * 2 : Input GET Single Data
 * 3 : Input REQUEST Single Data
 * @param type $var
 * @param type $varfilter
 * FILTER_DEFAULT
 * FILTER_SANITIZE_STRING
 * FILTER_SANITIZE_URL
 * FILTER_SANITIZE_EMAIL
 */
function filterinput($datatype, $var='', $varfilter = FILTER_SANITIZE_STRING) {
	switch ($datatype) {
		case 1:
			$retvar	 = stripslashes(trim(filter_var(filter_input(INPUT_POST, $var,
							$varfilter))));
			break;
		case 2:
			$retvar	 = stripslashes(trim(filter_var(filter_input(INPUT_GET, $var,
							$varfilter))));
			break;
		case 3:
			$retvar	 = stripslashes(trim(filter_var(filter_input(INPUT_REQUEST, $var,
							$varfilter))));
			break;
    case 4:
      $retvars = $_POST[$var];
      $retvar = array();
      foreach ($retvars as $i) {
        $s = filter_var($i, $varfilter);
        array_push($retvar, $s);
      }
      break;
		default:
			$retvar	 = stripslashes(trim(filter_var($var, $varfilter)));
			break;
	}
	return $retvar;
}
/**
 * Function for Cryptography with Random Hexdec, Bin2hex and Open SSL Random
 * @param type $min
 * @param type $max
 * @return type
 */
function crypto_rand_secure($min, $max) {
	$range	 = $max - $min;
  if ($range < 1) { return $min; } // not so random...
	$log		 = ceil(log($range, 2));
	$bytes	 = (int) ($log / 8) + 1; // length in bytes
	$bits		 = (int) $log + 1; // length in bits
	$filter	 = (int) (1 << $bits) - 1; // set all lower bits to 1
	do {
		$rnd = (hexdec(bin2hex(openssl_random_pseudo_bytes($bytes)))) & $filter;
	} while ($rnd > $range);
	return $min + $rnd;
}
/**
 * Function for Generate Token
 * @param type $length
 * @return string
 */
function getToken($length) {
	$token				 = "";
	$codeAlphabet	 = "ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	$codeAlphabet	 .= "abcdefghijklmnopqrstuvwxyz";
	$codeAlphabet	 .= "0123456789";
	$max					 = strlen($codeAlphabet);
	for ($i = 0; $i < $length; $i++) {
		$token .= $codeAlphabet[crypto_rand_secure(0, $max - 1)];
	}
	return $token;
}
/**
 * Function for Generate Token with Checking from Database
 * @param type $length
 * @return type
 */
function getTokenDB($length) {
	$token = getToken($length);
	$sql	 = "select ifnull(count(1),0)
		from useraccess
		where authkey = '".$token."'";
	$data	 = Yii::app()->db->createCommand($sql)->queryScalar();
	if ($data > 0) {
		getTokenDB($length);
	} else {
		return $token;
	}
}
/**
 * Function for Change Character Case Oracle Database
 * @param array $array
 * @param type $case
 * @param type $useMB
 * @param type $mbEnc
 * @return array
 */
function array_change_key_case_ext(array $array, $case = 10, $useMB = false,
																	 $mbEnc = 'UTF-8') {
	$newArray = array();
	if ($useMB === false) {
		$function = 'strToUpper'; //default
		switch ($case) {
			case 25:
				if (!function_exists('lcfirst')) {
					$function = create_function('$input',
						'
							return strToLower($input[0]) . substr($input, 1, (strLen($input) - 1));
					');
				} else {
					$function = 'lcfirst';
				}
				break;
			case 20:
				$function	 = 'ucfirst';
				break;
			case 10:
				$function	 = 'strToLower';
		}
	} else {
		switch ($case) {
			case 25:
				$function	 = create_function('$input',
					'
						return mb_strToLower(mb_substr($input, 0, 1, \''.$mbEnc.'\')) . 
								mb_substr($input, 1, (mb_strlen($input, \''.$mbEnc.'\') - 1), \''.$mbEnc.'\');
				');
				break;
			case 20:
				$function	 = create_function('$input',
					'
						return mb_strToUpper(mb_substr($input, 0, 1, \''.$mbEnc.'\')) . 
								mb_substr($input, 1, (mb_strlen($input, \''.$mbEnc.'\') - 1), \''.$mbEnc.'\');
				');
				break;
			case 15:
				$function	 = create_function('$input',
					'
						return mb_strToUpper($input, \''.$mbEnc.'\');
				');
				break;
			default: //case 10:
				$function	 = create_function('$input',
					'
						return mb_strToLower($input, \''.$mbEnc.'\');
				');
		}
	}

	foreach ($array as $key => $value) {
		if (is_array($value)) { //$value is an array, handle keys too
				$newArray[$function($key)] = array_change_key_case_ext($value, $case, $useMB);
    }
    elseif (is_string($key)) { $newArray[$function($key)] = $value; }
    else { $newArray[$key] = $value; } //$key is not a string
	}
	return $newArray;
}
/**
 * Function for Displaying SEO
 * @param type $metatag
 * @param type $description
 */
function display_seo($metatag, $description, $pageTitle) {
  $description = truncateword(getparameter('sitetitle').' '.$pageTitle,100);
  echo '<meta charset="utf-8">'."\n";
  echo '<meta http-equiv="X-UA-Compatible" content="IE=edge">'."\n";
	echo '<meta name="viewport" content="width=device-width, initial-scale=1.0">'."\n";
	echo '<meta name="description" content="'.$description.'">'."\n";
	echo '<meta name="generator" content="Capella CMS 1.0.0">';
	echo '<title>'.getparameter('sitetitle').' - '.$pageTitle.'</title>'."\n";
	echo '<meta property="og:site_name" content="'.getparameter('sitename').'">'."\n";
	echo '<meta property="og:description" content="'.$description.'">'."\n";
	if (is_array($metatag)) {
		$s = count($metatag);
		if ($s > 0) {
			foreach ($metatag as $meta) {
				echo '<meta property="article:tag" content="'.$meta.'">'."\n";
			}
		}
	}
}
/**
 * Function for Truncating Word with Length
 * @param type $text
 * @param type $length
 * @param type $ending
 * @param type $exact
 * @param type $considerHtml
 * @return string
 */
function truncateword($text, $length, $ending = "...", $exact = false,
											$considerHtml = true) {
	if ($considerHtml) {
		if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
			return $text;
		}
    $lines = '';
		preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
		$total_length	 = strlen($ending);
		$open_tags		 = array();
		$truncate			 = '';
    $tag_matchings = '';
    $entities = '';
		foreach ($lines as $line_matchings) {
			if (!empty($line_matchings[1])) {
				if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is',
						$line_matchings[1])) {
					
				} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1],
						$tag_matchings)) {
					$pos = array_search($tag_matchings[1], $open_tags);
					if ($pos !== false) {
						unset($open_tags[$pos]);
					}
				} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1],
						$tag_matchings)) {
					array_unshift($open_tags, strtolower($tag_matchings[1]));
				}
				$truncate .= $line_matchings[1];
			}
			$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i',
					' ', $line_matchings[2]));
			if ($total_length + $content_length > $length) {
				$left						 = $length - $total_length;
				$entities_length = 0;
				if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i',
						$line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
					foreach ($entities[0] as $entity) {
						if ($entity[1] + 1 - $entities_length <= $left) {
							$left--;
							$entities_length += strlen($entity[0]);
						} else {
							break;
						}
					}
				}
				$truncate .= substr($line_matchings[2], 0, $left + $entities_length);
				break;
			} else {
				$truncate			 .= $line_matchings[2];
				$total_length	 += $content_length;
			}
			if ($total_length >= $length) {
				break;
			}
		}
	} else {
		if (strlen($text) <= $length) {
			return $text;
		} else {
			$truncate = substr($text, 0, $length - strlen($ending));
		}
	}
	if (!$exact) {
		$spacepos = strrpos($truncate, ' ');
		if (isset($spacepos)) {
			$truncate = substr($truncate, 0, $spacepos);
		}
	}
	$truncate .= $ending;
	if ($considerHtml) {
		foreach ($open_tags as $tag) {
			$truncate .= '</'.$tag.'>';
		}
	}
	return $truncate;
}
/**
 *
 * @param type $htmlStr
 * @return type
 */
function parseToXML($htmlStr) {
	$xmlStr	 = str_replace("&", '&amp;',str_replace("'", '&#39;',str_replace('"', '&quot;', str_replace('>', '&gt;', str_replace('<', '&lt;', $htmlStr)))));
	return $xmlStr;
}
/**
 * Function for change from number to character in Indonesian format
 * @param type $value
 * @return type
 */
function eja($value) {
	$number				 = str_replace(',', '', $value);
	$before_comma	 = trim(to_word($number));
	$after_comma	 = trim(comma($number));
	$results			 = str_replace('koma nol', '',str_replace('nol nol', '',str_replace('nol nol nol', '',str_replace('nol nol nol', '',str_replace('nol nol nol nol nol', '',str_replace('nol nol nol nol nol', '', $before_comma.$after_comma))))));
	return ucwords($results);
}
/**
 * 
 * @param type $number
 * @return string
 */
function to_word($number) {
	$words			 = '';
	$arr_number	 = array(
		'',
		'satu',
		'dua',
		'tiga',
		'empat',
		'lima',
		'enam',
		'tujuh',
		'delapan',
		'sembilan',
		'sepuluh',
		'sebelas'
	);
	switch (true) {
		case ($number == 0) :
			$words = ' ';
			break;
		case (($number > 0) && ($number < 12)) :
			$words = ' '.$arr_number[$number];
			break;
		case ($number < 20) :
			$words = to_word($number - 10).' belas';
			break;
		case ($number < 100) :
			$words = to_word($number / 10).' puluh '.to_word($number % 10);
			break;
		case ($number < 200) :
			$words = 'seratus '.to_word($number - 100);
			break;
		case ($number < 1000) :
			$words = to_word($number / 100).' ratus '.to_word($number % 100);
			break;
		case ($number < 2000) :
			$words = 'seribu '.to_word($number - 1000);
			break;
		case ($number < 1000000) :
			$words = to_word($number / 1000).' ribu '.to_word($number % 1000);
			break;
		case ($number < 1000000000) :
			$words = to_word($number / 1000000).' juta '.to_word($number % 1000000);
			break;
		case ($number < 1000000000000) :
			$words = to_word($number / 1000000000).' milyar '.to_word($number % 1000000000);
			break;
		case ($number < 1000000000000000) :
			$words = to_word($number / 1000000000000).' trilyun '.to_word($number % 1000000000000);
			break;
		case ($number < 1000000000000000000) :
			$words = to_word($number / 1000000000000000).' bilyun '.to_word($number % 1000000000000000);
			break;
		default :
			$words = 'undefined';
	}
	return $words;
}
/**
 * 
 * @param type $number
 * @return string
 */
function comma($number) {
	$after_comma = stristr($number, '.');
	$results		 = ' ';
	if ($after_comma == true) {
		$results		 = ' koma ';
		$arr_number	 = array(
			'nol',
			'satu',
			'dua',
			'tiga',
			'empat',
			'lima',
			'enam',
			'tujuh',
			'delapan',
			'sembilan'
		);
		$length			 = strlen($after_comma);
		$i					 = 1;
		while ($i < $length) {
			$get		 = substr($after_comma, $i, 1);
			$results .= ' '.$arr_number[$get];
			$i++;
		}
	}
	return $results;
}
/**
 * Function for Convert from Boolean to String with default value
 * @param type $id
 * @return string
 */
function booltostr($id) {
	if ($id == false) {
		return 'false';
	} else
	if ($id == true) {
		return 'true';
	}
}
/**
 * Function for Convert from Integer to Boolean with default value
 * @param type $id
 * @return boolean
 */
function inttobool($id) {
	if ($id === 0) {
		return false;
	} else
	if ($id === 1) {
		return true;
	}
}
/**
 * Function for Convert from Integer to String with default value
 * @param type $id
 * @return string
 */
function inttostr($id) {
	if ($id == 0) {
		return 'Not Active';
	} else
	if ($id == 1) {
		return 'Active';
	}
}
/**
 * 
 * @param type $id
 * @return int
 */
function strtoint($id) {
	if (strtolower($id) == "active") {
		return 1;
	} else
	if (strtolower($id) == "not active") {
		return 0;
	}
}
/**
 * 
 * @param type $id
 * @return int
 */
function booltoint($id) {
	if ($id === false) {
		return 0;
	} else if ($id === true) {
		return 1;
	}
}
/**
 * 
 * @param type $quecommand
 * @param type $value
 * @return string
 */
function TranslateQuery($quecommand, $value) {
	$ret = '';
	switch ($quecommand) {
		case 'limit' :
			switch (Yii::app()->db->driverName) {
				case 'oci' :
					$ret = ' and rownum = '.$value;
					break;
				default :
					$ret = ' limit '.$value;
			}
			break;
	}
	return $ret;
}
/**
 *
 * @param type $datavalidate
 * @return boolean
 */
function ValidateData($datavalidate) {
	$error = false;
	foreach ($datavalidate as $x) {
		switch ($x[1]) {
			case 'email':
				$data	 = filterinput(1, $x[0], FILTER_SANITIZE_EMAIL);
				break;
			case 'integer':
				$data	 = filterinput(1, $x[0], FILTER_SANITIZE_NUMBER_INT);
				break;
			default:
				$data	 = filterinput(1, $x[0], FILTER_SANITIZE_STRING);
				break;
		}
		if (($data === false) || ($data === null)) {
			$error = true;
			GetMessage('error', $x[2]);
		}
	}
	return $error;
}
/**
 * 
 * @param type $FilePath
 * @param type $OldText
 * @param type $NewText
 * @return string
 */
function replace_in_file($FilePath, $OldText, $NewText) {
	$Result = array('isError' => true, 'message' => '');
	if (file_exists($FilePath) === TRUE) {
		if (is_writeable($FilePath)) {
			try {
				$FileContent = str_replace($OldText, $NewText, file_get_contents($FilePath));
				if (file_put_contents($FilePath, $FileContent) > 0) {
					$Result['isError'] = false;
				} else {
					$Result['message'] = 'Error while writing file';
				}
			} catch (Exception $e) {
				$Result['message'] = 'Error : '.$e;
			}
		} else {
			$Result['message'] = 'File '.$FilePath.' is not writable !';
		}
	} else {
		$Result['message'] = 'File '.$FilePath.' does not exist !';
	}
	return $Result;
}
/**
 * Function for Select Data with some parameter and securing parameter
 * @param type $selecttype
 * @param type $datatype
 * @param type $params
 * @param type $sqlinsert
 * @return type
 */
function SelectCommand($selecttype, $datatype, $params, $sqlinsert) {
	$connection	 = Yii::app()->db;
	$data				 = null;
	try {
		$command = $connection->createCommand($sqlinsert);
		foreach ($params as $data) {
			$command->bindvalue($data[0],
				filterinput($datatype, $data[1], FILTER_SANITIZE_STRING), $data[2]);
		}
		switch ($selecttype) {
			case 1:
				$data	 = $command->queryRow();
				break;
			case 1:
				$data	 = $command->queryAll();
				break;
			default:
				$data	 = $command->queryScalar();
				break;
		}
		return $data;
	} catch (Exception $ex) {
		GetMessage('error', $ex->getMessage());
	}
}
/**
 * Function for Insert, Update, Delete with parameter and securing it
 * @param type $datatype
 * 0 : Ordinary Variable
 * 1 : POST Variable
 * 2 : GET Variable
 * @param type $menuname
 * Menu Name
 * @param type $pk
 * Primary Key of Table
 * @param type $params
 * Parameters of Insert or Update
 * @param type $sqlinsert
 * Query for Insert
 * @param type $sqlupdate
 * Query for Update
 */
function ModifyCommand($datatype, $menuname, $pk, $params, $sqlinsert = '',
											 $sqlupdate = '', $isLog = true) {
	$connection	 = Yii::app()->db;
	$transaction = $connection->beginTransaction();
	try {
		$id			 = filterinput($datatype, $pk, FILTER_SANITIZE_NUMBER_INT);
		$sql		 = (($id !== '') ? (($sqlupdate !== '') ? $sqlupdate : $sqlinsert) : $sqlinsert);
		$command = $connection->createCommand($sql);
		(($id !== '') ? $command->bindvalue($params[0][0], $id, $params[0][2]) : null);
		$i			 = 0;
		foreach ($params as $data) {
			if ($i > 0) {
				if ($data[1] !== 'vcreatedby') {
					$cmd = ((filterinput(1, $data[1]) !== '') ? filterinput(1,
							$data[1]) : null);
				} else {
					$cmd = Yii::app()->user->id;
				}
				$command->bindvalue($data[0], $cmd, $data[2]);
			}
			$i++;
		}
		$command->execute();
		$transaction->commit();
		if ($isLog == true) {
			Inserttranslog($command, $id, $menuname);
		}
		getmessage('success', 'alreadysaved');
	} catch (Exception $ex) {
		$transaction->rollback();
		getmessage('error', $ex->getMessage());
	}
}
/**
 *
 * @return type
 */
function getCompany() {
	$username = filter_input(INPUT_POST, 'username');
	if (($username == '') || ($username == null)) {
		$username = Yii::app()->user->name;
	}
	$company = Yii::app()->db->createCommand(
			"select companyid,companyname
			from company a
			where companyid in
			(
				select gm.menuvalueid from groupaccess c
				inner join usergroup d on d.groupaccessid = c.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				inner join groupmenuauth gm on gm.groupaccessid = c.groupaccessid
				inner join menuauth ma on ma.menuauthid = gm.menuauthid
				where upper(e.username)=upper('".$username."')
					and upper(ma.menuobject) = upper('company')
			)
			limit 1
			")->queryRow();
	return $company;
}
/**
 * 
 * @param type $isadmin
 */
function getTheme($isadmin = false, $module = '') {
	$theme = Yii::app()->theme;
	if ($isadmin === false) {
		if ($module !== '') {
			$dependency				 = new CDbCacheDependency("select count(moduleid) from modules a
					inner join theme b on b.themeid = a.themeid
					where modulename = '".$module."'");
			$theme						 = Yii::app()->db->cache(1000, $dependency)->createCommand(
					"select themename
					from modules a
					inner join theme b on b.themeid = a.themeid
					where modulename = '".$module."'")->queryRow();
			Yii::app()->theme	 = $theme['themename'];
		} else {
			Yii::app()->theme = $theme;
		}
	} else {
		$dependency				 = new CDbCacheDependency("select count(moduleid) from modules a
					inner join theme b on b.themeid = a.themeid
					where modulename = '".$module."'");
		$theme						 = Yii::app()->db->cache(1000, $dependency)->createCommand(
				"select themename
				from modules a
				inner join theme b on b.themeid = a.themeid
				where modulename = 'admin'")->queryRow();
		Yii::app()->theme	 = $theme['themename'];
	}
}
/**
 *
 * @param type $menuname
 * @return type
 */
function getMenuModule($menuname = 'null') {
	$sql = "select a.modulename
			from modules a
			join menuaccess b on b.moduleid = a.moduleid
			where b.menuname = '".$menuname."'";
	return Yii::app()->db->createCommand($sql)->queryScalar().'/'.$menuname;
}
/**
 * 
 * @return type
 */
function getMyID() {
	$id = Yii::app()->db->createCommand("select useraccessid 
		from useraccess
		where username = '".Yii::app()->user->id."'")->queryRow();
	return $id['useraccessid'];
}
/**
 * 
 * @param type $dir
 */
function rrmdir($dir) {
	CFileHelper::removeDirectory($dir);
}
/**
 * 
 * @return type
 */
function getInboxLimit() {
	$dependency	 = new CDbCacheDependency("select count(userinboxid) from userinbox a
			inner join useraccess b on b.useraccessid = a.useraccessid
			inner join useraccess c on c.useraccessid = a.fromuserid
			where b.username = '".Yii::app()->user->id."'");
	$sql				 = "select b.username,c.username as fromusername,a.description,a.senddate,a.recordstatus
			from userinbox a
			inner join useraccess b on b.useraccessid = a.useraccessid
			inner join useraccess c on c.useraccessid = a.fromuserid
			where b.username = '".Yii::app()->user->id."'
			limit 5 ";
	return Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
}
/**
 *
 * @return string
 */
function getUserGroups() {
	$sql	 = "select c.groupname
			from useraccess a
			join usergroup b on b.useraccessid = a.useraccessid
			join groupaccess c on c.groupaccessid = b.groupaccessid
			where username = '".Yii::app()->user->id."'";
	$rows	 = Yii::app()->db->createCommand($sql)->queryAll();
	$grups = '';
	foreach ($rows as $row) {
		$grups .= $row['groupname'].',';
	}
	return $grups;
}
/**
 * 
 * @return type
 */
function getUserData() {
	$sql = "select email,phoneno,realname,userphoto
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			where username = '".Yii::app()->user->id."'";
	$row = Yii::app()->db->createCommand($sql)->queryRow();
	return $row;
}
/**
 * 
 * @return type
 */
function getUserAllMenu($username = '') {
	if ($username == '') {
		$username = Yii::app()->user->id;
	}
	$dependency	 = new CDbCacheDependency("SELECT count(d.menuaccessid)
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			inner join modules e on e.moduleid = d.moduleid
			where c.isread = 1 and username = '".$username."'
			order by d.sortorder asc");
	$sql				 = "select distinct d.menuurl,d.menuaccessid,d.menuname,d.menutitle,d.description,
				e.modulename,d.parentid
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			inner join modules e on e.moduleid = d.moduleid
			where c.isread = 1 and username = '".$username."'
			order by d.sortorder asc";
	$row				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	return $row;
}
/**
 * 
 * @return type
 */
function getUserSuperMenu($username = '') {
	if ($username == '') {
		$username = Yii::app()->user->id;
	}
	$dependency	 = new CDbCacheDependency("SELECT count(d.menuaccessid)
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			inner join modules e on e.moduleid = d.moduleid
			where c.isread = 1 and d.parentid is null and username = '".$username."'
			order by d.sortorder asc");
	$sql				 = "select distinct d.menuurl,d.menuaccessid,d.menuname,d.menutitle,d.description,
				(select count(1) from menuaccess e where e.parentid = d.menuaccessid) as jumlah,
				e.modulename
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			inner join modules e on e.moduleid = d.moduleid
			where c.isread = 1 and d.parentid is null and username = '".$username."'
			order by d.sortorder asc";
	$row				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	return $row;
}
/**
 * 
 * @param type $id
 * @return type
 */
function getUserMenu($id, $username = '') {
	if ($username == '') {
		$username = Yii::app()->user->id;
	}
	$dependency	 = new CDbCacheDependency("SELECT count(d.menuaccessid)
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			inner join modules e on e.moduleid = d.moduleid
			where c.isread = 1 and d.parentid = ".$id." and username = '".$username."'
			order by d.sortorder asc");
	$sql				 = "select distinct d.menuurl,d.menuname,d.menutitle,d.description,e.modulename
			from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			inner join modules e on e.moduleid = d.moduleid
			where c.isread = 1 and d.parentid = ".$id." and username = '".$username."'
			order by d.sortorder asc";
	$row				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryAll();
	return $row;
}
/**
 * 
 * @param type $paramname
 * @return type
 */
function GetParameter($paramname) {
	try {
		$sql				 = "select paramvalue ".
			" from parameter a ".
			" where paramname = '".$paramname."'";
		$dependency	 = new CDbCacheDependency('SELECT count(paramid) FROM parameter');
		$menu				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
		return $menu['paramvalue'];
	} catch (CDbException $ex) {
		return $ex->getMessage();
	}
}
/**
 *
 * @param type $status
 * @param type $catalogname
 */
function GetMessage($status, $catalogname) {
	/*$a = substr_count($catalogname, 'uq_');
	if ($a > 0) {
		$catalogname = 'duplicateentry';
	}
	$b = substr_count($catalogname, 'null');
	if ($b > 0) {
		$catalogname = 'notallownull';
	}
	$c = substr_count($catalogname, 'Integrity');
	if ($c > 0) {
		$catalogname = 'relationerror';
	}
	$d = substr_count($catalogname, 'Workflow tidak sesuai, silahkan kontak Admin');
	if ($d > 0) {
		$catalogname = 'Workflow tidak sesuai, silahkan kontak Admin';
	}*/
	echo CJSON::encode(array(
		'status' => $status,
		'msg' => getcatalog($catalogname)
	));
	Yii::app()->end();
}
/**
 * 
 * @param type $menuname
 * @return type
 */
function GetCatalog($menuname) {
	try {
		if (Yii::app()->user->id !== null) {
			$dependency	 = new CDbCacheDependency("SELECT count(catalogsysid)
					from catalogsys a
					inner join useraccess b on b.languageid = a.languageid
					where catalogname = '".$menuname."' and b.username = '".Yii::app()->user->id."'");
			$sql				 = "select catalogval as katalog
					from catalogsys a
					inner join useraccess b on b.languageid = a.languageid
					where catalogname = '".$menuname."' and b.username = '".Yii::app()->user->id."'";
		} else {
			$dependency	 = new CDbCacheDependency("SELECT count(catalogsysid)
					from catalogsys a
					inner join useraccess b on b.languageid = a.languageid
					where catalogname = '".$menuname."' and b.username = 'guest'");
			$sql				 = "select catalogval as katalog
					from catalogsys a
					inner join useraccess b on b.languageid = a.languageid
					where catalogname = '".$menuname."' and b.username = 'guest'";
		}
		$menu = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
		if ($menu != null) {
			return $menu;
		} else {
			return $menuname;
		}
	} catch (CDbException $ex) {
		return $ex->getMessage();
	}
}
/**
 * Function to Check Access
 * @param type $menuname
 * @param type $menuaction
 * @param type $username
 * @return boolean
 */
function CheckAccess($menuname, $menuaction, $username = '') {
	$baccess = false;
	if ($username == '') {
		$dependency	 = new CDbCacheDependency("select count(d.menuaccessid) from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			where username = '".Yii::app()->user->id."' and menuname = '".$menuname."'");
		$sql				 = "select `".$menuaction.
			"`	from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			where username = '".Yii::app()->user->id."' and menuname = '".$menuname."' limit 1";
	} else {
		$dependency	 = new CDbCacheDependency("select count(d/menuaccessid) from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			where username = '".$username."' and menuname = '".$menuname."'");
		$sql				 = "select `".$menuaction.
			"`	from useraccess a
			inner join usergroup b on b.useraccessid = a.useraccessid
			inner join groupmenu c on c.groupaccessid = b.groupaccessid
			inner join menuaccess d on d.menuaccessid = c.menuaccessid
			where username = '".$username."' and menuname = '".$menuname."' limit 1";
	}
	$isaction = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
	if ($isaction > 0) {
		$baccess = true;
	} else {
		$baccess = false;
	}
	return $baccess;
}
/**
 * 
 */
function GetTodos() {
	$dependency	 = new CDbCacheDependency("select count(usertodoid from usertodo");
	$s					 = Yii::app()->db->cache(1000, $dependency)->createCommand("select * from usertodo where username = '".Yii::app()->user->id."' order by tododate desc limit 10")->queryAll();
	return $s;
}
/**
 *
 * @return type
 */
function GetTotalTodo() {
	$dependency	 = new CDbCacheDependency("select count(usertodoid from usertodo");
	$s					 = Yii::app()->db->cache(1000, $dependency)->createCommand("select count(1) from usertodo where username = '".Yii::app()->user->id."'")->queryScalar();
	return $s;
}
/**
 * 
 * @param type $command
 * @param type $tableid
 */
function InsertTranslog($command, $tableid = '', $menuname = '') {
	if (getparameter('usinglog') == '1') {
		$useraction = 'Insert';
		if ($tableid !== '') {
			$useraction = 'Update';
		} else if ($tableid == '') {
			$sql		 = "select last_insert_id() as tableid";
			$id			 = Yii::app()->db->createCommand($sql)->queryRow();
			$tableid = $id['tableid'];
		}
		$newdata = $command->pdoStatement->queryString;
		foreach ($command->getParamLog() as $key => $value) {
			$newdata = trim(str_replace($key, $value, $newdata));
		}
		$sql = "insert into translog(username,useraction,newdata,menuname,tableid)
        values (:0,:1,:2,:3,:4)";
    $connection	 = Yii::app()->db;
    $command = $connection->createCommand($sql);
    $command->bindvalue(':0', Yii::app()->user->id, PDO::PARAM_STR);
    $command->bindvalue(':1', $useraction, PDO::PARAM_STR);
    $command->bindvalue(':2', $newdata, PDO::PARAM_STR);
    $command->bindvalue(':3', $menuname, PDO::PARAM_STR);
    $command->bindvalue(':4', $tableid, PDO::PARAM_STR);
    $command->execute();
	}
}
/**
 * 
 * @param type $username
 * @return type
 */
function GetKey($username) {
	$sql = "select authkey from useraccess where username = '".$username."'";
	return Yii::app()->db->createCommand($sql)->queryScalar();
}
/**
 * Function for Authorize User Sender from Capella API
 * @param type $username
 * @param type $token
 * @param type $deviceid
 */
function AuthorizeUserSender($username, $token, $deviceid = '') {
	if ($deviceid == '') {
		$error = ValidateData(array(
			array('username', 'string', 'invaliduser'),
			array('token', 'string', 'invalidtoken'),
		));
	} else {
		$error = ValidateData(array(
			array('username', 'string', 'invaliduser'),
			array('token', 'string', 'invalidtoken'),
			array('deviceid', 'string', 'invaliddevice'),
		));
	}
	$result = $error;
	if ($error == false) {
		$username	 = filterinput(1, 'username', FILTER_SANITIZE_STRING);
		$deviceid	 = filterinput(1, 'deviceid', FILTER_SANITIZE_STRING);
		$token		 = filterinput(1, 'token', FILTER_SANITIZE_STRING);
		$sql			 = "select ifnull(count(1),0)
			from useraccess a
			where a.username = '".$username."'
				and a.authkey = '".$token."'
			";
		$data			 = Yii::app()->db->createCommand($sql)->queryScalar();
		if ($data < 1) {
			$result = true;
		}
	}
	return $result;
}
function getUserObjectValues($menuobject='company') {
	$sql = "select distinct a.menuvalueid 
				from groupmenuauth a
				inner join menuauth b on b.menuauthid = a.menuauthid
				inner join usergroup c on c.groupaccessid = a.groupaccessid 
				inner join useraccess d on d.useraccessid = c.useraccessid 
				where b.menuobject = '".$menuobject."'
				and d.username = '" . Yii::app()->user->name . "'";
	$cid = '';
	$datas = Yii::app()->db->createCommand($sql)->queryAll();
	foreach ($datas as $data) {
		if ($cid == '') {
			$cid = $data['menuvalueid'];
		} else 
		if ($cid !== '') {
			$cid .= ','.$data['menuvalueid'];
		}
	}
	return $cid;
}
function getUserRecordStatus($wfname) {
	$sql = "select b.wfbefstat
				from workflow a
				inner join wfgroup b on b.workflowid = a.workflowid
				inner join usergroup d on d.groupaccessid = b.groupaccessid
				inner join useraccess e on e.useraccessid = d.useraccessid
				where a.wfname = '".$wfname."' and e.username = '" . Yii::app()->user->name . "'";
	$cid = '';
	$datas = Yii::app()->db->createCommand($sql)->queryAll();
	foreach ($datas as $data) {
		if ($cid == '') {
			$cid = $data['wfbefstat'];
		} else 
		if ($cid !== '') {
			$cid .= ','.$data['wfbefstat'];
		}
	}
	return $cid;
}
function findstatusname($workflowname,$recordstatus)
{
	$status = Yii::app()->db->createCommand("select wfstatusname
		from wfstatus a
		inner join workflow b on b.workflowid = a.workflowid
		where b.wfname = '".$workflowname."' and a.wfstat = ".$recordstatus)->queryScalar();
	if ($status != '')
	{
		return $status;
	}
	else 
	{
		return 0;
	}
}
function hasmodule($modulename) {
  $status = Yii::app()->db->createCommand("select ifnull(count(1),0)
		from modules a
		where a.modulename = '".$modulename."'")->queryScalar();
	return $status;
}
function resize($imagetype,$imageres,$width,$height,$oldwidth,$oldheight) {
  $layer=imagecreatetruecolor($width,$height);
  if ($imagetype == IMAGETYPE_PNG) {
    $background = imagecolorallocate($layer,0,0,0);
    imagecolortransparent($layer,$background);
    imagealphablending($layer,false);
    imagesavealpha($layer,true);
  }
  imagecopyresampled($layer,$imageres,0,0,0,0,$width,$height, $oldwidth,$oldheight);
  return $layer;
}
function convertImageToWebP($imageres, $destination, $quality=80) {
  if ($imageres != null) {
    return imagewebp($imageres, $destination, $quality);
  } else {
    return '';
  }
}
function getpicture($filename,$alt='',$width=0,$height=0,$cssclass='') {
  $newfile = $_SERVER['DOCUMENT_ROOT'].$filename;
  $image_prop = getimagesize($newfile);
  $image_type = $image_prop[2];
  try {
    if ($image_type == IMAGETYPE_JPEG) {
      $filename = $filename."_thumb".$width."x".$height.".jpg";
      if (!file_exists($newfile."_thumb".$width."x".$height.".jpg")) {
        $imageres = imagecreatefromjpeg($newfile);
        $layer = resize(IMAGETYPE_JPEG,$imageres,$width,$height,$image_prop[0],$image_prop[1]);
        imagejpeg($layer,$_SERVER['DOCUMENT_ROOT'].$filename);
      }
    } else 
    if ($image_type == IMAGETYPE_PNG) {
      $filename = $filename."_thumb".$width."x".$height.".png";
      if (!file_exists($newfile."_thumb".$width."x".$height.".png")) {
        $imageres = imagecreatefrompng($newfile);
        $layer = resize(IMAGETYPE_PNG,$imageres,$width,$height,$image_prop[0],$image_prop[1]);
        imagepng($layer,$_SERVER['DOCUMENT_ROOT'].$filename);
      }
    } else
    if ($image_type == IMAGETYPE_GIF) {
      $filename = $filename."_thumb".$width."x".$height.".gif";
      if (!file_exists($newfile."_thumb".$width."x".$height.".gif")) {
        $imageres = imagecreatefromgif($newfile);
        $layer = resize(IMAGETYPE_GIF,$imageres,$width,$height,$image_prop[0],$image_prop[1]);
        imagegif($layer,$_SERVER['DOCUMENT_ROOT'].$filename);
      }
    }
  } 
  catch (Exception $x) {      
  }
  echo "<img src='".$filename."' alt='".$alt."' class='lazyload ".$cssclass."'>";
}
function WriteTelegramLog($message) {
  $s = '';
  if (is_array($message)) {
    foreach ($message as $key => $val) {
      $s .= $key . " = ".$val . PHP_EOL;
    }
  } else {
    $s = $message . PHP_EOL;
  }
  $myfile = fopen(Yii::getPathOfAlias('webroot') . '/protected/data/telegram.log',"a");
  fwrite($myfile,$s);
  fclose($myfile);
}
function exec_curl_request($handle) {
  $response = curl_exec($handle);

  if ($response === false) {
    $errno = curl_errno($handle);
    $error = curl_error($handle);
    WriteTelegramLog("Curl returned error $errno: $error\n");
    curl_close($handle);
    return false;
  }

  $http_code = intval(curl_getinfo($handle, CURLINFO_HTTP_CODE));
  curl_close($handle);

  if ($http_code >= 500) {
    // do not wat to DDOS server if something goes wrong
    sleep(100);
    return false;
  } else if ($http_code != 200) {
    $response = json_decode($response, true);
    WriteTelegramLog("Request has failed with error {$response['error_code']}: {$response['description']}\n");
    if ($http_code == 401) {
      throw new Exception('Invalid access token provided');
    }
    return false;
  } else {
    $response = json_decode($response, true);
    if (isset($response['description'])) {
      WriteTelegramLog("Request was successfull: {$response['description']}\n");
    }
    $response = $response['result'];
  }
  return $response;
}
function apiRequestWebhook($method, $parameters) {
  if (!is_string($method)) {
    WriteTelegramLog("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    WriteTelegramLog("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  header("Content-Type: application/json");
  echo json_encode($parameters);
  return true;
}
function apiRequest($telegramurl,$telegramkey,$method, $parameters) {
  if (!is_string($method)) {
    WriteTelegramLog("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    WriteTelegramLog("Parameters must be an array\n");
    return false;
  }

  foreach ($parameters as $key => &$val) {
    // encoding to JSON array parameters, for example reply_markup
    if (!is_numeric($val) && !is_string($val)) {
      $val = json_encode($val);
    }
  }
  $url = $telegramurl.$telegramkey.'/'.$method.'?'.http_build_query($parameters);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, Yii::app()->params['ConnectTimeOut']);
  curl_setopt($handle, CURLOPT_TIMEOUT, Yii::app()->params['TimeOut']);
  $result = exec_curl_request($handle);
  if (curl_errno($handle)) {
    $error_msg = curl_error($handle); 
  }
  curl_close($handle);
  if (isset($error_msg)) {
    WriteTelegramLog($error_msg);
    return $error_msg;
  } else {
    return $result ;
  }
}
function apiRequestGetFile($telegramurl,$telegramkey,$fileid) {
  if (!is_string($fileid)) {
    WriteTelegramLog("Method name must be a string\n");
    return false;
  }
  $url = $telegramurl.$telegramkey.'/getFile?file_id='.$fileid;
  WriteTelegramLog($url);

  $handle = curl_init($url);
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, Yii::app()->params['ConnectTimeOut']);
  curl_setopt($handle, CURLOPT_TIMEOUT, Yii::app()->params['TimeOut']);
  return exec_curl_request($handle);
}
function apiRequestJson($telegramurl,$telegramkey,$method, $parameters) {
  if (!is_string($method)) {
    WriteTelegramLog("Method name must be a string\n");
    return false;
  }

  if (!$parameters) {
    $parameters = array();
  } else if (!is_array($parameters)) {
    WriteTelegramLog("Parameters must be an array\n");
    return false;
  }

  $parameters["method"] = $method;

  $handle = curl_init($telegramurl.$telegramkey.'/');
  curl_setopt($handle, CURLOPT_RETURNTRANSFER, true);
  curl_setopt($handle, CURLOPT_CONNECTTIMEOUT, 5);
  curl_setopt($handle, CURLOPT_TIMEOUT, 60);
  curl_setopt($handle, CURLOPT_POSTFIELDS, json_encode($parameters));
  curl_setopt($handle, CURLOPT_HTTPHEADER, array("Content-Type: application/json"));

  return exec_curl_request($handle);
}
function chatReply($param,$telegramurl,$telegramkey,$message) {
  try {
    $message_id = $message['message_id'];
    $chatid = $message['chat']['id'];
    $firstusertelegram = $message['from']['first_name'];
    $lastusertelegram = $message['from']['last_name'];
    $usertelegram = $message['from']['username'];
    $telegramid = $message['from']['id'];
    $reply = 'Maaf, saya tidak mengetahui maksud perintah anda. Coba saya tanyakan ke bagian IT terlebih dahulu';
    if (substr($param[0],0,1) == '/') {
      $param[0] = substr($param[0],1,strlen($param[0])-1);
    } 
    $sql = "select ifnull(count(1),0) 
      from chat 
      where coalesce(msgfrom,'') = '".$param[0]."'";
    $k = Yii::app()->db->createCommand($sql)->queryScalar();
    if ($k > 0) {
      $sql = "select chatid, msgresponid,ifnull(msgparam,'')
        from chat 
        where coalesce(msgfrom,'') = '".$param[0]."'
        limit 1";
      $datachat = Yii::app()->db->createCommand($sql)->queryRow();
      $sql = "
        select ifnull(msgreply,'') as msgreply,ifnull(sourcedata,'') as sourcedata
        from chatrespon 
        where chatresponid = ".$datachat['msgresponid'];
      $chatrespon = Yii::app()->db->createCommand($sql)->queryRow();   
      if ($chatrespon['msgreply'] != 'content') {
        $chatrespon['msgreply'] = str_replace('[usertelegram]',$usertelegram,$chatrespon['msgreply']);
        $chatrespon['msgreply'] = str_replace('[telegramid]',$telegramid,$chatrespon['msgreply']);
        $chatrespon['msgreply'] = str_replace('[firstusertelegram]',$firstusertelegram,$chatrespon['msgreply']);
        $chatrespon['msgreply'] = str_replace('[lastusertelegram]',$lastusertelegram,$chatrespon['msgreply']);
        if (count($param) > 0) {
          $chatrespon['msgreply'] = str_replace('[id]',$param[1],$chatrespon['msgreply']);
          $chatrespon['msgreply'] = str_replace('[data]',$param[1],$chatrespon['msgreply']);
        }
      }

      if ($chatrespon['sourcedata'] != '') {
        $chatrespon['sourcedata'] = str_replace('[usertelegram]',$usertelegram,$chatrespon['sourcedata']);
        $chatrespon['sourcedata'] = str_replace('[telegramid]',$telegramid,$chatrespon['sourcedata']);
        $chatrespon['sourcedata'] = str_replace('[firstusertelegram]',$firstusertelegram,$chatrespon['sourcedata']);
        $chatrespon['sourcedata'] = str_replace('[lastusertelegram]',$lastusertelegram,$chatrespon['sourcedata']);
        if (count($param) > 0) {
          $chatrespon['sourcedata'] = str_replace('[id]',$param[1], $chatrespon['sourcedata']);
          $chatrespon['sourcedata'] = str_replace('[data]',$param[1], $chatrespon['sourcedata']);
        }

        $commsource = substr($chatrespon['sourcedata'],0,4);
        if ($commsource == 'http') {
          $data = [
            'username'=>$usertelegram,
            'firstusertelegram'=>$firstusertelegram,
            'lastusertelegram'=>$lastusertelegram,
            'telegramid'=>$telegramid,
            'data'=>$param[1]
          ];
          $query = http_build_query($data);
          $ch = curl_init();
          curl_setopt($ch, CURLOPT_URL, $chatrespon['sourcedata']);
          curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
          curl_setopt($ch, CURLOPT_POSTFIELDS, $query);
          curl_setopt($ch, CURLOPT_HEADER, 0);
          curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

          $chatrespon['msgreply'] = curl_exec($ch);
          curl_close($ch);
        } else {
          $sql = $chatrespon['sourcedata'];
          $dataku = Yii::app()->db->createCommand($sql)->queryAll();
          $s = '';
          if (strpos($chatrespon['msgreply'],'[list]') !== FALSE) {
            foreach ($dataku as $data) {
              $keys = array_keys($data);
              foreach ($keys as $key) {
                $s .= $key.' : '.$data[$key]. "\n";
              }
            }
            $chatrespon['msgreply'] = str_replace('[list]',$s,$chatrespon['msgreply']);
          } else {
            foreach ($dataku as $data) {
              $keys = array_keys($data);
              foreach ($keys as $key) {
                $chatrespon['msgreply'] = str_replace($key,$data[$key],$chatrespon['msgreply']);
              }
            }
          }
        }
      }

      $reply = $chatrespon['msgreply'];
      $reply = str_replace(']','',str_replace('[','',$reply));
    } else {
      $reply = 'Maaf ya, perintah tidak dikenal. Nanti saya tanyakan lagi ke Administrator';
    }
    apiRequest($telegramurl,$telegramkey,"sendMessage", array('chat_id' => $chatid, "text" => $reply, 'parse_mode'=>'html'));
  } catch (Exception $ex) {
    WriteTelegramLog($ex->getMessage());
  }
}
function insertLogChat($grupbaca,$caption,$text,$usertelegram) {
  try {
    $sql = "insert into logchat (grupbaca,caption,logtext,idtelegram) 
      values (:grupbaca,:caption,:logtext,:idtelegram)";
    $command = Yii::app()->db->createCommand($sql);
    $command->bindvalue(':grupbaca', $grupbaca, PDO::PARAM_STR);
    $command->bindvalue(':caption', $caption, PDO::PARAM_STR);
    $command->bindvalue(':logtext', $text, PDO::PARAM_STR);
    $command->bindvalue(':idtelegram', $usertelegram, PDO::PARAM_STR);
    $command->execute();
    $sql = "select last_insert_id()";
    return Yii::app()->db->createCommand($sql)->queryScalar();
  }
  catch (Exception $ex) {
    WriteTelegramLog($ex->getMessage());
  }
}
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
function processMessage($telegramurl,$telegramkey,$message) {
  //task
  //cek di peserta - divisi - jabatan, istelegram = 1
  ini_set('memory_limit', '128M');
  $message_id = $message['message_id'];
  $chatid = $message['chat']['id'];
  $firstusertelegram = $message['from']['first_name'];
  $lastusertelegram = $message['from']['last_name'];
  $usertelegram = $message['from']['username'];
  $telegramid = $message['from']['id'];
  $usertelegram = $message['from']['username'];
  $sql = "
    select ifnull(count(1),0)
    from peserta a 
    join divisidetail b on b.pesertaid = a.pesertaid 
    join jabatan c on c.jabatanid = b.jabatanid 
    where a.idtelegram = '".$usertelegram."' 
    and c.istelegram = 1 
    and a.recordstatus = 1 
    and c.recordstatus = 1";
  $k = Yii::app()->db->createCommand($sql)->queryScalar();
  if ($k > 0) {
    if (isset($message['text']) || (isset($message['document']))) {
      if (isset($message['text'])) {
        if (strpos($message['text'],"\n") !== false) {
          $s = explode("\n",$message['text']);
          $param = [$s[0],$message['text']];
        } else {
          $param = explode(' ',$message['text']);     
        }
        chatReply($param,$telegramurl,$telegramkey,$message);  
      } else
      if (isset($message['document'])) {
          if (isset($message['caption'])) {
          $grupbaca = str_replace('Chat WhatsApp dengan ','',$message['document']['file_name']);
          $grupbaca = str_replace('WhatsApp Chat - ','',$message['document']['file_name']);
          $extfile = pathinfo($message['document']['file_name'], PATHINFO_EXTENSION);
          $grupbaca = str_replace('/','',$grupbaca);
          $grupbaca = str_replace('_','',$grupbaca);
          $grupbaca = str_replace('.zip','',$grupbaca);
          $caption = str_replace('kirimchat ','',$message['caption']);
          $sql = "
            select ifnull(count(1),0)
            from peserta a 
            join divisidetail b on b.pesertaid = a.pesertaid 
            join jabatan c on c.jabatanid = b.jabatanid 
            join grupbaca d on d.divisiid = b.divisiid 
            where a.idtelegram = '".$usertelegram."' 
            and c.istelegram = 1 
            and a.recordstatus = 1 
            and c.recordstatus = 1
            and d.namagrup = '".$grupbaca."'
          ";
          $k = Yii::app()->db->createCommand($sql)->queryScalar();
          if ($k > 0) {
            $fileid = $message['document']['file_id'];
            if ($extfile == 'zip') {
              $filename = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$message['document']['file_name'];
            } else {
              $filename = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$grupbaca;
            }
            $content = apiRequestGetFile($telegramurl,$telegramkey,$fileid);

            $url = Yii::app()->params['TelegramFileUrl'].$telegramkey.'/'.$content['file_path'];
            file_put_contents($filename, file_get_contents($url));

            if ($extfile == 'zip') {
              $zip = new ZipArchive;
              $res = $zip->open($filename);
              if ($res === TRUE) {
                $filename = $_SERVER['DOCUMENT_ROOT'].'/uploads/'.$grupbaca."/_chat.txt";
                $zip->extractTo($_SERVER['DOCUMENT_ROOT'].'/uploads/'.$grupbaca);
                $zip->close();
              }
            }
            $id = insertLogChat($grupbaca,$caption,$filename,$usertelegram);

            $param = ['replyfile',$id];
            chatReply($param,$telegramurl,$telegramkey,$message);

          } else {
            $param = ['youarenotgrupbaca'];
            chatReply($param,$telegramurl,$telegramkey,$message);
          }
        } else {
          $param = ['sendchaterror'];
          chatReply($param,$telegramurl,$telegramkey,$message);
        }
      } else {
        $param = ['unknownorder'];
        chatReply($param,$telegramurl,$telegramkey,$message);
      }
    } else {
      $param = ['unknownorder'];
      chatReply($param,$telegramurl,$telegramkey,$message);
    }
  } else {
    $param = ['youarenotauthorized'];
    chatReply($param,$telegramurl,$telegramkey,$message);
  }
}
function getChatRespon($msgfrom) {
  $chatrespon = '';
  $sql = "select ifnull(count(1),0) 
    from chat 
    where coalesce(msgfrom,'') like '%".$msgfrom."%'";
  $k = Yii::app()->db->createCommand($sql)->queryScalar();
  if ($k > 0) {
    $sql = "select chatid, msgresponid,ifnull(msgparam,'')
      from chat 
      where coalesce(msgfrom,'') like '%".$msgfrom."%'
      limit 1";
    $datachat = Yii::app()->db->createCommand($sql)->queryRow();
    $sql = "
      select ifnull(msgreply,'') as msgreply
      from chatrespon 
      where chatresponid = ".$datachat['msgresponid'];
    $chatrespon = Yii::app()->db->createCommand($sql)->queryScalar();   
  }
  return $chatrespon;
}
function getTemplateRespon($template) {
  $datacontent = getChatRespon($template);
  $datacontent = str_replace('[PICTURE_HAND_UP]',PICTURE_HAND_UP,$datacontent);
  $datacontent = str_replace('[PICTURE_CHAMPION]',PICTURE_CHAMPION,$datacontent);
  $datacontent = str_replace('[PICTURE_FINISH]',PICTURE_FINISH,$datacontent);
  $datacontent = str_replace('[PICTURE_NOT_FINISH]',PICTURE_NOT_FINISH,$datacontent);
  $datacontent = str_replace('[PICTURE_PESERTA]',PICTURE_PESERTA,$datacontent);
  $datacontent = str_replace('[PICTURE_FLAG_NOT_ACTIVE]',PICTURE_FLAG_NOT_ACTIVE,$datacontent);
  $datacontent = str_replace('[PICTURE_BOOK]',PICTURE_BOOK,$datacontent);
  $datacontent = str_replace('[PICTURE_LIGHT]',PICTURE_LIGHT,$datacontent);
  $datacontent = str_replace('[PICTURE_FLAG_FINISH]',PICTURE_FLAG_FINISH,$datacontent);
  $datacontent = str_replace('[PICTURE_100]',PICTURE_100,$datacontent);
  $datacontent = str_replace('[PICTURE_WOMAN]',PICTURE_WOMAN,$datacontent);
  $datacontent = str_replace('[PICTURE_MAN]',PICTURE_MAN,$datacontent);
  $datacontent = str_replace('[PICTURE_LAST_FINISH]',PICTURE_LAST_FINISH,$datacontent);
  $datacontent = str_replace('[PICTURE_LATE]',PICTURE_LATE,$datacontent);
  $datacontent = str_replace('[PICTURE_HAND_LEFT]',PICTURE_HAND_LEFT,$datacontent);
  $datacontent = str_replace('[PICTURE_LOVE]',PICTURE_LOVE,$datacontent);
  $datacontent = str_replace('[PICTURE_FIRE]',PICTURE_FIRE,$datacontent);
  $datacontent = str_replace('[PICTURE_CLAP]',PICTURE_CLAP,$datacontent);
  $datacontent = str_replace('[PICTURE_CROWN]',PICTURE_CROWN,$datacontent);
  $datacontent = str_replace('[PICTURE_PRAY]',PICTURE_PRAY,$datacontent);
  $datacontent = str_replace('[PICTURE_SUN]',PICTURE_SUN,$datacontent);
  $datacontent = str_replace('[PICTURE_STAR]',PICTURE_STAR,$datacontent);
  $datacontent = str_replace('[PICTURE_VIA_HP]',PICTURE_VIA_HP,$datacontent);
  $datacontent = str_replace('[PICTURE_RAINBOW]',PICTURE_RAINBOW,$datacontent);
  $datacontent = str_replace('[PICTURE_DRINK]',PICTURE_DRINK,$datacontent);
  $datacontent = str_replace('[PICTURE_FLOWER]',PICTURE_FLOWER,$datacontent);
  $datacontent = str_replace('[PICTURE_PLEASE]',PICTURE_PLEASE,$datacontent);
  $datacontent = str_replace('[PICTURE_ANGEL]',PICTURE_ANGEL,$datacontent);
  $datacontent = str_replace('[PICTURE_GOOD_JOB]',PICTURE_GOOD_JOB,$datacontent);
  $datacontent = str_replace('[PICTURE_SMILE]',PICTURE_SMILE,$datacontent);
  $datacontent = str_replace('[PICTURE_SMILE_TEETH]',PICTURE_SMILE_TEETH,$datacontent);

  return $datacontent;
}