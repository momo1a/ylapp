<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>

<div class="ui-box autosheildSetting">
	<div class="ui-box-outer">
		<div class="ui-box-inner">
			<div class="ui-box-body">
				<div class="ui-box ui-box2 autoShield">
					<div class="ui-box-head">
						<h2 class="ui-box-tit">APP版本号设置</h2>
					</div>
					<div class="ui-box-body">
						<form type="ajax" action="<?php echo site_url('setting/set_app_version'); ?>" method="post">
							<table cellspacing="0">
								<tbody>
								<tr>
									<td><b>Android（安卓）</b></td>
								</tr>
								<tr>
									<td>1、最低版本号：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_android_version" type="text" value="<?php echo isset($app_android_version)?$app_android_version:'';?>" data-rule="number" data-msg="请输入APP Android（安卓）版本号或版本号不正确" />版本。
									<span id="for_app_android_version" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>2、当前最高版本号：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_android_current_version" type="text" value="<?php echo isset($app_android_current_version)?$app_android_current_version:'';?>" data-rule="number" data-msg="请输入Android（安卓）当前最高版本号或版本号不正确" />版本。
									<span id="for_app_android_current_version" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>3、安装包大小(MB)：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_android_size" type="text" value="<?php echo isset($app_android_size)?$app_android_size:'';?>" data-rule="float" data-msg="请输入安装包大小" />
									<span id="for_app_android_size" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>4、显示使用的版本号：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_android_current_version_show" type="text" value="<?php echo isset($app_android_current_version_show)?$app_android_current_version_show:'';?>"/>
									</td>
								</tr>
								<tr>
									<td>5、当前最高版本下载地址：
									<input class="ui-form-text ui-form-textRed"  style="width:500px;" name="app_android_current_version_url" type="text" value="<?php echo isset($app_android_current_version_url)?$app_android_current_version_url:'';?>" />
									</td>
								</tr>
								<tr>
									<td><b>IOS（苹果）</b></td>
								</tr>
								<tr>
									<td>1、最低版本号：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_ios_version" type="text" value="<?php echo  isset($app_ios_version)?$app_ios_version:'';?>" data-rule="number" data-msg="请输入APP IOS（苹果）版本号或版本号不正确"/>版本。
									<span id="for_app_ios_version" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>2、当前最高版本号：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_ios_current_version" type="text" value="<?php echo isset($app_ios_current_version)?$app_ios_current_version:'';?>" data-rule="number" data-msg="请输入APP IOS（苹果）当前最高版本号或版本号不正确"/>版本。
									<span id="for_app_ios_current_version" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>3、安装包大小(MB)：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_ios_size" type="text" value="<?php echo isset($app_ios_size)?$app_ios_size:'';?>" data-rule="float" data-msg="请输入安装包大小" />
									<span id="for_app_ios_size" class="error"></span>
									</td>
								</tr>
								<tr>
									<td>4、显示使用的版本号：
									<input class="ui-form-text ui-form-textRed" style="width:100px;" name="app_ios_current_version_show" type="text" value="<?php echo isset($app_ios_current_version_show)?$app_ios_current_version_show:'';?>" />
									</td>
								</tr>
								<tr>
									<td>5、当前最高版本下载地址：
									<input class="ui-form-text ui-form-textRed"  style="width:500px;" name="app_ios_current_version_url" type="text" value="<?php echo isset($app_ios_current_version_url)?$app_ios_current_version_url:'';?>" />
									</td>
								</tr>
								</tbody>
							</table>
							
							<div class="autoShield-ft">
								<input name="versionpost" type="hidden" value="versionpost" />
								<input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div><!-- /ui-box -->

<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>