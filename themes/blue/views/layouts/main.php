<!DOCTYPE html>
<html lang="en">
<head>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/jquery/jquery-3.5.1.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/jquery-ui/jquery-ui.min.js"></script>
  <?php display_seo($this->metatag, $this->description, $this->pageTitle) ?>
</head>
<body class="hold-transition sidebar-mini layout-fixed">
<div class="wrapper">

  <!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-white navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
      <li class="nav-item">
        <a aria-label="-" class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->

  <!-- Main Sidebar Container -->
  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <!-- Brand Logo -->
    <a href="<?php echo Yii::app()->createUrl('admin') ?>" class="brand-link">
      <img src="<?php echo Yii::app()->request->baseUrl;?>/images/logo.jpg" alt="Capella CMS Logo" class="brand-image img-circle elevation-3 lazyload"
           style="opacity: .8">
      <span class="brand-text font-weight-light">Capella CMS</span>
    </a>

    <!-- Sidebar -->
    <div class="sidebar">
      <!-- Sidebar user panel (optional) -->
      <?php if (Yii::app()->user->id != '') { ?>
      <div class="user-panel mt-3 pb-3 mb-3 d-flex">
        <div class="image">
          <img src="<?php echo Yii::app()->request->baseUrl ?>/images/useraccess/<?php echo getuserdata()['userphoto'] ?>" class="img-circle elevation-2 lazyload" alt="User Image">
        </div>
        <div class="info">
          <a href="#" class="d-block"><?php echo Yii::app()->user->id?></a>
        </div>
      </div>
      <?php }?>

      <!-- Sidebar Menu -->
      <?php if (Yii::app()->user->id != '') { ?>
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        <?php
						foreach (getUserSuperMenu() as $row) {
              if ($row['jumlah'] > 0) { ?>
              <li class="nav-item has-treeview">
            <a href="#" class="nav-link">
              <i class="nav-icon fas fa-copy"></i>
              <p>
                <?php echo $row['menutitle']?>
                <i class="fas fa-angle-left right"></i>
              </p>
            </a>
                <ul class="nav nav-treeview">
								<?php foreach (getUserMenu($row['menuaccessid']) as $menu) {?>
              <li class="nav-item">
                <a href="<?php echo Yii::app()->createUrl($menu['modulename'].'/'.$menu['menuurl'])?>" class="nav-link">
                  <i class="far fa-circle nav-icon"></i>
                  <p><?php echo getCatalog($menu['menuname'])?></p>
                </a>
              </li>
                <?php }?>
              </ul>
              <?php } else { ?>
                <li class="nav-item">
                <a href="#" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
              <?php echo $row['menutitle']?>
              </p>
            </a>
              <?php }?>
              </li>
          <?php } ?>    
          <li class="nav-item">
                <a href="<?php echo Yii::app()->createUrl('site/logout')?>" class="nav-link">
              <i class="nav-icon fas fa-th"></i>
              <p>
              Logout
              </p>
              </a>
              </li>                
        </ul>
      </nav>
              <?php }?>
      <!-- /.sidebar-menu -->
    </div>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper bg-white">
  <div class="content-header">
      <div class="container-fluid">
      </div><!-- /.container-fluid -->
    </div>
    <section class="content">
      <div class="container-fluid">
    <?php echo $content?>
      </div>
    </section>
  </div>
              </div>
  <!-- /.content-wrapper -->
  <footer class="main-footer bg-white">
    <strong>Copyright &copy; 2015 - 2020 <a href="#" style="color: #000000"><?php echo getparameter('sitename') ?></a> All rights reserved.</strong>
  </footer>

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark">
    <!-- Control sidebar content goes here -->
  </aside>
  <!-- /.control-sidebar -->
</div>
<!-- ./wrapper -->
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl; ?>/plugins/toastr/toastr.min.css">
<link rel="stylesheet" href="<?php echo Yii::app()->theme->baseUrl;?>/plugins/fontawesome-free/css/all.min.css">
<script async src="<?php echo Yii::app()->request->baseUrl;?>/js/lazysizes.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/js/adminlte.min.js"></script>
<script src="<?php echo Yii::app()->theme->baseUrl;?>/plugins/toastr/toastr.min.js"></script>  
</body>
</html>
