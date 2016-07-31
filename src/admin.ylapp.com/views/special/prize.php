<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<script type="text/javascript">
//排序修改
function set_prize_cid(){
	var data = {};
	var v = $("input.prize_cid").each(function(i,o){
		var cid = parseInt($(o).val());
		if(cid > 0){
			data[$(o).attr('name')] = cid;	
		}
	});
	$.post(SITE_URL+"special/save", data, function(rs){
		if(AjaxFilter(rs)){
			load('<?php echo site_url(uri_string());?>', $('div#special_prize'), {listonly:'yes'})
		}
	},'json');
	return false;
}
function save(o){
	var id = parseInt($(o).data('pid')) || 0;
	if(id > 0){
		$('#prize_form input[name=pid]').val( id);
		$('#prize_form input[name=name]').val($('#row_'+id+' input#name').val());
		$('#prize_form input[name=quantity]').val($('#row_'+id+' input#quantity').val());
		$('#prize_form input[name=type]').val($('#row_'+id+' input#type').val());
		$('#prize_form input[name=cid]').val($('#row_'+id+' input#cid').val());
		$('#prize_form').submit();
	}
	return false;
}
function single_edit_prize_cid(o){
	$(o).hide();
	$('.item-cid span').hide();
	$(o).siblings().show();
	$('.item-cid input').show();
}

$(function(){
	$("#LinkList .editBtn").click(function(){
		var id = parseInt($(this).data('pid')) || 0;
		if(id > 0){
			$('#row_'+id+' .default_show').hide();
			$('#row_'+id+' .default_mode').hide();
			$('#row_'+id+' td span').hide();
			$('#row_'+id+' .edit_mode').show();
			$('#row_'+id+' td input').show();
		}
		return false;
	});
	$("#LinkList #cancel").click(function(){cancel(this)});
	$("#LinkList #save").click(function(){save(this)});
});

function cancel(o){
	var id = parseInt($(o).data('pid')) || 0;
	if(id > 0){
		$('#row_'+id+' .default_show').show();
		$('#row_'+id+' .default_mode').show();
		$('#row_'+id+' td span').show();
		$('#row_'+id+' .edit_mode').hide();
		$('#row_'+id+' td input').hide();
	}
	return false;
}
</script><style>
<!--
.special {
	word-break: break-all;
    word-wrap: break-word;
}

.special .ui-box-inner {
    padding: 15px;
}
-->
</style>
<div class="ui-box ui-box2 special" id="special_prize">
  <div class="ui-box-outer">
    <div class="ui-box-inner">
      <ul class="ui-tab-nav" id="tabs">
        <li class="ui-tab-item ui-tab-itemCurrent"><a href="<?php echo site_url('special/prize');?>">2014双11奖品</a></li>
      </ul>
      <div style="height:25px;border-top: 1px solid #CCCCCC; color:#00F; padding:10px;">奖品类型:1=>现金券，2=>实物，3=>谢谢参与<br>注:非现金券奖品，【现金券类型】填写无效，也不用理会。</div>
      <table id="LinkList" class="ui-table">
        <thead>
          <tr>
            <th style="width:5%;">奖品ID</th>
            <th style="width:20%;">奖品名</th>
            <th style="width:20%;">奖品份数</th>
            <th style="width:25%;">奖品类型</th>
            <th>现金券类型</th>
            <th style="width:15%;">操作</th>
          </tr>
        </thead>
        <tbody>
          <?php if(is_array($list)): foreach ($list as $k=>$v):?>
          <tr id="row_<?php echo $v['pid'];?>">
            <td>
			<?php echo $v['pid'];?>
			</td>
            <td class="item-name">
				<span><?php echo $v['name'];?></span>
				<input id="name" class="ui-form-text ui-form-textRed prize_name" style="display:none; text-align:center;" type="text" name="prize[<?php echo $v['pid'];?>][name]" value="<?php echo $v['name'];?>" />
			</td>
            <td class="item-quantity">
				<span><?php echo $v['quantity'];?></span>
				<input id="quantity" class="ui-form-text ui-form-textRed prize_quantity" style="display:none; text-align:center;" type="text" name="prize[<?php echo $v['pid'];?>][quantity]" value="<?php echo $v['quantity'];?>" />
			</td>
            <td class="item-type">
				<span><?php echo $v['type'];?></span>
				<input id="type" class="ui-form-text ui-form-textRed prize_type" style="display:none; text-align:center;" type="text" name="prize[<?php echo $v['pid'];?>][type]" value="<?php echo $v['type'];?>" />
			</td>
            <td class="item-cid">
				<span><?php echo $v['cid'];?></span>
				<input id="cid" class="ui-form-text ui-form-textRed prize_cid" style="display:none; text-align:center;" type="text" name="prize[<?php echo $v['pid'];?>][cid]" value="<?php echo $v['cid'];?>" />
            </td>
            <td>
            	<span class="edit_mode" style="display: none;">
					<a class="ui-form-button ui-form-buttonBlue" href="javascript:void(0);" data-pid="<?php echo $v['pid'];?>" id="save">保存</a>
					<a class="ui-form-button ui-form-buttonBlue" href="javascript:void(0);" data-pid="<?php echo $v['pid'];?>" id="cancel">取消</a>
				</span>
				<span class="default_mode" style="display: inline;">
					<a href="javascript:;" class="ui-operate-button ui-operate-buttonEdit editBtn" data-pid="<?php echo $v['pid'];?>" data-name="<?php echo $v['name'];?>" data-quantity="<?php echo $v['quantity'];?>" data-type="<?php echo $v['type'];?>" data-cid="<?php echo $v['cid'];?>" >编辑分类</a>
				</span>
            </td>
          </tr>
          <?php endforeach; endif;?>
        </tbody>
        <tfoot>
          <tr>
            <td colspan="6"></td>
          </tr>
        </tfoot>
      </table>
    </div>
	<form id="prize_form" type="ajax" method="post" callback="reload" action="<?php echo site_url('special/save')?>">
		<input type="hidden"  name="pid" value="0" />
		<input type="hidden" name="name" prefix="noempty" data-rule="required" data-msg="请输入奖品名" />
		<input type="hidden" name="quantity" prefix="noempty" data-rule="number" data-msg="请输入奖品份数，只能输入整数" />
		<input type="hidden" name="type" prefix="noempty" data-rule="number" data-msg="请输入奖品类型，只能输入整数" />
		<input type="hidden" name="cid" prefix="noempty" data-rule="number" data-msg="请输入奖品分类，只能输入整数" />
	</form>
  </div>
</div>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>
