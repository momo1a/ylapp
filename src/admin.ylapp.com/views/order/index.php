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
                            <h3 class="box-title">订单管理</h3>
                            <!--<button  href="javascript:void(0);" onclick="$('#doexport').val('yes')" style="float: right" class="btn primary-btn"><span class="glyphicon glyphicon-save"></span>导出excel</button>-->
                        </div>
                        <div class="bg-gray color-palette">
                            <input type="hidden" name="doexport"  id="doexport" value="no"/>
                            <div class="input-group">
                                <label for="keyword">购买者：</label>
                                <input type="search" name="keyword"   id="keyword" placeholder="请填写购买者关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 10px" size="30">&nbsp;&nbsp;&nbsp;
                                <label for="type">类型：</label>
                                <select  name="type"  id="type" style="margin-right: 10px;height: 25px">
                                    <?php foreach($type as $key=>$val):?>
                                        <option value="<?php echo $key;?>" <?php if($get['type'] == $key){echo 'selected';}?>><?php echo $val;?></option>
                                    <?php endforeach;?>
                                </select>&nbsp;&nbsp;&nbsp;

                                <label for="state">状态：</label>
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
                                <th>订单号</th>
                                <th>购买人</th>
                                <th>电话</th>
                                <th>性别</th>
                                <th>出生日期</th>
                                <th>下单时间</th>
                                <th>套餐</th>
                                <th>价格</th>
                                <th>类别</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sex = array(1=>'男',2=>'女');?>

                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['oid'];?></th>
                                        <th><?php echo $value['buyerName'];?></th>
                                        <th><?php echo $value['buyerTel'];?></th>
                                        <th><?php echo $sex[$value['sex']]?></th>
                                        <th><?php echo date('Y-m-d',$value['buyerBrithday']);?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['dateline']);?></th>
                                        <th><?php echo $value['packageTitle'];?></th>
                                        <th>￥<?php echo $value['price'];?>元</th>
                                        <th><?php echo $type[$value['type']];?></th>
                                        <th><?php echo $state[$value['orderStatus']];?></th>
                                        <th><?php switch(intval($value['orderStatus'])){
                                                case 2:
                                                    echo '<a status="4" oid="'.$value['oid'].'" onclick="setOrderStat(this);return false;" >通知</a>';
                                                    break;
                                                case 4:
                                                    echo '<a status="5" oid="'.$value['oid'].'" onclick="setOrderStat(this);return false;" >完成</a>';
                                                    break;
                                                default:
                                                    echo '';
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/order/order.js"></script>
