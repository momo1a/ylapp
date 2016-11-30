<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="help_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="$('#helpAdd')[0].reset();">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">添加帮助</h4>
            </div>
            <div class="modal-body">
                <form name="helpAdd" id="helpAdd">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">标题：</span>
                                <input type="text" class="form-control" name="title" />
                            </div>
                        </div>
                    </div>
                    <span style="margin-left: 3%">描述：</span>
                    <div class="row">
                        <textarea id="content" name="description" rows="10" cols="65" style="margin-left: 3%;color: #000"></textarea>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <select class="form-control" name="pos" id="pos">
                                <?php if(!empty($pos)):foreach($pos as $key=>$val):?>
                                    <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <select class="form-control" name="is_show" id="is_show">
                                <?php if(!empty($isShow)):foreach($isShow as $key=>$val):?>
                                    <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="hid"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal" onclick="$('#helpAdd')[0].reset();">取消</button>
                <button type="button" onclick="helpSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>