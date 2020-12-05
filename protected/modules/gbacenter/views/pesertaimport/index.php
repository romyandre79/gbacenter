//views/mahasiswa/_formimport.php
<script src="<?php echo Yii::app()->baseUrl; ?>/js/gbacenter/peserta.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/bootstrap/js/bootstrap.bundle.min.js"></script>
<div class="form">
<?php $form=$this->beginWidget('CActiveForm', array(
    'id'=>'import-form',
    'enableAjaxValidation'=>false,
    'htmlOptions'=> array('enctype'=>'multipart/form-data'),
)); ?>
<?php echo "test"; ?>
<div class="row">
        <?php echo $form->labelEx($model,'file_excel'); ?>
        <?php echo $form->Filefield($model,'file_excel'); ?>
        <?php echo $form->error($model,'file_excel'); ?>
    </div>