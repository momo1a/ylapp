<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="medi_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">添加药品</h4>
            </div>
            <div class="modal-body">
                <form name="newsAdd" id="mediAdd">
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">品名：</span>
                                <input type="text" class="form-control" name="name" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-10">
                            <div class="input-group">
                                <span class="input-group-addon">概述：</span>
                                <input type="text" class="form-control" name="outline" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <textarea id="content" name="content" rows="10" cols="60"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 medi-img">
                            <span style="color: #000000">上传缩略图片：</span>
                            <br />
                            <input id="medi-img" name="thumbnail" type="file">
                        </div>
                        <div class="col-sm-6 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-1" name="banner_1" type="file">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-2" name="banner_2" type="file">
                        </div>
                        <div class="col-sm-6 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-3" name="banner_3" type="file">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-4" name="banner_4" type="file">
                        </div>
                        <div class="col-sm-6 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-5" name="banner_5" type="file">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6">
                            <select class="form-control" name="cate" id="cate">
                                <?php if(!empty($cates)):foreach($cates as $value):?>
                                    <option value="<?php echo $value['cid'];?>"><?php echo $value['name'];?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="mid"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="mediSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>