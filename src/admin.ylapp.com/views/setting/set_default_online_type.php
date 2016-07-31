<form id="editForm" class="window_form" action="<?php echo site_url('setting/set_default_online_time')?>" method="post">
   <input name="dopost" type="hidden" value="yes" />
  <div class="h"> 
   <label>
  <span>
   <input type="radio" name="goods_default_online_type" value="1" <?php if($type==1){echo 'checked="checked" ';} ?>  onclick="defaultchan(1);"/>
    </span>
    <div class="pingzhen clearfix"> 系统自动（默认上线时间为下一个分场时间）</div>
    </label>
  </div>
  
  <div class="h">
  <label>
   <span>
    <input type="radio" name="goods_default_online_type" value="2" <?php if($type==2){echo 'checked="checked" ';} ?>  onclick="defaultchan(2);"/>
    </span>
    <div class="pingzhen clearfix">全部手动（审核"通过"需要手动选择时间）</div>
    </label>
  </div>
  <div class="h">
 
  <label>
   <span>
    <input type="radio" name="goods_default_online_type" value="3"  <?php if($type==3){echo 'checked="checked" ';} ?>  onclick="defaultchan(3);"/>
    </span>
    <div class="pingzhen clearfix">设置默认（默认上线时间统一为你选择的时间）</div>
    <div id="ordertime" style="padding-left:110px; padding-top:25px; "> 其它时间：
      <input data-datefmt="yyyy-MM-dd HH:mm:ss" name="orderTime" value="<?php echo $orderTime > 0  ? date("Y-m-d H:i:s",$orderTime) : date("Y-m-d 10:00:00", strtotime('1 days')); ?>" readonly class="ui-form-text ui-form-textGray ui-form-textDatetime" />
    </div>
    </label>
  </div>
</form>
<script language="javascript" type="text/javascript">
function defaultchan(val){
	if(val==3){
	  $('#ordertime').css("visibility","visible")
	}else{
	  $('#ordertime').css("visibility","hidden")
	}
}
</script> 
