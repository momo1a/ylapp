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
    <input type="hidden" name="privateToken" value="v41iif4ed4458td4gcwOAJjWfnoYM9uV+SZvbg4FeWSeGewf9d9YUckyQME7VezGucIaAmzp"/>
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


    public function getOnlineDiaList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/getOnlineDiaList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="+Rg4A2e8b6bb3eQiUzovxGRLgLYE6pJ8OtlNxtCs2bV9Na5AdanrJg9XytB632o6uiqR+4Cl"/>
    state<input type="text" name="state"/>

    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function getOnlineDiaDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/getOnlineDiaDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="+Rg4A2e8b6bb3eQiUzovxGRLgLYE6pJ8OtlNxtCs2bV9Na5AdanrJg9XytB632o6uiqR+4Cl"/>
    id<input type="text" name="id"/>

    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function commitRemark(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/commitRemark" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="gkrXCdb575f9fIqvSE4QBVcuWqVbt5Izeu3doD+Wt2Gi\/VJ+ZmlQ1yyUOyA\/PUrz31VjHGKV"/>
    id<input type="text" name="id"/>
    content<textarea name="content"></textarea>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function getRegList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/getRegList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="gkrXCdb575f9fIqvSE4QBVcuWqVbt5Izeu3doD+Wt2Gi\/VJ+ZmlQ1yyUOyA\/PUrz31VjHGKV"/>
    limit<input type="text" name="limit"/>
    offset<input type="text" name="offset"/>
    state<input type="text" name="state"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function getRegNumDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/getRegNumDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="gkrXCdb575f9fIqvSE4QBVcuWqVbt5Izeu3doD+Wt2Gi\/VJ+ZmlQ1yyUOyA\/PUrz31VjHGKV"/>
    ID<input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function getNewsListDoc(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/news/getNewsListDoc" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="gkrXCdb575f9fIqvSE4QBVcuWqVbt5Izeu3doD+Wt2Gi\/VJ+ZmlQ1yyUOyA\/PUrz31VjHGKV"/>
    limit<input type="text" name="limit"/>
    offset<input type="text" name="offset"/>
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