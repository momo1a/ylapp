<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('auth/priv_setting');?>
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <style>
        .operator_btn{
            cursor:pointer;
        }
    </style>
    <!-- Main content -->
    <section class="content">
        <div class="row">
            <div class="col-xs-12">
                <div class="box">
                    <div class="box-header">
                        <h3 class="box-title">账户管理</h3>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <table id="example2" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>编号</th>
                                <th>用户名</th>
                                <th>手机号码</th>
                                <th>用户身份</th>
                                <th>注册时间</th>
                                <th>权限</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                                <?php if(!empty($list)){;?>
                                    <?php foreach($list as $value){?>
                                        <tr>
                                            <th><?php echo $value['uid'];?></th>
                                            <th><?php echo $value['nickname'];?></th>
                                            <th><?php echo $value['phone'];?></th>
                                            <th><?php echo $value['role'];?></th>
                                            <th><?php echo date('Y-m-d H:i:s',$value['dateline']);?></th>
                                            <th><a data-target="#priv_setting" data-toggle="modal" onclick="getUserMenuByUid(this);" uid="<?php echo $value['uid'];?>"><span class="glyphicon glyphicon-cog operator_btn" title="设置权限"></span></a></th>
                                            <th><span class="glyphicon glyphicon-pencil operator_btn" title="设置账户"></span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<div class="glyphicon glyphicon-trash operator_btn" title="删除账户"></div></th>
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
    function getUserMenuByUid(e){
        var uid = $(e).attr('uid');
        $("input[name='uid']").val(uid);
        $.ajax({
            url: "<?php echo site_url().'Auth/getUserMenuAjax';?>",
            type: 'post',
            dataType: 'json',
            data: {'uid': uid},
            success: function (data, textStatus) {
                var arr = [];
                $.each(data,function(idx,obj){
                    arr.push(obj.mid);
                });
                console.log(arr);
                var checkBox = $('.menu_checkbox');
                for(var i = 0 ; i < checkBox.length; i++){
                    $(checkBox[i]).attr('checked',false);
                    if($.inArray($(checkBox[i]).val(),arr) != -1){
                        $(checkBox[i]).attr('checked',true);
                    }
                }
            }
        });
    }
</script>
