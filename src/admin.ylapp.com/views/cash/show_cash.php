           <div class="voucher-box">
                <div href="javascript:;" class="voucher-mz voucher-mz-<?php show_price($cash);?>">
                    <h4 class="clearfix"><span>众划算-<?php echo $cash['ctitle'];?></span></h4>
                    <span class="voucher-price">￥<em><?php echo $cash['cprice'];?></em></span>
                    <dl class="voucher-note clearfix">
                        <dt>使用条件：</dt>
                        <dd>
                            <ol>
                                <?php show_condition($cash);?>
                            </ol>
                        </dd>
                    </dl>
                    <dl class="voucher-note clearfix">
                        <dt>有效时间：</dt>
                        <dd><?php echo date('Y/m/d',$cash['valid_start_time']);?> - <?php echo date('Y/m/d',$cash['valid_end_time']);?></dd>
                    </dl>
                </div>
            </div>
<?php 

//显示现金券的使用条件
function show_condition( $v ){
	$i = 1;
	if( $v['not_limit'] ){
		echo '<li>'.$i++.'. 不需要使用条件'.'</li>';return;
	}
	if( $v['is_time_limit'] ){
		echo '<li>'.$i++.'. 抢购时间属于'.date('Y-m-d',$v['time_limit_start_time']).'到'.date('Y-m-d',$v['time_limit_end_time']).'</li>';
	}
	if( $v['is_phone'] ){
		echo '<li>'.$i++.'. 需要使用客户端抢购'.'</li>';
	}
	if( $v['category_id'] ){
		echo '<li>'.$i++.'. 商品分类限制为'.$v['category_name'].'</li>';
	}
	if( $v['sum_price'] !='0.00' ){
		echo '<li>'.$i++.'. 网购价总额满'.$v['sum_price'].'元'.'（抢购状态“已完成”时统计）</li>';
	}
	if( $v['sum_cost_price'] !='0.00' ){
		echo '<li>'.$i++.'. 活动价总额满'.$v['sum_cost_price'].'元'.'（抢购状态“已完成”时统计）</li>';
	}
	if( $v['sum_rebate'] !='0.00' ){
		echo '<li>'.$i++.'. 已返现总额满'.$v['sum_rebate'].'元'.'（抢购状态“已完成”时统计）</li>';
	}
}
//不同面额显示不同的颜色
function show_price($cash){
	if( in_array( intval($cash['cprice']) , array(5,10,20,50,100) ) ){
		echo intval($cash['cprice']);
	}else{
		echo '0';
	}
}
?>