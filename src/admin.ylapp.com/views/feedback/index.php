<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('hospital/hospital_detail');?>
<?php $this->load->view('hospital/hospital_add');?>
<?php $this->load->view('hospital/hospital_del');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/hospital/hospital.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">用户反馈</h3>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" id="feedback-search" action="<?php echo site_url()?>feedback/index">
                            <div class="input-group">
                                <label for="nickname">用户名：</label>
                                <input type="search" name="keyword"   placeholder="请输入反馈用户名关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 10px" size="30">
                                <input type="button" onclick="$('#feedback-search').submit()" value="搜索">
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>反馈内容</th>
                                <th>反馈时间</th>
                                <th>用户名</th>
                                <th>位置</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['id'];?></th>
                                        <th><?php echo $value['content'];?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['feedDate']);?></th>
                                        <th><?php echo $value['nickname'];?></th>
                                        <?php $pos = array('1'=>'用户端','2'=>'医生端');?>
                                        <th><?php echo $pos[$value['userType']];?></th>
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
