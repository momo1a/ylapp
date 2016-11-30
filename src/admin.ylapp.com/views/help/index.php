<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>

<?php $this->load->view('help/help_add');?>
<?php $this->load->view('help/help_edit');?>
<?php $this->load->view('help/help_del');?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/hospital/hospital.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">帮助管理</h3>
                        <a data-target="#help_add" data-toggle="modal" onclick="$('input[name=\'hid\']').val(0)"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加帮助</h3></a>
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
                                <th>标题</th>
                                <th>描述</th>
                                <th>操作时间</th>
                                <th>位置</th>
                                <th>是否显示</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th width="10%"><?php echo $value['id'];?></th>
                                        <th width="15%"><?php echo $value['title'];?></th>
                                        <th width="25%"><?php echo $value['description'];?></th>
                                        <th width="15%"><?php echo date('Y-m-d H:i:s',$value['dateline']);?></th>
                                        <th width="15%"><?php echo $pos[$value['type']];?></th>
                                        <th width="15%"><?php echo $isShow[$value['isShow']];?></th>
                                        <th width="15%">
                                            <a data-target="#help_edit" data-toggle="modal"  hid="<?php echo $value['id'];?>" onclick="editHelpPre(this);return false;" title="编辑帮助"><span class="glyphicon glyphicon-pencil"></span></a>
                                            &nbsp;&nbsp;<a data-target="#help_del" data-toggle="modal"  hid="<?php echo $value['id'];?>" onclick="$('#help_del input[name=\'hid\']').val(<?php echo $value['id'];?>);return false;" title="删除帮助"><span class="glyphicon glyphicon-trash"></span></a>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/help/help.js"></script>
