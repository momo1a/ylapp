<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * 医生评价模型
 * User: momo1a@qq.com
 * Date: 2016/8/10
 * Time: 17:10
 */

class Doctor_evaluate_model extends MY_Model
{
    public static $table_name = 'doctor_evaluate';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 获取医生的评价
     * @param $docId
     */
    public function getDoctorEvaluate($docId,$select){
        $this->where(array('YL_doctor_evaluate.docId'=>$docId,'YL_doctor_evaluate.state'=>1));
        $this->select($select);
        $this->join('YL_user','YL_user.uid=YL_doctor_evaluate.uid');
        $this->order_by('YL_doctor_evaluate.dateline','DESC');
        $res = $this->find_all();
        return $res;
    }
}