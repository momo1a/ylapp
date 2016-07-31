<?php
/**
 * 分期购活动拓展模型
 * Author: Moshiyou<momo1a@qq.com>
 * Date: 2015/12/9
 * Time: 14:25
 */

class Stages_goods_extend_model extends Common_stages_goods_extend_model
{
    /**
     * 根据gid获取记录
     * @param $gid  活动id
     */
    public function get_goods_by_gid($gid){
        $query = $this->db->where('gid',intval($gid))->get($this->_table);
        if(!$query){
            return false;
        }
        $result = $query->row_array();
        return $result;
    }
}