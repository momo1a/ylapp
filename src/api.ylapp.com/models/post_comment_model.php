<?php
/**
 * 帖子评论model
 * User: Administrator
 * Date: 2016/8/14 0014
 * Time: 下午 9:18
 */

class  Post_comment_model extends MY_Model
{

    public static $table_name = 'post_comment';

    public function __construct(){
        parent::__construct();
    }


    /**
     * 根据帖子id获取评论
     * @param $postId
     */
    public function getCommentByPostId($postId,$select='*'){
        $this->where(array('postId'=>$postId,'state'=>1));
        $this->select($select);
        $this->join('YL_user','YL_post_comment.recmdUid=YL_user.uid','left');
        $this->order_by('YL_post_comment.id','DESC');
        $res = $this->find_all();
        return $res;
    }

    /**
     * 添加评论
     * @param $data
     */
    public function addComment($data){
        $res = $this->insert($data);
        return $res;
    }

}