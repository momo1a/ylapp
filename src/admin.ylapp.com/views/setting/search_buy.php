<?php if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_top');
} ?>


                <div class="ui-box ui-box2 remindTime">
                    <div class="ui-box-head">
                        <h2 class="ui-box-tit">搜索下单参数设置</h2>
                    </div>
                    <div class="ui-box-body">
                        <form type="ajax" action="<?php echo site_url('setting/search_buy'); ?>" method="post">
                            <input name="action" type="hidden" value="save">
                            <table cellspacing="0">
                                <tbody>
                                    <tr>
                                        <td>一、活动网购价大于等于
                                            <input class="ui-form-text ui-form-textRed" name="not_search_buy_min_price" type="text" value="<?php if(isset($conf_search_buy['not_search_buy_min_price'])) echo $conf_search_buy['not_search_buy_min_price']; ?>" data-rule="required|money|range(0.01,1000000)" data-msg="（请输入活动最小网购价）|（阿拉伯数字，保留两位小数点，0.01＜X≤1000000.00）|（阿拉伯数字，保留两位小数点，0.01＜X≤1000000.00）">元
                                            <span id="for_not_search_buy_min_price" class="error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>二、商品总价值大于等于
                                            <input class="ui-form-text ui-form-textRed" name="not_search_buy_min_paid_guaranty" type="text" value="<?php if(isset($conf_search_buy['not_search_buy_min_paid_guaranty'])) echo $conf_search_buy['not_search_buy_min_paid_guaranty']; ?>" data-rule="required|money|range(0.01,1000000)" data-msg="（请输入活动最小总价值）|（阿拉伯数字，保留两位小数点，0.01＜X≤1000000.00）|（阿拉伯数字，保留两位小数点，0.01＜X≤1000000.00）">元
                                            <span id="for_not_search_buy_min_paid_guaranty" class="error"></span>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>三、可参与类目： (<font color="#FF0000">请至少选择一个类目</font>)
                                            <ul class="remindTime2-list clearfix" style="width:627px;">
                                            <?php foreach ($goods_categorys as $cate) : ?>
                                                <li>
                                                    <label>
                                                        <input type="checkbox" <?php if( isset($conf_search_buy['not_search_buy_category_pids']) && in_array( $cate['id'], $conf_search_buy['not_search_buy_category_pids'] ) ) :?>checked="checked"<?php endif;?> name="not_search_buy_category_pids[]" value="<?php echo $cate['id'];?>">
                                                        &nbsp;<?php echo $cate['name'];?>
                                                    </label>
                                                </li>
                                            <?php endforeach; ?>    
                                            </ul>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                            <div class="autoShield-ft">
                                <input type="submit" class="ui-form-button ui-form-buttonBlue" value="保存设置" /> (<font color="#FF0000">修改后,第二天 0:00 生效</font>)
                            </div>
                        </form>
                    </div>
                </div>


<?php
if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_foot');
}?>