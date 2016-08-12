<?php
class Mytest extends MY_Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        var_dump($this->config->item('upload_image_save_path'));
    }


    /*************yuyueguahao****************/


    public function regNumView()
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
<form action="http://api.ylapp.com/reg_num/regNumView" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="docId"/>
    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }

}