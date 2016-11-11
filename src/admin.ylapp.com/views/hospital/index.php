<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('hospital/hospital_detail');?>
<?php $this->load->view('hospital/hospital_add');?>
<?php $this->load->view('hospital/hospital_del');?>
<?php $this->load->view('hospital/hospital_edit');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/hospital/hospital.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">医院管理</h3>
                        <a data-target="#hospital_add" data-toggle="modal" onclick="hosAddPre();return false;"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加医院</h3></a>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" id="hospital-search" action="<?php echo site_url()?>hospital/index">
                            <div class="input-group">
                                <label for="nickname">医院名称：</label>
                                <input type="search" name="keyword"   placeholder="请输入医院名称关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 10px" size="30">
                                <input type="button" onclick="$('#hospital-search').submit()" value="搜索">
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>医院名称</th>
                                <th>地址</th>
                                <th>详情</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['hid'];?></th>
                                        <th><?php echo $value['name'];?></th>
                                        <th><?php echo $value['address'];?></th>
                                        <th><a data-target="#hospital_detail" data-toggle="modal" hid="<?php echo $value['hid'];?>" onclick="getHospitalDetail(this);" title="医院详情">详情</a></th>
                                        <th><a data-target="#hospital_del" data-toggle="modal"  hid="<?php echo $value['hid'];?>" onclick="hospitalDelPre(this);return
                                         false;" title="删除医院"><span class="glyphicon glyphicon-trash"></span></a>&nbsp;&nbsp;&nbsp;<a data-target="#hospital_edit" data-toggle="modal"  hid="<?php echo $value['hid'];?>" onclick="EditHospitalPre(this);return false;" title="编辑医院"><span class="glyphicon glyphicon-pencil"></span></a></th>
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
