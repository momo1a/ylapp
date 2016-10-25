<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/7 0007
 * Time: 下午 1:55
 */

class Paytest extends CI_Controller
{
    public function recharge(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_pay/recharge" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="PG1DR45dbd797ticmkzZlXInRhryKDQoznw6DyD0xhrFJTLat0qOgDvLRTlIv4cy9BuWsC\/Z"/>
    payType<input type="text" name="payType"/>
    amount<input type="text" name="amount"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /**
     * 订单支付测试
     */
    public function orderPay(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_pay/orderPay" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="rw2S+9c63cad0+y4oZQHn1GtqOoz1c0RxMJnw0ePrusc+NN4hncOBHNfUCorjkjUu8gkbzAf"/>
    payType<input type="text" name="payType"/>
    amount<input type="text" name="amount"/>
    orderType<input type="text" name="orderType"/>
    orderId<input type="text" name="orderId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }
}