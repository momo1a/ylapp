<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('medicine/appoint_add');?>
<?php /*$this->load->view('gene/package_del');*/?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/news/news.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">药品预约管理</h3>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="btn btn-primary btn-xs" style="margin-left: 20px"><a href="<?php echo site_url()?>medicine/index" style="color: #000000">返回药品管理</a></span>
                        <a data-target="#appoint_add" data-toggle="modal"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加预约</h3></a>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                                <!-- <label for="title">癌种分类：</label>
                                <input type="search" name="state" id="title"   placeholder="请输入分类名称关键字" value="<?php /*echo $get['keyword'];*/?>" style="margin-right: 20px" size="30">-->
                               <!-- <label for="illName">患者姓名：</label>
                                <input type="search" name="illName" id="illName"   placeholder="请输入患者姓名关键字" value="<?php /*echo $get['illName'];*/?>" style="margin-right: 20px" size="30">
                                <label for="telephone">患者手机：</label>
                                <input type="search" name="telephone" id="telephone"   placeholder="请输入患者手机号码关键字" value="<?php /*echo $get['telephone'];*/?>" style="margin-right: 20px" size="30">-->
                                <select name="search_key">
                                    <option value="illName">患者姓名</option>
                                    <option value="telephone">患者手机</option>
                                </select>
                                <input type="search" name="search-value"  value="<?php /*echo $get['telephone'];*/?>" style="margin-right: 20px" size="20">
                                <label for="mediName">药品名称：</label>
                                <input type="search" name="mediName" id="mediName"   placeholder="请输入药品名称关键字" value="<?php echo $get['mediName'];?>" style="margin-right: 20px" size="20">
                                <label for="startTime">预约时间：</label>
                                <input type="text" id="startTime"  size="20"/> -
                                <input type="text" id="endTime" style="margin-right: 20px" size="20"/>
                                <input type="submit" id="submit" value="搜索">
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>预约时间</th>
                                <th>手机号码</th>
                                <th>药品名</th>
                                <th>详情</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['id'];?></th>
                                        <th><?php echo $value['mediName'];?></th>
                                        <th><?php echo $value['outline'];?></th>
                                        <th>
                                            <a data-target="#medi_edit" data-toggle="modal"  mid="<?php echo $value['id'];?>" onclick="editMediPre(this);return false;" title="编辑药品"><span class="glyphicon glyphicon-pencil"></span></a><!--
                                            &nbsp;&nbsp;<a data-target="#package_del" data-toggle="modal"  pid="<?php /*echo $value['id'];*/?>" onclick="packageDelPre(this);return false;" title="删除套餐"><span class="glyphicon glyphicon-trash"></span></a>-->
                                            <!--&nbsp;&nbsp;<a href="#" nid="<?php /*echo $value['nid'];*/?>"   title="评论管理"><span class="glyphicon glyphicon-list"></span></a>-->
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
