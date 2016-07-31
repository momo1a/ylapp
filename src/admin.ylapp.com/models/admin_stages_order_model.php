<?php

/**
 * 分期订单模型
 * @author moshiyou <momo1a@qq.com>
 */
class Admin_stages_order_model extends Order_Model
{

    /**
     * 当前模型对应表名
     * @var string
     */
    private $_table;

    private $_error;

    /**
     * 构造函数
     */
    public function __construct()
    {
        parent::__construct();
        $this->_table = 'stages_order';
    }

    public  function error(){
        return $this->_error;
    }

    /**
     * 根据商品查询获取订单
     * @param number $gid
     * @param number $limit
     * @param number $offset
     */
    public function get_by_gid($gid, $limit = 10, $offset = 0)
    {
        $this->db->where('gid', $gid);
        $this->db->limit($limit, $offset);
        return $this->db->from($this->_table)->get()->result('array');
    }

    /**
     * 根据商品ID获取订单数量
     * @param number $gid
     */
    public function count_by_gid($gid)
    {
        $this->db->where('gid', $gid);
        return $this->db->from($this->_table)->get()->num_rows();
    }

    /**
     * 搜索订单
     *
     * @param string $key 搜索类型
     * @param string $val 搜索关键字
     * @param string $status 订单状态
     * @param string $field 字段
     * @param number $buyer_uid 买家uid
     * @param string $where 其它条件
     * @param number $limit 每页大小
     * @param number $offset 偏移量
     * @param string $order_by 排序
     * @return array
     */
    public function search($key = '', $val = '', $status = '', $field = '', $where = '',$limit = 0, $offset = 0, $order_by = ''){
        if('' !==$field){
            $this->db->select($field);
        }

        $this-> _search_where($key, $val, $status , $where);

        if($order_by !=='' ){
            $this->db->order_by($order_by);
        }
        if($limit){
            $this->db->limit($limit, $offset);
        }
        $this->db->from($this->_table);
        $this->db->join('stages_goods_extend','stages_order.gid=stages_goods_extend.gid','left');
        $order = $this->db->get()->result_array();
        //echo $this->db->last_query();exit;
        return $order;
    }

    /**
     * 行数
     * @param string $key 搜索类型
     * @param string $val 搜索关键字
     * @param string $status 订单状态
     * @param string $where 其它条件
     * @return array
     */
    public function search_count($key = '', $val = '', $status = '',  $where = ''){
        $this-> _search_where($key, $val, $status , $where);
        $this->db->from($this->_table);
        $count = $this->db->count_all_results();
        return $count;
    }

    /**
     * 查询的条件
     * @param string $key 搜索类型
     * @param string $val 搜索关键字
     * @param string $status 订单状态
     * @param string $where 其它条件
     */
    private function _search_where($key = '', $val = '', $status = '', $where = ''){
        if(''!==$key && ''!==$val){
            switch ($key){
                case 'oid':
                    $this->db->where('stages_order.oid', $val);
                    break;
                case 'trade_no':
                    $this->db->where('stages_order.trade_no', $val);
                    break;
                case 'gid':
                    $this->db->where('stages_order.gid', $val);
                    break;
                case 'title':
                    $this->db->like('stages_order.title', $val);
                    break;
                case 'buyer_uname':
                    $this->db->like('stages_order.buyer_uname', $val);
                    break;
                default:
                    break;
            }
        }
        if('' !== $status){
            $this->db->where('stages_order.state', $status);
        }
        if('' !== $where){
            if (is_array($where)) {
                foreach ($where as $k=>$v){
                    if(is_numeric($k)){
                        $this->db->where($v);
                        continue;
                    }
                    if(is_array($v)){
                        $this->db->where($k, array_shift($v), (boolean)array_shift($v));
                    }elseif (is_string($v) || is_numeric($v)){
                        $this->db->where($k, $v);
                    }
                }
            }elseif (is_string($where)){
                $this->db->where($where);
            }
        }
    }

    /**
     * 重写父类 get方法
     * @param int $oid  订单号
     * @return array
     */
    public function get($oid) {
        return $this->db->where('oid', $oid)->get('stages_order')->row_array();
    }

    /**
     * @param $data array();
     * @param $int $oid  订单id
     * @return bool|void
     */
    public function update($oid,$data = array()){
        return $this->db->where('oid',$oid)->update($this->_table,$data);
    }

    /**
     * 管理员确认付款操作
     * @param $oid  int 分期订单id
     * @param $uanme  string 当前操作用户名
     * @param $uid   int 当前操作用户uid
     * @param $content  string 操作内容
     * @param $ip   int 当前用户ip地址
     */
    public function confirm_pay($oid,$uanme,$uid,$content,$ip){
        $return = array();
        if($oid) {
            $this->db->reconnect();
            $sql = 'CALL proc_stages_order_pay_confirm(?,?,?,?,?)';
            $return = $this->db->query($sql,array($oid,$uanme,$uid,$content,$ip))->row_array();
            $this->db->reconnect();
        }

        return $return;
    }

}
