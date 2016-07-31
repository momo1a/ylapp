<?php
if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_top');
}
?>
<div class="ui-box ui-box2 remindData">
    <div class="ui-box-outer">
        <div class="ui-box-inner">
            <div class="ui-box-head">
                <div class="ui-box-tit">双11网购价排名</div>
            </div>
            <div class="ui-box-head">
                <form class="clearfix" rel="div#main-wrap" action="<?php echo site_url($this->router->class . '/' . $this->router->method); ?>" method="get">
                    <select class="ui-select" name="search_key">
                        <option value="uname">用户名称</option>
                        <option value="uid">用户ID</option>
                    </select>
                    <input class="ui-form-text ui-form-textRed" type="text" name="search_val" value="<?php echo $search_val; ?>" />
                    <button type="submit" class="ui-form-btnSearch">搜 索</button>
                    <input class="ui-form-button ui-form-buttonBlue" style="float:right; margin: 5px 15px 0 0;" type="button" name="export" value="导出"  onclick="dosubmit.call(this)"/>
                    <span style="float:right; margin:-2px 15px 0 0;">条记录</span>
                    <input class="ui-form-text" style="float:right; margin: 5px 15px 0 0;" type="text" id="end_page" name="end_page" value="10000" />
                    <span style="float:right; margin: -2px 15px 0 0;">~</span>
                    <input class="ui-form-text" style="float:right; margin: 5px 15px 0 0;" type="text" id="start_page" name="start_page" value="1" />
                    <input type="hidden" name="listonly" value="yes" />
                </form>
            </div>
            <div class="ui-box-body">
                <table class="ui-table">
                    <thead>
                        <tr>
                            <th>排名</th>
                            <th>用户ID</th>
                            <th>用户名</th>
                            <th>用户状态</th>
                            <th>双11网购价总和</th>
                            <th>双11已返现的网购价</th>
                        </tr>
                    </thead>
                    <tbody> 
                        <?php
                        foreach ($rank_list as $v) {
                            ?>
                            <tr>
                                <td><?php echo $v['rid'] ?></td>
                                <td><?php echo $v['uid'] ?></td>
                                <td><?php echo $v['uname'] ?></td>
                                <td><?php echo @$lock_status[$v['is_lock']]; ?></td>
                                <td><?php echo $v['amount'] ?></td>
                                <td><?php echo $v['repayment'] ?></td>
                            </tr>
                        <?php } ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="6">
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
        if (!$('#start_page').val()[0] || !$('#end_page').val()[0]) {
            art.dialog({
                icon: "error",
                title: "温馨提示",
                content: "由于数据太多，必须选择起止数据！"
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