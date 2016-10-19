<?php
/**
 * 药品预约模型
 * User: momo1a@qq.com
 * Date: 2016/10/17
 * Time: 10:16
 */

class   Medi_appoint_model extends MY_Model
{

    public static $table_name = 'medi_appoint';


    public function __construct(){
        parent::__construct();
    }


    /**
     * 预约列表
     * @param int $limit
     * @param int $offset
     * @param string $illName
     * @param string $mediName
     * @param int $startTime
     * @param $endTime
     */
    public function appointList($limit=10,$offset= 0,$searchKey='',$searchValue='',$mediName='',$startTime=0,$endTime=0,$select='*'){
        if($searchKey != '' && $searchValue != ''){
            switch($searchKey){
                case 'illName':
                    $this->like(array('YL_medi_appoint.name'=>$searchValue));
                    break;
                case 'telephone':
                    $this->like(array('YL_medi_appoint.telephone'=>$searchValue));
                    break;
                default:
                    break;
            }
        }
        if($mediName != ''){
            $this->like(array('YL_medicine.name'=>$mediName));
        }
        if($startTime == 0 && $endTime == 0){
            //$this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            //$this->where(array('YL_medi_appoint.appointTime <= '=>time()));
        }elseif($startTime == 0 && $endTime != 0 ){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>$endTime));
        }elseif($startTime != 0 && $endTime == 0){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>time()));
        }elseif($startTime != 0 && $endTime != 0){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>$endTime));
        }
        $this->select($select);
        $this->join('YL_user as user','YL_medi_appoint.userId=user.uid','left');
        $this->join('YL_user as guys','YL_medi_appoint.guysId=guys.uid','left');
        $this->join('YL_medicine','YL_medi_appoint.mediId=YL_medicine.id','left');
        $this->offset($offset);
        $this->limit($limit);
        return $this->find_all();
    }

    /**
     * 统计
     * @param string $illName
     * @param string $mediName
     * @param int $startTime
     * @param int $endTime
     */
    public function appointCount($searchKey='',$searchValue='',$mediName='',$startTime=0,$endTime=0){
        if($searchKey != '' && $searchValue != ''){
            switch($searchKey){
                case 'illName':
                    $this->like(array('YL_medi_appoint.name'=>$searchValue));
                    break;
                case 'telephone':
                    $this->like(array('YL_medi_appoint.telephone'=>$searchValue));
                    break;
                default:
                    break;
            }
        }
        if($mediName != ''){
            $this->where(array('YL_medicine.name'=>$mediName));
        }
        if($startTime == 0 && $endTime == 0){
            //$this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            //$this->where(array('YL_medi_appoint.appointTime <= '=>time()));
        }elseif($startTime == 0 && $endTime != 0 ){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>$endTime));
        }elseif($startTime != 0 && $endTime == 0){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>time()));
        }elseif($startTime != 0 && $endTime != 0){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>$endTime));
        }

        $this->join('YL_medicine','YL_medi_appoint.mediId=YL_medicine.id','left');

        return $this->count_all();
    }


    /**
     * 添加预约
     * @param $data
     */
    public function appointAdd($data){
        $this->insert($data);
        return $this->db->insert_id();
    }

    /**
     * 获取预约详情
     * @param $aid
     */
    public function getDetail($aid,$select='*'){
        $this->select($select);
        return $this->find_by(array('id'=>$aid));
    }

    /**
     * 预约分配
     * @param $aid
     * @param $data
     */
    public function appointAllot($aid,$data){
        $where = array('id'=>$aid);
        $this->update($where,$data);
        return $this->db->affected_rows();
    }
}