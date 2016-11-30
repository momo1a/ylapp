<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="appoint_allot" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="$('#guys').html('<option value=\'0\'>请选择伙计</option>');">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">分配药房伙计</h4>
            </div>
            <div class="modal-body">
                <form name="appointAllot" id="appointAllot">
                    <div class="row">
                        <div class="col-sm-4">
                            <select class="form-control" name="guys" id="guys">
                                <option value="0">请选择伙计</option>
                                <!--<?php /*if(!empty($medicines)):foreach($medicines as $value):*/?>
                                    <option value="<?php /*echo $value['id'];*/?>"><?php /*echo $value['name'];*/?></option>
                                --><?php /*endforeach;endif;*/?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <input type="text" class="form-control" name="guys-shaixuan" id="guys-shaixuan" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <input type="button" id="guys-btn" class="btn btn-default" value="筛选伙计"/>
                        </div>
                        <input type="hidden" name="aid"/>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal" onclick="$('#guys').html('<option value=\'0\'>请选择伙计</option>');">取消</button>
                <button type="button" onclick="appointToAllot();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>