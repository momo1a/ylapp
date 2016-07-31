<div class="ui-box ui-box2 paying" style="width:800px;">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-head paying-head">结算众划算商品</div>
			<div class="paying-body">
                    
                <?php 
                // 判断是否为搜索下单
                 if( 3 == Goods_model::TYPE_SEARCH_BUY ) :
                ?>
                <table class="ui-table">
					<thead>
						<tr>
							<th>结算商品名称</th>
                                    <th>每份结算担保金额（元）</th>
                                    <th>每份结算服务费金额（元）</th>
                                 <?php if($goodsInfo['type']==Goods_model::TYPE_SEARCH_BUY){echo '<th>每份结算搜索奖励金（元）</th>';} ?>  
                                    <th>总结算数量（份）</th>
                                    <th>合计（元）</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $goodsInfo['title'];?></td>
							<td><?php echo $goodsInfo['deposit_type']==1 ? $goodsInfo['single_rebate'] : $goodsInfo['price'];?></td>
							<td><?php echo $goodsInfo['single_fee'];?></td>
                            <?php if($goodsInfo['type']==Goods_model::TYPE_SEARCH_BUY){?> <td><?php echo $goodsInfo['search_reward'];?></td> <?php }?>
							<td><?php echo $remainQuantity;?></td>
							<td><?php echo $canBalanceSum;?></td>
						</tr>
					</tbody>
					<tfoot>
					<tr>
						<?php if($goodsInfo['type']==Goods_model::TYPE_SEARCH_BUY){ echo '<td colspan="6">'; }else{echo '<td colspan="5">';}?>
								<form action="<?php echo site_url();?>/goods/balance" method="post" callback="reload">
									<input type="hidden" value="1" name="doBalanceSubmit" />
                                            <input type="hidden" value="<?php echo $goodsInfo['gid'];?>" name="gid" />
                                            <span class="ps">平台损耗费 = 网购价 × <?php echo $rate;?>%<em style="color: #E95644;">（注：计算结果四舍五入，精确到两位小数）</em></span>
                                            <?php if($goodsInfo['type']==Goods_model::TYPE_SEARCH_BUY){?>
                                            <span style="display: block;">搜索奖励金 = （网购价 * 10%） -  平台损耗费<em style="color: #E95644;">（注：网购价*10%的结果四舍五入，精确到两位小数）</em></span>
                                            <?php  }?>
									总计:<strong style=" color:#F00;"><?php echo $canBalanceSum;?></strong>元
								</form>
							</td>
						</tr>
					</tfoot>
				</table>
<p class="explain" style="color:#999; line-height:20px; height:110px;padding-top: 10px;"> 
说明：返现金额将作为本次活动返还给购买者的折扣款项（返现金额）；平台损耗费为众划算按成交的笔数逐笔收取；其余未售出的商品返现金及平台损耗费将在活动结算后退还到您的互联支付账号。
          				</p>
                <?php else :?>
          
				<table class="ui-table">
					<thead>
						<tr>
							<th>结算商品名称</th><th>每份结算担保金额（元）</th><th>每份结算服务费金额（元）</th><th>总结算数量（份）</th><th>合计（元）</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td><?php echo $goodsInfo['title'];?></td>
							<td><?php echo $goodsInfo['deposit_type']==1 ? $goodsInfo['single_rebate'] : $goodsInfo['price'];?></td>
							<td><?php echo $goodsInfo['single_fee'];?></td>
							<td><?php echo $remainQuantity;?></td>
							<td><?php echo $canBalanceSum;?></td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<td colspan="5">
								<form action="<?php echo site_url();?>/goods/balance" method="post" callback="reload">
									<input type="hidden" value="1" name="doBalanceSubmit" />
                                    <input type="hidden" value="<?php echo $goodsInfo['gid'];?>" name="gid" />
                                    <span class="ps">平台损耗费 = 网购价 × <?php echo $rate;?>%<em style="color: #E95644;">（注：计算结果四舍五入，精确到两位小数）</em></span>总计:
									<strong style=" color:#F00;"><?php echo $canBalanceSum;?></strong>元
								</form>
							</td>
						</tr>
					</tfoot>
				</table>
          <p class="explain" style="color:#999; line-height:20px; height:50px;padding-top: 10px;"> &nbsp; &nbsp; 说明：担保金款项部分作为本次众划算活动返还给购买者的折扣款项（返现金额），另一部分在活动中逐笔退还到您的互联支付；平台损耗费为众划算按成交的笔数逐笔收取；其余未售出的商品担保金及平台损耗费，将在活动结束后退还到您的互联支付账户。</p>
                    <?php endif;?>
			</div><!-- /paying-body-->
		</div><!-- /ui-box-inner -->
	</div><!-- /ui-box-outer -->
</div><!-- /ui-box -->