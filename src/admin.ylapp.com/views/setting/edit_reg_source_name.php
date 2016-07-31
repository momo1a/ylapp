<form id="editForm" class="window_form" action="<?php echo site_url('setting/edit_reg_source_name')?>" method="post">
   <input name="editpost" type="hidden" value="yes" />
   <input name="id" type="hidden" value="<?php echo $id; ?>" />
  <div class="h"> 
  <span>用户来源：</span>
      <input name="source" type="text" size="20" value="<?php echo $urls['name']; ?>" style=" padding:2px;"  />
  </div>
  <div class="h">
   <span>统计方式：</span>
   &nbsp; <input type="radio" name="type" value="1" <?php if($urls['type']==1) echo 'checked="checked"';?> />模糊
   &nbsp;<input type="radio" name="type" value="2"  <?php if($urls['type']==2) echo 'checked="checked"';?>/>精确
  </div>
  <div class="h">
   <span>用户来源URL：</span>
      <input name="url" type="text" size="40" value="<?php echo $urls['url']; ?>"  style=" padding:2px; margin-right:20px;"/>
    </div>
  </div>
</form>
