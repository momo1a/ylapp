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
                    $imgArr[$k]=$imgFile;
                }
            }
        }
        $imgArr = !empty($imgArr) ? json_encode($imgArr) : '';
        $nickName = $this->user->getUserInfoByUid(self::$currentUid,'nickname');
        $data = array(
            'postUid'=>self::$currentUid,
            'postNickname'=>$nickName,
            'postTitle'=>$title,
            'postContent'=>$content,
            'img'=>$imgArr,
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
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $flag = intval($this->input->get_post('flag'));
        $flag = !$flag ? 1 : $flag;
        $uid = !self::$currentUid ? 0 : self::$currentUid;
        $res = $this->post->postList($flag,$limit,$offset,$uid);
        //var_dump($this->db->last_query());
        $this->response($this->responseDataFormat(0, '请求成功', array('postList'=>$res,'imgServer'=>$this->getImgServer())));
    }

    /**
     * 贴子详情
     */
    public function detailPost(){
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));
        $limit = $limit == 0 ? 10 : $limit;
        $postId = intval($this->input->get_post('postId'));
        $select = 'YL_user.avatar,YL_post.postTitle,YL_post.postNickname,FROM_UNIXTIME(YL_post.postTime) AS postDate,YL_post.postContent,YL_post.isAnonymous,YL_post.img';
        $post = $this->post->postDetail($postId,$select);
        if(!empty($post)){
            foreach($post as $key=>$value){
                $post[$key]['img'] = json_decode($post[$key]['img'],true);
            }
        }
        $likeCount = $this->click_like->getCountByPostId($postId);
        $isClickLike = $this->click_like->getCommentByUidAndPostId(self::$currentUid,$postId);  //当前用户是否已点赞
        $isClickLike = $isClickLike ? '已点赞' : '未点赞';
        $commentSelect = 'YL_post_comment.id,YL_user.avatar,YL_post_comment.recmdNickname,FROM_UNIXTIME(YL_post_comment.recmdTime) AS recmdDate,YL_post_comment.recmdContent';
        $comment = $this->comment->getCommentByPostId($postId,$commentSelect,$limit,$offset);
        $imgServer = $this->getImgServer();
        $this->response($this->responseDataFormat(0, '请求成功', array('post'=>$post,'likeCount'=>$likeCount,'isClickLike'=>$isClickLike,'commentList'=>$comment,'imgServer'=>$imgServer)));
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
     * 取消点赞
     */
    public function cancelLike(){
        $this->checkUserLogin();
        $postId = intval($this->input->get_post('postId'));
        $res = $this->click_like->cancelLike($postId,self::$currentUid);
        if($res) {
            $this->response($this->responseDataFormat(0, '取消成功', array()));
        }else{
            $this->response($this->responseDataFormat(1, '取消失败', array()));
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
            'recmdNickname'=>$this->user->getUserInfoByUid(self::$currentUid,'nickname'),
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