<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="appoint_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">添加药品预约用户</h4>
            </div>
            <div class="modal-body">
                <form name="newsAdd" id="appointAdd">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">预约时间：</span>
                                <input type="text" class="form-control" name="appointTime" id="appointTime" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">姓名：</span>
                                <input type="text" class="form-control" name="realName" id="realName" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">预约人电话：</span>
                                <input type="text" class="form-control" name="telephone" id="telephone" />
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-8">
                            <select class="form-control" name="cate" id="cate">
                                <option value="0">请选择药品</option>
                                <?php if(!empty($medicines)):foreach($medicines as $value):?>
                                    <option value="<?php echo $value['id'];?>"><?php echo $value['name'];?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <textarea id="content" name="content" rows="10" cols="60"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="addAppoint();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>