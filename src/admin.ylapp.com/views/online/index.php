<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('online/time_update');?>



<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!--<link rel="stylesheet" href="<?php /*echo config_item('domain_static').'admin/';*/?>css/news/news.css">-->
    <style>
        .btn-action{
            margin-bottom: 5px;
            display: block;
        }
        tbody{
            vertical-align: middle;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">电话问诊管理</h3>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <!--<span class="btn btn-primary"><a href="<?php /*echo site_url()*/?>post/commentList" style="color: #000000">评论管理</a></span>-->
                        <!--<a data-target="#package_add" data-toggle="modal" onclick="packageAddPre();return false;"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加套餐</h3></a>-->
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                                <label for="title">就诊人姓名：</label>
                                <input type="search" name="keyword" id="title"   placeholder="请输入就诊人姓名关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 20px" size="30">
                                <label for="state">状态：</label>
                                <select  id="state" name="state" style="height: 25px;margin-right: 20px">
                                    <?php foreach($state as $key=>$value):?>
                                        <option value="<?php echo $key;?>" <?php if($get['state'] == $key){ echo 'selected';}?>><?php echo $value;?></option>
                                    <?php endforeach;?>
                                </select>
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
                                <th>就诊人</th>
                                <th>性别</th>
                                <th>年龄</th>
                                <th>就诊人电话</th>
                                <th>指定医生</th>
                                <th>指定医生电话</th>
                                <th>医生职位</th>
                                <th>所属医院</th>
                                <th>医院地址</th>
                                <th>沟通时长</th>
                                <th>下单时间</th>
                                <th>期望沟通时间</th>
                                <th>价格</th>
                                <th>当前状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $sex = array('1'=>'男','2'=>'女');
                            if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['aid'];?></th>
                                        <th><?php echo $value['askNickname'];?></th>
                                        <th><?php echo $sex[$value['asex']];?></th>
                                        <th><?php echo $value['aage'];?></th>
                                        <th><?php echo $value['askTelephone'];?></th>
                                        <th><?php echo $value['nickname'];?></th>
                                        <th><?php echo $value['phone'];?></th>
                                        <th><?php echo $value['docLevel'].'('.$value['officeName'].')';?></th>
                                        <th><?php echo $value['name'];?></th>
                                        <th><?php echo $value['address'];?></th>
                                        <th><?php echo $value['phoneTimeLen'];?>分钟</th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['atime']);?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['hopeCalldate']);?></th>
                                        <th><?php echo $value['price'];?>元</th>
                                        <th><?php echo $state[$value['astatus']];?></th>
                                        <th>
                                           <?php switch(intval($value['astatus'])){
                                               case 1 :  // 已付款 待处理
                                                   /**/
                                                   $action = '<button onclick="setOrderStat(this);return false;" oid="'.$value['aid'].'"  status="2" class="btn btn-primary btn-xs btn-action">确认沟通时间</button>';
                                                   $action.= '<button data-target="#time_update" data-toggle="modal" onclick="updateATimePre(this);return false;" oid="'.$value['aid'].'" class="btn btn-primary btn-xs btn-action">修改预约时间</button>';
                                                   echo $action;
                                                   break;
                                               case 2:
                                                   $action =  '<button onclick="setOrderStat(this);return false;" oid="'.$value['aid'].'"  status="3" class="btn btn-primary btn-xs btn-action">完成</button>';
                                                   $action.= '<button onclick="setOrderStat(this);return false;"  oid="'.$value['aid'].'"  status="4" class="btn btn-primary btn-xs btn-action">失败</button>';
                                                   echo $action;
                                                   break;
                                              /* case 6 :
                                                   $action = '<button onclick="setOrderStat(this);return false;"  oid="'.$value['aid'].'"  status="4" class="btn btn-primary btn-xs btn-action">预约失败</button>';
                                                   echo $action;
                                                   break;*/
                                               default:
                                                   echo '';

                                           }?>

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
<script src="<?php echo config_item('domain_static').'admin/';?>js/online/online.js"></script>