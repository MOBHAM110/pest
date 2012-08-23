<?php
class Mypage_Controller extends Template_Controller 
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
			if (isset($indata['txt_first_name'])) $this->mr['customers_firstname'] = $indata['txt_first_name'];
			if (isset($indata['txt_last_name'])) $this->mr['customers_lastname'] = $indata['txt_last_name'];
			if (isset($indata['txt_email'])) $this->mr['user_email'] = $indata['txt_email'];
			if (isset($indata['txt_address'])) $this->mr['customers_address'] = $indata['txt_address'];		
			if (isset($indata['txt_phone'])) $this->mr['customers_phone'] = $indata['txt_phone'];
		}	
			
	}
	
	public function __call($method, $arguments)
	{
		if (Login_Model::get('customer') === FALSE)		
		{	
			$this->warning_msg('restrict_access');
		}
		else
		{		
			switch ($method)
			{			
				case 'index' : $this->index(); break;
				
				case 'viewaccount' : $this->viewaccount(); break;
				
				case 'update_account' :
					if (isset($_POST)) $this->update_account();
					else $this->warning_msg('wrong_pid');
					break;
				
				case 'calendar' : 
				{
					if ($this->site['config']['GOOGLE_CALENDAR']) $this->show_google_calendar();
					else $this->warning_msg('restrict_access');
					break;
				}
				
				default : $this->warning_msg('wrong_pid');	
			}			
		}		
	}
    public function index()
    {
         $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/mypage/index'); 
    }
    private function viewaccount()
    { 	
        $this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/mypage/info'); 
        $mr = ORM::factory('customer')->get($this->sess_cus['id']);
    
      	$this->mr = array_merge($this->mr, $mr);
		  
		$email = explode('@', $this->mr['user_email']);
		if ($email[0] == 'gmail.com')
		{			
			$this->mr['calendar'] = true;
		}
		
		$this->mr['page_title'] = Kohana::lang('account_lang.tt_my_account'); 	
      
      	$this->template->content->mr = $this->mr;
 
    }   
	
	public function _check_email($array,$field)
	{
		if (Model::factory('user')->field_value_exist('user_email', $array[$field]))
		{
			$array->add_error($field,'_check_email');
		}	
	}
	
	public function _check_old_pass($array,$field)
	{
		$old_pass = ORM::factory('user_orm')->find($this->sess_cus['id'])->user_pass;
		
		if ($old_pass !== md5($array[$field]))
		{
			$array->add_error($field,'_check_old_pass');
		}
	}
    
    private function _get_myacc_valid()
    {
    	$old_pass = $this->input->post('txt_old_pass');
    	$new_pass = $this->input->post('txt_new_pass');
		
		$form = Array
    	(
    		'txt_first_name' => '',
    		'txt_last_name' => '',
    		'txt_email' => '',
    		'txt_address' => '',
    		'txt_city' => '',
    		'txt_state' => '',
    		'txt_zipcode' => '',
    		'txt_phone' => '',    		
    		'txt_old_pass' => '',
			'txt_new_pass' => '',
			'txt_cf_new_pass' => ''
		);
		
		$errors = $form;
		
		if ($_POST)
		{
			$post = new Validation($_POST);
			
			$post->pre_filter('trim', TRUE);			
			$post->add_rules('txt_email','required','email');		
			$post->add_rules('txt_first_name','length[1,50]');
			$post->add_rules('txt_last_name','length[1,50]');			
			
			if ($this->sess_cus['email'] !== $this->input->post('txt_email'))
				$post->add_callbacks('txt_email',array($this,'_check_email'));
						
			if (!empty($old_pass) || !empty($new_pass))
			{				
				$post->add_rules('txt_new_pass','required','length[5,50]');
				$post->add_rules('txt_cf_new_pass','matches[txt_new_pass]');
				$post->add_callbacks('txt_old_pass',array($this,'_check_old_pass'));
			}
			
			if ($post->validate())
			{
				$form = arr::overwrite($form, $post->as_array());
 				return $form;				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('account_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.='<br>'.$name;
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect('mypage/viewaccount');
				die();
			}
		}
    }
    
    private function update_account()
    {
    	$old_pass = $this->input->post('txt_old_pass');
        $frm = $this->_get_myacc_valid();
		
		$sess_cus = Login_Model::get('customer');
		
		if ($sess_cus !== FALSE)
		{
			$user = ORM::factory('user_orm', $sess_cus['id']);
			$user->user_email = $frm['txt_email'];
			if (!empty($old_pass))
			{
				$user->user_pass = md5($frm['txt_new_pass']);
				$this->session->set_flash('info_msg',Kohana::lang('errormsg_lang.msg_change_pass'));
			}
			$user->save();
			
			$set = array(
				'user_id' => $user->user_id,			
				'customers_firstname' => $frm['txt_first_name'],
				'customers_lastname' => $frm['txt_last_name'],		
				'customers_address' => $frm['txt_address'],
				'customers_city' => $frm['txt_city'],
				'customers_state' => $frm['txt_state'],
				'customers_zipcode' => $frm['txt_zipcode'],
				'customers_phone' => $frm['txt_phone']	            		
			);		
			
			$result = Model::factory('customer')->update_mod($set, array('user_id' => $sess_cus['id']));
			
			if ($result || $user->saved)
				$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
			else
				$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
			
			url::redirect('mypage/viewaccount');
			die();
		}
		else
		{
			$this->warning_msg('restrict_access');
		}			
    }
	
	private function show_google_calendar()
	{
		$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/mypage/google_calendar');
		
		$cus_email = ORM::factory('user_orm',$this->sess_cus['id'])->user_email;
		$email = explode('@', $cus_email);
		
		if ($email[1] == 'gmail.com' && $this->site['config']['GOOGLE_CALENDAR'])
		{
			$this->mr['email'] = str_replace('@', '%40', $cus_email);
			$this->mr['calendar'] = true;		
		}
		else $this->mr['calendar'] = false;
		
		$this->template->content->mr = $this->mr;
	} 
}
?>