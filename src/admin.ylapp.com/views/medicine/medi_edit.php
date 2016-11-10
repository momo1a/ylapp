<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="medi_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">编辑药品</h4>
            </div>
            <div class="modal-body">
                <form name="mediEdit" id="mediEdit">
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
                        <textarea id="contentEdit" name="content" rows="10" cols="60"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 medi-img">
                            <span style="color: #000000">上传缩略图片：</span>
                            <br />
                            <input id="news-img-edit" name="thumbnail" type="file">
                        </div>
                        <div class="col-sm-6 banner_1 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-edit-1" name="banner_1" type="file">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 banner_2 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-edit-2" name="banner_2" type="file">
                        </div>
                        <div class="col-sm-6 banner_3 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-edit-3" name="banner_3" type="file">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-6 banner_4 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-edit-4" name="banner_4" type="file">
                        </div>
                        <div class="col-sm-6 banner_5  banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-edit-5" name="banner_5" type="file">
                        </div>
                    </div>

                    <?php $c = array();if(!empty($cates)):foreach($cates as $value):
                            $c[$value['cid']] = $value['name'];
                    endforeach;endif;
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <select class="form-control" name="cate" id="cateEdit">
                                <?php if(!empty($c)):foreach($c as $key =>$val):?>
                                    <option value="<?php echo $key;?>"><?php echo $val;?></option>
                                <?php endforeach;endif;?>
                            </select>
                        </div>
                    </div>

                    <input type="hidden" name="mid" value="0"/>
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