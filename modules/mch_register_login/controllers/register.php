<?php
class Register_Controller extends Template_Controller {
	
	public $template;	
	
	public function __construct()
	{
		parent::__construct();
		$this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
		$this->template->layout = $this->get_MCH_layout();	// init layout for template controller		
        
		$this->_get_session_msg();
		
		//$this->mr = array_merge($this->mr, $this->page_model->get_page_lang($this->get_admin_lang(),$this->page_id));
        $this->mr = array_merge($this->mr, $this->page_model->get_page_lang($this->get_client_lang(),$this->page_id));
        
	}
	
	private function _get_session_msg()
	{
		if($this->session->get('error_msg'))
			$this->template->error_msg = $this->session->get('error_msg');
		if($this->session->get('warning_msg'))
			$this->template->warning_msg = $this->session->get('warning_msg');
		if($this->session->get('success_msg'))
			$this->template->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->info_msg = $this->session->get('info_msg');
		if ($this->session->get('input_data'))	
		{
			$indata = $this->session->get('input_data');
			
			if (isset($indata['txt_username'])) $this->mr['txt_username'] = $indata['txt_username'];
			if (isset($indata['txt_email'])) $this->mr['txt_email'] = $indata['txt_email'];
			if (isset($indata['chk_staff'])) $this->mr['chk_staff'] = $indata['chk_staff'];
		}
	}
	
	public function __call($method, $arguments)
	{
		if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{		
			switch ($method)
			{			
				case 'index' : $this->index(); break;
				
				case 'save' : 
					if (isset($_POST)) $this->save();
					else $this->warning_msg('wrong_pid');
					break;				
				
				default : $this->warning_msg('wrong_pid');	
			}			
		}		
	} 
	
	private function index()
	{	
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/register/frm');               
        
		$this->template->content->mr = $this->mr;				
	}
	
	public function _check_user($array,$field)
	{
		if (Model::factory('user')->field_value_exist('user_name', $array[$field]))
		{
			$array->add_error($field,'_check_user');
		}	
	}	
	
	public function _check_email($array,$field)
	{
		if (Model::factory('user')->field_value_exist('user_email', $array[$field]))
		{
			$array->add_error($field,'_check_email');
		}	
	}
	
	private function _get_register_valid()
	{
		$form = array 
		(
			'txt_username' => '',
			'txt_pass' => '',
			'txt_cfpass' => '',	
			'txt_email' => '',	
			'captcha_response' => '',	
			'chk_staff' => ''				
		);
		
		$errors = $form;
		
		if ($_POST)
		{
			$post = new Validation($_POST);
			
			$post->pre_filter('trim', TRUE);			
			$post->add_rules('txt_username','required','length[3,50]');
			$post->add_rules('txt_pass','required','length[6,50]');
			$post->add_rules('txt_cfpass','required','matches[txt_pass]');
			$post->add_rules('txt_email','required','email');			
			$post->add_rules('captcha_response','required','Captcha::valid');
			
			$post->add_callbacks('txt_username',array($this,'_check_user'));
			$post->add_callbacks('txt_email',array($this,'_check_email'));			
			
			if ($post->validate())
			{
				$form = arr::overwrite($form, $post->as_array());
 				return $form;				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('register_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect('register');
				die();
			}
		}
	}
	
	
	private function save()
	{
		$frm = $this->_get_register_valid();
		
		$user = ORM::factory('user_orm');
		$user->user_pass = md5($frm['txt_pass']);
		$user->user_reg_date = time();		
		$user->user_name = $frm['txt_username'];
		$user->user_email = $frm['txt_email'];
		$user->user_level = $frm['chk_staff'] ? 3 : 4;
		$user->user_status = $this->site['config']['DEF_SREG'];
		$user->save();
		
		if ($user->saved)
		{		
			$set = array(
				'user_id' => $user->user_id							
			);		
			
			$result = Model::factory('customer')->insert_mod($set);				
			
            if($user && $frm && $_SERVER['SERVER_NAME'] != "localhost")
                $this->send_email($user,$frm);
            
			$this->thanks();			
		}
		else 
		{
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));	
		
			url::redirect(uri::segment(1));
			die();
		}
	}
	
    private function send_email($result,$frm)
    {
		//Use connect() method to load Swiftmailer
		$swift = email::connect();
		 
		//From, subject
		$from = $this->site['site_email'];
		$subject = Kohana::lang('register_lang.tt_page').' '.$this->site['site_name'];
		
		//HTML message
		$html_content = Data_template_Model::get_value('EMAIL_REGISTER',$this->get_client_lang()); 
		//Replate content	
		$name = $result->user_name;
				
		$html_content = str_replace('#name#',$name,$html_content);
        $html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);		
		$html_content = str_replace('#username#',$result->user_name,$html_content);			
		$html_content = str_replace('#password#',$frm['txt_pass'],$html_content);
        
		//Build recipient lists
		$recipients = new Swift_RecipientList;
		$recipients->addTo($result->user_email);
		$recipients->addTo($this->site['site_email']);
		 
		 //Build the HTML message
		$message = new Swift_Message($subject, $html_content, "text/html");
        $swift->send($message, $recipients, $from);
        
		//Disconnect
		$swift->disconnect();
    }
    
	private function thanks()
	{		
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/register/thanks');
		
		$this->template->content->site = $this->site;
		$this->template->content->mr = $this->mr;
	}
}
?>