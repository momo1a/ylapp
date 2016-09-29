<?php
/**
 * 提现model
 * User: momo1a@qq.com
 * Date: 2016/8/16
 * Time: 9:06
 */

class  Take_cash_model extends MY_Model
{
    public static $table_name = 'take_cash';

    /**
     * 提现列表
     * @param string $keyword
     * @param int $userType
     * @param int $status
     * @param int $limit
     * @param int $offset
     */
    public function cashList($keyword='',$userType=0,$status = -1,$limit=10,$offset=0){

    }

    /**
     * 列表统计
     * @param string $keyword
     * @param int $userType
     * @param int $status
     */
    public function cashCount($keyword='',$userType=0,$status = -1){

    }
}