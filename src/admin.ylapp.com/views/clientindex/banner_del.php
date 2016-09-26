<div class="modal fade modal-primary" id="banner_del" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">删除首页banner</h4>
            </div>
            <div class="modal-body">
                <div style="text-align: center">你确定要删除该首页推荐吗？</div>
            </div>
            <input name="nid" type="hidden"/>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="bannerDel();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>