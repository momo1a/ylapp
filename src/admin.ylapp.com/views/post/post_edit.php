<style>
    .left{
        color: #000000;
        font-weight: bold;
        margin: 20px 20px;
    }
    .row{
        margin: 20px 0;
    }
</style>
<div class="modal fade modal-primary" id="post_edit" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="news-edit-title">帖子详情</h4>
            </div>
            <div class="modal-body">
                <div class="row"><span class="left"> 发帖人：</span><span id="postUname" class="right"></span></div>
                <div class="row"><span class="left">帖子内容：</span><span id="postContent" class="right"></span></div>
                <div class="row" id="postImg"></div>
                <div class="row"><span class="left">更改点赞数量：</span><span><input id="clickCount"  type="text" name="clickCount" style="color: #000000;border: #808080"/></span></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" onclick="postSave();return false;" class="btn btn-outline pull-left">确定</button>
            </div>
            <input type="hidden" name="pid"/>
        </div>
        <!-- /.modal-content -->
    </div>
</div>