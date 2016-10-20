<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!--<link rel="stylesheet" href="<?php /*echo config_item('domain_static').'admin/';*/?>css/user/user.css">-->
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">系统设置</h3>
                    </div>
                    <div class="bg-gray color-palette">
                        &nbsp;
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <form action="" method="post">
                            <input type="hidden" name="dosave" value="telephone"/>
                            <div class="alert alert-info alert-dismissible">
                                <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
                                <h4><i class="glyphicon glyphicon-phone-alt"></i>客服电话设置</h4>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-right: 20px">
                                        <div class="input-group">
                                            <div class="input-group-addon">
                                                <i class="fa fa-phone"></i>
                                            </div>
                                            <input type="text" name="c-phone" id="c-phone" class="form-control" value="<?php echo $telephone;?>">
                                        </div>
                                    </div>
                                    <div class="col-xs-5">
                                        <input type="submit" class="btn btn-default btn-sm" value="保存">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
            <!-- /.col -->
        </div>

    </section>
    <!-- /.content -->
</div>
<!-- /.content-wrapper -->
<!-- Main Footer -->
<?php $this->load->view('foot');?>
<script src="<?php echo config_item('domain_static').'admin/';?>js/system/system.js"></script>
