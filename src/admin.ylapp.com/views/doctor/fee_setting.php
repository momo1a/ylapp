<div class="modal fade modal-primary" id="fee_setting" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog modal-dialog-info">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">费用设置</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class=" col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon">留言费用</span>
                            <input type="text" class="form-control" name="fee[leaving_fee]">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon">分成</span>
                            <input type="text" class="form-control" name="fee[leaving_per]">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class=" col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon">挂号费用</span>
                            <input type="text" class="form-control" name="fee[reg_fee]">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                    <div class="col-xs-6">
                        <div class="input-group">
                            <span class="input-group-addon">分成</span>
                            <input type="text" class="form-control" name="fee[reg_per]">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="row">
                    <div class=" col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">时长1</span>
                            <input type="text" class="form-control" name="fee[t_len_f]">
                            <span class="input-group-addon">分</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">费用</span>
                            <input type="text" class="form-control" name="fee[ask_fee_f]">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">分成</span>
                            <input type="text" class="form-control" name="fee[ask_per_f]">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class=" col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">时长2</span>
                            <input type="text" class="form-control" name="fee[t_len_s]">
                            <span class="input-group-addon">分</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">费用</span>
                            <input type="text" class="form-control" name="fee[ask_fee_s]">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">分成</span>
                            <input type="text" class="form-control" name="fee[ask_per_s]">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                </div>
                <br/>
                <div class="row">
                    <div class=" col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">时长3</span>
                            <input type="text" class="form-control" name="fee[t_len_t]">
                            <span class="input-group-addon">分</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">费用</span>
                            <input type="text" class="form-control" name="fee[ask_fee_t]">
                            <span class="input-group-addon">元</span>
                        </div>
                    </div>
                    <div class="col-xs-4">
                        <div class="input-group">
                            <span class="input-group-addon">分成</span>
                            <input type="text" class="form-control" name="fee[ask_per_t]">
                            <span class="input-group-addon">%</span>
                        </div>
                    </div>
                    <input name="uid" type="hidden"/>
                </div>
                <hr/>
                <div class="row" style="text-align: center"><button type="button" class="btn btn-default" onclick="saveDoctorFee();return false;">保存</button></div>
            </div>
            <!--<div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-outline pull-left" onclick="setInfo(this);return false;">确定</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
</div>