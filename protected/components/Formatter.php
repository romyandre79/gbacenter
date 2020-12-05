<?php
class Formatter extends CFormatter {
	protected function GetParameter($paramname) {
		try {
			$sql				 = "select paramvalue ".
				" from parameter a ".
				" where lower(paramname) = lower('".$paramname."')";
			$dependency	 = new CDbCacheDependency('SELECT MAX(paramid) FROM parameter');
			$menu				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryScalar();
			return $menu;
		} catch (CDbException $ex) {
			return $ex->getMessage();
		}
	}
	public $numberFormat	 = array('decimals' => 2);
	public $currencyFormat = array('decimals' => 0);
	public function formatNumber($value) {
		if ($value === null) return null;
		if ($value === '') return '';
		return number_format($value, getparameter('decimalqty'),
			getparameter('decimalSeparator'),
			getparameter('groupSeparator'));
	}
	public function formatNumberWODecimal($value) {
		if ($value === null) return null;		// new
		if ($value === '') return '';				// new
		return number_format($value, 0, getparameter('decimalSeparator'),
			getparameter('groupSeparator'));
	}
	public function formatCurrency($value, $symbol = '') {
		if ($value === null) return null;
		if ($value === '') return '';
		if ($symbol === '') {
			$symbol = getparameter('basecurrency');
		}
		return $symbol.number_format($value, getparameter('decimalprice'),
				getparameter('decimalSeparator'),
				getparameter('groupSeparator'));
	}
	public function formatDate($value) {
		if ($value === null) return null;
		if ($value === '') return '';
		$objdate = DateTime::createFromFormat('Y-m-d', $value);
		return $objdate->format(getparameter('dateformat'));
	}
	public function formatTime($value) {
		if ($value === null) return null;
		if ($value === '') return '';
		$objdate = DateTime::createFromFormat('H:i:s', $value);
		return $objdate->format(getparameter('timeformat'));
	}
	public function formatDateTime($value) {
		if ($value === null) return null;
		if ($value === '') return '';
		$objdate = DateTime::createFromFormat('Y-m-d H:i:s', $value);
		return $objdate->format(getparameter('datetimeformat'));
	}
	public function formatDateSQL($value) {
		if ($value === null) return null;
		if ($value === '') return '';
		return date('Y-m-d', strtotime($value));
	}
	public function formatDateTimeSQL($value) {
		if ($value === null) return null;
		if ($value === '') return '';
		return date('Y-m-d h:i:s', strtotime($value));
	}
	public function unformatNumber($formatted_number) {
		if ($formatted_number === null) return null;
		if ($formatted_number === '') return '';
		if (is_float($formatted_number)) return $formatted_number;

		$value = str_replace(getparameter('thousandSeparator'), '',
			$formatted_number);
		$value = str_replace(getparameter('decimalSeparator'), '.', $value);
		return (float) $value;
	}
}