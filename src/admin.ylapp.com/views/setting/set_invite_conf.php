<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_top');}?>
    <div class="ui-box">
        <div class="ui-box-outer">
            <div class="ui-box-inner">
                <form class="onlineTime" type="ajax" action="<?php echo base_url('setting/set_invite_conf'); ?>" method="post">
                    <table cellspacing="0">
                        <tbody>
                        <tr>
                            <td>
                                一、邀请新会员注册后产生奖励金有效期=
                                <input class="ui-form-text ui-form-textRed" type="text" value="<?php echo isset($iv_expiry) ? ($iv_expiry / $day_time) : '' ?>" name="iv_expiry" data-rule="number|range(1,365)" data-msg="只能输入整数|数字范围为1-365之间" />天。
                                <span id="for_iv_expiry" class="error"></span>
                                <input type="hidden" name="iv_expiry_remark" value="<?php echo isset($iv_expiry_remark) ? $iv_expiry_remark : '' ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                二、获取奖励金要求：在有效期状态下，已完成抢购中，网购价≥
                                <input class="ui-form-text ui-form-textRed" type="text" value="<?php echo isset($iv_order_sum) ? $iv_order_sum : '' ?>" name="iv_order_sum" data-rule="money|range(0.01,10000)" data-msg="（阿拉伯数字，保留两位小数点，0.01＜X≤10000.00）|（阿拉伯数字，保留两位小数点，0.01＜X≤10000.00）" />元。
                                <span id="for_iv_order_sum" class="error"></span>
                                <input type="hidden" name="iv_order_sum_remark" value="<?php echo isset($iv_order_sum_remark) ? $iv_order_sum_remark : '' ?>" />
                            </td>
                        </tr>
                        <tr>
                            <td>
                                三、成功邀请好友后获得奖励金=
                                <input class="ui-form-text ui-form-textRed" type="text" value="<?php echo isset($iv_commission) ? $iv_commission : '' ?>" name="iv_commission" data-rule="money|range(0.01,10000)" data-msg="（阿拉伯数字，保留两位小数点，0.01＜X≤10000.00）|（阿拉伯数字，保留两位小数点，0.01＜X≤10000.00）" />元。
                                <span id="for_iv_commission" class="error"></span>
                                <input type="hidden" name="iv_commission_remark" value="<?php echo isset($iv_commission_remark) ? $iv_commission_remark : '' ?>" />
                            </td>
                        </tr>
                        </tbody>
                    </table>
                    <div class="onlineTime-ft">
                        <input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" />
                        <input type="hidden" name="dosubmit" value="true" />
                    </div>
                </form>
            </div>

        </div>
    </div><!-- /ui-box -->
<?php if(!$this->input->is_ajax_request()){$this->load->view('public/wrap_foot');}?>