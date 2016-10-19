<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>


<?php /*$this->load->view('doctor/doctor_detail');*/?><!--
<?php /*$this->load->view('doctor/order_info');*/?>
<?php /*$this->load->view('doctor/trade_info');*/?>
<?php /*$this->load->view('doctor/fee_setting');*/?>
--><?php /*$this->load->view('doctor/doctor_add');*/?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!--<link rel="stylesheet" href="<?php /*echo config_item('domain_static').'admin/';*/?>css/doctor/doctor.css">-->
    <style>
        a{
            cursor: pointer;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form method="get" action="" id="cashForm">
                    <div class="box-header">
                        <h3 class="box-title">提现管理</h3>
                        <button  href="javascript:void(0);" onclick="$('#doexport').val('yes')" style="float: right" class="btn primary-btn btn-xs"><span class="glyphicon glyphicon-save"></span>导出excel</button>
                    </div>
                    <div class="bg-gray color-palette">
                            <input type="hidden" name="doexport"  id="doexport" value="no"/>
                            <div class="input-group">
                                <label for="keyword">姓名：</label>
                                <input type="search" name="keyword"   id="keyword" placeholder="请填写用户姓名关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 10px" size="30">&nbsp;&nbsp;&nbsp;
                                <label for="userType">类型：</label>
                                <select  name="userType"  id="userType" style="margin-right: 10px;height: 25px">
                                    <?php foreach($userType as $key=>$val):?>
                                        <option value="<?php echo $key;?>" <?php if($get['userType'] == $key){echo 'selected';}?>><?php echo $val;?></option>
                                    <?php endforeach;?>
                                </select>&nbsp;&nbsp;&nbsp;

                                <label for="state">到款状态：</label>
                                <select  name="state"  id="state" style="margin-right: 10px;height: 25px">
                                    <?php foreach($state as $key=>$val):?>
                                        <option value="<?php echo $key;?>" <?php if($get['state'] == $key){echo 'selected';}?>><?php echo $val;?></option>
                                    <?php endforeach;?>
                                </select>&nbsp;&nbsp;&nbsp;
                                <input type="submit" onclick="$('#doexport').val('no');" id="submit" value="搜索">
                            </div>
                    </div>
                    </form>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>姓名</th>
                                <th>身份证号</th>
                                <th>提现银行</th>
                                <th>提现账户</th>
                                <th>开户地区</th>
                                <th>提现金额</th>
                                <th>申请时间</th>
                                <th>类型</th>
                                <th>当前状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['id'];?></th>
                                        <th><?php echo $value['realName'];?></th>
                                        <th><?php echo $value['identity'];?></th>
                                        <th><?php echo $value['bank'];?></th>
                                        <th><?php echo $value['cardNum'];?></th>
                                        <th><?php echo $value['address'];?></th>
                                        <th>￥<?php echo $value['amount'];?>元</th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['dateline']);?></th>
                                        <th><?php switch(intval($value['userType'])){
                                                case 1:
                                                    echo '用户端';
                                                    break;
                                                case 2:
                                                    echo '医生端';
                                                    break;
                                                default:
                                                    echo '未知';
                                            }?></th>
                                        <th><?php switch(intval($value['status'])){
                                                case 0:
                                                    echo '待处理';
                                                    break;
                                                case 1:
                                                    echo '已确认';
                                                    break;
                                                case 2:
                                                    echo '驳回';
                                                    break;
                                                default:
                                                    echo '未知状态';
                                            }?></th>
                                        <th><?php switch(intval($value['status'])){
                                                case 0:
                                                    echo '<a status="1" tid="'.$value['id'].'" onclick="setCashStat(this);return false;" >确认</a>&nbsp;&nbsp;<a status="2" tid="'.$value['id'].'" onclick="setCashStat(this);return false;" >驳回</a>';
                                                    break;
                                                case 2:
                                                   /* echo '<a status="1" tid="'.$value['id'].'" onclick="setCashStat(this);return false;" >确认</a>';*/
                                                    break;
                                                case 1:
                                                    /*echo '<a status="2" tid="'.$value['id'].'" onclick="setCashStat(this);return false;" >驳回</a>';*/
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/cash/cash.js"></script>
