
<div style="width:600px; height:400px; border:1px #ccc solid; " >
	<form action="<?php echo site_url($action)?>" method="get">
		<table style="width:580px; margin: 10px 0px 0px 10px; ">
			<tr style="height:50px;"><td>您批量推送的活动，将会在【<b><?php echo $crumbs;?></b>】展示</td></tr>
			<tr>
				<td>
					<textarea name="content"  style="width:100%; height:180px"  placeholder="1001,1002,1003,1004,1005"></textarea>
				</td>
			</tr>
			<tr>
				<td>
<b>举例：</b><br/>
&nbsp;&nbsp;&nbsp;&nbsp;1001,1002,1003,1004,1005<br/>
<b>使用说明：</b><br/>
&nbsp;&nbsp;&nbsp;&nbsp;1.输入活动编号，并以英文逗号分隔。<br/>

<b>注意事项：</b><br/>
&nbsp;&nbsp;&nbsp;&nbsp;1.程序有检测活动是否存在的判断，避免误推送。<br/>
&nbsp;&nbsp;&nbsp;&nbsp;2.同一个选项卡中，系统会检测是否有重复推荐活动,避免重复推送。<br/>
				</td>
			</tr>
		</table>
		<input type="hidden" name="ispost" value="yes"/>
		<input type="hidden" name="type_id" value="<?php echo $type_id;?>"/>
		<input type="hidden" name="cate_id" value="<?php echo $cate_id;?>"/>
	</form>
</div>
 
 