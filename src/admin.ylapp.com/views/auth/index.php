<?php $this->load->view('head');?>
<!-- Left side column. contains the logo and sidebar -->
<?php $this->load->view('left');?>
<?php $this->load->view('auth/priv_setting');?>
<?php $this->load->view('auth/info_setting');?>
<?php $this->load->view('auth/del_auth');?>
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
                        <h3 class="box-title" style="float: right;cursor: pointer"><span class="glyphicon glyphicon-user"></span>创建账户</h3>
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
                                            <th><a data-target="#info_setting" data-toggle="modal" onclick="getUserInfo(this);" uid="<?php echo $value['uid'];?>"><span class="glyphicon glyphicon-pencil operator_btn" title="设置账户"></span></a>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                                <a data-target="#del_auth" data-toggle="modal" onclick="delUser(this);" uid="<?php echo $value['uid'];?>"><div class="glyphicon glyphicon-trash operator_btn" title="删除账户"></div></a></th>
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

    /**
     * 获取用户菜单
     * @param e
     */
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
                var checkBox = $('.menu_checkbox');
                $(checkBox).attr('checked',false);
                for(var i = 0 ; i < checkBox.length; i++){
                    if($.inArray($(checkBox[i]).val(),arr) != -1){
                        if($(checkBox[i]).is(':checked') == false){
                            $(checkBox[i]).prop('checked',true);
                        }
                    }
                }
            }
        });
    }

    /**
     * 设置用户菜单权限
     * @param e
     */
    function setPrv(e){
        var menu = $("input[name='menu']:checked").serialize();
        var uid = $("input[name='uid']").val();
        $.ajax({
            url: "<?php echo site_url().'Auth/settingUserPrivileges';?>",
            type: "post",
            data: {menu:menu,uid:uid},
            dataType: 'json',
            success: function (result) {
                if(result.code == 0){
                    alert('操作成功');
                    location.reload();
                }else{
                    alert('操作失败');
                    location.reload();
                }
            }
        });
    }

    /**
     * 获取用户信息
     * @param e
     */
    function getUserInfo(e){
        var uid = $(e).attr('uid');
        $("input[name='uid']").val(uid);
        $.ajax({
            url: "<?php echo site_url().'Auth/getUserInfoByUid';?>",
            type: 'post',
            dataType: 'json',
            data: {'uid': uid},
            success: function (data, textStatus) {
                 $("input[name='username']").val(data.data.nickname);
                 $("input[name='password']").val(data.data.password);
                 $("input[name='telephone']").val(data.data.phone);
            }
        });
    }

    /**
     * 修改用户信息
     * @param e
     */
    function setInfo(e){
        var uid = $("input[name='uid']").val();
        var username = $("#username").val();
        var telephone = $("#telephone").val();
        var password = $("#password").val();
        $.ajax({
            url: "<?php echo site_url().'Auth/updateUserInfo';?>",
            type: "post",
            data: {uid:uid,telephone:telephone,username:username,password:password},
            dataType: 'json',
            success: function (result) {
                if(result.code == 0){
                    alert('操作成功');
                    location.reload();
                }else{
                    alert('操作失败');
                    location.reload();
                }
            }
        });
    }

    /**
     * 获取用户信息
     * @param e
     */
    function delUser(e){
        var uid = $(e).attr('uid');
        $("input[name='uid']").val(uid);
    }

    function delAuth(e){
        var uid = $("input[name='uid']").val();
        $.ajax({
            url: "<?php echo site_url().'Auth/delUser';?>",
            type: "post",
            data: {uid:uid},
            dataType: 'json',
            success: function (result) {
                if(result.code == 0){
                    alert('操作成功');
                    location.reload();
                }else{
                    alert('操作失败');
                    location.reload();
                }
            }
        });
    }

</script>
