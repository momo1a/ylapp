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

    /**
     * 给用户设置菜单权限
     * @param $uid
     * @param $data
     */
    public function setUserMenu($uid,$data){
        $this->db->trans_begin();
        $this->db->query('DELETE FROM `YL_user_menu` WHERE `uid`='.$uid);
        if($data) {
            $this->db->query('REPLACE INTO `YL_user_menu`(`uid`,`mid`) VALUE ' . $data);
        }
        if ($this->db->trans_status() === FALSE)
        {
            $this->db->trans_rollback();
            return false;
        }
        else
        {
            $this->db->trans_commit();
            return true;
        }
    }

}
