<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * banner 控制器
 * User: momo1a@qq.com
 * Date: 2016/8/4 0004
 * Time: 下午 10:58
 */

class Banner_model extends MY_Model
{

    public static $table_name = 'banner';

    /**
     * @param $userType
     */
    public function getBannerByUserType($userType,$select,$limit=4){
        $this->select($select);
        $this->order_by('createTime','desc');
        $this->limit($limit);
        $res = $this->find_all_by(array('type'=>$userType));
        return $res;
    }
}

