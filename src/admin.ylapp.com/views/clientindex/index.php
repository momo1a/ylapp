<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('news/news_add');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/news/news.css">
    <style>
        .banner-content{
            margin-left: 25%;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">首页管理</h3>
                    </div>
                    <div class="bg-gray color-palette">
                        &nbsp;
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="row">
                            <div class="col-xs-6" style="text-align: center;">
                                <div class="col-xs-5 banner-content alert alert-success alert-dismissible">用户端</div>
                                <div class="alert alert-warning alert-dismissible">
                                <?php if(!empty($userClient)):foreach($userClient as $key=>$value):?>
                                    <div class="alert alert-info alert-dismissible">
                                        <div class="banner-img"><img src="<?php echo $imageServers[0].$value['banner'];?>" /></div>
                                        <span>标题：</span>
                                        <span><?php echo $value['title'];?></span>
                                        <span><a nid="<?php echo $value['nid'];?>">删除</a></span>
                                    </div>
                                <?php endforeach;endif;?>
                                </div>
                            </div>
                            <div class="col-xs-6" style="text-align: center">
                                <div class="col-xs-5 banner-content alert alert-success alert-dismissible">医生端</div>
                            </div>
                        </div>
                    </div>
                </div>
                <!--<div class="ui-paging-center" style="margin-top:20px;">
                    <div class="ui-paging"><?php /*echo $pager;*/?></div>
                </div>-->
            </div>
            <!-- /.col -->
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Main Footer -->
<?php $this->load->view('foot');?>
<script src="<?php echo config_item('domain_static').'admin/';?>js/news/news.js"></script>
