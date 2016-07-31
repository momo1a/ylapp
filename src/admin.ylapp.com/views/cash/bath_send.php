<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 advertisement">
  <div class="ui-box-outer">
    <div class="ui-box-inner">
      <ul class="ui-tab-nav" id="tabs">
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_send');?>">手动发放</a></li>
        <li class="ui-tab-item ui-tab-itemCurrent"><a href="<?php echo site_url('cash/index/bath_send');?>">批量发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/code_send');?>">兑换码发放</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/detail_send');?>">发放记录</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_type');?>">现金券类型</a></li>
        <li class="ui-tab-item"><a href="<?php echo site_url('cash/index/cash_cdkey');?>">兑换码记录</a></li>
      </ul>
      <div class="ui-box ui-box2 advertisement-add">
        <div class="ui-box-head"><span class="">发放现金券</span></div>
        <form id="cash_send_form" type="ajax" callback="reload" method="post" action="<?php echo site_url('cash/bath_send')?>" enctype="multipart/form-data">
          <ul>
           	<li><span>现金券类型:</span>
              <select name="cash[cid]" id="cash_cid">
<?php if(is_array($cash_info)): foreach ($cash_info as  $item):?>
                <option value="<?php echo $item['cid'];?>"><?php echo $item['cname'];?></option>
<?php endforeach; endif;?>
              </select>
	        </li>
	        <li>
	          <div style="margin: 0px 0px 0 108px;" id="cash_condition">
              </div>
	        </li>
	        <li><span>发放数量:</span>
               <input data-type="quantity" class="ui-form-text ui-form-textRed" size="10" name="cash[quantity]" data-rule="required|number|min(1)|max(20000)" data-msg="*请输入发放的数量|*只能输入整数|*发放数量最小值为1|*发放数量最大值为20000"/> 个<span id="for_cash_quantity_" style="color: red; display: inline;"></span>
            </li>
            <li><span>发放条件:</span></li>
            <li id="bath_send_detail" style="margin-left:111px;margin-top:-47px">
            </li>
            <li>
                <div style="margin: 0px 0px 0 108px;">• 互联支付可用余额：<em data-type="balance"><img style="vertical-align: middle;" src="<?php echo $this->config->item('domain_static'); ?>admin/images/loading.gif"/></em></div>
                <div style="margin: 0px 0px 0 108px;" data-type="show-amount"></div>
            </li>
            <li>
          <input type="submit" value="确定" class="ui-form-button ui-form-buttonBlue"/>
          </li>
          </ul>
        </form>
      </div>
    </div>
  </div>
</div>
<script type="text/javascript">
!function($form){

    // 读取余额
    $.get("<?php echo site_url('cash/get_money/');?>",function(data){
        $form.find("[data-type=balance]").html(data+"元");
    });
    
    // 现金券类型切换
    var cash_id = $('#cash_cid').val();
    if( cash_id ){
        $('#cash_condition').load('<?php echo site_url('cash/show_cash/').'/';?>'+$('#cash_cid').val(), function(){
            $form.find("[data-type=quantity]").blur();
        });
        $('#bath_send_detail').load('<?php echo site_url('cash/bath_send_detail/').'?cid=';?>'+$('#cash_cid').val());
    }
    $('#cash_cid').change(function(){
        $('#cash_condition').load('<?php echo site_url('cash/show_cash/').'/';?>'+$('#cash_cid').val(), function(){
            $form.find("[data-type=quantity]").blur();
        });
        $('#bath_send_detail').load('<?php echo site_url('cash/bath_send_detail/').'?cid=';?>'+$('#cash_cid').val());
    });

    // 冻结金额计算
    var $show_amount = $form.find("[data-type=show-amount]");
    $form.find("[data-type=quantity]").blur(function(){
        var lh = parseInt($(this).val()),lh=isNaN(lh)?0:lh,
            mz = $form.find(".voucher-price em").html();
        $show_amount.html("• 互联支付账户“<em class=\"ui-table-statusR\">众划算</em>”需冻结金额："+mz+"（面额）*"+lh+"（数量）=<strong>"+(Number(mz)*lh).toFixed(2)+"</strong>元");
    });

    // 表单提交
    $form.submit(function(){
        var $this = $(this);
        if(!FormValidate($this)){return false};
        art.dialog({
            lick   : true,
            icon   : "question",
            title  : "发放现金券--确认操作",
            content: "确定要发放现金券？",
            cancel : true,
            ok:function(){
                var dialog = this,
                    ok = $(dialog.DOM.buttons[0]).find('button:first').html('操作中...').attr('disabled', 'disabled').removeClass('aui_state_highlight');
                $.ajax({
                    url : $this.attr("action"),
                    data: $this.serialize(),
                    type: $this.attr("method"),
                    dataType:"json",
                    error:function(){dialog.close();PopupTips("网络连接失败","error",3000);},
                    success:function(ret){
                        dialog.close();
                        if(!ret.error){
                            art.dialog({
                                lick   : true,
                                title  : "发放现金券--确认操作",
                                content: "付款后完成现金券发放"
                                        +'<p style="margin-top:15px;">'
                                        +   '<a href="<?php echo site_url('/cash/pay/').'?id='?>'+ret.data.pay_id+'" target="_blank" class="ui-form-button ui-form-buttonBlue">马上去付款</a>'
                                        +   '<a href="javascript:;" class="ui-form-button ui-form-buttonGray" style="margin-left:20px;">取消</a>'
                                        +'</p>',
                                init:function(){
                                    var dialog = this,
                                        con = $(this.DOM.content[0]);
                                    con.find(".ui-form-buttonGray").click(function(){dialog.close()});
                                    con.find(".ui-form-buttonBlue").click(function(){
                                        dialog.title("提示");
                                        dialog.content('已完成付款？<a href="<?php echo site_url('/cash/index/detail_send/');?>" style="color:#148AFF;">去发放记录看看</a>');
                                    });
                                }
                            });
                        }else{
                            PopupTips(ret.msg,"error",3000);
                        }
                    }
                });
            }
        })
        return false;
    });

}($("#cash_send_form"));





</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>