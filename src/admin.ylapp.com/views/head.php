<!DOCTYPE html>
<!--
This is a starter template page. Use this page to start your new project from
scratch. This page gets rid of all links and provides the needed markup only.
-->
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>移动医疗后台管理</title>
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.6 -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>bootstrap/css/bootstrap.min.css">
    <!-- Font Awesome -->
    <!--<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.5.0/css/font-awesome.min.css">-->
    <!-- Ionicons -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>third/font_awesome.min.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>third/ionicons.min.css">
    <!-- Theme style -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>dist/css/AdminLTE.min.css">
    <!-- AdminLTE Skins. We have chosen the skin-blue for this starter
          page. However, you can choose any other skin. Make sure you
          apply the skin class to the body tag so the changes take effect.
    -->
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>dist/css/skins/skin-blue.min.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/datatables/dataTables.bootstrap.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/iCheck/all.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/fileinput/fileinput.min.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/datepicker/datepicker3.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/timepicker/bootstrap-timepicker.min.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/date/jquery.datetimepicker.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>dist/css/skins/_all-skins.min.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>plugins/bootstrap-wysihtml5/bootstrap3-wysihtml5.min.css">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/common.css">

    <script type="text/javascript">
        var STATIC_URL = "<?php echo config_item('domain_static');?>";
    </script>
    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<!--
BODY TAG OPTIONS:
=================
Apply one or more of the following classes to get the
desired effect
|---------------------------------------------------------|
| SKINS         | skin-blue                               |
|               | skin-black                              |
|               | skin-purple                             |
|               | skin-yellow                             |
|               | skin-red                                |
|               | skin-green                              |
|---------------------------------------------------------|
|LAYOUT OPTIONS | fixed                                   |
|               | layout-boxed                            |
|               | layout-top-nav                          |
|               | sidebar-collapse                        |
|               | sidebar-mini                            |
|---------------------------------------------------------|
-->
<body class="hold-transition skin-blue sidebar-mini">
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo site_url();?>" class="logo">
            <!-- mini logo for sidebar mini 50x50 pixels -->
            <span class="logo-mini">YL</span>
            <!-- logo for regular state and mobile devices -->
            <span class="logo-lg">移动医疗后台管理</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top" role="navigation">
            <!-- Sidebar toggle button-->
            <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                <span class="sr-only">Toggle navigation</span>
            </a>
            <!-- Navbar Right Menu -->
            <div class="navbar-custom-menu">
                <ul class="nav navbar-nav">
                    <!-- Notifications Menu -->
                    <li class="dropdown notifications-menu">
                        <!-- Menu toggle button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <i class="fa fa-bell-o"></i>
                            <span class="label label-warning"><?php echo $vars[2]['count'];?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="header">您有<?php echo $vars[2]['count'];?>个通知</li>
                            <li>
                                <!-- Inner Menu: contains the notifications -->
                                <ul class="menu">
                                    <?php
                                    function msgTemplate($href,$content)
                                    {
                                        $msgTemplate = ' <li><a href="'.$href.'"><i class="fa fa-users text-aqua"></i>'.$content.'</a></li>';
                                        return $msgTemplate;
                                    }
                                    ?>
                                    <?php if(!empty($vars[2]['msg'])): foreach($vars[2]['msg'] as $val): ?>
                                         <?php
                                        switch($val['msgType']){
                                            case '帖子':
                                                echo msgTemplate(site_url().'post/index?keyword='.$val['postTitle'],mb_substr($val['postTitle'],0,10).'...');
                                                break;
                                            case '评论':
                                                echo msgTemplate(site_url().'post/commentList?keyword='.$val['recmdContent'],mb_substr($val['recmdContent'],0,10).'...');
                                                break;

                                        }?>
                                    <?php  endforeach;endif;?>
                                    <!-- end notification -->
                                </ul>
                            </li>
                            <!--<li class="footer"><a href="#">View all</a></li>-->
                        </ul>
                    </li>
                    <!-- User Account Menu -->
                    <li class="dropdown user user-menu">
                        <!-- Menu Toggle Button -->
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <!-- The user image in the navbar-->
                            <img src="<?php $imgServer = config_item('image_servers'); echo $vars[1][0]['avatar'] ?  $imgServer[0]. $vars[1][0]['avatar'] : config_item('domain_static').'admin/dist/img/avatar_default.jpg';?>" class="user-image" alt="User Image">
                            <!-- hidden-xs hides the username on small devices so only the image appears. -->
                            <span class="hidden-xs"><?php echo  $vars[1][0]['nickname'];?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <!-- The user image in the menu -->
                            <li class="user-header">
                                <img src="<?php $imgServer = config_item('image_servers'); echo  $vars[1][0]['avatar'] ?  $imgServer[0]. $vars[1][0]['avatar'] : config_item('domain_static').'admin/dist/img/avatar_default.jpg';?>" class="img-circle" alt="用户头像">

                                <p>
                                    <?php echo  $vars[1][0]['nickname'].' - '. $vars[1][0]['role']; ?>
                                    <small><?php echo date('Y-m-d H:i:s');?></small>
                                </p>
                            </li>
                            <!-- Menu Body -->
                            <!--<li class="user-body">
                                <div class="row">
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Followers</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Sales</a>
                                    </div>
                                    <div class="col-xs-4 text-center">
                                        <a href="#">Friends</a>
                                    </div>
                                </div>
                            </li>-->
                            <!-- Menu Footer-->
                            <li class="user-footer">
                               <!-- <div class="pull-left">
                                    <a href="#" class="btn btn-default btn-flat">简介</a>
                                </div>-->
                                <div class="pull-right">
                                    <a href="<?php echo site_url()?>/home/logout" class="btn btn-default btn-flat">退出</a>
                                </div>
                            </li>
                        </ul>
                    </li>
                    <!-- Control Sidebar Toggle Button -->
                    <!--<li>
                        <a href="#" data-toggle="control-sidebar"><i class="fa fa-gears"></i></a>
                    </li>-->
                </ul>
            </div>
        </nav>
    </header>