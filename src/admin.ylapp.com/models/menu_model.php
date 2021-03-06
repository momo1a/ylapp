<?php
class Menu_model extends My_Model{

    public static $table_name = 'menu';

    public function __construct(){
        parent::__construct();
    }



    /*
     * 获取一级菜单
    */
    public function get_menu($mids = ''){
        $where =  array('p_id'=>0);
        if(''!=$mids){
            $this->where_in('id',$mids);
        }
        $this->order_by('sort','desc');
        $res = $this->find_all_by($where);
        return $res;
    }

}
