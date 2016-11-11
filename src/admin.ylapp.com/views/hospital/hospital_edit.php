<style>
    .row{
        margin: 10px 10px;
    }
</style>
<div class="modal fade modal-primary" id="hospital_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form id="myFormEdit" method="post" action="<?php echo site_url();?>Hospital/saveHospital" enctype="multipart/form-data">
            <div class="modal-content">
                <div class="modal-header">
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" onclick="$('#myFormEdit')[0].reset()">
                        <span aria-hidden="true">&times;</span></button>
                    <h4 class="modal-title">编辑医院</h4>
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
                        <input id="hospital-img-edit" name="hospital_img" type="file">
                    </div>
                    <input type="hidden" name="hid"/>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline " data-dismiss="modal" onclick="$('#myFormEdit')[0].reset()">取消</button>
                    <button type="button" onclick="hospitalSaveEdit();return false;" class="btn btn-outline pull-left">保存</button>
                </div>
            </div>
        </form>
        <!-- /.modal-content -->
    </div>
</div>