<style>
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="news_add" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">添加资讯</h4>
            </div>
            <div class="modal-body">
                <form name="newsAdd" id="newsAdd">
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
                    <textarea id="content" name="content" rows="10" cols="60"></textarea>
                </div>
                <div class="row">
                    <div class="col-sm-6 news-img">
                        <span style="color: #000000">上传缩略图片：</span>
                        <br />
                        <input id="news-img" name="thumbnail" type="file">
                    </div>
                    <div class="col-sm-6 banner-img">
                        <span style="color: #000000">上传banner图：</span>
                        <br />
                        <input id="banner" name="banner" type="file">
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-4">
                        <div class="input-group">
                            <span class="input-group-addon">标签：</span>
                            <input type="text" class="form-control" name="tag" placeholder="选填" />
                        </div>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control" name="postPos" id="postPos">
                            <option value="-1">发布位置</option>
                            <option value="0">全部</option>
                            <option value="1">用户端</option>
                            <option value="2">医生端</option>
                        </select>
                    </div>
                    <div class="col-sm-4">
                        <select class="form-control" name="isRecmd" id="isRecmd">
                            <option value="-1">是否推荐</option>
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>
                </div>

                <div class="row">
                    <div class="col-sm-6">
                        <select class="form-control" name="isRecmdIndex" id="isRecmdIndex">
                            <option value="-1">是否推荐至首页</option>
                            <option value="0">否</option>
                            <option value="1">是</option>
                        </select>
                    </div>

                    <div class="col-sm-6">
                        <select class="form-control" name="state" id="state">
                            <option value="-1">状态</option>
                            <option value="0">未发布</option>
                            <option value="1">发布</option>
                        </select>
                    </div>
                </div>
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