<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="cate_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">添加药品分类</h4>
            </div>
            <div class="modal-body">
                <form name="newsAdd" id="mediAdd">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">分类名称：</span>
                                <input type="text" class="form-control" name="cateName" />
                            </div>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="addCate();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>