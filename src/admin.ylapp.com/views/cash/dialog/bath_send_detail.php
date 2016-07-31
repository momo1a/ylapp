
<?php 
function show_state( $v ){
	if( $v['is_reg_time'] + $v['is_phone_reg'] + $v['is_last_order_time'] + $v['is_sum_price'] + $v['is_sum_cost_price']+ $v['is_sum_rebate']==0){
		echo '&nbsp;&nbsp;&nbsp;还没有设置批量发放条件';exit;
	}
	$i = 1;
	if( $v['is_reg_time']==1 ){
		echo $i++.'、注册时间是  '.date('Y-m-d H:i:s',$v['reg_start_time']).' 到  '.date('Y-m-d H:i:s',$v['reg_end_time']).'<br/>';
	}
	if( $v['is_phone_reg']==1 ){
		echo $i++.'、需要是手机注册'.'<br/>';
	}
	if( $v['is_last_order_time']==1 ){
		echo $i++.'、'.date('Y-m-d H:i:s',$v['last_order_start_time']).'≤最后一次抢购时间≤'.date('Y-m-d H:i:s',$v['last_order_end_time']).'<br/>';
	}
	if( $v['is_sum_price']==1 ){
		echo $i++.'、';
		echo date( 'Y-m-d H:i:s',$v['sum_price_start_time'] ) . ' 到  ' .date( 'Y-m-d H:i:s',$v['sum_price_end_time'] ) .'网购价总额';
		echo $v['sum_price_or']==0 ? '＜' : '≥';
		echo $v['send_sum_price'] .'元<br/>';
	}
	if( $v['is_sum_cost_price']==1 ){
		echo $i++.'、';
		echo date( 'Y-m-d H:i:s',$v['sum_cost_price_start_time'] ) . ' 到  ' .date( 'Y-m-d H:i:s',$v['sum_cost_price_end_time'] ) .'活动价总额';
		echo $v['sum_cost_price_or']==0 ? '＜' : '≥';
		echo $v['send_sum_cost_price'] .'元<br/>';
	}
	if( $v['is_sum_rebate']==1 ){
		echo $i++.'、';
		echo date( 'Y-m-d H:i:s',$v['sum_rebate_start_time'] ) . ' 到  ' .date( 'Y-m-d H:i:s',$v['sum_rebate_end_time'] ) .'已返现总额';
		echo $v['sum_rebate_or']==0 ? '＜' : '≥';
		echo $v['send_sum_rebate'] .'元<br/>';
	}
}
?>
<div style="width:450px">
<br/>
<?php show_state( $cash_info );?>
<br/>
<br/>
</div>