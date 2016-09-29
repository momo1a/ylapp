<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('post/post_edit');?>
<?php $this->load->view('news/news_del');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/news/news.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">交流圈管理</h3>
                        <!--&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                        <span class="btn btn-primary"><a href="<?php /*echo site_url()*/?>news/commentList" style="color: #000000">评论管理</a></span>-->
                       <!-- <a data-target="#news_add" data-toggle="modal" onclick="newsAddPre();return false;"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加资讯</h3></a>-->
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                                <label for="title">帖子标题：</label>
                                <input type="search" name="keyword" id="title"   placeholder="请输入帖子标题关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 20px" size="30">
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
                                <th>帖子标题</th>
                                <th>发布时间</th>
                                <th>发帖人</th>
                                <th>状态</th>
                                <th>审核</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['id'];?></th>
                                        <th><?php echo $value['postTitle'];?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['postTime']);?></th>
                                        <th><?php echo $value['nickname'];?></th>
                                        <th><?php switch(intval($value['state'])){
                                                case 0:
                                                    echo '待审核';
                                                    break;
                                                case 1:
                                                    echo '通过';
                                                    break;
                                                case 2:
                                                    echo '未通过';
                                                    break;
                                                default:
                                                    echo '未知';
                                            }?></th>
                                        <th>
                                            <?php
                                            switch(intval($value['state'])){
                                                case 0 :
                                                    echo '<a pid="'.$value['id'].'" onclick="postPass(this);return false;">通过</a>&nbsp;&nbsp;&nbsp;<a pid="'.$value['id'].'" onclick="postNotPass(this);return false;">不通过</a>';
                                                    break;
                                                case 1 :
                                                    echo '<a pid="'.$value['id'].'" onclick="postNotPass(this);return false;">不通过</a>';
                                                    break;
                                                case 2 :
                                                    echo '<a pid="'.$value['id'].'" onclick="postPass(this);return false;">通过</a>';
                                                    break;
                                                default :
                                                    echo '异常';
                                            }?>
                                        </th>

                                        <th>
                                            <a data-target="#post_edit" data-toggle="modal"  pid="<?php echo $value['id'];?>" onclick="editPostPre(this);return false;" title="编辑帖子"><span class="glyphicon glyphicon-pencil"></span></a>
                                            &nbsp;&nbsp;<a data-target="#news_del" data-toggle="modal"  nid="<?php echo $value['nid'];?>" onclick="newsPostPre(this);return false;" title="删除帖子"><span class="glyphicon glyphicon-trash"></span></a>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/post/post.js"></script>