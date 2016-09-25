<style>
    .row{
        margin: 10px 10px;
    }
</style>
<div class="modal fade modal-primary" id="hospital_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="myForm" method="post" action="<?php echo site_url();?>Hospital/saveHospital" enctype="multipart/form-data">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">添加医院</h4>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon">医院名称</span>
                        <input type="text" class="form-control" placeholder="请填写医院名称" name="hos_name">
                    </div>
                </div>

                <div class="row">
                    <div class="input-group col-xs-12">
                        <span class="input-group-addon">医院地址</span>
                        <input type="text" class="form-control" placeholder="请填写医院地址" name="address">
                    </div>
                </div>
                <div class="row">
                    <span style="color: #000000">上传缩略图片：</span>
                    <br />
                    <input id="hospital-img" name="hospital_img" type="file">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="hospitalSave();return false;" class="btn btn-outline pull-left">保存</button>
            </div>
        </div>
        </form>
        <!-- /.modal-content -->
    </div>
</div>