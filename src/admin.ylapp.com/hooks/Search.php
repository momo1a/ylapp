<?php
/**
 * 搜索钩子
 * User: Administrator
 * Date: 2016/11/11
 * Time: 13:51
 */
class Search
{
    protected  $CI = null;

    public function __construct(){

        if(!$this->CI){
            $this->CI = &get_instance();
        }
    }


    public function searchSubmit(){
        //TODO
    }
}