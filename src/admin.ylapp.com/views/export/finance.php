<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
<div class="ui-box ui-box2 syslog">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head">
				<form action="<?php echo site_url('export/finance');?>" target="exportifrm" method="post">
					<span>活动类型:</span>
					<select name="type">
						<option value="all">所有活动</option>
						<option value="new">全新活动</option>
						<option value="add">追加活动</option>
					</select>
                    
                 <select name="pid" id="pid" onchange="changecid()">
                  <option value="0">全部主类目</option>
                  <?php  foreach($pidlist as $pv){?>
                   <option value="<?php echo $pv['id']; ?>"><?php echo $pv['name']; ?></option>
                   <?php } ?>
                </select>
                  <select name="cid"  id="cid">
                  <option value="0">全部子类目</option>
                </select>
                    
                    
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" name="startTime" readonly data-dateFmt="yyyy-MM-dd HH:mm:ss"> -
					<input class="ui-form-text ui-form-textGray ui-form-textDatetime" name="endTime" readonly data-dateFmt="yyyy-MM-dd HH:mm:ss">
					<input type="hidden" name="doexport" value="yes" />
					<input class="ui-form-btnSearch" type="submit" value="导出" />
				</form>
				<iframe id="exportifrm" name="exportifrm" src="#" frameborder="0" width="0" height="0"></iframe>
			</div>
		</div>
	</div>
</div>
<script language="javascript" type="text/javascript">
	var child_arr = $.parseJSON('<?php if($cidlist != ''){echo $cidlist;}else{echo '[]';}?>');
	function changecid(){
	var pdata = "<option value='0'>全部子类目</option>";
		var cdata = pdata;
		var pid= $("#pid").val();
		if(pid && child_arr[pid]){
		$.each(child_arr[pid], function(key, val) {   
	   pdata += "<option value='"+val['id']+"'>"+val['name']+"</option>";
	}); 
	}  	
	$("#cid").html(pdata);
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>