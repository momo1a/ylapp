<?php
/**
 * 药品管理控制器
 * User: momo1a@qq.com
 * Date: 2016/10/12 0012
 * Time: 下午 8:32
 */

class Medicine extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Medicine_model','medi');
        $this->load->model('Medi_appoint_model','appoint');
        $this->load->model('Medi_category','cate');
        $this->load->model('User_model','user');
    }


    public function index(){
        $limit = 10;
        $cate = intval($this->input->get_post('cate'));
        $total = $this->medi->mediCount($cate);
        $offset = intval($this->uri->segment(3));
        $list = $this->medi->mediList($limit,$offset,'*,YL_medicine.name as mediName',$cate);
        $cates = $this->cate->get_all();
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['cates'] = $cates;
        $data['get'] = $_GET;
        $this->load->view('medicine/index',$data);
    }


    public function mediSave(){
        $mid = intval($this->input->get_post('mid'));
        $name = trim($this->input->get_post('name'));
        $outline = trim($this->input->get_post('outline'));
        $content = $this->input->get_post('content');
        $cate = intval($this->input->get_post('cate'));
        $thumbnail_relative_path = '';
        if($_FILES['thumbnail']['tmp_name'] != '') {
            $thumbnail_relative_path = $this->upload->save('medicine', $_FILES['thumbnail']['tmp_name']);
        }
        $banner_relative_path = '';
        if($_FILES['banner']['tmp_name'] != '') {
            $banner_relative_path = $this->upload->save('medicine', $_FILES['banner']['tmp_name']);
        }
        if($mid == 0) {  // 添加资讯
            $data = array(
                'name' => $name,
                'outline' => $outline,
                'content' => $content,
                'thumbnail' => $thumbnail_relative_path,
                'banner' => $banner_relative_path,
                'cid' =>$cate,
                'dateline' => time(),
            );
            $res = $this->medi->mediAdd($data);
        }else{   // 编辑资讯
            $data = array(
                'name' => $name,
                'content' => $content,
                'outline' => $outline,
                'cid'=>$cate,
                'editTime'=>time()
            );
            if($banner_relative_path != ''){
                $data['banner'] = $banner_relative_path;
                // 删除原来图片
                @unlink(config_item('upload_image_save_path').$this->input->post('origin-news-banner'));
            }

            if($thumbnail_relative_path != ''){
                $data['thumbnail'] = $thumbnail_relative_path;
                // 删除原来图片
                @unlink(config_item('upload_image_save_path').$this->input->post('origin-news-img'));

            }

            $res = $this->medi->mediEdit($mid,$data);
        }

        if ($res) {
            $this->ajax_json(0, '操作成功');
        } else {
            $this->ajax_json(-1, '系统错误');
        }
    }



    public function  getMedicineDetail(){
        $mid = intval($this->input->get_post('mid'));
        $res = $this->medi->getMedicineDetail($mid);
        $this->ajax_json(0,'请求成功',$res);
    }

    /**
     * 添加药品分类
     */
    public function addCate(){
        $cateName = trim(addslashes($this->input->get_post('cateName')));
        $res = $this->cate->add_cate($cateName);
        if ($res) {
            $this->ajax_json(0, '操作成功');
        } else {
            $this->ajax_json(-1, '系统错误');
        }
    }


    /*预约管理  start*/

    //  预约列表
    public function appointList(){
        $limit = 10;
        $state = array('0'=>'未分配','1'=>'已分配');
        $search = array('illName'=>'患者姓名','telephone'=>'患者电话');
        $searchKey = $this->input->get_post('search-key');
        isset($_GET['search-key']) || $searchKey = '';
        if($searchKey != 'illName' && $searchKey != 'telephone' && $searchKey != ''){
            header('content-type:text/html;charset=utf-8');
            echo '<script>alert("搜索非法请求！");</script>';
            return;
        }
        $searchValue = trim(addslashes($this->input->get_post('search-value')));
        $mediName = addslashes(trim($this->input->get_post('mediName'))); // 药品名称
        $startTime = strtotime($this->input->get_post('startTime'));     // 预约开始时间
        $endTime = strtotime($this->input->get_post('endTime'));       // 预约结束时间
        $startTime = !$startTime ? 0 : $startTime;
        $endTime = !$endTime ? 0 : $endTime;
        //var_dump($startTime,$endTime);
        if($startTime > $endTime){
            header('content-type:text/html;charset=utf-8');
            echo '<script>alert("开始时间不能大于结束时间！");</script>';
            return false;
        }
        $total = $this->appoint->appointCount($searchKey,$searchValue,$mediName,$startTime,$endTime);
        $offset = intval($this->uri->segment(3));
        $list = $this->appoint->appointList($limit,$offset,$searchKey,$searchValue,$mediName,$startTime,$endTime,'*,YL_medi_appoint.name as illName,YL_medicine.name as mediName,YL_medi_appoint.id as aid,YL_medi_appoint.state as appointState');
        //var_dump($this->db->last_query());
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $data['state'] = $state;
        $data['search'] = $search;
        if($this->input->get_post('doexport') == 'yes'){
            $this->exportData();
        }
        $this->load->view('medicine/appoint_list',$data);
    }


    //  预约数据导出
    protected function exportData(){
        //header("Content-type:application/vnd.ms-excel");
        $filename = '药品预约数据'.date("Y-m-d-H:i:s", time()).'.xls';


        $searchKey = $this->input->get_post('search-key');
        isset($_GET['search-key']) || $searchKey = '';
        if($searchKey != 'illName' && $searchKey != 'telephone' && $searchKey != ''){
            header('content-type:text/html;charset=utf-8');
            echo '<script>alert("搜索非法请求！");</script>';
            return;
        }
        $searchValue = trim(addslashes($this->input->get_post('search-value')));
        $mediName = addslashes(trim($this->input->get_post('mediName'))); // 药品名称
        $startTime = strtotime($this->input->get_post('startTime'));     // 预约开始时间
        $endTime = strtotime($this->input->get_post('endTime'));       // 预约结束时间
        $startTime = !$startTime ? 0 : $startTime;
        $endTime = !$endTime ? 0 : $endTime;
        //var_dump($startTime,$endTime);
        if($startTime > $endTime){
            header('content-type:text/html;charset=utf-8');
            echo '<script>alert("开始时间不能大于结束时间！");</script>';
            return;
        }


        /*默认可以导出最新的2000条数据*/
        $data = $this->appoint->appointList(2000,0,$searchKey,$searchValue,$mediName,$startTime,$endTime,'*,YL_medi_appoint.name as illName,YL_medicine.name as mediName,YL_medi_appoint.id as aid,YL_medi_appoint.state as appointState');
        $state = array('0'=>'未分配','1'=>'已分配');
        $header = array(
            '编号',
            '预约时间',
            '患者手机',
            '患者姓名',
            '药品名',
            '当前状态',
        );

        $rows = array();
        if(!empty($data)) {
            foreach ($data as $k => $v) {
                $rows[$k]['aid'] = $v['aid'];
                $rows[$k]['appointTime'] = date('Y-m-d H:i:s',$v['appointTime']);
                $rows[$k]['telephone'] = $v['telephone'];
                $rows[$k]['illName'] = $v['illName'];
                $rows[$k]['mediName'] = $v['mediName'];
                $rows[$k]['appointState'] = $state[$v['appointState']];
            }
        }
        array_unshift($rows, $header);
        $this->data_export($rows, $filename);
    }


    // 获取所有药品
    public function getAllMedicine(){
        $allMedicine = $this->medi->getAllMedicine();
        $this->ajax_json(0,'请求成功',$allMedicine);

    }

    //  获取所有正常用户
    public function getAllUser(){
        $users = $this->user->getAllUser();  // 获取所有正常用户
        $this->ajax_json(0,'请求成功',$users);
    }


    // 获取伙计

    public function getGuys(){
        $guys = $this->user->getAllUser(2,array('YL_doctor_info.isDude'=>1),true);
        $this->ajax_json(0,'请求成功',$guys);
    }


    // 分配

    public function allot(){
        $aid = intval($this->input->get_post('aid'));
        $guysId = intval($this->input->get_post('guys'));
        if($guysId == 0){
            $this->ajax_json(1, '请选择药房伙计');
        }
        $res = $this->appoint->appointAllot($aid,array('guysId'=>$guysId,'allotTime'=>time(),'state'=>1));
        if($res){
            $this->ajax_json(0, '分配成功');
        }else{
            $this->ajax_json(-1, '分配失败');
        }
    }


    //  添加预约
    public function appointAdd(){
        $appointTime = $this->input->get_post('appointTime');
        if(strtotime($appointTime) < time()){
            $this->ajax_json(1, '请输入合理的时间');
        }
        $realName = addslashes(trim($this->input->get_post('realName')));
        if(mb_strlen($realName) > 20 || mb_strlen($realName) < 2){
            $this->ajax_json(1, '姓名不能大于20个字符小于2个字符');
        }

        $telephone = trim($this->input->get_post('telephone'));
        if(mb_strlen($telephone) > 20 || mb_strlen($telephone) < 5){
            $this->ajax_json(1, '电话号码不能大于20个字符小于5个字符');
        }

        $mediId = intval($this->input->get_post('mediName'));
        if($mediId == 0){
            $this->ajax_json(1, '请选择药品名');
        }

        $userId = intval($this->input->get_post('regTel'));
        if($userId == 0){
            $this->ajax_json(1, '请选择用户注册手机号');
        }
        $content = trim($this->input->get_post('content'));
        if(mb_strlen($content) > 20000 || mb_strlen($content) < 15){
            $this->ajax_json(1, '图文信息不能大于20000个字符小于15个字符');
        }


        $data = array(
            'name'=>$realName,
            'appointTime'=>strtotime($appointTime),
            'telephone'=>$telephone,
            'mediId'=>$mediId,
            'userId'=>$userId,
            'content'=>$content,
            'dateline'=>time()
        );
        $res = $this->appoint->appointAdd($data);
        if($res){
            $this->ajax_json(0, '添加成功');
        }else{
            $this->ajax_json(-1,'添加失败');
        }

    }

    // 获取预约详情
    public function getAppointDetail(){
        $aid = intval($this->input->get_post('aid'));
        $res = $this->appoint->getDetail($aid);
        $this->ajax_json(0, '请求成功',$res);
    }
    /*预约管理  end*/
}