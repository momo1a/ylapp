<?php
/**
 * 关于我们model
 * User: momo1a@qq.com
 * Date: 2016/8/17 0017
 * Time: 下午 7:57
 */

class About_us_model extends MY_Model
{
    public static $table_name = 'about_us';

    public function __construct(){
        parent::__construct();
    }

    /**
     *
     * 获取企业信息
     */
    public function getAboutUs(){
        $this->order_by(array('id'=>'desc'));
        $this->limit(1);
        $res = $this->find_all();
        return $res;
    }


}