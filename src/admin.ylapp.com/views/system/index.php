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
                                        <input type="button" onclick="setting('c-phone','telephone',$('#c-phone').val());return false;" class="btn btn-default btn-sm" value="保存">
                                    </div>
                                </div>
                            </div>
                        </form>
                        <hr/>

                        <form action="" method="post">
                            <div class="alert alert-info alert-dismissible">
                                <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
                                <h4><i class="glyphicon glyphicon-volume-up"></i>滚动消息设置</h4>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-right: 20px">
                                        <?php $patt = array('auto'=>'自动读取模式','manual'=>'手动添加模式');?>
                                        <select class="form-control" name="msg_pattern" id="msg_pattern">
                                            <?php foreach($patt as $key=>$val):?>
                                                <option value="<?php echo $key;?>" <?php if($key == $msgPattern){echo 'selected';}?>><?php echo $val;?></option>
                                            <?php endforeach;?>
                                        </select>
                                    </div>
                                    <div class="col-xs-5">
                                        <input type="button" onclick="setting('msg_pattern','rollmsg',$('#msg_pattern').val());return false;" class="btn btn-default btn-sm" value="保存">
                                    </div>
                                </div>
                            </div>
                        </form>


                        <form action="" method="post" name="user-manual-form" id="user-manual-form">
                            <div class="alert alert-info alert-dismissible" style="position: relative;">
                                <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
                                <h4><i class="glyphicon glyphicon-th-list"></i>用户使用手册设置</h4>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-right: 20px">
                                        <textarea id="user-manual" name="value">
                                            <?php echo $userManual;?>
                                        </textarea>
                                    </div>
                                    <div class="col-xs-5" style="position: absolute;left:50%;bottom: 15px">
                                        <input type="button" onclick="textSetting('user-manual-form',$('#user-manual').val());return false;" class="btn btn-default btn-sm" value="保存">
                                        <input type="hidden" name="dosave" value="user-manual-save"/>
                                    </div>
                                </div>
                            </div>
                        </form>


                        <form action="" method="post" name="agree-book-form" id="agree-book-form">
                            <div class="alert alert-info alert-dismissible" style="position: relative;">
                                <!--<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>-->
                                <h4><i class="glyphicon glyphicon-list-alt"></i>知情同意书设置</h4>
                                <div class="row">
                                    <div class="col-xs-5" style="margin-right: 20px">
                                        <textarea id="agree" name="agree">
                                            <?php echo $agree;?>
                                        </textarea>
                                    </div>
                                    <input type="hidden" name="dosave" value="agree-book-save"/>
                                    <div class="col-xs-5" style="position: absolute;left:50%;bottom: 15px" >
                                        <input type="button" onclick="textSetting('agree-book-form',$('#user-manual').val());return false;" class="btn btn-default btn-sm" value="保存">
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
<script>
    CKEDITOR.replace('user-manual');
    CKEDITOR.replace('agree');
    $(function(){

    })
</script>
