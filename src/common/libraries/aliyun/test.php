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


    /**
     * 淘宝sdk对象
     * @var null
     */
    protected static $_TopClientInstance = null;

    /**
     * 用户添加请求对象
     * @var null
     */
    protected static $_userAddRequest = null;


    /**
     * 获取用户请求对象
     * @var null
     */
    protected static $_usersGetRequest = null;


    /**
     * 初始化
     */
    public function __construct(){
        self::$_TopClientInstance = new TopClient;
        self::$_TopClientInstance->appkey = $this->_appKye;
        self::$_TopClientInstance->secretKey = $this->_sectet;
        self::$_userAddRequest = new OpenimUsersAddRequest;
        self::$_usersGetRequest = new OpenimUsersGetRequest;
    }

    /**
     * 添加用户
     * @param array $userInfo 用户参数
     */
    public function userAdd($userInfo){
        $userinfos['nick']="myking";
        //$userinfos['icon_url']="http://xxx.com/xxx";
        //$userinfos['email']="uid@taobao.com";
        //$userinfos['mobile']="18600000000";
        //$userinfos['taobaoid']="tbnick123";
        $userinfos['userid']="mylolol";
        $userinfos['password']="123456a";
        /* $userinfos['remark']="demo";
         $userinfos['extra']="{}";
         $userinfos['career']="demo";
         $userinfos['vip']="{}";
         $userinfos['address']="demo";
         $userinfos['name']="demo";
         $userinfos['age']="123";
         $userinfos['gender']="M";
         $userinfos['wechat']="demo";
         $userinfos['qq']="demo";
         $userinfos['weibo']="demo";*/
        self::$_userAddRequest->setUserinfos(json_encode($userInfo));
        $response = self::$_TopClientInstance->exec(self::$_userAddRequest);
        return $response;
    }

    /**
     * 获取用户信息
     * @param $userId
     */
    public function getUserInfo($userId){
        self::$_usersGetRequest->setUserids($userId);
        $response = self::$_TopClientInstance->execute(self::$_usersGetRequest);
        return $response;
    }



}
