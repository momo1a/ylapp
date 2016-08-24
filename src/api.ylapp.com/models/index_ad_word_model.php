<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * User: momo1a@qq.com
 * Date: 2016/8/5
 * Time: 14:50
 */
class Index_ad_word_model extends MY_Model
{
    public static $table_name = 'index_ad_word';

    public function __construct(){
        parent::__construct();
    }

    /**
     * 广告词
     * @return array
     */
    public function getWords(){
        $this->select('word,type');
        $res = $this->find_all();
        return $res;
    }
}