<!DOCTYPE html>
    <html>
        <head>
            <meta charset="utf-8">
            <title>调整返现金额</title>
            <style type="text/css">
                .m-cn{
                    color: #333;
                    /*background-color: #003399;*/
                    background-color:#E8FCF2 ;
                    padding: 10px;
                }
                .aui_main{
                    background-color:#E8FCF2 ;
                }
                .aui_state_noTitle .aui_inner{
                    border: 1px solid #E0FCF0 !important;
                    background-color:#CDDCF3 ;
                }
            </style>
        </head>
        <body>
            <div class="m-cn">
                <p>原返现金额：<?php echo $due_rebate;?>元</p>
                <?php foreach ($adjust_rebate_logs as $log){
                	$adjust_tips = $log['adjust_type'] == zhs_order_adjust_rebate_model::ADJUST_TYPE_APP ? '手机客户端下单' : '管理员处理申诉';
                ?>
                   <p style="font-weight: 700"><?php echo $adjust_tips.'，系统调整返现金额：'.$log['adjust_rebate'];?>元
                       <?php if($log['adjust_type'] == zhs_order_adjust_rebate_model::ADJUST_TYPE_APP){?>
                       （电脑网购价<?php echo $goodsDetail['price'];?>元，手机客户端网购价<?php echo  $goodsDetail['mobile_price'];?>元，差价<?php echo  $goodsDetail['price'] - $goodsDetail['mobile_price'];?>元）
                      <?php } ?>
                   </p>
                <?php }?>
                <p>最终返现金额为：<?php echo $real_rebate;?>元</p>
                <p>说明：“-”表示扣除返现金额，“+”表示增加返现金额</p>
            </div>
        </body>
    </html>