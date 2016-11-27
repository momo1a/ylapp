<?php
/**
 * 帖子点赞model
 * User: Administrator
 * Date: 2016/8/14 0014
 * Time: 下午 6:54
 */

class Post_click_like_model extends MY_Model
{
    public static $table_name = 'post_click_like';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 根据帖子id获取点赞数
     * @param $postId
     */
    public function getCountByPostId($postId){
        $this->where(array('postId'=>$postId));
        $res = $this->count_all();
        return $res;
    }

    /**
     * 点赞
     * @param $postId
     * @param $uid
     */
    public function clickLike($postId,$uid){
        /*开始事务*/
        $this->db->trans_begin();
        $this->insert(array('postId' => $postId, 'uid' => $uid, 'clickTime' => time()));
        $this->db->query('UPDATE `YL_post` SET `clickLikeCount`=`clickLikeCount`+1 WHERE `id`='.$postId);
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


    /**
     * 取消点赞
     * @param $postId
     * @param $uid
     * @return bool
     */
    public function cancelLike($postId,$uid){
        $this->db->trans_begin();
        $where = array('postId'=>$postId,'uid'=>$uid);
        $this->delete_where($where);
        $this->db->query('UPDATE  `YL_post` SET `clickLikeCount` = IF(`clickLikeCount` = 0, `clickLikeCount`, `clickLikeCount`-1) WHERE id='.$postId);
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


    /**
     * @param $uid
     * @param $postId
     */
    public function getCommentByUidAndPostId($uid,$postId){
        $this->where(array('uid'=>$uid,'postId'=>$postId));
        $res = $this->find_all();
        return $res;
    }
}