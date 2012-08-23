<?php
class Forgotpass_Controller extends Template_Controller 
{
	public $template;
	
    public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
        $this->template->layout = $this->get_MCH_layout();	// init layout for template controller
        
        $this->_get_session_msg();
		
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
						
			if (isset($indata['txt_email'])) $this->mr['txt_email'] = $indata['txt_email'];			
		}
	}
	
	public function __call($method, $arguments)
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
    
    private function index()
    {	
       $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/login/frm_forgetpass');
	   
	   $this->mr['page_title'] = Kohana::lang('account_lang.tt_forgot_pass');
	   
	   $this->template->content->mr = $this->mr;		
    }
	
	private function send_email($result)
    {
    	// Create new password for customer
    	$user = ORM::factory('user_orm', $result['user_id']);
    	$new_pass = text::random('alnum',8);
    	$user->user_pass = md5($new_pass);
    	$user->save();
    	
		//Use connect() method to load Swiftmailer
		$swift = email::connect();
		 
		//From, subject
		$from = $this->site['site_email'];
		$subject = Kohana::lang('login_lang.lbl_forgotpass').' '.$this->site['site_name'];
		
		//HTML message
		$html_content = Data_template_Model::get_value('EMAIL_FORGOTPASS',$this->get_client_lang()); 
		//Replate content	
		$name = $result['customers_firstname'].' '.$result['customers_lastname'];
				
		$html_content = str_replace('#name#',$name,$html_content);
        $html_content = str_replace('#sitename#',$this->site['site_name'],$html_content);		
		$html_content = str_replace('#username#',$result['user_name'],$html_content);			
		$html_content = str_replace('#password#',$new_pass,$html_content);

		//Build recipient lists
		$recipients = new Swift_RecipientList;
		$recipients->addTo($result['user_email']);
		 
		 //Build the HTML message
		$message = new Swift_Message($subject, $html_content, "text/html");
		 
		if($swift->send($message, $recipients, $from))		
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.info_mail_change_pass'));
		else
	 		$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_send_email'));
		
		//Disconnect
		$swift->disconnect();
    }
    
    public function _check_email($array,$field)
	{
		if (!Model::factory('user')->field_value_exist('user_email', $array[$field]))
		{
			$array->add_error($field,'_check_email');
		}	
	}
    
	private function _get_frm_valid()
    {
    	$form = array
	    (
	       'txt_email' => ''	        
	    );
		
		$errors = $form;
		
		if($_POST)
    	{
    		$post = new Validation($_POST);
    		
			$post->pre_filter('trim', TRUE);
			$post->add_rules('txt_email','required','email');
			$post->add_callbacks('txt_email', array($this, '_check_email'));
			
			if($post->validate())
 			{
 				$form = arr::overwrite($form, $post->as_array());
 				return $form; 				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());	// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('login_lang'));
				$str_error = '';
				
				foreach ($errors as $name) if($name) $str_error .= $name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect(uri::segment(1));
				die();
			}
        }
    }
	
	private function save()
    {
		$frm = $this->_get_frm_valid();
		$user_id = ORM::factory('user_orm')->where(array('user_email' => $frm['txt_email'],'user_level>' => '2'))->find()->user_id;		
	
		$result = Model::factory('customer')->get($user_id);
		
        if($result && $_SERVER['SERVER_NAME'] != "localhost")
                $this->send_email($result);
				
		url::redirect(uri::segment(1));
		die();	
	}
}
?>