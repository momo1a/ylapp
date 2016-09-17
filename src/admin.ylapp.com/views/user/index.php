<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>

<?php $this->load->view('user/ill_info');?>
<?php $this->load->view('user/order_info');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/user/user.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">用户管理</h3>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                        <div class="input-group">
                            <label for="nickname">用户昵称：</label>
                            <input type="search" name="nickname"   id="nickname" placeholder="请填写用户昵称关键字" value="<?php echo $get['nickname'];?>">
                            <label for="telephone">手机号码：</label>
                            <input type="search" name="telephone" id="telephone"   placeholder="请填手机号码" value="<?php echo $get['telephone']?>">
                            <input type="submit" id="submit" value="搜索">
                        </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>用户</th>
                                <th>手机号码</th>
                                <th>出生日期</th>
                                <th>病历</th>
                                <th>订单记录</th>
                                <th>余额</th>
                                <th>交易记录</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['nickname'];?></th>
                                        <th><?php echo $value['phone'];?></th>
                                        <th><?php echo date('Y-m-d',$value['birthday']);?></th>
                                        <th><a data-target="#ill_info" data-toggle="modal" uid="<?php echo $value['uid'];?>" onclick="getIllInfo(this);">详情</a></th>
                                        <th><a data-target="#order_info" data-toggle="modal" uid="<?php echo $value['uid'];?>" onclick="getOrderInfo(this);">详情</th>
                                        <th>￥<?php echo intval($value['amount']);?></th>
                                        <th>详情</th>
                                        <th>加入黑名单</th>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/user/user.js"></script>
