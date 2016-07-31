<form id="editForm" class="window_form" action="<?php echo site_url('goods/edit')?>" method="post">
	<div class="h">
		<span>浏览器标题：</span>
		<div class="pingzhen clearfix">
			<input name="seo_title" value="<?php echo $goods['seo_title'];?>" size="50" data-rule="required" data-msg="请输入浏览器标题">
		</div>
	</div>
	<div class="h">
		<span>活动标题：</span>
		<div class="pingzhen clearfix">
			<input name="title" value="<?php echo $goods['title'];?>" size="50" data-rule="required|maxlength(50)" data-msg="请输入活动标题|活动标题最多50个字符">
		</div>
	</div>
	<div class="h">
		<span>商品类目：</span>
		<div class="pingzhen clearfix">
			<select id="cbopid" name="pid" onchange="resetcid(this)" data-rule="required" data-msg="请选择一级商品类目">
				<option value="">--请选择--</option>
				<?php foreach ($cates as $k=>$v):?>
				<option<?php if($v['id']==$goods['pid']): $child_cates = $v['children'];?> selected="selected"<?php endif;?> value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
				<?php endforeach;?>
			</select>
			<select id="cbocid" name="cid" data-rule="required" data-msg="请选择二级商品类目">
				<option value="">--请选择--</option>
				<?php foreach ($child_cates as $k=>$v):?>
				<option<?php if($v['id']==$goods['cid']):?> selected="selected"<?php endif;?> value="<?php echo $v['id'];?>"><?php echo $v['name'];?></option>
				<?php endforeach;?>
			</select>
		</div>
	</div>
    <?php if(is_array($prompts_list) && count($prompts_list)>0):  ?>
        <div class="h">
		<span>温馨提示：</span>
		<div class="pingzhen clearfix J_wenxintishi">
        <?php foreach ($prompts_list as $k=>$v):
		if($v['type']==1){?>
        <p><input type="checkbox" name="items[]" value="<?php echo $v['id']; ?>" style="display:inline;"<?php if(in_array($v['id'],$goods['items'])){ echo ' checked="checked"';} ?> /> <?php echo  $v['title'];?></p>
	<?php	 }else{  ?>
	  <p> <input type="checkbox" name="items[]" value="<?php echo $v['id']; ?>" style="display:inline;"<?php if(in_array($v['id'],$goods['items'])){ echo ' checked="checked"';} ?> /> <?php echo  $v['title'];?> <input name="prompts[<?php echo $v['id'] ?>]" placeholder="<?php echo $v['prompts'] ?>" value="<?php if(isset($goods['prompts'][$v['id']])){ echo  $goods['prompts'][$v['id']];}?>" size="30"  style="display:inline;"  ></p>
		<?php }  endforeach; ?>
        </div></div>
      <?php endif; ?>
    <div class="h">
		<span>拍下须知：</span>
		<div class="pingzhen clearfix">
		<input name="instruction" value="<?php echo $goods['instruction'];?>" size="50"  maxlength="30"/>
		</div>
	</div>
	<div class="h">
		<span>商品简介：</span>
		<div class="pingzhen clearfix">
			<textarea name="content" style="width:360px;height:80px; " ><?php echo $goods['content'];?></textarea>
		</div>
	</div>
	<div class="h">
		<span>连接地址：</span>
		<div class="pingzhen clearfix">
			<input name="url" value="<?php echo $goods['url'];?>" size="50" data-rule="required|url" data-msg="请输入连接地址|连接地址错误"/>
		</div>
	</div>
	<div class="h">
		<span>关键字：</span>
		<div class="pingzhen clearfix">
			<input name="seo_keyword" value="<?php echo $goods['seo_keyword'];?>" size="50" data-rule="required" data-msg="请输入关键字">
		</div>
	</div>
	<div class="h">
		<span>增加天数：</span>
		<div class="pingzhen clearfix">
			<input name="days" value="0" size="5" data-rule="number" data-msg="增加天数只能为整数">
			活动天数：<?php echo $goods['first_days'];?> 天
		</div>
	</div>
	<div class="h">
		<span>抢购限制：</span>
		<div class="pingzhen clearfix">
			每个会员最多可以抢购 <?php echo (int)$goods['buy_limit'] ? $goods['buy_limit'] : 3;?> 次
		</div>
	</div>
	<div class="h">
		<span>活动类型：</span>
		<div class="pingzhen clearfix">
			<select id="cbotype" name="goodstype" data-yzcm="<?php echo $goods['type'];?>"  <?php if($goods['type'] == Goods_model::TYPE_STAGES):?> disabled <?php endif;?>>
				<?php foreach ($goods_types as $k=>$typename):?>
				<option<?php if($goods['type'] == $k):?> selected="selected"<?php endif;?> value="<?php echo $k;?>"><?php echo $typename;?></option>
				<?php endforeach;?>
			</select>
            <?php if($goods['type'] == Goods_model::TYPE_STAGES):?><input type="hidden" name="goodstype" value="<?php echo Goods_model::TYPE_STAGES;?>"/><?php endif;?>
		</div>
	</div>
    <?php if($goods['type'] == Goods_model::TYPE_STAGES  && !empty($goods['stg_ext'])):?>
    <div class="h">
        <span>会员返利金额：</span>
        <div class="pingzhen clearfix">
            <input name="back_money" value="<?php echo $goods['stg_ext']['back_money']?>" size="5" data-rule="float" data-msg="请填写正确的会员返利金额" style="display: inline">
            <span>元</span>
        </div>
    </div>
        <div class="h">
            <span>免息天数：</span>
            <div class="pingzhen clearfix">
                <input name="escape_interest_days" value="<?php echo $goods['stg_ext']['escape_interest_days'];?>" size="5" data-rule="number" data-msg="免息天数只能为整数" style="display: inline">
                <span>天</span>
            </div>
        </div>
        <div class="h">
            <span>滞纳金：</span>
            <div class="pingzhen clearfix">
                <input name="late_fee_percent" value="<?php echo $goods['stg_ext']['late_fee_percent'];?>" size="5" data-rule="float" data-msg="滞纳金百分比只能为小数" style="display: inline">
                <span>%</span>
            </div>
        </div>
        <div class="h">
            <span>利率：</span>
            <div class="pingzhen clearfix">
                <input name="interest_percent" value="<?php echo $goods['stg_ext']['interest_percent'];?>" size="5" data-rule="float" data-msg="滞纳金百分比只能为小数" style="display: inline">
                <span>%</span>
            </div>
        </div>
        <div class="h">
            <span>礼包占活动价的百分数：</span>
            <div class="pingzhen clearfix">
                <input name="gifts_percent" value="<?php echo $goods['stg_ext']['gifts_percent'];?>" size="5" data-rule="number" data-msg="礼包百分数只能为整数" style="display: inline">
                <span>%</span>
            </div>
        </div>
    <?php endif;?>
	<div class="h">
		<span>商品SEO描述：</span>
		<div class="pingzhen clearfix">
			<textarea name="seo_description" style="width:360px;height:80px;" data-rule="maxlength(200)" data-msg="请输入商品SEO描述|SEO描述最少30个字符|SEO描述最多200个字符"><?php echo $goods['seo_description'];?></textarea>
		</div>
	</div>
	<input type="hidden" name="dosave" value="yes" />
	<input type="hidden" name="gid" value="<?php echo $goods['gid'];?>" />
