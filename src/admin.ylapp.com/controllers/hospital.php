<?php
/**
 * 医院管理控制器
 * User: momo1a@qq.com
 * Date: 2016/9/23
 * Time: 10:11
 */
class Hospital extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Hospital_model','hospital');
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $total = $this->hospital->getHospitalCount($keyword);
        $offset = intval($this->uri->segment(3));
        if(!empty($keyword)){
            $offset = 0;
        }
        $list = $this->hospital->getHospitalList(0,$keyword,'*',$limit,$offset);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('hospital/index',$data);
    }


    /**
     * 医院详情
     */
    public function getHospitalDetail(){
        $hid = intval($this->input->get_post('hid'));
        $res = $this->hospital->getHospitalDetail($hid);
        if($res) {
            $this->ajax_json(0,'请求成功',$res);
        }else{
            $this->ajax_json(-1,'请求失败');
        }
    }


    /**
     * 添加医院
     */
    public function saveHospital(){
        $hospitalName = trim(addslashes($this->input->get_post('hos_name')));
        $address = trim(addslashes($this->input->get_post('address')));
        if(!$_FILES['hospital_img']){
            $this->ajax_json(-1,'请上传医院图片缩略图');
        }
        $imgSize = getimagesize($_FILES['hospital_img']['tmp_name']);
        if($imgSize[0] > 350 || $imgSize[1] > 350){
            $this->ajax_json(-1,'图片长，宽不能大于350px');
        }
        $relativePath = $this->upload->save('hospital',$_FILES['hospital_img']['tmp_name']);
        $data = array(
            'name'=>$hospitalName,
            'address'=>$address,
            'img'=>$relativePath,
            'createTime'=>time()
        );

        $res = $this->hospital->saveHospital($data);

        if($res) {
            $this->ajax_json(0,'添加成功');
        }else{
            $this->ajax_json(-1,'系统错误');
        }
    }

    /**
     * 删除医院
     */
    public function delHospital(){
        $hid = intval($this->input->get_post('hid'));
        $res = $this->hospital->delHospital($hid);
        if($res) {
            $this->ajax_json(0,'删除成功');
        }else{
            $this->ajax_json(-1,'删除失败');
        }
    }
}