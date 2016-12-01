<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="office_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="$('#officeEdit')[0].reset();">&times;</span></button>
                <h4 class="modal-title" id="office-edit-title">添加科室</h4>
            </div>
            <div class="modal-body">
                <form name="officeEdit" id="officeEdit">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">科室名称：</span>
                                <input type="text" class="form-control" name="officeName" />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="hid"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal" onclick="$('#officeEdit')[0].reset();">取消</button>
                <button type="button" onclick="officeSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>