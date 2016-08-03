<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Order_model
 */
class User_model extends MY_Model
{
    public static $table_name = 'user';


    public function reg($mobile,$userType,$pwd){
        $data = array('phone'=>$mobile,'userType'=>$userType,'password'=>$pwd);
        $res = $this->insert($data);
        return $res;

    }
    public function getUserMobile($mobile){
        $res = $this->find_by('phone',$mobile);
        return $res;
    }
}
