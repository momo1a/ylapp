<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>

<?php $this->load->view('hospital/office_add');?>
<?php $this->load->view('hospital/office_edit');?>
<?php /*$this->load->view('hospital/office_del');*/?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/hospital/hospital.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">科室管理</h3>
                        &nbsp;&nbsp;&nbsp;
                        <a href="<?php echo site_url();?>hospital/index"><button class="btn btn-xs btn-primary">返回医院列表</button></a>
                        <a data-target="#office_add" data-toggle="modal" onclick="$('input[name=\'hid\']').val(0)"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加科室</h3></a>
                    </div>
                    <div class="bg-gray color-palette">
                        <!--<form method="get" id="feedback-search" action="<?php  /*echo site_url()*/ ?>feedback/index">
                            <div class="input-group">
                                <label for="nickname">用户名：</label>
                                <input type="search" name="keyword"   placeholder="请输入反馈用户名关键字" value="<?php  /*echo $get['keyword'];*/ ?>" style="margin-right: 10px" size="30">
                                <input type="button" onclick="$('#feedback-search').submit()" value="搜索">
                            </div>
                        </form>-->

                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>科室名称</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th width="10%"><?php echo $value['id'];?></th>
                                        <th width="15%"><?php echo $value['officeName'];?></th>
                                        <th width="15%">
                                            <a data-target="#help_edit" data-toggle="modal"  hid="<?php echo $value['id'];?>" onclick="editHelpPre(this);return false;" title="编辑科室"><span class="glyphicon glyphicon-pencil"></span></a>
                                           <!-- &nbsp;&nbsp;<a data-target="#help_del" data-toggle="modal"  hid="<?php /*echo $value['id'];*/?>" onclick="$('#office_del input[name=\'hid\']').val(<?php /*echo $value['id'];*/?>);return false;" title="删除帮助"><span class="glyphicon glyphicon-trash"></span></a>-->
                                        </th>
                                    </tr>
                                <?php }}?>
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="ui-paging-center" style="margin-top:20px;">
                    <div class="ui-paging"><?php echo $pager;?></div>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/hospital/hospital.js"></script>
