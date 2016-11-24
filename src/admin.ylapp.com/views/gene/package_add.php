<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="package_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true" onclick="$('#packageAdd')[0].reset();CKEDITOR.instances.content.setData('');$('.fileinput-remove').trigger('click');">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">添加套餐</h4>
            </div>
            <div class="modal-body">
                <form name="packageAdd" id="packageAdd">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">套餐名称：</span>
                                <input type="text" class="form-control" name="title" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <textarea id="content" name="content" rows="10" cols="60"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-8 package-img">
                            <span style="color: #000000">上传列表缩略图片：</span>
                            <br />
                            <input id="gene_img" name="thumbnail" type="file">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <select class="form-control" name="status" id="status">
                                <option value="-1">状态</option>
                                <option value="1">上架</option>
                                <option value="2">下架</option>
                            </select>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-5">
                            <div class="input-group">
                                <span class="input-group-addon">价格：</span>
                                <input type="text" class="form-control" name="price" />
                            </div>
                        </div>
                    </div>
                    <input type="hidden" name="pid"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal" onclick="$('#packageAdd')[0].reset();CKEDITOR.instances.content.setData('');$('.fileinput-remove').trigger('click');">取消</button>
                <button type="button" onclick="packageSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>