<div class="modal fade modal-primary" id="rolling_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加滚动消息</h4>
            </div>
            <div class="modal-body">
                <div style="text-align: center">
                    <div class="form-group">
                        <label>消息内容</label>
                        <input type="text" class="form-control" placeholder="请输入消息内容" name="content">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="addRollingMsg();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>