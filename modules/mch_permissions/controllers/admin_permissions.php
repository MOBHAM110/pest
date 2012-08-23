<?php
class Admin_permissions_Controller extends Template_Controller {
	
	var $mr;
	var $mlist;
	
	public $template = 'admin/index';
	
	public function __construct()
    {
		parent::__construct(); // This must be included
		$this->permissions_moldel = new Permissions_Model();
		
		$this->_get_session_msg();
    }
	
	public function _get_session_msg()
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
	
	public function __call($method, $arguments)
	{
		$this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
		
		$this->index();
	}

	public function index()
	{
		$this->search['keyword'] = '';
		$this->showlist(); 
	}
	
	//Search
	public function search() {
		$txt_keyword = $this->input->post('txt_keyword');	// new key search
		
    	if ($txt_keyword !== NULL)	// if new search key exist
			$this->search['keyword'] = $txt_keyword;		
			
		//Set
		$this->session->set_flash('sess_search',$this->search);
		
		$this->showlist();
	}
	
	// Khởi tạo permis
	public function create() {
		if(!$this->permisController('add')) {
    		$this->session->set_flash('error_msg',Kohana::lang('global_lang.authorized'));
			url::redirect($this->site['history']['back']);
			die();
		} else {
			$this->template->content = new View('admin_permissions/frm');
		}
	}
	
	//Updata permis
	public function update() {
		$this->template->content = new View('admin_permissions/list');
	}
	
	//Luu vào database
	public function save(){
		if (empty($_POST)) url::redirect('admin_permissions');
		$frm = $this->_get_frm_valid();
		
		
		if(empty($frm['hd_id'])) {
			$permis = ORM::factory('permissions_orm');
		} else {
			$permis = ORM::factory('permissions_orm', $frm['hd_id']);
		}
		
		$permis->permissions_code = $frm['txt_code'];
		$permis->permissions_name = $frm['txt_name'];
		$permis->permissions_status = $frm['sel_status'];
		$permis->save();
		
		if($permis->saved){
			$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
		} else {
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
		}
		url::redirect($this->site['history']['back']);
		die();
	}
	
	public function edit($id)
    {
		$this->template->content = new View('admin_permissions/frm');   
		$this->template->content->mr = $this->permissions_moldel->get($id);
    }
	
	public function action()
    {	
		if ($this->input->post('chk_id'))	// If select checkbox
    	{			
			$select_action = $this->input->post('sel_action');
    		$chk_id = $this->input->post('chk_id');
    		
    		if ($select_action == 'delete')	// If select action delete
    			$this->delete($chk_id);
    		else	// if select action active or inactive 		
				$this->setstatus($chk_id, $select_action=='active' ? 1 : 0);			
    	}
    	
    	url::redirect('admin_permissions');
    	die();
    }

/****************************************************/
/*				FUNCTION SUPPORT					*/
/****************************************************/

	public function delete($id)
    {			
		if(!$this->permisController('delete')) {
    		$this->session->set_flash('error_msg',Kohana::lang('global_lang.authorized'));
			url::redirect(uri::segment(1));
			die();
		} else {
			if ($this->permissions_moldel->delete($id))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_del'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_del'));    	
				
