<?php
class UsertodoController extends AdminController {
	public function actionPurge() {
		$connection	 = Yii::app()->db;
		$transaction = $connection->beginTransaction();
		try {
			$sql = "delete from usertodo where usertodoid = ".$_POST['id'];
			Yii::app()->db->createCommand($sql)->execute();
			$transaction->commit();
			getMessage('success', 'alreadysaved');
		} catch (Exception $e) {
			$transaction->rollback();
			getMessage('error', $e->getMessage());
		}
	}
	public function actionGetTask() {
		if (getparameter('usingrealtimenotif') == 1) {
			echo '<a href="#" class="dropdown-toggle" data-toggle="dropdown">';
			echo '<i class="fa fa-flag-o"></i>';
			echo '<span class = "label label-danger">'.GetTotalTodo().'</span>';
			echo '</a>';
			echo '<ul class="dropdown-menu">';
			echo '<li class="header">'.getCatalog('youhave').' '.GetTotalTodo().' tasks</li>';
			echo '<li>';
			echo '<ul class="menu">';
			$todos = GetTodos();
			foreach ($todos as $todo) {
				echo '<li>';
				echo '<a href="'.Yii::app()->createUrl(getMenuModule($todo['menuname']).'/index',
					array('name' => $todo['usertodoid'])).'">';
				echo '<p>';
				echo $todo['description'].' <span class="label label-primary">DOC/ID '.$todo['docno'].'</span>';
				echo '</p>';
				echo '</a>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</li>';
			echo '<li class="footer">';
			echo '<a href="'.Yii::app()->createUrl('admin').'">'.getCatalog('viewalltask').'</a>';
			echo '</li>';
			echo '</ul>';
		}
	}
}