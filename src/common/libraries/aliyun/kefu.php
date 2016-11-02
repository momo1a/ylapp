<?php

include dirname(__FILE__).DIRECTORY_SEPARATOR."TopSdk.php";

class Kefu
{

    /**
     * ci对象
     * @var CI_Controller|null
     */
    protected $CI = null;


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
     * 修改用户请求对象
     * @var null
     */
    protected static $_usersUpdateRequest = null;


    /**
     * 初始化
     */
    public function __construct(){
        //date_default_timezone_set('Asia/Shanghai');
        $this->CI = & get_instance();
        $this->CI->load->config('openim');
        self::$_TopClientInstance = new TopClient;
        self::$_TopClientInstance->appkey = config_item('openim_appkey');
        self::$_TopClientInstance->secretKey = config_item('openim_sectet');
        self::$_userAddRequest = new OpenimUsersAddRequest;
        self::$_usersGetRequest = new OpenimUsersGetRequest;
        self::$_usersUpdateRequest = new OpenimUsersUpdateRequest;
    }



    /**
     * 添加用户
     * @param array $userInfo 用户参数
     */
    public function userAdd($userInfos){
        $userInfo = new Userinfos;
        $userInfo->nick = $userInfos['nick'];
        $userInfo->icon_url = $userInfos['icon_url'];
        $userInfo->userid = $userInfos['userid'];
        $userInfo->password = $userInfos['password'];
        $userInfo->gender = $userInfos['gender'];
        self::$_userAddRequest->setUserinfos(json_encode($userInfo));
        $response = self::$_TopClientInstance->execute(self::$_userAddRequest);
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


    /**
     * 修改用户信息
     * @param $userInfos
     * @return mixed|ResultSet|SimpleXMLElement
     */
    public function updateUserInfo($userInfos){
        $userInfo = new Userinfos;
        $userInfo->nick = $userInfos['nick'];
        $userInfo->icon_url = $userInfos['icon_url'];
        $userInfo->userid = $userInfos['userid'];
        $userInfo->password = $userInfos['password'];
        $userInfo->gender = $userInfos['gender'];
        self::$_usersUpdateRequest->setUserinfos(json_encode($userInfo));
        $response = self::$_TopClientInstance->execute(self::$_usersUpdateRequest);
        return $response;
    }


}
