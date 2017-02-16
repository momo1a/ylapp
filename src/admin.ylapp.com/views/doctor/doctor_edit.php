<div class="modal fade modal-primary" id="doctor_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
     aria-hidden="true" xmlns="http://www.w3.org/1999/html">
    <div class="modal-dialog modal-dialog-info">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="$('#dEdit')[0].reset()">&times;</span></button>
                <h4 class="modal-title">编辑医生</h4>
            </div>
            <form id="dEdit">
                <div class="modal-body">
                    <div class="row">
                        <div class=" col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">账号</span>
                                <input type="text" class="form-control" readonly name="account" placeholder="请填写账号名">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">密码</span>
                                <input type="password" class="form-control" name="pwd" readonly>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class=" col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">姓名</span>
                                <input type="text" class="form-control" name="username">
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">性别</span>
                                <select class="form-control" name="sex" id="sex_edit">
                                    <option value="1">男</option>
                                    <option value="2">女</option>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class=" col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">医院</span>
                                <select class="form-control" name="hospital" id="hospital_edit">
                                    <?php if($hospital):foreach($hospital as $key=>$value):?>
                                        <option value="<?php echo $value['hid'];?>"><?php echo $value['name'];?></option>
                                    <?php endforeach;endif;?>
                                </select>
                            </div>
                        </div>
                        <div class="col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">科室</span>
                                <select class="form-control" name="office" id="office_edit">
                                    <?php if($office):foreach($office as $key=>$value):?>
                                        <option value="<?php echo $value['officeId'];?>"><?php echo $value['officeName'];?></option>
                                    <?php endforeach;endif;?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class=" col-xs-6">
                            <div class="input-group">
                                <span class="input-group-addon">医生职称</span>
                                <input type="text" class="form-control" name="docLevel">
                            </div>
                        </div>
                    </div>
                    <hr/>
                    <div class="row">
                        <div class="checkbox" style="margin-left: 5%">
                            <label>
                                <input type="checkbox" name="isDude" id="isDude_edit" value="1" >
                                是否药房伙计
                            </label>
                        </div>
                    </div>
                    <hr/>
                    <input name="uid" type="hidden"/>
                    <div class="row" style="text-align: center"><button type="button" class="btn btn-default" onclick="saveDoctor();return false;">保存</button></div>
            </form>
        </div>
        <!--<div class="modal-footer">
            <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
            <button type="button" class="btn btn-outline pull-left" onclick="setInfo(this);return false;">确定</button>
        </div>-->
    </div>
    <!-- /.modal-content -->
</div>
</div>