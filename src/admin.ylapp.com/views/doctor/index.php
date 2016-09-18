<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>

<?php $this->load->view('doctor/doctor_detail');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/user/user.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">医生管理</h3>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                                <label for="nickname">医生昵称：</label>
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
                                <th>编号</th>
                                <th>医生</th>
                                <th>电话一</th>
                                <th>出生日期</th>
                                <th>简介</th>
                                <th>订单记录</th>
                                <th>余额</th>
                                <th>交易记录</th>
                                <th>费用设置</th>
                                <th>当前状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['uid'];?></th>
                                        <th><?php echo $value['nickname'];?></th>
                                        <th><?php echo $value['phone'];?></th>
                                        <th><?php echo date('Y-m-d',$value['birthday']);?></th>
                                        <th><a data-target="#doctor_detail" data-toggle="modal" uid="<?php echo $value['uid'];?>" onclick="getDoctorDetail(this);">详情</a></th>
                                        <th><a data-target="#order_info" data-toggle="modal" uid="<?php echo $value['uid'];?>" onclick="getOrderInfo(this);">详情</a></th>
                                        <th>￥<?php echo intval($value['amount']);?></th>
                                        <th><a data-target="#trade_info" data-toggle="modal" uid="<?php echo $value['uid'];?>" onclick="getTradeInfo(this);">详情</a></th>
                                        <th><a data-target="#trade_info" data-toggle="modal" uid="<?php echo $value['uid'];?>" onclick="getTradeInfo(this);">详情</a></th>
                                        <th><?php switch(intval($value['doctorState'])){
                                                case 0:
                                                    echo '待审核';
                                                    break;
                                                case 1:
                                                    echo '已通过';
                                                    break;
                                                case 2:
                                                    echo '未通过';
                                                    break;
                                                default:
                                                    echo '未知状态';
                                            }?></th>
                                        <th><?php switch(intval($value['doctorState'])){
                                                case 0:
                                                    echo '<a state="1" uid="'.$value['uid'].'" onclick="setDoctorStat(this);return false;" >通过</a>&nbsp;&nbsp;<a state="2" uid="'.$value['uid'].'" onclick="setDoctorStat(this);return false;" >不通过</a>';
                                                    break;
                                                case 2:
                                                    echo '<a state="1" uid="'.$value['uid'].'" onclick="setDoctorStat(this);return false;" >通过</a>';
                                                    break;
                                                case 1:
                                                    echo '<a state="2" uid="'.$value['uid'].'" onclick="setDoctorStat(this);return false;" >不通过</a>';
                                                    break;
                                                default:
                                                    echo '状态异常';
                                            }?></th>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/doctor/doctor.js"></script>
