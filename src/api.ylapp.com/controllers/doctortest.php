<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/8/20 0020
 * Time: 上午 11:36
 */


class Doctortest extends MY_Controller
{

    public function index(){
        var_dump(strtotime("1958-2-2"));
        var_dump(date('Y-m-d',-375951600));
    }



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



    public function docInfo(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/docInfo" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="EtvtG7162be6963hW3r6R0mCjRIopTOrEOXc3tcrMGArLP\/eALSL9zusD56yIwxtx+ixTVc4"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }




    public function docEditInfoView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/docEditInfoView" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="6511d42638c6faeLj0mJ1fYRcaPj+NQmRmHduyfCWow9A\/GBnO5LZZbx+XmjPokFzU3HWSGL"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }






    public function docInfoEdit(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/docInfoEdit" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="6511d42638c6faeLj0mJ1fYRcaPj+NQmRmHduyfCWow9A\/GBnO5LZZbx+XmjPokFzU3HWSGL"/>
    name<input type="text" name="name"/>
    sex<input type="text" name="sex"/>
    birthday<input type="date" name="birthday"/>
    telFrt<input type="text" name="telFrt"/>
    telSed<input type="text" name="telSed"/>
    hosId<input type="text" name="hosId"/>
    officeId<input type="text" name="officeId"/>
    degree<input type="text" name="degree"/>
    summary<input type="text" name="summary"/>
    goodAt<input type="text" name="goodAt"/>
    img1<input type="file" name="img1"/>
    img2<input type="file" name="img2"/>
    img3<input type="file" name="img3"/>
    img4<input type="file" name="img4"/>
    img5<input type="file" name="img5"/>
    img6<input type="file" name="img6"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    /**
     * $name = trim(addslashes($this->input->get_post('name')));
    $sex = intval($this->input->get_post('sex'));
    if($sex != 1 && $sex != 2){$this->response($this->responseDataFormat(1,'sex参数非法',array()));}
    $birthday = strtotime($this->input->get_post('birthday'));
    $telFrt = trim($this->input->get_post('telFrt'));
    $telSed = trim($this->input->get_post('telSed'));
    $hosId = intval($this->input->get_post('hosId'));
    if(!$hosId){$this->response($this->responseDataFormat(2,'请选择医院',array()));}
    $officeId = intval($this->input->get_post('officeId'));
    if(!$officeId){$this->response($this->responseDataFormat(3,'请选择科室',array()));}
    $degree = addslashes(trim($this->input->get_post('degree')));
    $summary = addslashes($this->input->get_post('summary')); // 简介
    $goodAt = addslashes($this->input->get_post('goodAt'));  // 擅长
     */

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