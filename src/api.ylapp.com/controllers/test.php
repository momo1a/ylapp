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
    public function testPage(){

        /*var_dump($this->cache->get('15977675495'));*/
        var_dump($this->encryption(111111));
        //var_dump(strtoupper(md5(111111)));
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
</head>
<form action="http://api.ylapp.com/User_doctor_ctrl/userIndex" method="post">
    <input type="text" name="token" value="96E79218965EB72C92A549DD5A330112"/><br/>
    <input type="text" name="privateToken" value="XEMEMd9aad29cTzmn1v8+1eu3MGY+zQ7LnQTIgD3qgzBXqD25nEClFQ4+pMoqw=="/>
    <!--<input type="text" name="user" /><br/>
    <input type="text" name="pwd" /><br/>-->
    <!--<input type="text" name="rePwd"/><br/>-->
    <input type="submit" value="submit"/>

</form>
HTML;
        echo $form;

    }
}