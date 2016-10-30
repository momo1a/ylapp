<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('hospital/hospital_detail');?>
<?php $this->load->view('hospital/hospital_add');?>
<?php $this->load->view('hospital/hospital_del');?>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <link rel="stylesheet" href="<?php echo config_item('domain_static').'admin/';?>css/hospital/hospital.css">
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">评价管理</h3>
                    </div>
                    <div class="bg-gray color-palette">
                        <form method="get" id="feedback-search" action="<?php echo site_url()?>evaluate/index">
                            <div class="input-group">
                                <label for="nickname">评价内容：</label>
                                <input type="search" name="keyword"   placeholder="请输入评价内容关键字" value="<?php echo $get['keyword'];?>" style="margin-right: 10px" size="30">
                                <input type="button" onclick="$('#feedback-search').submit()" value="搜索">
                            </div>
                        </form>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>被评价医生</th>
                                <th>评价人</th>
                                <th>评价内容</th>
                                <th>评价时间</th>
                                <th>当前状态</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php if(!empty($list)){;?>
                                <?php foreach($list as $value){?>
                                    <tr>
                                        <th><?php echo $value['vid'];?></th>
                                        <th><?php echo $value['docName'];?></th>
                                        <th><?php echo $value['userName'];?></th>
                                        <th><?php echo $value['content'];?></th>
                                        <th><?php echo date('Y-m-d H:i:s',$value['evaluateDate']);?></th>
                                        <th><?php echo $state[$value['estate']];?></th>
                                        <th>
                                            <?php if($value['estate'] == 0):;?>
                                                <button class="btn btn-primary btn-xs" title="通过" state="1" onclick="checkEvaluate(this);return false;" vid=<?php echo $value['vid'];?>>通过</button><br/>
                                                <button class="btn btn-primary btn-xs" title="不通过" state="2" onclick="checkEvaluate(this);return false;" vid=<?php echo $value['vid'];?>>不通过</button>
                                            <?php endif;?>

                                            <?php if($value['estate'] == 1):;?>
                                                <button class="btn btn-primary btn-xs" title="不通过" state="2" onclick="checkEvaluate(this);return false;" vid=<?php echo $value['vid'];?>>不通过</button>
                                            <?php endif;?>

                                            <?php if($value['estate'] == 2):;?>
                                                <button class="btn btn-primary btn-xs" title="通过" state="1" onclick="checkEvaluate(this);return false;" vid=<?php echo $value['vid'];?>>通过</button>
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
    function checkEvaluate(e){
        var state = $(e).attr('state');
        var vid = $(e).attr('vid');
        $.ajax({
            url: SITE_URL + "evaluate/checkPass",
            type: "post",
            data:{state:state,vid:vid},
            dataType: 'json',
            success: function (result) {
                if(result.code == 0){
                    alert(result.msg);
                    location.reload();
                }else{
                    alert(result.msg);
                }
            }
        });
    }
</script>
