<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/20 0020
 * Time: 上午 11:36
 */


class Doctortest extends MY_Controller
{



    public function doctorIndex(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/index" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="zq3S856c5ff19ZCjL2sh9HJVbQ3ZkAZ9HuaKyr4tiCqwUikv5pPvyBLUq\/BwG0SJAtFIFdIB"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function msgList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/msgList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="zq3S856c5ff19ZCjL2sh9HJVbQ3ZkAZ9HuaKyr4tiCqwUikv5pPvyBLUq\/BwG0SJAtFIFdIB"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function leavList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/leavingMsgList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="zq3S856c5ff19ZCjL2sh9HJVbQ3ZkAZ9HuaKyr4tiCqwUikv5pPvyBLUq\/BwG0SJAtFIFdIB"/>
    <input type="text" name="state"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function leavDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/leavingDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="GGfOnaf0bb840zaVH2r2uzhivo9mPYHCCtmBFr+X7IHxAO4AIU0g89tSXEJOzCo8Csbr0Plo"/>
    <input type="text" name="id"/>
    <input type="text" name="state"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function commitReply(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/commitReply" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="GGfOnaf0bb840zaVH2r2uzhivo9mPYHCCtmBFr+X7IHxAO4AIU0g89tSXEJOzCo8Csbr0Plo"/>
    <input type="text" name="id"/>
    <input type="text" name="content"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }
    public function userIndex(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_index/index" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="hHeno77f8685dXm3EaxAcaXoO0drlDOFOCc0crwW\/IpS4nZYn+X8rZ9WPq9\/9Qw1kt92d9XL"/>
    <input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


}