</form>
<script type="text/javascript">
// 温馨提示效果（选中复选框激活文本框焦点、文本框有内容则选中复选框，没有内容则相反）
$(".J_wenxintishi :text").change(function(){
	var $this = $(this);
	$this.closest("p").find(":checkbox").prop("checked", $this.val()?true:false);
});
$(".J_wenxintishi :checkbox").change(function(){
	var $this = $(this);
	$this.prop("checked") && $this.closest("p").find(":text").focus();
});

$('.pingzhen').delegate("#cbotype","change",function(){
	var g_type = $('#cbotype').val();
	var g_current_type = $('#cbotype').data('yzcm');

	if(  ['1','3','4','5'].in_array(g_type) || ['1','3','4','5'].in_array(g_current_type)  ){
		var cf = artDialog({
			id : 'Confirm',
			title : '操作提示',
			icon : 'question',
			fixed : true,
			lock : true,
			opacity : 0.5,
			content : '<style>b.setyzcm{color:red;}</style>该活动类型不允许修改类型',
			ok : function(here) {
				$('#cbotype').val(g_current_type);
				return true;
			}
		});
	}
	
});
Array.prototype.S=String.fromCharCode(2);
Array.prototype.in_array=function(e){
    var r=new RegExp(this.S+e+this.S);
    return (r.test(this.S+this.join(this.S)+this.S));
};
function resetcid(e){
	var cates = <?php echo json_encode($cates);?>;
	var currpid = $(e).val();
	$(e).siblings("select").html('<option value="">--请选择--</option>');
	$.each(cates, function(i, o){
		if(o.id == currpid){
			var cidsel = $(e).siblings("select");
			$.each(o.children, function(si, so){
				$("<option>").attr("value", so.id).text(so.name).appendTo(cidsel);
			});
			return;
		}
	});
}
</script>
<style type="text/css">
.window_form .clearfix span {
    float: none;
    text-align: left;
    width: auto;
}

</style>