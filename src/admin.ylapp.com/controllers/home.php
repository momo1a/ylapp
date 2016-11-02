<?php
class Home extends MY_Controller
{
    public function __construct(){
        parent::__construct();
    }

    public function index(){
        $data['is_super'] = self::$_is_super;
        $this->load->view('index/index',$data);
    }


    /**
     * 退出登录
     */
    public function logout(){
        unset($_SESSION['userInfo']);
        redirect('login/index');
    }

    public function ckImgUpload(){
        if(!empty($_FILES['upload']['tmp_name'])){
            $relativePath = $this->upload->save('ckUpload',$_FILES['upload']['tmp_name']);
            $callback = $_REQUEST["CKEditorFuncNum"];
            if($relativePath){
                $imgServers = config_item('image_servers');
                $previewname = $imgServers[0].$relativePath;
                echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction($callback,'".$previewname."','');</script>";
            }else{
                echo "<font color=\"red\"size=\"2\">*文件格式不正确（必须为.jpg/.gif/.bmp/.png文件）</font>";
            }
        }

    }



}