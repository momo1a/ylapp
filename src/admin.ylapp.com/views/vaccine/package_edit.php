<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="package_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">编辑套餐</h4>
            </div>
            <div class="modal-body">
                <form name="newsAdd" id="packageEdit">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">套餐名称：</span>
                                <input type="text" class="form-control" name="title" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <textarea id="contentEdit" name="content" rows="10" cols="60"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 package-img">
                            <span style="color: #000000">上传列表缩略图片：</span>
                            <br />
                            <input id="vaccine_img_edit" name="thumbnail" type="file">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <?php $state = array('-1'=>'状态','1'=>'上架','2'=>'下架');?>
                            <select class="form-control" name="status" id="statusEdit">
                                <?php foreach($state as $key=>$value):?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                        <div class="col-sm-4">
                            <?php $type = array('-1'=>'种类','1'=>'儿童','2'=>'成人');?>
                            <select class="form-control" name="type" id="typeEdit">
                                <?php foreach($type as $key=>$value):?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">定金：</span>
                                <input type="text" class="form-control" name="price" id="price"/>
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">剩余款项：</span>
                                <input type="text" class="form-control" name="remainAmount" id="remainAmount"/>
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="pid"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="packageSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>