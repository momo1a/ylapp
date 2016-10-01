<?php
/**
 * 疫苗接种控制器
 * User: momo1a@qq.com
 * Date: 2016/9/30 0030
 * Time: 下午 10:17
 */

class Vaccine extends MY_Controller
{
    public function __construct(){
        parent::__construct();
        $this->load->model('Vaccinum_model','vaccine');
    }

    public function index(){
        $limit = 10;
        $keyword = addslashes(trim($this->input->get_post('keyword')));
        $total = $this->vaccine->viccinumCount($keyword);
        $offset = intval($this->uri->segment(3));
        $list = $this->vaccine->getList($keyword,$limit,$offset);
        $page_conf['base_url'] = site_url($this->router->class.'/'.$this->router->method.'/');
        $page_conf['first_url'] = site_url($this->router->class.'/'.$this->router->method.'/0');
        $pager = $this->pager($total, $limit,$page_conf);
        $data['pager'] = $pager;
        $data['list'] = $list;
        $data['get'] = $_GET;
        $this->load->view('vaccine/index',$data);
    }



    public function packageSave(){
        $pid = intval($this->input->get_post('pid'));
        $title = trim($this->input->get_post('title'));
        $content = $this->input->get_post('content');
        $status = intval($this->input->get_post('status'));
        $type = intval($this->input->get_post('type'));
        $price = floatval($this->input->get_post('price'));
        $thumbnail_relative_path = '';
        if($_FILES['thumbnail']['tmp_name'] != '') {
            $thumbnail_relative_path = $this->upload->save('package', $_FILES['thumbnail']['tmp_name']);
        }
        if($pid == 0) {  // 添加套餐
            $data = array(
                'name' => $title,
                'detail' => $content,
                'thumbnail' => $thumbnail_relative_path,
                'price'=>$price,
                'type'=> $type,
                'dateline' => time(),
                'status' => $status
            );
            $res = $this->vaccine->addPackage($data);
        }else{   // 编辑套餐
            $data = array(
                'name' => $title,
                'detail' => $content,
                'price'=>$price,
                'type'=> $type,
                'dateline' => time(),
                'status' => $status
            );
            if($thumbnail_relative_path != ''){
                $data['thumbnail'] = $thumbnail_relative_path;
                // 删除原来图片
                @unlink(config_item('upload_image_save_path').$this->input->post('origin-thumbnail-img'));

            }

            $res = $this->vaccine->editPackage($pid,$data);
        }

        if ($res) {
            $this->ajax_json(0, '操作成功');
        } else {
            $this->ajax_json(-1, '系统错误');
        }
    }


    /**
     * 获取套餐详情
     */
    public function getPackageDetail(){
        $pid = intval($this->input->get_post('pid'));
        $res = $this->vaccine->getDetail($pid);
        $this->ajax_json(0,'请求成功',$res);
    }


    /**
     * 删除套餐
     */
    public function packageDel(){
        $pid = intval($this->input->get_post('pid'));
        $res = $this->vaccine->delPackage($pid);
        if ($res) {
            $this->ajax_json(0, '操作成功');
        } else {
            $this->ajax_json(-1, '系统错误');
        }

    }
}
