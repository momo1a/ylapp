<?php (defined('BASEPATH')) OR exit('No direct script access allowed');

require COMPATH."core/ZHS_Controller.php";

class MY_Controller extends ZHS_Controller
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->model('user_model');
		$this->load->helper('url');
		$this->load->library('auth');
	}
}

/* End of file MY_Controller.php */
/* Location: ./application/core/MY_Controller.php */