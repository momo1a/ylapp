<!--  此视图废弃 -->
<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('news/news_add');?>
<?php $this->load->view('news/news_edit');?>
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
                        <h3 class="box-title">评论管理</h3>
                        <a data-target="#news_add" data-toggle="modal" onclick="newsAddPre();return false;"><h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-plus"></span>添加资讯</h3></a>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" action="">
                            <div class="input-group">
                                <label for="nickname">资讯标题：</label>
                                <input type="search" name="keyword" id="nickname"   placeholder="请输入资讯标题关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 20px" size="30">
                                <label for="postPos">发布位置：</label>
                                <select  id="postPos" name="postPos" style="height: 25px;margin-right: 20px">
                                    <?php foreach($post_pos as $key=>$value):?>
                                        <option value="<?php echo $key;?>" <?php if($get['postPos'] == $key){ echo 'selected';}?>><?php echo $value;?></option>
                                    <?php endforeach;?>
                                </select>

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
                                <th>资讯主题</th>
                                <th>评论内容</th>
                                <th>评论时间</th>
                                <th>评论人</th>
                                <th>分类</th>
                                <th>发布位置</th>
                                <th>状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['nid'];?></th>
                                        <th><?php echo $value['title'];?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['createTime']);?></th>
                                        <th><?php echo $value['tag'];?></th>
                                        <th><?php switch(intval($value['postPos'])){
                                                case 1:
                                                    echo '用户端';
                                                    break;
                                                case 2:
                                                    echo '医生端';
                                                    break;
                                                default:
                                                    echo '全部';
                                            }?></th>
                                        <th><?php switch(intval($value['isRecmd'])){
                                                case 0 :
                                                    echo '否';
                                                    break;
                                                case 1 :
                                                    echo '是';
                                                    break;
                                                default:
                                                    echo '未知';
                                            }?></th>

                                        <th><?php switch(intval($value['isRecmdIndex'])){
                                                case 0 :
                                                    echo '否';
                                                    break;
                                                case 1 :
                                                    echo '是';
                                                    break;
                                                default:
                                                    echo '未知';
                                            }?></th>
                                        <th><?php switch(intval($value['state'])){
                                                case 0 :
                                                    echo '未发布';
                                                    break;
                                                case 1 :
                                                    echo '发布';
                                                    break;
                                                default:
                                                    echo '未知';
                                            }?></th>
                                        <th>
                                            <a data-target="#news_edit" data-toggle="modal"  nid="<?php echo $value['nid'];?>" onclick="editNews(this);return false;" title="编辑资讯"><span class="glyphicon glyphicon-pencil"></span></a>
                                            &nbsp;&nbsp;<a data-target="#news_del" data-toggle="modal"  nid="<?php echo $value['nid'];?>" onclick="newsDelPre(this);return false;" title="删除资讯"><span class="glyphicon glyphicon-trash"></span></a>
                                            &nbsp;&nbsp;<a href="#" nid="<?php echo $value['nid'];?>"   title="评论管理"><span class="glyphicon glyphicon-list"></span></a>
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
<script src="<?php echo config_item('domain_static').'admin/';?>js/news/news.js"></script>
