<?php
class Test extends MY_Controller
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        echo 'test';
    }

    /*test*/
    public function login(){

        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/api/login" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <!--input type="text" name="privateToken" value="jVAavd11771a9dWgMdRGYzxpYlfj5RIJKt93K1YGrMNO3vDLLOfByhSxcylD5\/9gnv\/UoYEO"/><br/>-->
    <input type="text" name="user" /><br/>
    <input type="text" name="pwd" /><br/>
    <!--<input type="text" name="rePwd"/><br/>-->
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;

    }

    /*注册*/
    public function register(){
        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
           display: block;
        }
        </style>
        </head>
<form action="http://api.ylapp.com/api/register" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <input type="hidden" name="userType" value="1"/>
    mobile:<input type="text" name="mobile"/>
    pwd:<input type="text" name="pwd" /><br/>
    rePwd:<input type="text" name="rePwd"/><br/>
    code:<input type="text" name="code"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    public function getCode(){
        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
           display: block;
        }
        </style>
        </head>
<form action="http://api.ylapp.com/api/sendIdentifyCode" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    mobile:<input type="text" name="mobile"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    public function reSetPwd(){
        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/api/reSettingPwd" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="pwd" /><br/>
    <input type="text" name="rePwd" /><br/>
    <!--<input type="text" name="rePwd"/><br/>-->
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    public function getUserBanner(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/user_index/getBannerImg" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    public function getIndexDoc(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/user_index/getIndexDoctorList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    public function getUserNewsIndex(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/user_index/getIndexNewsList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

//添加病历

    /**
     *  'illName'=>addslashes(trim($this->input->post('illName'))),   //病历名称
    'realname'=>addslashes(trim($this->input->post('realName'))), //姓名
    'age'=>intval($this->input->post('age')),  //年龄
    'sex'=>intval($this->input->post('sex')),  //性别
    'result'=>addslashes(trim($this->input->post('result'))), //诊断结果
    'stages'=>$this->input->post('stages'), // 分期
    'situation'=>$this->input->post('situation') //基本病情
     */



    public function addHistory(){
        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/medical/addIllnessHistory" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    illName<input type="text" name="illName" /><br/>
    realname<input type="text" name="realName" /><br/>
    age<input type="text" name="age" /><br/>
    男：<input type="radio" checked value="1" name="sex">  女：<input type="radio" value="2" name="sex"/>
    result<input type="text" name="result"/>
    stages<input type="text" name="stages"/>
    allergyHistory<input type="text" name="allergyHistory"/>
    <textarea name="situation"></textarea>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    /**
     * 获取病历列表
     */
    public function getIllList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/medical/getIllnessList" method="get">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
            echo $form;
    }

    public function getIllDetail(){

        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/medical/getIllnessDetail" method="get">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    IllId<input type="text" name="illId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
            echo $form;
    }



}


