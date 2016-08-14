<?php
/**
 * 交流圈帖子控制器
 * User: momo1a@qq.com
 * Date: 2016/8/14 0014
 * Time: 下午 1:39
 */

class Post extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Post_model','post');
        $this->load->model('User_model','user');
        $this->load->model('Post_click_like_model','click_like');
        $this->load->model('Post_comment_model','comment');
        $this->load->library('Upload_image',null,'upload_image');
    }


    /**
     * 添加帖子
     */
    public function postAdd(){
        $this->checkUserLogin();
        $title = trim(addslashes($this->input->get_post('title')));
        $content = addslashes($this->input->get_post('content'));
        $isAnonymous = intval($this->input->get_post('isAnonymous'));  // 0 否 1是
        $imgArr = array();
        if(!empty($_FILES)){
            foreach($_FILES as $k=>$val){
                if($val['name'] != '') {
                    $imgFile = $this->upload_image->save('post', $val['tmp_name']);
                    array_push($imgArr, $imgFile);
                }
            }
        }
        $nickName = $this->user->getNickname(self::$currentUid,'nickname');
        $data = array(
            'postUid'=>self::$currentUid,
            'postNickname'=>$nickName,
            'postTitle'=>$title,
            'postContent'=>$content,
            'img'=>json_encode($imgArr),
            'postTime'=>time(),
            'isAnonymous'=>$isAnonymous,
        );
        $res = $this->post->postAdd($data);
        if($res) {
            $this->response($this->responseDataFormat(0, '请求成功', array()));
        }
    }


    /**
     * 帖子列表
     */

    public function listPost(){
        $flag = intval($this->input->get_post('flag'));
        $flag = !$flag ? 1 : $flag;
        $res = $this->post->postList($flag);
        $this->response($this->responseDataFormat(0, '请求成功', array($res)));
    }

    /**
     * 贴子详情
     */
    public function detailPost(){
        $postId = intval($this->input->get_post('postId'));
        $select = 'YL_user.avatar,YL_post.postTitle,YL_post.postNickname,FROM_UNIXTIME(YL_post.postTime) AS postDate,YL_post.postContent,YL_post.isAnonymous,YL_post.img';
        $post = $this->post->postDetail($postId,$select);
        $likeCount = $this->click_like->getCountByPostId($postId);
        $commentSelect = 'YL_post_comment.id,YL_user.avatar,YL_post_comment.recmdNickname,FROM_UNIXTIME(YL_post_comment.recmdTime) AS recmdDate,YL_post_comment.recmdContent';
        $comment = $this->comment->getCommentByPostId($postId,$commentSelect);
        $this->response($this->responseDataFormat(0, '请求成功', array('post'=>$post,'likeCount'=>$likeCount,'commentList'=>$comment)));
    }


    /**
     * 点赞
     */
    public function clickLike(){
        $this->checkUserLogin();
        $postId = intval($this->input->get_post('postId'));
        $checkUserClick = $this->click_like->getCommentByUidAndPostId(self::$currentUid,$postId);
        if($checkUserClick){
            $this->response($this->responseDataFormat(1, '你已经点过赞了', array()));
        }
        $res = $this->click_like->clickLike($postId,self::$currentUid);
        if($res) {
            $this->response($this->responseDataFormat(0, '点赞成功', array()));
        }
    }


    /**
     * 添加评论
     */
    public function addComment(){
        $this->checkUserLogin();
        $postId = intval($this->input->get_post('postId'));
        $content = addslashes($this->input->get_post('content'));
        $data = array(
            'postId'=>$postId,
            'recmdUid'=>self::$currentUid,
            'recmdNickname'=>$this->user->getNickname(self::$currentUid,'nickname'),
            'recmdContent'=>$content,
            'recmdTime'=>time()
        );
        $res = $this->comment->addComment($data);
        if($res){
            $this->response($this->responseDataFormat(0, '评论成功', array()));
        }else{
            $this->response($this->responseDataFormat(-1, '系统错误', array()));
        }
    }
}