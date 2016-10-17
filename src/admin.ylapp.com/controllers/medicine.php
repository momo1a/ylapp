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
        $illName = addslashes(trim($this->input->get_post('illName')));   // 患者姓名
        $mediName = addslashes(trim($this->input->get_post('mediName'))); // 药品名称
        $startTime = strtotime($this->input->get_post('startTime'));     // 预约开始时间
        $endTime = strtotime($this->input->get_post('endTime'));       // 预约结束时间
        $total = $this->appoint->appointCount($illName,$mediName,$startTime,$endTime);
        $offset = intval($this->uri->segment(3));
        $list = $this->appoint->appointList($limit,$offset,$illName,$mediName,$startTime,$endTime);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('medicine/appoint_list',$data);
    }



    /*预约管理  end*/
}