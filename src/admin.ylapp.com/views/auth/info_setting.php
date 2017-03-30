<div class="modal fade modal-primary" id="info_setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">基本信息修改</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url();?>Auth/settingUserPrivileges" method="post">
                        <label for="username" class="">姓名</label>
                        <input type="text" class="form-control" id="username" name="username" required>
                        <label for="telephone" class="">手机号码</label>
                        <input type="text" class="form-control" id="telephone" name="telephone" required>
                        <label for="password" class="">密码</label>
                        <input type="text"  class="form-control" id="password"  name="password"  placeholder="如要重置密码请输入密码">
                    <input type="hidden" name="uid"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-outline pull-left" onclick="setInfo(this);return false;">确定</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>