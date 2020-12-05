<div id="myCarousel" class="carousel slide" data-ride="carousel">
  <!-- Indicators -->
  <ol class="carousel-indicators">
		<?php
		for ($i = 0; $i <= $datacount - 1; $i++) {
			if ($i == 0) {
				echo '<li data-target="#myCarousel" data-slide-to="'.$i.'" class="active"></li>';
			} else {
				echo '<li data-target="#myCarousel" data-slide-to="'.$i.'"></li>';
			}
		}
		?>
  </ol>

  <!-- Wrapper for slides -->
  <div class="carousel-inner" role="listbox">
		<?php
		$i = 0;
		foreach ($datas as $data) {
			if ($i == 0) {
				echo '<div class="item active">';
				echo '<img src="'.Yii::app()->baseUrl.'/images/'.$data['slidepic'].'" alt="'.$data['slidetitle'].'">';
				echo '</div>';
				$i += 1;
			} else {
				echo '<div class="item">';
				echo '<img src="'.Yii::app()->baseUrl.'/images/'.$data['slidepic'].'" alt="'.$data['slidetitle'].'">';
				echo '</div>';
			}
		}
		?>
  </div>

  <!-- Left and right controls -->
  <a class="left carousel-control" href="#myCarousel" role="button" data-slide="prev">
    <span class="glyphicon glyphicon-chevron-left" aria-hidden="true"></span>
    <span class="sr-only">Previous</span>
  </a>
  <a class="right carousel-control" href="#myCarousel" role="button" data-slide="next">
    <span class="glyphicon glyphicon-chevron-right" aria-hidden="true"></span>
    <span class="sr-only">Next</span>
  </a>
</div>