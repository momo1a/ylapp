<?php
class Test extends MY_Controller
{

    public function __construct(){
        parent::__construct();
    }

    public function index(){
        var_dump($this->config->item('upload_image_save_path'));
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
<form action="http://api.ylapp.com/medical/editIllness" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    illId<input type="text" name="illId"/><br/>
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


    /**
     * 添加病历记录
     */
    public function addIllRemark(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/medical/addIllRemark" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    IllId<input type="text" name="illId"/>
    visitDate<input type="text" name="visitDate"/>
    content<input type="text" name="content"/>
    stages<input type="text" name="stages"/>
    图片1：<input type="file" name="img1"/>
    图片2：<input type="file" name="img2"/>
    图片3：<input type="file" name="img3"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    /**
     * 显示编辑病历页面
     */
    public function editIllnessView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/medical/editIllnessView" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    IllId<input type="text" name="illId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }



    public function EditHistory(){
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
<form action="http://api.ylapp.com/medical/editIllness" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    illId<input type="text" name="illId"/><br/>
    illName<input type="text" name="illName" /><br/>
    realname<input type="text" name="realName" /><br/>
    age<input type="text" name="age" /><br/>
    男：<input type="radio" checked value="1" name="sex">  女：<input type="radio" value="2" name="sex"/>
    result<input type="text" name="result"/>
    stages<input type="text" name="stages"/>
    allergyHistory<input type="text" name="allergyHistory"/>
    <textarea name="situation"></textarea>
    <input type="hidden" name="remarkIds" value="1-2-3"/><br/>
    visitDate<input type="text" name="visitDate_1"/>
    content<input type="text" name="content_1"/>
    stages<input type="text" name="stages_1"/>
    图片1：<input type="file" name="img1_1"/>
    图片2：<input type="file" name="img2_1"/>
    图片3：<input type="file" name="img3_1"/>
    <hr/>
    visitDate<input type="text" name="visitDate_2"/>
    content<input type="text" name="content_2"/>
    stages<input type="text" name="stages_2"/>
    图片1：<input type="file" name="img1_2"/>
    图片2：<input type="file" name="img2_2"/>
    图片3：<input type="file" name="img3_2"/>
    <hr/>

    visitDate<input type="text" name="visitDate_3"/>
    content<input type="text" name="content_3"/>
    stages<input type="text" name="stages_3"/>
    图片1：<input type="file" name="img1_3"/>
    图片2：<input type="file" name="img2_3"/>
    图片3：<input type="file" name="img3_3"/>
    <hr/>
     <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }
}


