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
     * 评论列表
     * @param string $keyword
     * @param int $state
     * @param int $limit
     * @param int $offset
     */
    public function commentList($keyword = '',$state = -1,$limit=10,$offset=0){
        if($keyword != ''){
            $this->like(array('YL_post_comment.recmdContent'=>$keyword));
        }

        if($state != -1){
            $this->where(array('YL_post_comment.state'=>$state));
        }
        $this->select('YL_post_comment.id,pu.nickname as postUser,post.postTitle,YL_post_comment.recmdContent,from_unixtime(YL_post_comment.recmdTime) as recmdTime,cu.nickname as cmdUser,YL_post_comment.state');
        $this->join('YL_post','YL_post.id=YL_post_comment.postId','left');
        $this->join('YL_user AS pu','pu.uid=post.postUid','left');
        $this->join('YL_user AS cu','cu.uid=YL_post_comment.recmdUid','left');
        $this->offset($offset);
        $this->limit($limit);
        $this->order_by(array('recmdTime'=>'desc'));
        $res = $this->find_all();
        return $res;
    }

    /**
     * 评论数
     * @param string $keyword
     * @param int $state
     */
    public function commentCount($keyword = '',$state = -1){
        if($keyword != ''){
            $this->like(array('YL_post.postTitle'=>$keyword));
        }

        if($state != -1){
            $this->where(array('state'=>$state));
        }
        $this->join('YL_post','YL_post.id=YL_post_comment.postId','left');
        return $this->count_all();
    }

    /**
     * 设置
     * @param $cid  帖子id
     * @param $field  字段
     * @param $value  值
     */
    public function commentSetting($cid,$field,$value){
        $where = array('id'=>$cid);
        $data = array($field=>$value);
        $res = $this->update($where,$data);
        return $res;
    }



    /**
     * 删除帖子
     * @param $pid
     */
    public function commentDel($cid){
        $where = array('id'=>$cid);
        $res = $this->delete_where($where);
        return $res;
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


    //  获取管理员未处理的
    public function getNotDeal($select='*'){
        $this->select($select,false);
        $this->where(array('state'=>0));
        return $this->find_all();
    }


}