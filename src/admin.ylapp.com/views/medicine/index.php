<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('medicine/medi_add');?>
<?php $this->load->view('medicine/cate_add');?>
<?php $this->load->view('medicine/medi_edit');?>
<?php $this->load->view('medicine/medi_del');?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/news/news.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">药品管理</h3>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="btn btn-primary btn-xs"><a data-target="#cate_add" data-toggle="modal"  style="color: #000000">添加分类</a></span>
                        <span class="btn btn-primary btn-xs" style="margin-left: 20px"><a href="<?php echo site_url()?>medicine/appointList" style="color: #000000">药品预约管理</a></span>
                        <a data-target="#medi_add" data-toggle="modal" onclick="mediAddPre();return false;"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加药品</h3></a>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                               <!-- <label for="title">癌种分类：</label>
                                <input type="search" name="state" id="title"   placeholder="请输入分类名称关键字" value="<?php /*echo $get['keyword'];*/?>" style="margin-right: 20px" size="30">-->
                                <label for="cate">癌种分类：</label>
                                <select  id="cate" name="cate" style="height: 25px;margin-right: 20px">
                                    <option value="0" selected>全部</option>
                                    <?php foreach($cates as $value):?>
                                        <option value="<?php echo $value['cid'];?>" <?php if($get['cate'] == $value['cid']){ echo 'selected';}?>><?php echo $value['name'];?></option>
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
                                <th>品名</th>
                                <th>概述</th>
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
                                            <a data-target="#medi_edit" data-toggle="modal"  mid="<?php echo $value['id'];?>" onclick="editMediPre(this);return false;" title="编辑药品"><span class="glyphicon glyphicon-pencil"></span></a>
                                            &nbsp;&nbsp;<a data-target="#medi_del" data-toggle="modal"  mid="<?php echo $value['id'];?>" onclick="$('#medi_del input[name=\'mid\']').val(<?php echo $value['id'];?>);return false;" title="删除药品"><span class="glyphicon glyphicon-trash"></span></a>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/medi/medi.js"></script>