<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="news_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">编辑资讯</h4>
            </div>
            <div class="modal-body">
                <form name="newsAdd" id="newsEdit">
                    <div class="row">
                        <div class="col-sm-8">
                            <div class="input-group">
                                <span class="input-group-addon">标题：</span>
                                <input type="text" class="form-control" name="title" />
                            </div>
                        </div>
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">作者：</span>
                                <input type="text" class="form-control" name="author" />
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <textarea id="contentEdit" name="content" rows="10" cols="60"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 news-img">
                            <span style="color: #000000">上传缩略图片：</span>
                            <br />
                            <input id="news-img-edit" name="thumbnail" type="file">
                        </div>
                        <div class="col-sm-6 banner-img">
                            <span style="color: #000000">上传banner图：</span>
                            <br />
                            <input id="banner-edit" name="banner" type="file">
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-4">
                            <div class="input-group">
                                <span class="input-group-addon">标签：</span>
                                <input type="text" class="form-control" name="tag" placeholder="选填" />
                            </div>
                        </div>
                        <?php $position = array(
                            -1=>'发布位置',
                            0=>'全部',
                            1=>'用户端',
                            2=>'医生端'
                        );?>
                        <div class="col-sm-4">
                            <select class="form-control" name="postPos" id="postPosEdit">
                                <?php foreach($position as $key=>$value):?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <?php $isRecmd = array(
                            -1 => '是否推荐',
                             0 => '否',
                             1 => '是'
                        );?>
                        <div class="col-sm-4">
                            <select class="form-control" name="isRecmd" id="isRecmdEdit">
                                <?php foreach($isRecmd as $key=>$value):?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <?php $isRecmdIndex = array(
                        -1 => '是否推荐至首页',
                        0 => '否',
                        1 => '是'
                    );
                            $state = array(
                                -1 => '状态',
                                0  => '未发布',
                                1  => '发布',
                            );
                    ?>
                    <div class="row">
                        <div class="col-sm-6">
                            <select class="form-control" name="isRecmdIndex" id="isRecmdIndexEdit">
                                <?php foreach($isRecmdIndex as $key=>$value):?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>

                        <div class="col-sm-6">
                            <select class="form-control" name="state" id="stateEdit">
                                <?php foreach($state as $key=>$value):?>
                                    <option value="<?php echo $key;?>"><?php echo $value;?></option>
                                <?php endforeach;?>
                            </select>
                        </div>
                    </div>
                    <input type="hidden" name="nid" value="0"/>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="newsSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
        </div>
        <!-- /.modal-content -->
    </div>
</div>