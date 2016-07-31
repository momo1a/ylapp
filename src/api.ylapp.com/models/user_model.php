<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Class Order_model
 */
class User_model extends MY_Model
{
    public static $table_name = 'user';


    public function getAllUsers(){
        $res = $this->find_all();
        return $res;
    }
}
