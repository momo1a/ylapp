<form id="editForm" class="window_form" action="<?php echo site_url('setting/set_reg_source_name')?>" method="post">
   <input name="dopost" type="hidden" value="yes" />
  <div class="h"> 
  <span>用户来源：</span>
      <input name="source" type="text" size="20" style=" padding:2px;" />
  </div>
  <div class="h">
   <span>统计方式：</span>
   &nbsp; <input type="radio" name="type" value="1" checked="checked" />模糊
   &nbsp;<input type="radio" name="type" value="2" />精确
  </div>
  <div class="h">
   <span>用户来源URL：</span>
      <input name="url" type="text" size="40" style=" padding:2px; margin-right:20px;" />
    </div>
  </div>
</form>
