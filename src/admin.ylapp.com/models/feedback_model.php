<?php
/**
 * 反馈模型
 * User: momo1a@qq.com
 * Date: 2016/8/17 0017
 * Time: 下午 8:28
 */

class Feedback_model extends MY_Model
{

    public static $table_name = 'feedback';


    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取列表
     * @param string $keyword
     * @param $limit
     * @param $offset
     */
    public function getList($keyword='',$limit=10,$offset=0,$select="*"){
        if($keyword != ''){
            $this->like('YL_user.nickname',$keyword);
        }
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_feedback.uid');
        $this->limit($limit);
        $this->offset($offset);
        $this->order_by(array('id'=>'desc'));
        return $this->find_all();
    }

    /**
     * 统计
     * @param string $keyword
     */
    public function feedbackCount($keyword = ''){
        if($keyword != ''){
            $this->like('YL_user.nickname',$keyword);
        }
        $this->join('YL_user','YL_user.uid=YL_feedback.uid');
        return $this->count_all();
    }
}