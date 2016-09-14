<div class="modal fade modal-primary" id="add_auth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加账户</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    <div class="form-group">
                        <?php $i=1;if(!empty($menu)): foreach($menu as $value):?>
                            <?php if($value['ctrl'] == 'Auth'){continue;}?>
                            <input type="checkbox" class="minimal menu_checkbox" value="<?php echo $value['id'];?>" name="menu"/>&nbsp;<?php echo  $value['title'];?>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                            <?php if($i%5 == 0)echo '<br/>';?>
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