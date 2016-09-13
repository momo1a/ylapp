<div class="modal fade modal-primary" id="del_auth" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">删除账户</h4>
            </div>
            <div class="modal-body">
                <form action="" method="post">
                    你确定要删除账户？
                    <input type="hidden" name="uid"/>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-outline pull-left" onclick="delAuth(this);return false;">确定</button>
            </div>
            </form>
        </div>
        <!-- /.modal-content -->
    </div>
</div>