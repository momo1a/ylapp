<style>
    .order_cat_tab span{
        display: inline-block;
        width: 24%;
        height: 25px;
        border-radius: 3px;
        border: 1px solid royalblue;
        margin-right: 1px;
        background: gainsboro;
        text-align: center;
        color: #000000;
        cursor: pointer;
    }
</style>
<div class="modal fade modal-primary" id="order_info" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-order">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title">订单记录</h4>
            </div>
            <div class="modal-body">
                <div class="order_cat_tab">
                   <span style="background: #808080" id="order_sub_tab_frt" class="order_sub_tab" onclick="orderTurnTab(this);return false;" type="1" for="zxwz">问诊记录</span>
                   <span class="order_sub_tab" onclick="orderTurnTab(this);return false;" type="2" for="yygh">挂号记录</span>
                   <span class="order_sub_tab" onclick="orderTurnTab(this);return false;" type="3" for="lywd">问答记录</span>
                   <span class="order_sub_tab" onclick="orderTurnTab(this);return false;" type="4" for="gmjl">购买记录</span>
                </div>
                <input type="hidden" name="uid"/>
                <div class="order_detail">
                <div id="zxwz">
                    <!--在线问诊-->
                </div>
                <div id="yygh" style="display: none">
                    <!--预约挂号-->
                </div>
                <div id="lywd" style="display: none">
                    <!--留言问答-->
                </div>
                <div id="gmjl" style="display: none">
                    <!--购买记录-->
                </div>
                </div>
            </div>


            <!--<div class="modal-footer">
                <button type="button" class="btn btn-outline " data-dismiss="modal">取消</button>
                <button type="button" class="btn btn-outline pull-left" onclick="setInfo(this);return false;">确定</button>
            </div>-->
        </div>
        <!-- /.modal-content -->
    </div>
</div>