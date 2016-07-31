<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends MY_Controller 
{
	
	public function __construct()
	{
		parent::__construct();
	}
	public $check_access = FALSE;
	public $except_methods = array('index');

	/**
	 * Index Page for this controller
	 */
	public function index()
	{
		$this->load->view('welcome/index');
	}
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */