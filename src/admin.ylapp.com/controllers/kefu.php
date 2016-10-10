<?php
/**
 * Created by PhpStorm.
 * User: Administrator
 * Date: 2016/10/10
 * Time: 15:30
 */

class Kefu extends CI_Controller
{

    public function __construct(){
        parent::__construct();
        $this->load->library('aliyun/Test',null,'test');
    }

    public function index(){

    }
}