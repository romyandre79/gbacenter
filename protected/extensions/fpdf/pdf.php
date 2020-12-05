<?php
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
define('FPDF_FONTPATH', 'font/');
require_once("fpdf.php");
class PDF extends FPDF {
	public $title					 = '';
	public $subtitle			 = '';
	public $isfooter			 = true;
	public $isheader			 = true;
	public $iscustomborder = false;
	public $isneedpage		 = false;
	public $colheader;
	public $colalign;
	public $coldetailalign;
	public $companyid			 = 1;
	var $widths;
	var $aligns;
	var $border				 = true;
	var $bordercell;
	var $legends;
	var $wLegend;
	var $sum;
	var $NbVal;
	function PieChart($w, $h, $data, $format, $colors = null) {
		$this->SetFont('Courier', '', 10);
		$this->SetLegends($data, $format);

		$XPage	 = $this->GetX();
		$YPage	 = $this->GetY();
		$margin	 = 2;
		$hLegend = 5;
		$radius	 = min($w - $margin * 4 - $hLegend - $this->wLegend, $h - $margin * 2);
		$radius	 = floor($radius / 2);
		$XDiag	 = $XPage + $margin + $radius;
		$YDiag	 = $YPage + $margin + $radius;
		if ($colors == null) {
			for ($i = 0; $i < $this->NbVal; $i++) {
				$gray				 = $i * intval(255 / $this->NbVal);
				$colors[$i]	 = array($gray, $gray, $gray);
			}
		}

		//Sectors
		$this->SetLineWidth(0.2);
		$angleStart	 = 0;
		$angleEnd		 = 0;
		$i					 = 0;
		foreach ($data as $val) {
			$angle = ($val * 360) / doubleval($this->sum);
			if ($angle != 0) {
				$angleEnd		 = $angleStart + $angle;
				$this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
				$this->Sector($XDiag, $YDiag, $radius, $angleStart, $angleEnd);
				$angleStart	 += $angle;
			}
			$i++;
		}

		//Legends
		$this->SetFont('Courier', '', 10);
		$x1	 = $XPage + 2 * $radius + 4 * $margin;
		$x2	 = $x1 + $hLegend + $margin;
		$y1	 = $YDiag - $radius + (2 * $radius - $this->NbVal * ($hLegend + $margin)) / 2;
		for ($i = 0; $i < $this->NbVal; $i++) {
			$this->SetFillColor($colors[$i][0], $colors[$i][1], $colors[$i][2]);
			$this->Rect($x1, $y1, $hLegend, $hLegend, 'DF');
			$this->SetXY($x2, $y1);
			$this->Cell(0, $hLegend, $this->legends[$i]);
			$y1 += $hLegend + $margin;
		}
	}
	function LineGraph($w, $h, $data, $options = '', $colors = null, $maxVal = 0,
										$nbDiv = 4) {
		/*		 * *****************************************
		  Explain the variables:
		  $w = the width of the diagram
		  $h = the height of the diagram
		  $data = the data for the diagram in the form of a multidimensional array
		  $options = the possible formatting options which include:
		  'V' = Print Vertical Divider lines
		  'H' = Print Horizontal Divider Lines
		  'kB' = Print bounding box around the Key (legend)
		  'vB' = Print bounding box around the values under the graph
		  'gB' = Print bounding box around the graph
		  'dB' = Print bounding box around the entire diagram
		  $colors = A multidimensional array containing RGB values
		  $maxVal = The Maximum Value for the graph vertically
		  $nbDiv = The number of vertical Divisions
		 * ***************************************** */
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.2);
		$keys					 = array_keys($data);
		$ordinateWidth = 10;
		$w						 -= $ordinateWidth;
		$valX					 = $this->getX() + $ordinateWidth;
		$valY					 = $this->getY();
		$margin				 = 1;
		$titleH				 = 8;
		$titleW				 = $w;
		$lineh				 = 5;
		$keyH					 = count($data) * $lineh;
		$keyW					 = $w / 5;
		$graphValH		 = 5;
		$graphValW		 = $w - $keyW - 3 * $margin;
		$graphH				 = $h - (3 * $margin) - $graphValH;
		$graphW				 = $w - (2 * $margin) - ($keyW + $margin);
		$graphX				 = $valX + $margin;
		$graphY				 = $valY + $margin;
		$graphValX		 = $valX + $margin;
		$graphValY		 = $valY + 2 * $margin + $graphH;
		$keyX					 = $valX + (2 * $margin) + $graphW;
		$keyY					 = $valY + $margin + .5 * ($h - (2 * $margin)) - .5 * ($keyH);
		//draw graph frame border
		if (strstr($options, 'gB')) {
			$this->Rect($valX, $valY, $w, $h);
		}
		//draw graph diagram border
		if (strstr($options, 'dB')) {
			$this->Rect($valX + $margin, $valY + $margin, $graphW, $graphH);
		}
		//draw key legend border
		if (strstr($options, 'kB')) {
			$this->Rect($keyX, $keyY, $keyW, $keyH);
		}
		//draw graph value box
		if (strstr($options, 'vB')) {
			$this->Rect($graphValX, $graphValY, $graphValW, $graphValH);
		}
		//define colors
		if ($colors === null) {
			$safeColors = array(0, 51, 102, 153, 204, 225);
			for ($i = 0; $i < count($data); $i++) {
				$colors[$keys[$i]] = array($safeColors[array_rand($safeColors)], $safeColors[array_rand($safeColors)],
					$safeColors[array_rand($safeColors)]);
			}
		}
		//form an array with all data values from the multi-demensional $data array
		$ValArray = array();
		foreach ($data as $key => $value) {
			foreach ($data[$key] as $val) {
				$ValArray[] = $val;
			}
		}
		//define max value
		if ($maxVal < ceil(max($ValArray))) {
			$maxVal = ceil(max($ValArray));
		}
		//draw horizontal lines
		$vertDivH = $graphH / $nbDiv;
		if (strstr($options, 'H')) {
			for ($i = 0; $i <= $nbDiv; $i++) {
				if ($i < $nbDiv) {
					$this->Line($graphX, $graphY + $i * $vertDivH, $graphX + $graphW,
						$graphY + $i * $vertDivH);
				} else {
					$this->Line($graphX, $graphY + $graphH, $graphX + $graphW,
						$graphY + $graphH);
				}
			}
		}
		//draw vertical lines
		$horiDivW = floor($graphW / (count($data[$keys[0]]) - 1));
		if (strstr($options, 'V')) {
			for ($i = 0; $i <= (count($data[$keys[0]]) - 1); $i++) {
				if ($i < (count($data[$keys[0]]) - 1)) {
					$this->Line($graphX + $i * $horiDivW, $graphY, $graphX + $i * $horiDivW,
						$graphY + $graphH);
				} else {
					$this->Line($graphX + $graphW, $graphY, $graphX + $graphW,
						$graphY + $graphH);
				}
			}
		}
		//draw graph lines
		foreach ($data as $key => $value) {
			$this->setDrawColor($colors[$key][0], $colors[$key][1], $colors[$key][2]);
			$this->SetLineWidth(0.8);
			$valueKeys = array_keys($value);
			for ($i = 0; $i < count($value); $i++) {
				if ($i == count($value) - 2) {
					$this->Line(
						$graphX + ($i * $horiDivW),
						$graphY + $graphH - ($value[$valueKeys[$i]] / $maxVal * $graphH),
						$graphX + $graphW,
						$graphY + $graphH - ($value[$valueKeys[$i + 1]] / $maxVal * $graphH)
					);
				} else if ($i < (count($value) - 1)) {
					$this->Line(
						$graphX + ($i * $horiDivW),
						$graphY + $graphH - ($value[$valueKeys[$i]] / $maxVal * $graphH),
						$graphX + ($i + 1) * $horiDivW,
						$graphY + $graphH - ($value[$valueKeys[$i + 1]] / $maxVal * $graphH)
					);
				}
			}
			//Set the Key (legend)
			$this->SetFont('Courier', '', 10);
			if (!isset($n)) $n = 0;
			$this->Line($keyX + 1, $keyY + $lineh / 2 + $n * $lineh, $keyX + 8,
				$keyY + $lineh / 2 + $n * $lineh);
			$this->SetXY($keyX + 8, $keyY + $n * $lineh);
			$this->Cell($keyW, $lineh, $key, 0, 1, 'L');
			$n++;
		}
		//print the abscissa values
		foreach ($valueKeys as $key => $value) {
			if ($key == 0) {
				$this->SetXY($graphValX, $graphValY);
				$this->Cell(30, $lineh, $value, 0, 0, 'L');
			} else if ($key == count($valueKeys) - 1) {
				$this->SetXY($graphValX + $graphValW - 30, $graphValY);
				$this->Cell(30, $lineh, $value, 0, 0, 'R');
			} else {
				$this->SetXY($graphValX + $key * $horiDivW - 15, $graphValY);
				$this->Cell(30, $lineh, $value, 0, 0, 'C');
			}
		}
		//print the ordinate values
		for ($i = 0; $i <= $nbDiv; $i++) {
			$this->SetXY($graphValX - 10, $graphY + ($nbDiv - $i) * $vertDivH - 3);
			$this->Cell(8, 6, sprintf('%.1f', $maxVal / $nbDiv * $i), 0, 0, 'R');
		}
		$this->SetDrawColor(0, 0, 0);
		$this->SetLineWidth(0.2);
	}
	function BarDiagram($w, $h, $data, $format, $color = null, $maxVal = 0,
										 $nbDiv = 4) {
		$this->SetFont('Courier', '', 10);
		$this->SetLegends($data, $format);

		$XPage	 = $this->GetX();
		$YPage	 = $this->GetY();
		$margin	 = 2;
		$YDiag	 = $YPage + $margin;
		$hDiag	 = floor($h - $margin * 2);
		$XDiag	 = $XPage + $margin * 2 + $this->wLegend;
		$lDiag	 = floor($w - $margin * 3 - $this->wLegend);
		if ($color == null) $color	 = array(155, 155, 155);
		if ($maxVal == 0) {
			$maxVal = max($data);
		}
		$valIndRepere	 = ceil($maxVal / $nbDiv);
		$maxVal				 = $valIndRepere * $nbDiv;
		$lRepere			 = floor($lDiag / $nbDiv);
		$lDiag				 = $lRepere * $nbDiv;
		if ($maxVal > 0) {
			$unit = $lDiag / $maxVal;
		} else {
			$unit = 0;
		}
		$hBar		 = floor($hDiag / ($this->NbVal + 1));
		$hDiag	 = $hBar * ($this->NbVal + 1);
		$eBaton	 = floor($hBar * 80 / 100);

		$this->SetLineWidth(0.2);
		$this->Rect($XDiag, $YDiag, $lDiag, $hDiag);

		$this->SetFont('Courier', '', 10);
		$this->SetFillColor($color[0], $color[1], $color[2]);
		$i = 0;
		foreach ($data as $val) {
			//Bar
			$xval	 = $XDiag;
			$lval	 = (int) ($val * $unit);
			$yval	 = $YDiag + ($i + 1) * $hBar - $eBaton / 2;
			$hval	 = $eBaton;
			$this->Rect($xval, $yval, $lval, $hval, 'DF');
			//Legend
			$this->SetXY(0, $yval);
			$this->Cell($xval - $margin, $hval, $this->legends[$i], 0, 0, 'R');
			$i++;
		}

		//Scales
		for ($i = 0; $i <= $nbDiv; $i++) {
			$xpos	 = $XDiag + $lRepere * $i;
			$this->Line($xpos, $YDiag, $xpos, $YDiag + $hDiag);
			$val	 = $i * $valIndRepere;
			$xpos	 = $XDiag + $lRepere * $i - $this->GetStringWidth($val) / 2;
			$ypos	 = $YDiag + $hDiag - $margin;
			$this->Text($xpos, $ypos, $val);
		}
	}
	function SetLegends($data, $format) {
		$this->legends = array();
		$this->wLegend = 0;
		$this->sum		 = array_sum($data);
		$this->NbVal	 = count($data);
		foreach ($data as $l => $val) {
			if ($this->sum > 0) {
				$p = sprintf('%.2f', $val / $this->sum * 100).'%';
			} else {
				$p = sprintf('%.2f', 0).'%';
			}
			$legend					 = str_replace(array('%l', '%v', '%p'), array($l, $val, $p),
				$format);
			$this->legends[] = $legend;
			$this->wLegend	 = max($this->GetStringWidth($legend), $this->wLegend);
		}
	}
	function SetWidths($w) {
		//Set the array of column widths
		$this->widths = $w;
	}
	function SetAligns($a) {
		//Set the array of column alignments
		$this->aligns = $a;
	}
	function SetBorder($a) {
		//Set the array of column alignments
		$this->border = $a;
	}
	function SetBorderCell($a) {
		//Set the array of column alignments
		$this->bordercell = $a;
	}
	//Page header
	function Header() {
		if ($this->companyid !== '') {
			$this->companyid = 1;
		};
		$sql		 = "select ifnull(count(1),0) 
			from company a 
			join city b on b.cityid = a.cityid 
			where companyid = ".$this->companyid;
		$company = Yii::app()->db->createCommand($sql)->queryScalar();
		//var_dump($this->w);
		if ($company > 0) {
      $sql		 = "select a.*,b.cityname 
        from company a 
        join city b on b.cityid = a.cityid 
        where companyid = ".$this->companyid;
      $company = Yii::app()->db->createCommand($sql)->queryRow();
			if ($this->isheader == true) {
				//Landscape A4
				if (($this->w > 297) && ($this->w < 298)) {
					$this->Image('images/'.$company['leftlogofile'], 5, 5, 25);
					$this->SetFont('Arial', 'B', 20);
					$this->cell(0);
					$this->Text(120, 10, $company['companyname']);
					$this->SetFont('Arial', '', 9);
					$this->cell(0);
					$this->Cell(-270, 15,
						'Office: '.$company['address'].' '.$company['cityname'].' '.$company['zipcode'].' - Indonesia',
						0, 0, 'C');
					$this->cell(0);
					$this->Cell(-270, 24, 'Telp.: '.$company['phoneno'].' '.$company['faxno'],
						0, 0, 'C');
					$this->cell(0);
					$this->Cell(-270, 32,
						'Email: '.$company['email'].' Web : '.$company['webaddress'], 0, 0, 'C');
					if (($company['rightlogofile'] != null) && ($company['rightlogofile'] != '')) {
						$this->Image('images/'.$company['rightlogofile'], 245, 5, 50);
					}
					$this->SetLineWidth(0.5);
					$this->Line(0, 30, 380, 30);
					$this->SetFont('Arial', 'B', 16);
					$this->cell(0);
					$this->Cell(-270, 60, $this->title, 0, 0, 'C');
					$this->cell(0);
					$this->Cell(-270, 80, $this->subtitle, 0, 0, 'C');
				} else
				//Landscape Legal
				if (($this->w > 355) && ($this->w < 356)) {
					$this->Image('images/'.$company['leftlogofile'], 5, 5, 25);
					$this->SetFont('Arial', 'B', 20);
					$this->cell(0);
					$this->Text(140, 10, $company['companyname']);
					$this->SetFont('Arial', '', 9);
					$this->cell(0);
					$this->Cell(-350, 15,
						'Office: '.$company['address'].' '.$company['cityname'].' '.$company['zipcode'].' - Indonesia',
						0, 0, 'C');
					$this->cell(0);
					$this->Cell(-350, 24, 'Telp.: '.$company['phoneno'].' '.$company['faxno'],
						0, 0, 'C');
					$this->cell(0);
					$this->Cell(-350, 32,
						'Email: '.$company['email'].' Web : '.$company['webaddress'], 0, 0, 'C');
            if (($company['rightlogofile'] != null) && ($company['rightlogofile'] != '')) {
              $this->Image('images/'.$company['rightlogofile'], 245, 5, 50);
					}
					$this->SetLineWidth(0.5);
					$this->Line(0, 30, 380, 30);
					$this->SetFont('Arial', 'B', 16);
					$this->cell(0);
					$this->Cell(-350, 60, $this->title, 0, 0, 'C');
					$this->cell(0);
					$this->Cell(-350, 80, $this->subtitle, 0, 0, 'C');
				} else
				//Landscape lebih dari Legal
				if ($this->w >= 380) {
					$this->Image('images/'.$company['leftlogofile'], 5, 5, 25);
					$this->SetFont('Arial', 'B', 20);
					$this->cell(0);
					$this->Cell(-430, 0, $company['companyname'], 0, 0, 'C');
					$this->SetFont('Arial', '', 9);
					$this->cell(0);
					$this->Cell(-430, 15,
						'Office: '.$company['address'].' '.$company['cityname'].' '.$company['zipcode'].' - Indonesia',
						0, 0, 'C');
					$this->cell(0);
					$this->Cell(-430, 23, 'Telp.: '.$company['phoneno'].' '.$company['faxno'],
						0, 0, 'C');
					$this->cell(0);
					$this->Cell(-430, 31,
						'Email: '.$company['email'].' Web : '.$company['webaddress'], 0, 0, 'C');
            if (($company['rightlogofile'] != null) && ($company['rightlogofile'] != '')) {
              $this->Image('images/'.$company['rightlogofile'], 290, 5, 50);
					}
					$this->SetLineWidth(0.5);
					$this->Line(0, 30, 450, 30);
					$this->SetFont('Arial', 'B', 16);
					$this->cell(0);
					$this->Cell(-430, 60, $this->title, 0, 0, 'C');
					$this->cell(0);
					$this->Cell(-430, 80, $this->subtitle, 0, 0, 'C');
				} else {
					// Portrait A4
					if (($this->w > 210) && ($this->w < 211)) {
						$this->Image('images/'.$company['leftlogofile'], 0, 5, 25);
						$this->SetFont('Arial', 'BI', 20);
						$this->cell(0);
						$this->Cell(-190, 0, $company['companyname'], 0, 0, 'C');
						$this->SetFont('Arial', '', 9);
						$this->cell(0);
						$this->Cell(-190, 15, 'Office: '.$company['address'], 0, 0, 'C');
						$this->cell(0);
						$this->Cell(-190, 23, 'Telp.: '.$company['phoneno'].' '.$company['faxno'],
							0, 0, 'C');
						$this->cell(0);
						$this->Cell(-190, 31,
							'Email: '.$company['email'].' Web : '.$company['webaddress'], 0, 0, 'C');
              if (($company['rightlogofile'] != null) && ($company['rightlogofile'] != '')) {
                $this->Image('images/'.$company['rightlogofile'], 185, 5, 25);
						}
						$this->SetLineWidth(0.5);
						$this->Line(0, 30, 300, 30);
						$this->SetFont('Arial', 'B', 16);
						$this->cell(0);
						$this->Cell(-190, 60, $this->title, 0, 0, 'C');
						$this->cell(0);
						$this->Cell(-190, 80, $this->subtitle, 0, 0, 'C');
					} else
					//Portrait Legal
					if (($this->w > 215) && ($this->w < 216)) {
						$this->Image('images/'.$company['leftlogofile'], 0, 5, 25);
						$this->SetFont('Arial', 'BI', 20);
						$this->cell(0);
						$this->Cell(-190, 0, $company['companyname'], 0, 0, 'C');
						$this->SetFont('Arial', '', 9);
						$this->cell(0);
						$this->Cell(-190, 15, 'Office: '.$company['address'], 0, 0, 'C');
						$this->cell(0);
						$this->Cell(-190, 23, 'Telp.: '.$company['phoneno'].' '.$company['faxno'],
							0, 0, 'C');
						$this->cell(0);
						$this->Cell(-190, 31,
							'Email: '.$company['email'].' Web : '.$company['webaddress'], 0, 0, 'C');
						if ($company['rightlogofile'] !== null) {
							$this->Image('images/'.$company['rightlogofile'], 185, 5, 25);
						}
						$this->SetLineWidth(0.5);
						$this->Line(0, 30, 300, 30);
						$this->SetFont('Arial', 'B', 16);
						$this->cell(0);
						$this->Cell(-190, 60, $this->title, 0, 0, 'C');
						$this->cell(0);
						$this->Cell(-190, 80, $this->subtitle, 0, 0, 'C');
					}
				}
			}
		}
		$this->sety($this->gety() + 50);
		$this->AliasNbPages();
	}
	//Page footer
	function Footer() {
		if ($this->isfooter == true) {
			if ($this->w < 350) {
				//Position at 1.5 cm from bottom
				$this->SetY(-15);
				//Arial italic 8
				$this->SetFont('Arial', 'I', 8);
				//Page number
				$this->SetLineWidth(0.5);
				$this->Line(0, $this->GetY(), 300, $this->GetY());
				$site	 = Yii::app()->db->createCommand("select paramvalue from parameter where paramname = 'sitetitle'")->queryRow();
				$tag	 = Yii::app()->db->createCommand("select paramvalue from parameter where paramname = 'tagline'")->queryRow();
				$this->Cell(0, 10,
					$site['paramvalue'].' - '.$tag['paramvalue'].'   Print : '.date('d-m-Y H:i:s').' By : '.Yii::app()->user->id,
					0, 0, 'L');
				$this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb} ', 0, 0, 'R');
			} else {
				//Position at 1.5 cm from bottom
				$this->SetY(-15);
				//Arial italic 8
				$this->SetFont('Arial', 'I', 8);
				//Page number
				$this->SetLineWidth(0.5);
				$this->Line(0, $this->GetY(), 450, $this->GetY());
				$site	 = Yii::app()->db->createCommand("select paramvalue from parameter where paramname = 'sitetitle'")->queryRow();
				$tag	 = Yii::app()->db->createCommand("select paramvalue from parameter where paramname = 'tagline'")->queryRow();
				$this->Cell(0, 10,
					$site['paramvalue'].' - '.$tag['paramvalue'].'   Print : '.date('d-m-Y H:i:s').' By : '.Yii::app()->user->id,
					0, 0, 'L');
				$this->Cell(0, 10, 'Page '.$this->PageNo().'/{nb} ', 0, 0, 'R');
			}
		}
	}
	function SetTableHeader() {
		//Colors, line width and bold font
		$this->SetFillColor(255, 0, 0);
		$this->SetTextColor(255);
		$this->SetDrawColor(128, 0, 0);
		$this->SetLineWidth(.3);
		$this->SetFont('', 'B');
	}
	function SetTableData() {
		//Color and font restoration
		$this->SetFillColor(224, 235, 255);
		$this->SetTextColor(0);
		$this->SetFont('');
	}
	function Row($data) {
		$this->setaligns($this->coldetailalign);
		$this->setFont('Arial', '', 8);
		//Calculate the height of the row
		$nb	 = 0;
		for ($i = 0; $i < count($data); $i++)
			$nb	 = max($nb, $this->NbLines($this->widths[$i], $data[$i]));
		$h	 = 5 * $nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		$k	 = 0;
		//Draw the cells of the row
		for ($i = 0; $i < count($data); $i++) {
			$w = $this->widths[$i];
			$a = $this->aligns[$i];
			$x = $this->GetX();
			$y = $this->GetY();
			$c = $this->bordercell[$i];
			if ($this->iscustomborder == false) {
				if ($c == '') {
					$c = 'LRTB';
				}
				$this->Rect($x, $y, $w, $h);
				$this->MultiCell($w, 5, $data[$i], 0, $a);
			}
			if ($this->iscustomborder == true) {
				//$this->Rect($x, $y, $w, $h);
				$this->MultiCell($w, 5, $data[$i], $c, $a);
			}
			$this->SetXY($x + $w, $y);
		}
		//Go to the next line
		$this->Ln($h);
	}
	function RowHeader() {
		$this->setaligns($this->colalign);
		//Calculate the height of the row
		$nb	 = 0;
		for ($i = 0; $i < count($this->colheader); $i++)
			$nb	 = max($nb, $this->NbLines($this->widths[$i], $this->colheader[$i]));
		$h	 = 5 * $nb;
		//Issue a page break first if needed
		$this->CheckPageBreak($h);
		$k	 = 0;
		//Draw the cells of the row
		$this->setFont('Arial', 'B', 8);
		for ($i = 0; $i < count($this->colheader); $i++) {
			$w = $this->widths[$i];
			$a = $this->aligns[$i];
			$x = $this->GetX();
			$y = $this->GetY();
			$c = $this->bordercell[$i];
			if ($this->iscustomborder == false) {
				if ($c == '') {
					$c = 'LRTB';
				}
				$this->Rect($x, $y, $w, $h);
				$this->MultiCell($w, 5, $this->colheader[$i], 0, $a);
			}
			if ($this->iscustomborder == true) {
				//$this->Rect($x, $y, $w, $h);
				$this->MultiCell($w, 5, $this->colheader[$i], $c, $a);
			}
			$this->SetXY($x + $w, $y);
		}
		//Go to the next line
		$this->Ln($h);
		$this->setaligns($this->coldetailalign);
	}
	function CheckPageBreak($h) {
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger) {
			$this->AddPage($this->CurOrientation, $this->CurPageFormat);
			//$this->ln(30);
			$this->RowHeader($this->colheader);
		}
	}
	function CheckNewPage($h) {
		//If the height h would cause an overflow, add a new page immediately
		if ($this->GetY() + $h > $this->PageBreakTrigger) {
			$this->AddPage($this->CurOrientation, $this->CurPageFormat);
		}
	}
	function NbLines($w, $txt) {
		//Computes the number of lines a MultiCell of width w will take
		$cw		 = &$this->CurrentFont['cw'];
		if ($w == 0) $w		 = $this->w - $this->rMargin - $this->x;
		$wmax	 = ($w - 2 * $this->cMargin) * 1000 / (($this->FontSize == 0) ? 12 : $this->FontSize);
		$s		 = str_replace("\r", '', $txt);
		$nb		 = strlen($s);
		if ($nb > 0 and $s[$nb - 1] == "\n") $nb--;
		$sep	 = -1;
		$i		 = 0;
		$j		 = 0;
		$l		 = 0;
		$nl		 = 1;
		while ($i < $nb) {
			$c = $s[$i];
			if ($c == "\n") {
				$i++;
				$sep = -1;
				$j	 = $i;
				$l	 = 0;
				$nl++;
				continue;
			}
			if ($c == ' ') $sep = $i;
			$l	 += $cw[$c];
			if ($l > $wmax) {
				if ($sep == -1) {
					if ($i == $j) $i++;
				} else $i	 = $sep + 1;
				$sep = -1;
				$j	 = $i;
				$l	 = 0;
				$nl++;
			} else $i++;
		}
		return $nl;
	}
	function RoundedRect($x, $y, $w, $h, $r, $corners = '1234', $style = '') {
		$k		 = $this->k;
		$hp		 = $this->h;
		if ($style == 'F') $op		 = 'f';
		elseif ($style == 'FD' || $style == 'DF') $op		 = 'B';
		else $op		 = 'S';
		$MyArc = 4 / 3 * (sqrt(2) - 1);
		$this->_out(sprintf('%.2F %.2F m', ($x + $r) * $k, ($hp - $y) * $k));

		$xc	 = $x + $w - $r;
		$yc	 = $y + $r;
		$this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - $y) * $k));
		if (strpos($corners, '2') === false)
				$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $y) * $k));
		else
				$this->_Arc($xc + $r * $MyArc, $yc - $r, $xc + $r, $yc - $r * $MyArc,
				$xc + $r, $yc);

		$xc	 = $x + $w - $r;
		$yc	 = $y + $h - $r;
		$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - $yc) * $k));
		if (strpos($corners, '3') === false)
				$this->_out(sprintf('%.2F %.2F l', ($x + $w) * $k, ($hp - ($y + $h)) * $k));
		else
				$this->_Arc($xc + $r, $yc + $r * $MyArc, $xc + $r * $MyArc, $yc + $r, $xc,
				$yc + $r);

		$xc	 = $x + $r;
		$yc	 = $y + $h - $r;
		$this->_out(sprintf('%.2F %.2F l', $xc * $k, ($hp - ($y + $h)) * $k));
		if (strpos($corners, '4') === false)
				$this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - ($y + $h)) * $k));
		else
				$this->_Arc($xc - $r * $MyArc, $yc + $r, $xc - $r, $yc + $r * $MyArc,
				$xc - $r, $yc);

		$xc	 = $x + $r;
		$yc	 = $y + $r;
		$this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $yc) * $k));
		if (strpos($corners, '1') === false) {
			$this->_out(sprintf('%.2F %.2F l', ($x) * $k, ($hp - $y) * $k));
			$this->_out(sprintf('%.2F %.2F l', ($x + $r) * $k, ($hp - $y) * $k));
		} else
				$this->_Arc($xc - $r, $yc - $r * $MyArc, $xc - $r * $MyArc, $yc - $r, $xc,
				$yc - $r);
		$this->_out($op);
	}
	function _Arc($x1, $y1, $x2, $y2, $x3, $y3) {
		$h = $this->h;
		$this->_out(sprintf('%.2F %.2F %.2F %.2F %.2F %.2F c ', $x1 * $this->k,
				($h - $y1) * $this->k, $x2 * $this->k, ($h - $y2) * $this->k,
				$x3 * $this->k, ($h - $y3) * $this->k));
	}
}
?>
