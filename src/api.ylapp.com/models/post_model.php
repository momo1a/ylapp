<?php
/**
 * 帖子model.
 * User: momo1a@qq.com
 * Date: 2016/8/14 0014
 * Time: 下午 1:55
 */

class Post_model extends MY_Model
{

    public static $table_name = 'post';


    public function __construct(){
        parent::__construct();
    }

    /**
     * 添加帖子
     * @param $data
     */
    public function postAdd($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 帖子列表
     * @param $flag 1 为最新  2 本周最热
     * @return mixed
     */
    public function postList($flag=1,$limit=10,$offset=0,$uid=0){
        $str = '';
        switch($flag){
            case 2:
                $str = 'ORDER BY `postTime` DESC ';
                break;
            case 1:
                $str =' AND postTime >= UNIX_TIMESTAMP(curdate()) ORDER BY commentCount DESC ';
                break;
            default:
                break;
        }
        $sql = <<<SQL
SELECT  a.`id`,b.`id` AS collId,`postTitle`,`postNickname`,FROM_UNIXTIME(`postTime`) AS postDate,LEFT(`postContent`,24) AS content,(SELECT avatar FROM YL_user WHERE `uid`=a.postUid) AS avatar,(SELECT COUNT(1) FROM YL_post_click_like WHERE `postId`=a.id) AS clickCount,(SELECT COUNT(1) FROM YL_post_comment WHERE state=1 AND postId=a.id) AS commentCount FROM YL_post AS a LEFT JOIN YL_post_click_like AS b ON a.id=b.postId  AND b.uid={$uid}  WHERE state=1 {$str} LIMIT {$offset},{$limit};
SQL;
        $query = $this->db->query($sql);
        $res = $query->result_array();
        return $res;
    }

    /**
     * 帖子详情页面
     * @param $postId
     * @param string $select
     * @return array
     */
    public function postDetail($postId,$select='*'){
        $this->where(array('id'=>$postId));
        $this->select($select);
        $this->join('YL_user','YL_post.postUid=YL_user.uid','left');
        $res = $this->find_all();
        return $res;
    }

    /**
     * 我的发帖列表
     * @param $uid
     * @param $select
     */
    public function myPostList($uid,$select){
        $this->where(array('postUid'=>$uid));
        $this->select($select);
        $this->order_by(array('id'=>'desc'));
        $res = $this->find_all();
        return $res;
    }

}