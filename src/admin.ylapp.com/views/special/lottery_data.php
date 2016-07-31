<?php
if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_top');
}
?>
<div class="ui-box ui-box2 remindData">
    <div class="ui-box-outer">
        <div class="ui-box-inner">
            <div class="ui-box-head">
                <div class="ui-box-tit">抽奖记录</div>
            </div>
            <div class="ui-box-head">
                <form class="clearfix" rel="div#main-wrap" action="<?php echo site_url($this->router->class . '/' . $this->router->method); ?>" method="get">
                    <input id="startTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="start_time" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo $start_time; ?>">
                    <input id="endTime" class="ui-form-text ui-form-textGray ui-form-textDatetime" type="text" readonly name="end_time" data-datefmt="yyyy-MM-dd HH:mm:ss" value="<?php echo $end_time; ?>">
                    <select class="ui-select" name="search_key">
                        <option value="uname">用户名称</option>
                        <option value="uid">用户ID</option>
                    </select>
                    <input class="ui-form-text ui-form-textRed" type="text" name="search_val" value="<?php echo $search_val;?>" />
                    <button type="submit" class="ui-form-btnSearch">搜 索</button>
                    <input class="ui-form-button ui-form-buttonBlue" style="float:right; margin: 5px 15px 0 0;" type="button" name="export" value="导出"  onclick="dosubmit.call(this)"/>
                    <input type="hidden" name="listonly" value="yes" />
                </form>
            </div>
            <div class="ui-box-body">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>用户状态</th>
                            <th>抽奖时间</th>
                            <th>第N次抽奖</th>
                            <th>抽中奖品</th>
                            <th>收货地址</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        foreach ($win_list as $k => $v) {
                            ?>

                            <tr>
                                <td><?php echo $v['uid'] ?></td>
                                <td><?php echo $v['uname'] ?></td>
                                <td><?php echo @$lock_status[$v['is_lock']];; ?></td>
                                <td><?php echo date('Y-m-d H:i:s', $v['win_time']) ?></td>
                                <td><?php echo $v['times'] ?></td>
                                <td><?php echo $v['name'] ?></td>
                                <td><?php echo "{$v['address']},{$v['zip']},{$v['consignee']},{$v['phone']}"; ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="7">
                                <div class="ui-paging"><?php echo $pager; ?></div>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>	
        </div>
    </div>
</div>
<script language="javascript">
    function dosubmit() {
        var parent = $(this).parent();
        if (!parent.find(".ui-form-textDatetime").eq(0).val() || !parent.find(".ui-form-textDatetime").eq(1).val()) {
            art.dialog({
                icon: "error",
                title: "温馨提示",
                content: "由于数据太多，必须选择起止时间！"
            });
            return;
        }
        location.href = '<?php echo site_url($this->router->class.'/'.$this->router->method.'_export/?'); ?>' + $(this).parent().serialize();
    }
</script>
<?php
if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_foot');
}?>