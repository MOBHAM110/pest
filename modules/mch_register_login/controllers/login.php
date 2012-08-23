<?php
class Login_Controller extends Template_Controller {
	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');		
		$this->template->layout = $this->get_MCH_layout(); // init layout for template controller 		
		
		$this->_get_session_template();
		
		$this->mr = array_merge($this->mr, $this->page_model->get_page_lang($this->get_client_lang(),$this->page_id));	
	}
	
	public function __call($method, $arguments)
	{		
		if (!empty($this->warning) && !empty($this->page_id))
		{
			$this->warning_msg($this->warning);
		}
		else
		{			
			switch ($method)
			{
				case 'index' : 
					if (Login_Model::get('customer') === FALSE) $this->index();
					else 
					{
						url::redirect('mypage');
						die();
					}	
				break;
				
				case 'check_login' : $this->check_login(); break;
				
				case 'log_out' : $this->log_out(); break;
				
				default : $this->warning_msg('wrong_pid');	
			}
		}
	}
	
	private function _get_session_template()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
	}
		
	private	function index()
	{		
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/login/index');		
			
		$this->template->content->mr = $this->mr;		
	}
	
	private function check_login()
	{	
		$login = $this->input->post('txt_username','',TRUE);	// security input data
		$pass = md5($this->input->post('txt_pass'));			// encrypt md5 input password
		
		$valid = Model::factory('user')->account_exist($login, $pass);
		
		if($valid !== FALSE)		// if login success	
		{
			if(!$valid['user_status'])	// if account status is inactive
			{
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.msg_inactive_error'));							
				url::redirect($this->site['history']['current']);
				die();				
			}
			else
			{								
				$sess['id'] = $valid['user_id'];
				$sess['level'] = $valid['user_level'];
				$sess['username'] = $valid['user_name'];				
				$sess['email'] = $valid['user_email'];
				
				Login_Model::set('customer',$sess);		
		
				url::redirect($this->site['history']['current']);			
				die();				
			}
		}
		else
		{					
			$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_login_pass'));		
				
			url::redirect($this->site['history']['current']);
			die();
		}		
	}
		
	private function log_out()
	{	
		Login_Model::log_out();
		
		url::redirect($this->site['history']['current']);
		
		die();
	}	
}
?>