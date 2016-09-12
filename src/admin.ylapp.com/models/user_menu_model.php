<?php
class User_menu_model extends My_Model{

    public static $table_name = 'user_menu';

    public function __construct(){
        parent::__construct();
    }


    /**
     * 根据uid获取菜单
     * @param $uid
     * @return array
     */
    public function get_menu_by_uid($uid){
        $where =  array('uid'=>intval($uid));
        $res = $this->find_all_by($where);
        return $res;
    }

}
