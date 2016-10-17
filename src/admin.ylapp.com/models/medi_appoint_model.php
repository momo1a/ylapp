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
    public function appointList($limit=10,$offset= 0,$illName='',$mediName='',$startTime=0,$endTime=0){
        if($illName != ''){
            $this->where(array('YL_medi_appoint.name'=>$illName));
        }
        if($mediName != ''){
            $this->where(array('YL_medicine.name'=>$mediName));
        }
        if($startTime == 0 && $endTime == 0){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>time()));
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

        $this->join('YL_user as user','YL_medi_appoint.userId=user.uid','left');
        $this->join('YL_user as guys','YL_medi_appoint.guysId=guys.uid','left');
        $this->join('YL_medicine as medi','YL_medi_appoint.mediId=medi.id','left');
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
    public function appointCount($illName='',$mediName='',$startTime=0,$endTime=0){
        if($illName != ''){
            $this->where(array('YL_medi_appoint.name'=>$illName));
        }
        if($mediName != ''){
            $this->where(array('medi.name'=>$mediName));
        }
        if($startTime == 0 && $endTime == 0){
            $this->where(array('YL_medi_appoint.appointTime > '=>$startTime));
            $this->where(array('YL_medi_appoint.appointTime <= '=>time()));
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

        $this->join('YL_medicine as medi','YL_medi_appoint.mediId=medi.id','left');

        return $this->count_all();
    }
}