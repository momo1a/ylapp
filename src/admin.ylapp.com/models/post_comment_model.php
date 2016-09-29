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
    public function getCommentByPostId($postId,$select='*',$limit=10,$offset=0){
        $this->where(array('postId'=>$postId,'state'=>1));
        $this->select($select);
        $this->join('YL_user','YL_post_comment.recmdUid=YL_user.uid','left');
        $this->order_by('YL_post_comment.id','DESC');
        $this->limit($limit);
        $this->offset($offset);
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

    /**
     * 我的回复  （我的帖子的评论）
     * @param $uid
     * @param $select
     */
    public function myReply($uid,$select='*'){
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_post_comment.recmdUid','left');
        $this->join('YL_post','YL_post_comment.postId=YL_post.id','left');
        $this->where(array('YL_post.postUid'=>$uid,'YL_post_comment.state'=>1));
        $this->order_by(array('YL_post_comment.id'=>'desc'));
        $res = $this->find_all();
        return $res;
    }

    /**
     * 我的评论
     * @param $uid
     * @param $select
     */
    public function myComment($uid,$select='*'){
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_post_comment.recmdUid','left');
        $this->join('YL_post','YL_post.id=YL_post_comment.postId','left');
        $this->where(array('YL_post_comment.recmdUid'=>$uid));
        $this->order_by(array('YL_post_comment.id'=>'desc'));
        $res = $this->find_all();
        return $res;
    }


}