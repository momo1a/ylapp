<table cellspacing="0" class="ui-table appeal-detail">
	<thead>
		<tr>
			<th>申诉/回应人</th>
			<th>申诉类型</th>
			<th>申诉/回应内容</th>
			<th>相关凭证图片</th>
			<th>联系方式</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td><?php echo $vo['uname'];?>(<?php echo $vo['uid'];?>)</td>
			<td><?php echo $vo['type_name'];?></td>
			<td><?php echo $vo['content'];?></td>
			<td><?php
				$atts = explode(',', $vo['attachement']);
				$i = 1;
				foreach ($atts as $k=>$v){
					if(strlen($v) >= 5){
						echo anchor(image_url($i, $v), '凭证'.$i, 'target="_blank"');
						$i += 1;
					}
				}
			?></td>
			<td><?php echo $vo['contact_qq']?'QQ:'.$vo['contact_qq'].'<br />':'';?><?php echo $vo['contact_wangwang']?'旺旺:'.$vo['contact_wangwang'].'<br />':'';?><?php echo $vo['contact_telephone']?'电话:'.$vo['contact_telephone']:'';?></td>
		</tr>
		<?php if($vo['reply_time']):?>
		<tr>
			<td><?php echo $vo['reply_uname'];?>(<?php echo $vo['reply_uid'];?>)</td>
			<td><?php echo $vo['type_name'];?></td>
			<td><?php echo $vo['reply_content'];?></td>
			<td><?php
				$atts = explode(',', $vo['reply_attachement']);
				$i = 1;
				foreach ($atts as $k=>$v){
					if(strlen($v) >= 5){
						echo anchor(image_url($i, $v), '凭证'.$i, 'target="_blank"');
						$i += 1;
					}
				}
			?></td>
			<td></td>
		</tr>
		<?php endif;?>
	</tbody>
</table>