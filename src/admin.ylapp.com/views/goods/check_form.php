<form id="editForm" class="window_form" action="<?php echo site_url($this->router->class.'/'.$this->router->method);?>" method="post">
<div class="h" style="padding: 20px;">
 <?php 
	 $today=strtotime(date('Y-m-d',time()));
	 $curtime=time();
	 $mtime=0;
	 $chevaltype=0;
	 $goods_new_parvial_field=explode(',',$this->config->item('goods_new_parvial_field'));
	 if($goods_new_parvial_field[0]> 0 ){
	 //今天的上线时间
	 foreach($goods_new_parvial_field as $k=>$val){
		    $val=intval($val);
			$disval='';
			$timeval=$today+$val*60*60;
			if($timeval < $curtime){
			   $disval=' disabled="disabled" ';
			}
			$cheval = '';
			if($chevaltype==0 && $timeval > $curtime){
				 $cheval=' checked="checked" ';
				 $chevaltype=1;
			}
			echo '<label><input type="radio" name="endTimetype" value="'.$timeval.'"  '.$disval.$cheval.' /> 今天'.date('H:i',$timeval).'&nbsp; &nbsp;</label> ';
			$cheval='';
		 }
	        echo '<br/><br/>';
	//明天的上线时间
	 foreach($goods_new_parvial_field as $k=>$val){
		    $val=intval($val);
		    $tomorrowtime=$today+$val*60*60+86400;
			echo '<label><input type="radio" name="endTimetype" value="'.$tomorrowtime.'" /> 明天'.date('H:i',$tomorrowtime).'&nbsp; &nbsp; </label>';
		 }
		 
	 } 
 ?>
 </div>
	<div class="h" style="padding:20px; padding-top:0px;">
		<input style="display:none;" data-datefmt="yyyy-MM-dd HH:mm:ss" name="startTime" value="<?php echo date("Y-m-d H:i:s", time()); ?>" readonly class="ui-form-text ui-form-textGray ui-form-textDatetime" />
		<label><input type="radio" name="endTimetype" value="0" /> 其它上线时间：<input data-datefmt="yyyy-MM-dd HH:mm:ss" name="endTime" value="<?php echo date("H")<10 ? date("Y-m-d 10:00:00") : date("Y-m-d 10:00:00", strtotime('1 days')); ?>" readonly class="ui-form-text ui-form-textGray ui-form-textDatetime" /></label>
	</div>
	<input type="hidden" name="dosave" value="yes" />
	<input type="hidden" name="pass" value="1" />
	<input type="hidden" name="gid" value="<?php echo $gid;?>" />
</form>