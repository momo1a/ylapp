<?php if($type==2):?>
    <?php if($goods['type'] == Goods_model::TYPE_SEARCH_BUY):?>
    <table cellpadding="3" cellspacing="1" class="ui-table">
      <thead>
        <tr>
          <th width="50"></th>
          <th>商品编号</th>
          <th>商家编号</th>
          <th>返还担保金</th>
          <th>返还搜索奖励金</th>
          <th>返还服务费</th>
          <th>结算时间</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $k=>$v):?>
        <tr>
          <td><?php echo $k+1;?></td>
          <td><?php echo $v['gid'];?></td>
          <td><?php echo $v['seller_uid'];?></td>
          <td><?php
          //计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
          echo $deposit_type==1 ? $v['due_rebate'] : $v['price'];?>元</td>
          <td><?php echo $v['search_reward'];?> 元</td>
          <td><?php echo $v['fee']; ?> 元</td>
          <td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="7" class="ui-paging"><?php  echo $pager;?></td>
        </tr>
      </tfoot>
    </table>
    <?php else:?>
    <table cellpadding="3" cellspacing="1" class="ui-table">
      <thead>
        <tr>
          <th width="50"></th>
          <th>商品编号</th>
          <th>商家编号</th>
          <th>返还担保金</th>
          <th>返还服务费</th>
          <th>结算时间</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $k=>$v):?>
        <tr>
          <td><?php echo $k+1;?></td>
          <td><?php echo $v['gid'];?></td>
          <td><?php echo $v['seller_uid'];?></td>
          <td><?php
          //计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
          echo $deposit_type==1 ? $v['due_rebate'] : $v['price'];?>元</td>
          <td><?php echo $v['fee']; ?> 元</td>
          <td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="6" class="ui-paging"><?php  echo $pager;?></td>
        </tr>
      </tfoot>
    </table>
    <?php endif;?>

<?php else:?>

    <?php if($goods['type'] == Goods_model::TYPE_SEARCH_BUY):?>
    <table cellpadding="3" cellspacing="1" class="ui-table">
      <thead>
        <tr>
          <th width="50"></th>
          <th>商品编号</th>
          <th>商家编号</th>
          <th>买家编号</th>
          <th>返还担保金</th>
          <th>返现</th>
           <th>返还搜索奖励金</th>
          <th>扣除服务费</th>
          <th>返现时间</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $k=>$v):?>
        <tr>
          <td><?php echo $k+1;?></td>
          <td><?php echo $v['gid'];?></td>
          <td><?php echo $v['seller_uid'];?></td>
          <td><?php echo $v['buyer_uid'];?></td>
          <td><?php
          //计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
          $real_single_guaranty = $deposit_type==1 ? $v['due_rebate'] : $v['price'];
          echo bcsub($real_single_guaranty, $v['real_rebate'], 2);?> 元</td>
          <td><?php echo $v['real_rebate'];?> 元</td>
          <td><?php echo $v['search_reward'];?> 元</td>
          <td><?php echo $v['fee'];?> 元</td>
          <td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="9" class="ui-paging"><?php  echo $pager;?></td>
        </tr>
      </tfoot>
    </table>
    <?php else:?>
    <table cellpadding="3" cellspacing="1" class="ui-table">
      <thead>
        <tr>
          <th width="50"></th>
          <th>商品编号</th>
          <th>商家编号</th>
          <th>买家编号</th>
          <th>返还担保金</th>
          <th>返现</th>
          <th>扣除服务费</th>
          <th>返现时间</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($logs as $k=>$v):?>
        <tr>
          <td><?php echo $k+1;?></td>
          <td><?php echo $v['gid'];?></td>
          <td><?php echo $v['seller_uid'];?></td>
          <td><?php echo $v['buyer_uid'];?></td>
          <td><?php
          //计算商家存入的每份担保金金额 说明：之前存入的每份担保金金额为网购价，修改后存入的每份担保金金额为返回给买家的金额  updateby 关小龙 2015-09-22 10:12:00
          $real_single_guaranty = $deposit_type==1 ? $v['due_rebate'] : $v['price'];
          echo bcsub($real_single_guaranty, $v['real_rebate'], 2);?> 元</td>
          <td><?php echo $v['real_rebate'];?> 元</td>
          <td><?php echo $v['fee'];?> 元</td>
          <td><?php echo date("Y-m-d H:i:s", $v['dateline']);?></td>
        </tr>
        <?php endforeach;?>
      </tbody>
      <tfoot>
        <tr>
          <td colspan="8" class="ui-paging"><?php  echo $pager;?></td>
        </tr>
      </tfoot>
    </table>
    <?php endif;?>

<?php endif;?>