<?php

include dirname(__FILE__).DIRECTORY_SEPARATOR."TopSdk.php";

class Test
{
    /**
     * 阿里平台分配
     * @var string
     */
    protected $_appKye = '23475933';


    /**
     * 阿里平台分配
     * @var string
     */
    protected $_sectet = 'a3af895021da1f5784d872f14c7124c0';


    public function __construct(){
        $c = new TopClient;
        $c->appkey = $this->_appKye;
        $c->secretKey = $this->_sectet;
        $req = new OpenimUsersAddRequest;
        $userinfos['nick']="king";
        $userinfos['icon_url']="http://xxx.com/xxx";
        $userinfos['email']="uid@taobao.com";
        $userinfos['mobile']="18600000000";
        $userinfos['taobaoid']="tbnick123";
        $userinfos['userid']="imuser123";
        $userinfos['password']="123456a";
        $userinfos['remark']="demo";
        $userinfos['extra']="{}";
        $userinfos['career']="demo";
        $userinfos['vip']="{}";
        $userinfos['address']="demo";
        $userinfos['name']="demo";
        $userinfos['age']="123";
        $userinfos['gender']="M";
        $userinfos['wechat']="demo";
        $userinfos['qq']="demo";
        $userinfos['weibo']="demo";
        $req->setUserinfos(json_encode($userinfos));
        $resp = $c->execute($req);
        var_dump($resp);
    }









}
