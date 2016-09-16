<div class="modal fade modal-primary" id="priv_setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">权限设置</h4>
            </div>
            <div class="modal-body">
                <form action="<?php echo site_url();?>Auth/settingUserPrivileges" method="post">
                    <div class="form-group">
                <?php $i=1;if(!empty($menu)): foreach($menu as $value):?>
                    <?php if($value['ctrl'] == 'Auth'){continue;}?>
                    <input type="checkbox" class="menu_checkbox icheckbox_minimal-green" value="<?php echo $value['id'];?>" name="menu"/>&nbsp;<?php echo  $value['title'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                    <?php if($i%2 == 0)echo '<br/>';?>
                <?php $i++; endforeach; endif;?>
                    </div>
                    <input type="hidden" name="uid"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-outline pull-left" onclick="setPrv(this);return false;">确定</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>