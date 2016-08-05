<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Doctor_info_model extends MY_Model
{
    public static $table_name = 'doctor_info';

    protected static $_prefix = 'YL_';

    public function __construct(){
        parent::__construct();
    }

}
