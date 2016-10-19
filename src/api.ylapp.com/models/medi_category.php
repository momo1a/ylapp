<?php
/**
 * 药品分类控制器
 * User: momo1a@qq.com
 * Date: 2016/10/15 0015
 * Time: 上午 11:07
 */


class Medi_category extends MY_Model
{

    public static $table_name = 'medi_category';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取所有分类
     * @return array
     */
    public function get_all($select="*"){
        $this->where(array('pid'=>0));
        $this->select($select);
        return $this->find_all();
    }

}