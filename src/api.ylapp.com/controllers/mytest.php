<?php
class Mytest extends My_Controller
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

    public function regNumPayView()
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
<form action="http://api.ylapp.com/reg_num/payView" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    docId<input type="text" name="docId"/>
    person<input type="text" name="person"/>
    appointTime<input type="date" name="appointTime"/>
    sex
    男<input type="radio" name="sex" value="1"/>
    女<input type="radio" name="sex" value="2"/>
    birthday<input type="date" name="birthday"/>
    telephone<input type="text" name="telephone"/>
    illId<input type="text" name="illId"/>
    remark<input type="text" name="remark"/>

    <input type="submit" value="submit"/>
</form>
HTML;
        echo $form;
    }


    /**********基因检测**********/

    public function geneList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/gene_check/geneCheckList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="limit"/>
    <input type="text" name="offset/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    /**
     * 基因详情
     */
    public function geneDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/gene_check/geneCheckDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="geneId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    public function genePayView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/gene_check/payView" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="geneId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    /*疫苗接种*/
    public function vacciList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/vaccinum/vaccinumList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    keyword<input type="text" name="keyword"/>
    vacciType<input type="text" name="vacciType"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    /**
     * 基因详情
     */
    public function vacciDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/vaccinum/vaccinumDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="vaccinumId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function vacciPayView(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/vaccinum/payView" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="vaccinumId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /*交流圈*/
    public function postAdd(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/post/postAdd" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="opGLJ4fd419c7Lg60S3XF3gSSfFY4Hiy6aUAt98aSuAArKTFIUIo7zrex8HpDAoS3PjFeDSg"/>
    title<input type="text" name="title"/>
    content<input type="text" name="content"/>
    isAnonymous<input type="checkbox" name="isAnonymous" value="1"/>
    img1<input type="file" name="img1" />
    img2<input type="file" name="img2" />
    img3<input type="file" name="img3" />
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function postList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/post/listPost" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="H+xJr5ffe87abc9WJvGU3xpBpdaQwdT5z73G6emgInqCTGx7WJqbeeVtdaFwquA23Xt1sfjV"/>
    flag<input type="text" name="flag"/>
    limit<input type="text" name="limit"/>
    offset<input type="text" name="offset"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function postDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/post/detailPost" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="XjFkc3332d20aReRGyiRkDxd8sgyb1LzM3rUuMCYfGilPW8+KRT6Ydl+m41N2E7zdE\/I6XhT"/>
    postId<input type="text" name="postId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    public function clickLike(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/post/clickLike" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    postId<input type="text" name="postId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    /**
     * pinglun
     */
    public function addComment(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/post/addComment" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    postId<input type="text" name="postId"/>
    content<input type="text" name="content"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /*gerenzhongxin*/

    public function userCenterIndex(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/userCenterIndex" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }




    public function userCenterDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/userCenterDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function userDetailSave(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/userDetailSave" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="QwA2e89aef4671Oi8zKxljHTWvFTaZGo0yDk0rfnjZeUK9Z5fXbXqZceHASMiEZeDck3AXfh"/>
    sex<input type="text" name="sex"/>
    birthday<input type="text" name="birthday"/>
    nickname<input type="text" name="nickname"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function avatarUpload(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/avatarUpload" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="file" name="avatar"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function updateNickname(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/updateNickname" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="nickname"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }




    public function updatePwd(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/doctor_center/updatePwd" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="SzjiZ25df98efs+05ijZCMV06qkBzTYLidZg0xIpHtihZr+KaZIV1gNH6ihmP2Kq3wA+SuY+"/>
    oldPwd<input type="text" name="oldPwd"/>
    newPwd<input type="text" name="newPwd"/>
    reNewPwd<input type="text" name="reNewPwd"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /*我的钱包*/
    public function myMoneyIndex(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/myMoneyIndex" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /**
     * tixian
     */
    public function takeCash(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/takeCash" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    bank<input type="text" name="bank"/>
    cardNum<input type="text" name="cardNum"/>
    address<input type="text" name="address"/>
    realName<input type="text" name="realName"/>
    identity<input type="text" name="identity"/>
    amount<input type="text" name="amount"/>
    <input type="hidden" name="userType" value="1"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /**
     * jiaoyijilu
     */
    public function tradeLog(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/tradeLog" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /**
     * jiaoyijilu
     */
    public function onlineAskList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/user_center/onlineAskList" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="04DHhf839d08dKYTiTAVrv0h5CDJFkT\/Srh615Ds2CSNqE8eMN+yA5jystIJSP5Bpcxv7QHY"/>
    limit<input type="text" name="limit"/>
    offset<input type="text" name="offset"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    /**
     * jiaoyijilu
     */
    public function cancelOnlineAsk(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/cancelOnlineAsk" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Z6ET30ca5ef8fq9139r4bmeLqXgmYdE7d\/+CuORXx7u9xPXqv8PofOraKhbzk0GAOQLqnqAv"/>
    id<input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    /**
     * wenzhengxiangqing
     */
    public function onlineAskDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/onlineAskDetail" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="fLRyl5c36d975OCSfuBoRy6LfeGXE+RMms\/Hm31YVzFBT6BOkp8lQ+qyTM3ojZdT3rq5dBiN"/>
    id<input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function evaluate(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/evaluate" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    docId<input type="text" name="docId"/>
    content<input type="text" name="content"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /**
     * 我的预约
     */
    public function appointList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/user_center/appointList" method="post" enctype="multipart/form-data">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="04DHhf839d08dKYTiTAVrv0h5CDJFkT\/Srh615Ds2CSNqE8eMN+yA5jystIJSP5Bpcxv7QHY"/>
    limit<input type="text" name="limit"/>
    offset<input type="text" name="offset"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    /**
     *
     */
    public function appointDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/appointDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="fLRyl5c36d975OCSfuBoRy6LfeGXE+RMms\/Hm31YVzFBT6BOkp8lQ+qyTM3ojZdT3rq5dBiN"/>
    id<input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function appointCancel(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/appointCancel" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Z6ET30ca5ef8fq9139r4bmeLqXgmYdE7d\/+CuORXx7u9xPXqv8PofOraKhbzk0GAOQLqnqAv"/>
    id<input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



/*问答*/
    public function askAnswerList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/user_center/askAnswerList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="04DHhf839d08dKYTiTAVrv0h5CDJFkT\/Srh615Ds2CSNqE8eMN+yA5jystIJSP5Bpcxv7QHY"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function askAnswerDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/askAnswerDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Z6ET30ca5ef8fq9139r4bmeLqXgmYdE7d\/+CuORXx7u9xPXqv8PofOraKhbzk0GAOQLqnqAv"/>
    <input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function order(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://123.207.87.83:8080/user_center/order" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="04DHhf839d08dKYTiTAVrv0h5CDJFkT\/Srh615Ds2CSNqE8eMN+yA5jystIJSP5Bpcxv7QHY"/>
    limit<input type="text" name="limit"/>
    offset<input type="text" name="offset"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function myPostList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/postList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function myPostReply(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/myPostReply" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function myPostComment(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/myPostComment" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function myCollections(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/myCollections" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function delCollection(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/delCollection" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>
    <input type="text" name="collId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function aboutUs(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/us/aboutUs" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



    public function feedback(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/feedback" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="z8u7r1cfa4ad1Qly7e9xGEJPWaYOk4E9aalIARThDor6drxRzgwqHd9Xg2rgmjulTlWODpxP"/>
    <textarea name="content"></textarea>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function logout(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_center/logout" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="z8u7r1cfa4ad1Qly7e9xGEJPWaYOk4E9aalIARThDor6drxRzgwqHd9Xg2rgmjulTlWODpxP"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


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


/********************************************************************/

    public function getIndexScrollLog(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/user_index/getIndexScrollLog" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <!--<input type="hidden" name="privateToken" value="Gunbh0b63a168VZFX7\/QzDj1faeV7ylH3QyQQ1Rne\/d5ZXgOUFmaIALEDSkg04VXnrotj2Ti"/>-->
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }





    public function getHelpList(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/api/getHelpList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="3tGel22a4d8eaRVypFLKcmh2FxbEEn2o+fw9aNdAmp4YrYSWaECD7zDL6GVthzkHXf\/xahTN"/>
    <input type="text" name="type"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }

    public function helpDetail(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/api/helpDetail" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="3tGel22a4d8eaRVypFLKcmh2FxbEEn2o+fw9aNdAmp4YrYSWaECD7zDL6GVthzkHXf\/xahTN"/>
    <input type="text" name="id"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }


    public function cancelLike(){
        $form = <<<HTML
        <head>
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
        <style>
        input{
        display: block;
        }
</style>
</head>
<form action="http://api.ylapp.com/post/cancelLike" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="3tGel22a4d8eaRVypFLKcmh2FxbEEn2o+fw9aNdAmp4YrYSWaECD7zDL6GVthzkHXf\/xahTN"/>
    <input type="text" name="postId"/>
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
<form action="http://api.ylapp.com/user_center/msgList" method="post">
    <input type="hidden" name="token" value="96E79218965EB72C92A549DD5A330112"/>
    <input type="hidden" name="privateToken" value="A\/vim2790f7c9ezwpPoopYL9maKS9tobeXgOo1qzDvckwvacadXR\/zm6d3C9\/kEItBk+ul+M"/>
    <input type="text" name="postId"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }



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
    <input type="hidden" name="privateToken" value="MuBi48227bd74sCxb25dBtrRxlRNM44qZJnpXRnhBTaCUukAYAdjcXYz0NFMytDzjcxQX7v\/"/>
    payType<input type="text" name="payType"/>
    amount<input type="text" name="amount"/>
    <input type="submit" value="submit"/>
HTML;
        echo $form;
    }
}