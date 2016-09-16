<div class="modal fade modal-primary" id="add_auth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" xmlns="http://www.w3.org/1999/html" xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加账户</h4>
            </div>
            <form action="" method="post" >
            <div class="modal-body">
                <div class="input-group">
                    <span class="input-group-addon">姓&nbsp;&nbsp;名</span>
                    <input type="text" class="form-control" name="auth[username]" id="nickname"  placeholder="请输入2-15个字符" required>
                </div>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">手机号码</span>
                    <input type="text" name="auth[phone]" class="form-control" id="phone" placeholder="请输入正确手机号码" required>
                </div>
                <br />
                <div class="input-group">
                    <span class="input-group-addon">密&nbsp;&nbsp;码</span>
                    <input type="password" class="form-control" name="auth[password]" id="pwd" placeholder="请输入大于等于6位的非纯数字密码" required>
                </div>
                <br />
                <div class="form-group">
                    <span class="input-group-addon">用户身份</span>
                    <select class="form-control" id="role">
                        <option value="1" checked>管理员</option>
                        <option value="2">客服</option>
                    </select>
                </div>
                <h5>权限设定：</h5></br>
                    <div class="form-group">
                        <?php $i=1;if(!empty($menu)): foreach($menu as $value):?>
                            <?php if($value['ctrl'] == 'Auth'){continue;}?>
                            <input type="checkbox" class="menu_checkbox icheckbox_minimal-green add_auth" value="<?php echo $value['id'];?>" name="menu"/>&nbsp;<?php echo  $value['title'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if($i%2 == 0)echo '<br/>';?>
                            <?php $i++; endforeach; endif;?>
                    </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-outline pull-left" onclick="addAuth(this);return false;">确定</button>
            </div>
            </form>


        </div>
        <!-- /.modal-content -->
    </div>
</div>