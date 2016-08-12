<?php
class Test extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        var_dump($this->config->item('upload_image_save_path'));
    }

    /*test*/
    public function login()
    {

        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/api/login" method="post">
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
    public function register()
    {
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
<form action="http://123.207.87.83:8080/api/register" method="post">
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

    public function getCode()
    {
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
<form action="http://123.207.87.83:8080/api/sendIdentifyCode" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    mobile:<input type="text" name="mobile"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    public function reSetPwd()
    {
        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        var_dump($this->encryption(111111));
        var_dump(time());
        var_dump(ip2long($_SERVER['REMOTE_ADDR']));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/api/reSettingPwd" method="post">
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

    public function getUserBanner()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/user_index/getBannerImg" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    public function getIndexDoc()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/user_index/getIndexDoctorList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

    public function getUserNewsIndex()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/user_index/getIndexNewsList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;
    }

//添加病历

    /**
     *  'illName'=>addslashes(trim($this->input->post('illName'))),   //病历名称
     * 'realname'=>addslashes(trim($this->input->post('realName'))), //姓名
     * 'age'=>intval($this->input->post('age')),  //年龄
     * 'sex'=>intval($this->input->post('sex')),  //性别
     * 'result'=>addslashes(trim($this->input->post('result'))), //诊断结果
     * 'stages'=>$this->input->post('stages'), // 分期
     * 'situation'=>$this->input->post('situation') //基本病情
     */


    public function addHistory()
    {
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
<form action="http://123.207.87.83:8080/medical/editIllness" method="post">
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
    public function getIllList()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/medical/getIllnessList" method="get">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    public function getIllDetail()
    {

        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://123.207.87.83:8080/medical/getIllnessDetail" method="get">
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
    public function addIllRemark()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/medical/addIllRemark" method="post" enctype="multipart/form-data">
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
    public function editIllnessView()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/medical/editIllnessView" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    IllId<input type="text" name="illId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }


    public function EditHistory()
    {
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
<form action="http://123.207.87.83:8080/medical/editIllness" method="post" enctype="multipart/form-data">
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

    public function delRemark()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/medical/delRemark" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    remarkId<input type="text" name="remarkId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }


    /*************************  资讯start **************************************/


    /**
     * 资讯首页
     */
    public function newsList()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/news/getNewsList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    /**
     * 资讯首页
     */
    public function getNewsDetail()
    {
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/news/getNewsDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="nid"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }



    /************************* 医生start ******************************/

    public function getHospitalList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/hospital/getAllHospital" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <!--<input type="text" name="hid"/>
    <input type="text" name="keyword"/>-->
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    public function getDoctorList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/doctor/getDoctorList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    hid<input type="text" name="hid"/>
    officeId<input type="text" name="officeId"/>
    keyword<input type="text" name="keyword"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }



    public function getAllOffices(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/hospital/getAllOffices" method="get">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    /**
     * 医生详情
     * @param $docId
     */
    public function getDoctorDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/doctor/getDoctorDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="docId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    /*************在线问诊****************/



    public function TimeLenView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/Diagnosis_online/diaSelectTimeLenView" method="get">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="docId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    /**
     * 支付页面
     */
    public function payView(){
            $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/Diagnosis_online/diaDoPostTempOne" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    docId<input type="text" name="docId"/>
    phoneTimeLen<input type="text" name="phoneTimeLen"/>
    price<input type="text" name="price"/>
    ask_sex 男<input type="radio" name="sex" value="1"/>
    ask_sex 女<input type="radio" name="sex" value="2"/>
    askNickname<input type="text" name="person"/>
    askTelephone<input type="text" name="telephone"/>
    hopeCalldate<input type="date" name="hopeCallDate"/>
    content<input type="text" name="content"/>
    illnessId<input type="text" name="illnessId"/>
    otherIllness<input type="text" name="otherIllness"/>
    <input type="submit" value="submit"/>
</form>
HTML;
            echo $form;

    }




    /****************留言问答****************/

    public function leavView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/Leaving_msg/leavingMsgView" method="get">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="docId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

    /**
     * 留言问答支付页面
     */
    public function payLeavView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/Leaving_msg/commitStepFrt" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    docId<input type="text" name="docId"/>
    content<input type="text" name="content"/>
    price<input type="text" name="price"/>
    img1<input type="file" name="img1">
    img2<input type="file" name="img2">
    img3<input type="file" name="img3">
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;


        /**
         * $content = addslashes($this->input->get_post('content'));
        $price = floatval($this->input->get_post('price'));
        $docId = intval($this->input->get_post('docId'));
        $imgArr = array();
        if(!empty($_FILES)){
        foreach($_FILES as $k=>$val){
        $imgFile = $this->upload_image->save('leavingMsg',$val['tmp_name']);
        array_push($imgArr,$imgFile);
        }
        }
         */
    }

}

