<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box ui-box2 concurrent"><div class="ui-box-outer"><div class="ui-box-inner">
	<div class="ui-box-head concurrent-head">已发送站内信</div>
	<div class="ui-box-body clearfix">
		<div style="width: 59%;float:left;" id="sent_list"></div>
		<div style="width: 40%;float:right;" id="sent_detail"></div>
	</div>

</div></div></div>
<script type="text/javascript">
$(function(){
	load_sent_list();
});
function load_sent_list(url){
	var url = (url || '<?php echo site_url('message/sent?mod=list');?>');
	$.get(url, function(data){
		$('#sent_list').html(data);
		$("#msgsentform table tbody tr").eq(0).find("a").click();	/*默认打开第一条*/
	});
}
</script>
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>