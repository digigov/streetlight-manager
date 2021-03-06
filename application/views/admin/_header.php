<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?><!DOCTYPE html>
<html lang="zh-TW">
  <head>
  <meta charset="utf-8">
  <title> 
      <?php if(isset($pageTitle) ){ ?>
          <?=h($pageTitle)?> | <?=SITE_TITLE?>
      <?php }else{ ?>
          <?=SITE_TITLE?>
      <?php } ?>
  </title>

    <?php if(isset($og_desc)) { ?>
    <meta name="description" content="<?=h($og_desc)?>" /> 
    <?php } ?>

    <!-- Mobile Specifics -->
    <meta name="viewport" content="width=device-width, initial-scale=1" />

    <!--給FB看的設定-->
    <?php if(isset($og_image)){ ?>
<meta property="og:image" content="<?=$og_image?>" />
    <?php } ?>

<meta property="og:type" content="website" />
<meta property="fb:app_id" content="1631332217194428" />
<!-- <meta property="fb:admins" content="107507809341864"/> -->

  <meta property="og:site_name" content="<?=SITE_TITLE?>" />


    <?php if(isset($pageTitle) ){ ?>
        <meta property="og:title" content="<?=h($pageTitle)?> | <?=SITE_TITLE?>" />
    <?php }else{ ?>
        <meta property="og:title" content="<?=SITE_TITLE?>" />
    <?php } ?>

    <?php if(isset($og_url)) { ?>
        <meta property="og:url" content="<?=h($og_url)?>" />
    <?php } ?>

    <?php if(isset($og_desc)) { ?>
    <meta name="og:description" content="<?=h($og_desc)?>" /> 
    <?php } ?>

<?php if(0){ ?>
    <!-- Fav Icon -->
    <link rel="shortcut icon" href="<?=cdn_url("sys_images/favicon.ico") ?>" type="image/x-icon" />
    <link rel="icon" href="<?=cdn_url("sys_images/favicon.ico") ?>" type="image/x-icon" />
<?php } ?>

  <!-- Latest compiled and minified CSS -->
  <link rel="stylesheet" href="<?=base_url("bootstrap/css/bootstrap.min.css")?>" />

  <link rel="stylesheet" href="<?=base_url("bootstrap/css/bootstrap-theme.min.css")?>" />


  <link rel="stylesheet" href="<?=base_url("css/main.css")?>" />


<?php if(function_exists("css_section")) {
  css_section();
  }?>
</head>
<body>


<nav class="navbar navbar-default">
  <div class="container-fluid">
    <!-- Brand and toggle get grouped for better mobile display -->
    <div class="navbar-header">
      <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1" aria-expanded="false">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?=site_url("/")?>"><?=SITE_TITLE?></a>
    </div>

    <!-- Collect the nav links, forms, and other content for toggling -->
    <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
      <ul class="nav navbar-nav">
        <li><a href="<?=site_url("/admin/light/index")?>">後台首頁</a></li>
        <li><a href="<?=site_url("/admin/light/point_list")?>">路燈管理</a></li>
        <li><a href="<?=site_url("/admin/light/reports")?>">過去三個月報修紀錄</a></li>
        <li><a href="<?=site_url("/admin/light/repair")?>">維修中路燈</a></li>
      </ul>

      <ul class="nav navbar-nav pull-right">
        <li>
          <a href="<?=site_url("/admin/light/index")?>"><?=$_SESSION["user"]->city?></a>
        </li>
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown" href="#"><?=h($_SESSION["user"]->acc)?>
          <span class="caret"></span></a>
          <ul class="dropdown-menu">
            <li><a href="<?=site_url("/admin/user/line_connect")?>">綁定 line 回報通知</a></li>
            <li><a href="<?=site_url("/admin/user/line_bind2_connect")?>">綁定 line LED 回報</a></li>
            <li><a href="<?=site_url("/admin/user/pass")?>">改密碼</a></li>
            <li><a href="<?=site_url("/admin/user/logout")?>">登出</a></li>
          </ul>
        </li>
      </ul>
    </div><!-- /.navbar-collapse -->
  </div><!-- /.container-fluid -->
</nav>
