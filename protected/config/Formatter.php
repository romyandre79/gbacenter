<?php
class Formatter extends CFormatter {
	protected function GetParameter($paramname) {
		try {
			$sql				 = "select paramvalue ".
				" from parameter a ".
				" where lower(paramname) = lower('".$paramname."')";
			$dependency	 = new CDbCacheDependency('SELECT MAX(paramid) FROM parameter');
			$menu				 = Yii::app()->db->cache(1000, $dependency)->createCommand($sql)->queryRow();
			return $menu['paramvalue'];
		} catch (CDbException $ex) {
			return $ex->getMessage();
		}
	}
	public $numberFormat	 = array('decimals' => 4);
	public $currencyFormat = array('decimals' => 2);
	public function formatNumber($value) {
		if ($value === null) return null;		// new
		if ($value === '') return '';				// new
		return number_format($value, getparameter('decimalqty'),
			getparameter('decimalSeparator'),
			getparameter('thousandSeparator'));
	}
	public function formatCurrency($value) {
		if ($value === null) return null;		// new
		if ($value === '') return '';				// new
		$curr = Yii::app()->db->createCommand("select paramvalue from parameter where paramname = 'basecurrency'")->queryScalar();
		return $curr.number_format($value, getparameter('decimalprice'),
				getparameter('decimalSeparator'),
				getparameter('thousandSeparator'));
	}
	public function unformatNumber($formatted_number) {
		if ($formatted_number === null) return null;
		if ($formatted_number === '') return '';
		if (is_float($formatted_number))
				return $formatted_number; // only 'unformat' if parameter is not float already

		$value = str_replace($this->numberFormat['thousandSeparator'], '',
			$formatted_number);
		$value = str_replace($this->numberFormat['decimalSeparator'], '.', $value);
		return (float) $value;
  }
}