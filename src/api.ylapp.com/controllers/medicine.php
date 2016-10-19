<?php
/**
 * 药品控制器
 * User: momo1a@qq.com
 * Date: 2016/8/8 0008
 * Time: 下午 10:34
 */

class Medicine extends MY_Controller
{

    /**
     * 图片服务器
     * @var null
     */
    protected static $_imgServer = null;

    public function __construct(){
        parent::__construct();
        $this->load->model('Medicine_model','medicine');
        $this->load->model('Medi_category','cate');
        self::$_imgServer = $this->getImgServer();
    }

    // 药品列表
    public function medicineList(){
        $keyword = trim(addslashes($this->input->get_post('keyword'))); // 药品名称关键字
        $cid = intval($this->input->get_post('cid'));   // 癌种分类
        $limit = intval($this->input->get_post('limit'));
        $offset = intval($this->input->get_post('offset'));

        $limit = !$limit ? 10 : $limit;
        $medicine = $this->medicine->mediList($limit,$offset,$select='YL_medicine.id as mid,YL_medicine.name as medicineName,outline,thumbnail',$cid,$keyword); // 药品列表
        $category = $this->cate->get_all($select="cid,name as cateName");  // 所有分类
        $this->response($this->responseDataFormat(0,'请求成功',array('category'=>$category,'medicine'=>$medicine,'imgServer'=>self::$_imgServer)));
    }
}
