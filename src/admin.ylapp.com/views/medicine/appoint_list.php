<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('medicine/appoint_add');?>
<?php $this->load->view('medicine/appoint_detail');?>
<?php $this->load->view('medicine/appoint_allot');?>
<?php /*$this->load->view('gene/package_del');*/?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/news/news.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <form method="get" action="">
                    <div class="box-header">
                        <h3 class="box-title">药品预约管理</h3>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="btn btn-primary btn-xs" style="margin-left: 20px"><a href="<?php echo site_url()?>medicine/index" style="color: #000000">返回药品管理</a></span>
                        <button  href="javascript:void(0);" onclick="$('#doexport').val('yes')" style="float: right;margin-left: 15px" class="btn primary-btn btn-xs"><span class="glyphicon glyphicon-save"></span>导出excel</button>
                        <a data-target="#appoint_add" data-toggle="modal" onclick="appointAddPre();return false;"><button class="btn primary-btn btn-xs" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加预约</button></a>
                    </div>
                    <div class="bg-gray color-palette">
                            <div class="input-group">
                                <input type="hidden" name="doexport"  id="doexport" value="no"/>
                                <select name="search-key">
                                   <?php foreach($search as $key=>$value):?>
                                       <option value="<?php echo $key;?>" <?php if($key == $get['search-key']) {echo 'selected';}?>><?php echo $value;?></option>
                                    <?php endforeach;?>
                                </select>
                                <input type="search" name="search-value"  value="<?php echo $get['search-value'];?>" style="margin-right: 20px" size="20">
                                <label for="mediName">药品名称：</label>
                                <input type="search" name="mediName" id="mediName"   placeholder="请输入药品名称关键字" value="<?php echo $get['mediName'];?>" style="margin-right: 20px" size="20">
                                <label for="startTime">预约时间：</label>
                                <input type="text" id="startTime"  name="startTime" size="20" value="<?php echo $get['startTime'] ? $get['startTime']: date('Y-m-d H:i:s',strtotime('today')); ?>"/> -
                                <input type="text" id="endTime" name="endTime" style="margin-right: 20px" size="20" value="<?php echo $get['startTime'] ? $get['endTime']: date('Y-m-d H:i:s'); ?>"/>
                                <input type="submit" id="submit" onclick="$('#doexport').val('no');" value="搜索">
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>预约时间</th>
                                <th>患者手机</th>
                                <th>患者姓名</th>
                                <th>药品名</th>
                                <th>药房伙计</th>
                                <th>详情</th>
                                <th>当前状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['aid'];?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['appointTime']);?></th>
                                        <th><?php echo $value['telephone'];?></th>
                                        <th><?php echo $value['illName'];?></th>
                                        <th><?php echo $value['mediName'];?></th>
                                        <th><?php echo !$value['guysName'] ? '暂未分配' : $value['guysName'];?></th>
                                        <th>
                                            <a data-target="#appoint_detail" data-toggle="modal"  aid="<?php echo $value['aid'];?>" onclick="appointDetailPre(this);return false;" title="预约详情"><span class="glyphicon glyphicon-list-alt"></span></a><!--
                                            &nbsp;&nbsp;<a data-target="#package_del" data-toggle="modal"  pid="<?php /*echo $value['id'];*/?>" onclick="packageDelPre(this);return false;" title="删除套餐"><span class="glyphicon glyphicon-trash"></span></a>-->
                                            <!--&nbsp;&nbsp;<a href="#" nid="<?php /*echo $value['nid'];*/?>"   title="评论管理"><span class="glyphicon glyphicon-list"></span></a>-->
                                        </th>
                                        <th><?php echo $state[$value['appointState']];?></th>
                                        <th>
                                            <?php if($value['appointState'] == 0):?>
                                            <a data-target="#appoint_allot" data-toggle="modal" aid="<?php echo $value['aid'];?>" onclick="appointAllotPre(this);return false;"><span class="glyphicon glyphicon-compressed" style="cursor: pointer"></span>分配</a>
                                            <?php else: ?>
                                                <span>已分配伙计</span>
                                            <?php endif;?>
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
<script>
    $('#startTime').datetimepicker({
        format:"Y-m-d H:i:s",      //格式化日期
        lang:"ch"
    });
    $('#endTime').datetimepicker({
        format:"Y-m-d H:i:s",      //格式化日期
        lang:"ch"
    });
</script>
<script src="<?php echo config_item('domain_static').'admin/';?>js/medi/appoint.js"></script>