			url::redirect(uri::segment(1));
			die();
		}
    }

	public function setstatus($id,$status)
    { 
		if(!$this->permisController('edit')) {
    		$this->session->set_flash('error_msg',Kohana::lang('global_lang.authorized'));
			url::redirect(uri::segment(1));
			die();
		} else {
			if ($this->permissions_moldel->set_status($id, $status))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
			
			url::redirect(uri::segment(1));
			die();
		}
    }
	
	private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
    	//Khởi tạo valid
		$form = array 
		(
			'hd_id'=> '',
			'txt_name' => '',
			'txt_code' => '',
			'sel_status' =>''
		);
		$errors = $form;
		
		if ($_POST)
		{
			//Load validation
			$post = new Validation($_POST);
			//Setting rules
			$post->pre_filter('trim', TRUE);			
			$post->add_rules('txt_name','required','length[2,50]');			
			$post->add_rules('txt_code','required','length[2,50]');
			if(empty($hd_id)) {
				$post->add_callbacks('txt_code',array($this,'_check_code'));
			}
			//Check valid form
			if ($post->validate())
			{
				$form = arr::overwrite($form, $post->as_array());
 				return $form;				
			}
			else
			{
				$form = arr::overwrite($form,$post->as_array());		// Retrieve input data
				$this->session->set_flash('input_data',$form);		// Set input data in session
				
				$errors = arr::overwrite($errors, $post->errors('permissions_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect($this->site['history']['current']);
				die();
			}
		}
    }
	
	public function _check_code($array,$field)
	{		
		if (Model::factory('permissions')->field_value_exist('permissions_code', $array[$field]))
		{
			$array->add_error($field,'_check_code');
		}			
	}
	
	//Get List permis
	private function showlist()
    {
		$this->template->content = new View('admin_permissions/list');
			
		//Assign
		$this->template->content->keyword = $this->search['keyword'];
		
		if ($this->search['keyword'] != "")
		{														
			$this->permissions_moldel->search($this->search);			
		}
		
		$total_items = count($this->permissions_moldel->get());
				
		//Pagination
		$this->pagination = new Pagination(array(
			'base_url'    => 'admin_permissions/search/',
			'uri_segment'    => 'page',
			'total_items'    => $total_items,
			'items_per_page' => 100,
			'style'          => 'classic',
		));		
		$this->permissions_moldel->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
		
		$this->permissions_moldel->search($this->search);
		
		$this->template->content->mlist = $this->permissions_moldel->get();
    }
	
	public function sort_order($action, $id)
	{
		$list_file = ORM::factory('permissions_orm')->orderby('permissions_order','asc')->find_all()->as_array();

		
		for($i=0;$i<count($list_file);$i++){
			//echo $list_file[$i]->permissions_id.'<br />';
			$this->db->update('permissions',array('permissions_order' => $i+1),array('permissions_id' =>$list_file[$i]->permissions_id));
		}
		
		//die();
		foreach ($list_file as $i => $file)
		{
			if ($file->permissions_id == $id)
			{
				$cur_file = $file;
				if ($action == 'down'){
					$j=$i+1;
					$change_file = $list_file[$j];
				} 
				else {
				$j=$i-1;
				$change_file = $list_file[$j];
				}
			}
		}
		
		$tmp = $cur_file->permissions_order;
		$cur_file->permissions_order = $change_file->permissions_order;
		$change_file->permissions_order = $tmp;
		
		$cur_file->save();
		$change_file->save();
		
		url::redirect('admin_permissions');
		die();
	}
	
	public function sort_top($id)
	{
		$list_file = ORM::factory('permissions_orm')->orderby('permissions_order','asc')->find_all()->as_array();
		
		for($i=0;$i<count($list_file);$i++){
			$this->db->update('permissions',array('permissions_order' => $i+1),array('permissions_id' =>$list_file[$i]->permissions_id));
		}
		//die();
		foreach ($list_file as $i => $file)
		{
			if ($file->permissions_id == $id)
			{
				$cur_file = $file;
					$j=0;
					$change_file = $list_file[$j];
				
			}
		}
		
		$tmp = $cur_file->permissions_order;
		$cur_file->permissions_order = $change_file->permissions_order;
		$change_file->permissions_order = $tmp;
		$cur_file->save();
		$change_file->save();
		
		$list_file = ORM::factory('permissions_orm')->orderby('permissions_order','asc')->find_all()->as_array();
		
		for($i=0;$i<count($list_file);$i++){
			$this->db->update('permissions',array('permissions_order' => $i+1),array('permissions_id' =>$list_file[$i]->permissions_id));
		}
		
		url::redirect('admin_permissions');
		die();
	}
	
}//Ket thuc class