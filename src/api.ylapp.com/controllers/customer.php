<?php
/**
 * 客服控制器
 * User: momo1a@qq.com
 * Date: 2016/10/22 0022
 * Time: 下午 8:22
 */

class Customer extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->library('aliyun/Kefu',null,'kefu');
        $this->load->model('User_model','user');
    }

    public function index(){
        $this->checkUserLogin();
        $currentUid = self::$currentUid;
        $currUser = $this->user->getUserByUid($currentUid);
        $currUser ? '' : $this->response($this->responseDataFormat(-1,'用户异常',array()));
        //$res = $this->cache->get($currUser['phone'].'customer');
        $res = $this->kefu->getUserInfo($currUser['phone'].'customer');
        $userInfo = $this->object2array($res->userinfos);

        //$userInfo = null;
        if(empty($userInfo)){
            $avatar = array('1'=>'img/man_default.png','2'=>'img/woman_default.png');
            $gender = array('1'=>'M','2'=>'F');
            $user = array(
                'nick'=>$currUser['nickname'],
                'icon_url'=> $currUser['avatar'] ? $this->getImgServer().$currUser['avatar'] : config_item('domain_static').$avatar[$currUser['sex']],
                'userid'=>$currUser['phone'].'customer',
                'password'=>$currUser['password'],
                'gender'=>$gender[$currUser['sex']],
            );
            $response = $this->kefu->userAdd($user);
            if($this->object2array($response->fail_msg)){
                $this->response($this->responseDataFormat(1,'添加用户失败',array($response->fail_msg)));
            }
            $infos = $this->kefu->getUserInfo($user['userid']);
            //$this->cache->save($user['userid'],$infos->userinfos,86400*5);
            $infos = $this->object2array($infos->userinfos);
            $infos['userinfos'][0]->groupid = config_item('openim_group_id');
            $infos['userinfos'][0]->touid = config_item('openim_touid');
            $this->response($this->responseDataFormat(0,'请求成功',$infos['userinfos'][0]));
        }else{
            $userInfo['userinfos'][0]->groupid = config_item('openim_group_id');
            $userInfo['userinfos'][0]->touid = config_item('openim_touid');
            $this->response($this->responseDataFormat(0,'请求成功',$userInfo['userinfos'][0]));
        }
    }


    /**
     * 对象转数组
     * @param $object
     * @return mixed
     */
    protected function object2array($object) {
        if (is_object($object)) {
            foreach ($object as $key => $value) {
                $array[$key] = $value;
            }
        } else {
            $array = $object;
        }
        return $array;
    }
}