<?php
class Admin_contact_Controller extends Template_Controller
{	
	public $template = 'admin/index';	
	
    public function __construct()
    {
    	parent::__construct();	
		
		$this->_get_session_msg();	
   	}
   	
   	public function __call($method, $arguments)
	{
		$this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
		
		$this->index();
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
	}
	
	public function index()
	{
		$this->template->content = new View(uri::segment(1).'/frm');	
		
		$mr = Model::factory('contact')->get_mod(ORM::factory('contact_orm')->find()->contact_id);
		$mr['title'] = Kohana::lang('contact_lang.tt_admin_page');		
		
		$this->template->content->mr = $mr;		
		//echo Kohana::debug($this->template->content->mr);die();
	}
	
	public function change_client_view($element)
    {
    	$contact = ORM::factory('contact_orm')->find();
    	
    	$contact->{'contact_'.$element} =  abs($contact->{'contact_'.$element} - 1);
    	$contact->save();
    	
    	if ($contact->saved)
			$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_update'));
		else
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_update'));
    	
    	url::redirect(uri::segment(1));
		die();
    }    
}
?>