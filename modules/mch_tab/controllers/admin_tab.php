<?php
class Admin_tab_Controller extends Template_Controller
{	
	public $template = 'admin/index';	
	
    public function __construct()
    {
    	parent::__construct();
		
		$this->page_model = new Page_Model();	
		
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
		if($this->session->get('input_data'))
		{
			$indata = $this->session->get('input_data');
			
			if (isset($indata['txt_name'])) $this->mr['templates_name'] = $indata['txt_name'];
			if (isset($indata['sel_status'])) $this->mr['templates_status'] = $indata['sel_status'];
		}		
	}
	
	public function config($id)
	{
		$this->template->content = new View(uri::segment(1).'/frm');		
		
		$page = $this->page_model->get_page_lang($this->get_admin_lang(), $id);
		
		if (empty($page))
		{
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
			
			url::redirect('admin_page');
			die();
		}
		
		$list_pid = $this->page_model->get_page_type(array('news','general','bbs'));		
		
		if (empty($list_pid)) $list_page = $list_pid;
		else
		{
			$this->page_model->set_query('where','page_status',1);
			$list_page = $this->page_model->get_page_lang($this->get_admin_lang(), $list_pid);
		}
		
		$this->mr['title'] = $page['page_title'].' -> '.Kohana::lang('global_lang.lbl_edit');
		$this->mr['arr_pid'] = explode('|', $page['page_content']);
		
		$this->template->content->mr = array_merge($this->mr, $page);
		$this->template->content->mlist = $list_page;
		//echo Kohana::debug($list_page, $list_pid);die();
	}
	public function save()
	{
		$sel_pid = $this->input->post('sel_pid');
		$pid = $this->input->post('hd_id');
		
		$set = array(
			'page_content' => implode('|', $sel_pid==NULL ? array() : $sel_pid)
		);
		
		$result = Model::factory('page_description')->update_mod($set, array('page_id' => $pid));
		
		if ($result)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_update'));
		else
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.error_data_update'));
		
		url::redirect(uri::segment(1).'/config/'.$pid);
		die();
	}
	
}
?>