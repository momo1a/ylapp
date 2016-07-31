<?php if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_top');
} ?>
    <style>
        .simpletooltip {
            display: inline-block;
            *display: inline;
            *zoom: 1;
            width: 15px;
            height: 15px;
            background-image: url(<?php echo $this->config->item('domain_static')?>images/admin/question.png);
            vertical-align: middle;
            cursor: pointer;
        }

        .apptip {
            background-color: #f1273a;
            color: #fff;
            width: 65px;
            margin: 5px auto 0;
        }
    </style>
<?php change_to_minify("/javascript/common/jquery/simpleToolTip/style-simpletooltip-min.css"); ?>
    <div class="ui-box ui-box2 userList">
        <div class="ui-box-outer">
            <div class="ui-box-inner">
                <div class="ui-box-head">
				<span>当前位置：<a href="<?php echo site_url('goods/deduct_money'); ?>">活动管理</a> &gt;
					<a href="<?php echo site_url('goods/index/all'); ?>">所有活动</a> &gt;
					<a href="<?php echo site_url('goods/index/all?goods_type=-1&type=0&search_key=gid&search_value=' . $goods['gid']); ?>"
                       title="<?php echo $goods['title']; ?>"><?php echo cutstr($goods['title'], 20); ?></a> &gt;  进入活动
				</span>
                </div>
                <div class="ui-box-body">
                    <?php $this->load->view('goods/goods_base_info'); ?>
                    <form rel="div#main-wrap" action="<?php echo site_url('goods/order/' . $goods['gid']) ?>">
                        <select name="search_key">
                            <option value="oid"  <?php if ($search_key == 'oid') echo 'selected="selected"'; ?> >抢购编号
                            </option>
                            <option
                                value="trade_no" <?php if ($search_key == 'trade_no') echo 'selected="selected"'; ?>>
                                代付交易号
                            </option>
                            <option
                                value="buyer_uname" <?php if ($search_key == 'buyer_uname') echo 'selected="selected"'; ?>>
                                买家名称
                            </option>
                        </select>
                        <input name="search_val" class="ui-form-text ui-form-textRed"
                               value="<?php echo $search_val; ?>"/>
                        <button type="submit" class="ui-form-btnSearch">搜 索</button>
                    </form>
                    <table class="ui-table">
                        <thead>
                        <tr>
                            <th style="width: 14%;">抢购编号</th>
                            <th style="width: 14%;">买家名称</th>
                            <th style="width: 14%;">网购价/会员返利</th>
                            <th style="width: 8%;">分期状态</th>
                            <th style="width: 8%;">逾期</th>
                            <th style="width: 10%;">代付账号</th>
                            <th style="width: 10%;">填写的交易号</th>
                            <th style="width: 8%;">核检</th>
                            <th style="width: 10%;">抢购状态</th>
                            <th style="width: 10%;">操作</th>
                        </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($orders as $k => $v): ?>
                            <tr>
                                <td><?php echo $v['oid'];?></td>
                                <td><?php echo $v['buyer_uname'];?></td>
                                <td><?php echo '￥'.$v['price'].'/￥'.$v['back_money'];?></td>
                                <td><?php echo $v['current_stage'].'/'.$v['stages_num'].'<br />';?></td>
                                <td><?php echo '是否逾期';?></td>
                                <td><?php echo $v['pay_account'];?></td>
                                <td><?php echo $v['trade_no'];?></td>
                                <td><?php echo '核对';?></td>
                                <!--  状态：1待填单号;2超时自动清除(保留);3已填单号;4审核通过;5审核不通过;6待付款;7已付款',  -->
                                <td><?php switch($v['state']){
                                        case '1':
                                            echo '待填单号';
                                            break;
                                        case '2':
                                            echo '超时自动清除';
                                            break;
                                        case '3':
                                            echo '已填单号';
                                            break;
                                        case '4':
                                            echo '审核通过';
                                            break;
                                        case '5':
                                            echo '审核不通过';
                                            break;
                                        case '6':
                                            echo '待支付';
                                            break;
                                        case '7':
                                            echo '已支付';
                                            break;
                                        default:
                                            echo '未知状态';
                                            break;
                                    }?></td>
                                <td class="ui-table-operate">
                                    <a href="<?php echo site_url('goods/order_flow?type='.$goods['type'])?>" type="dialog" width="500" height="220" data-oid="<?php echo $v['oid'];?>" title="查看抢购记录">抢购记录</a><br />
                                    <?php if($v['state'] == Order_model::STATUS_CHECK_SUCCESS):?><!--待卖家审核订单通过以后 再显示该功能-->
                                    <a href="<?php echo site_url('goods/confirm_pay?oid=').$v['oid'];?>" type="confirm"  title="你确定已经付款了吗" callback="reload">确认付款</a>
                                    <?php endif;?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                        <tr>
                            <th colspan="10" class="ui-paging"><?php echo $pager ?></th>
                        </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>
    </div>

<?php if (!$this->input->is_ajax_request()) {
    $this->load->view('public/wrap_foot');
} ?>