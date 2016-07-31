<?php 
//定义活动的状态
$goods_status=array(
		'1'=>'未付款待审核',
		'2'=>'待审核付款中',
		'3'=>'已支付待审核',
		'4'=>'发布修改退款中',
		'5'=>'审核通过待上线',
		'10'=>'取消退款中',
		'11'=>'已取消',
		'12'=>'审核未通过退款中',
		'13'=>'审核未通过',
		'20'=>'正在进行',
		'21'=>'已屏蔽',
		'22'=>'已下架',
		'23'=>'追加付款中',
		'30'=>'结算退款中',
		'31'=>'结算中',
		'32'=>'已结算'); 
?>
<table class="ui-table">
  <thead>
    <tr>
      <th style="width: 5%;">活动编号</th>
      <th style="width: 20%;">用户邮箱</th>
      <th style="width: 8%;">商家名称</th>
      <th style="width: 10%;">用户头像</th>
      <th style="width: 30%;">活动标题</th>
      <th style="width: 12%;">活动图片</th>
      <th style="width: 10%;">活动状态</th>
      <th style="width: 5%;">操作</th>
    </tr>
  </thead>
  <tbody>
    <?php if(isset($search_goods)): foreach ($search_goods as $k=>$v):?>
    <tr id="search_row_<?php echo $v['gid'];?>">
      <td><?php echo $v['gid'];?></td>
      <td><?php echo $v['email'];?></td>
      <td><?php echo $v['uname'];?><br /></td>
      <td><?php echo img(avatar($v['uid'], 'small'));?></td>
      <td><?php echo $v['title'];?></td>
      <td><?php echo img(image_url(rand(0, count($this->config->item('image_servers'))-1), $v['img'], '60x60'));?></td>
      <td><?php echo $goods_status[$v['state']];?></td>
      <td>
         <?php if(in_array($v['gid'],$list_gids)){ ?>
         <font color="#999999">已推荐</font>
         <?php }else{ ?>
      <a href="<?php echo site_url('recommend/set_recommend');?>" type="post" data-recommend_type="<?php echo $recommend_type?>" data-targetid="<?php echo $v['gid'];?>" data-category="<?php echo $category;?>" data-cate_id="<?php echo $cate_id;?>" callback="load('<?php echo site_url($uri_string);?>', $('div#RecommendList'), {listonly:'yes'});onCallback('<?php echo $v['gid'];?>');">推荐</a>
      <?php } ?>
      </td>
    </tr>
    <?php endforeach; endif;?>
  </tbody>
  <tfoot>
    <tr>
      <td colspan="8" class="ui-paging"><?php echo isset($pager) ? $pager : '';?></td>
    </tr>
  </tfoot>
</table>
<script>
function onCallback(s){
	$('tr#search_row_'+s+' td:last').html('<font color="#999999">已推荐</font>');
}
</script>