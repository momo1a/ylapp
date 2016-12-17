<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('gene/package_add');?>
<?php $this->load->view('gene/package_edit');?>
<?php $this->load->view('gene/package_del');?>


<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/news/news.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">检测服务</h3>
                        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <!--<span class="btn btn-primary"><a href="<?php /*echo site_url()*/?>post/commentList" style="color: #000000">评论管理</a></span>-->
                         <a data-target="#package_add" data-toggle="modal" onclick="packageAddPre();return false;"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加套餐</h3></a>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                                <label for="title">套餐名称：</label>
                                <input type="search" name="keyword" id="title"   placeholder="请输入套餐名称关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 20px" size="30">
                                <!--<label for="state">状态：</label>
                                <select  id="state" name="state" style="height: 25px;margin-right: 20px">
                                    <?php /*foreach($state as $key=>$value):*/?>
                                        <option value="<?php /*echo $key;*/?>" <?php /*if($get['state'] == $key){ echo 'selected';}*/?>><?php /*echo $value;*/?></option>
                                    <?php /*endforeach;*/?>
                                </select>-->
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
                                <th>套餐名称</th>
                                <th>发布时间</th>
                                <th>价格</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['id'];?></th>
                                        <th><?php echo $value['name'];?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['dateline']);?></th>
                                        <th><?php echo $value['price'];?></th>
                                        <th><?php switch(intval($value['status'])){
                                                case 1:
                                                    echo '已上架';
                                                    break;
                                                case 2:
                                                    echo '已下架';
                                                    break;
                                                default:
                                                    echo '未知';
                                            }?></th>
                                        <th>
                                            <a data-target="#package_edit" data-toggle="modal"  pid="<?php echo $value['id'];?>" onclick="editPackagePre(this);return false;" title="编辑套餐"><span class="glyphicon glyphicon-pencil"></span></a>
                                            &nbsp;&nbsp;<a data-target="#package_del" data-toggle="modal"  pid="<?php echo $value['id'];?>" onclick="packageDelPre(this);return false;" title="删除套餐"><span class="glyphicon glyphicon-trash"></span></a>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/gene/gene.js"></script>