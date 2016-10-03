<div class="modal fade modal-primary" id="time_update" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">修改预约时间</h4>
            </div>
            <div class="modal-body">
                <div style="text-align: center">
                    <div>
                        <div class="row">
                            <div class="input-group date col-xs-5" style="display: inline-block">
                                <div class="input-group-addon">
                                    <i class="fa fa-calendar"></i>
                                </div>
                                <span style="position: relative; z-index: 9999;">
                                <input type="text" class="form-control pull-right" data-date-format="yyyy-mm-dd" id="adate" name="adate">
                                </span>
                            </div>

                            <div class="input-group date col-xs-5 bootstrap-timepicker" style="display: inline-block">
                                <div class="input-group-addon">
                                    <i class="fa fa-clock-o"></i>
                                </div>
                                <span style="position: relative; z-index: 9999;">
                                    <input type="text" class="form-control timepicker"  id="atime" name="atime">
                                </span>
                            </div>
                        </div>
                    </div>
                </div>
                <input type="hidden" name="aid">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="updateATime();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>