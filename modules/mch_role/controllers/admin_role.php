<?php
class Admin_role_Controller extends Template_Controller {
	
	var $mr;
	var $mlist;
	var $mRole_Permis;
	public $template = 'admin/index';
	
	public function __construct()
    {
		parent::__construct(); // This must be included
		$this->role_model = new Role_Model();
		$this->permissions_moldel = new Permissions_Model();
		$this->role_perms_model = new Role_perms_Model();
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
		$this->template->content = new View('admin_role/frm');
		$this->template->content->mlist = $this->permissions_moldel->get_with_active();
	}
	
	//Updata permis
	public function update() {
		$this->template->content = new View('admin_role/list');
	}
	
	//Luu vào database
	public function save(){
		if (empty($_POST)) url::redirect('admin_role');
		$values = $this->input->post('group');
		
		//Validation save
		$frm = $this->_get_frm_valid();
		//Create Role
		if(empty($frm['hd_id'])) {
			$role = ORM::factory('role_orm');
		} else {
			$role = ORM::factory('role_orm', $frm['hd_id']);
		}
		$role->role_name 	= $frm['txt_name'];
		$role->role_status 	= $frm['sel_status'];
		$role->save();
		
		if($role->saved) {
			
			// Xu ly values
			$values = $this->_get_Values_Permis($values);
			
			if(empty($frm['hd_group'])) {
				foreach($values as $rootID => $rootValues)
				{
					$role_perms = ORM::factory('role_perms_orm');
					$role_perms->role_id 		= $role->role_id;
					$role_perms->permission_id 	= $rootID;
					//$role_perms->role_perms_value = implode('|',$rootValues);
					$role_perms->role_perms_value = $this->_return_value($rootValues);
					$role_perms->save();
				}
			} else {
				$this->role_perms_model->delete($role->role_id,$frm['hd_group']);
				foreach($values as $rootID => $rootValues)
				{
					$role_perms = ORM::factory('role_perms_orm');
					$role_perms->role_id 		= $role->role_id;
					$role_perms->permission_id 	= $rootID;
					//$role_perms->role_perms_value = implode('|',$rootValues);
					$role_perms->role_perms_value = $this->_return_value($rootValues);
					$role_perms->save();
				}
			}
			
			
		}
		//die();
		
		if ($role->saved || $role_perms->saved)
			$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_save'));
		else
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_save'));
		url::redirect($this->site['history']['back']);
		die();
		
	}
	
	public function edit($id)
    {
    	$this->template->content = new View('admin_role/frm');   
    	
    	$this->template->content->mr = $this->role_model->get($id);
		$this->template->content->mlist = $this->permissions_moldel->get_with_active();
		$this->template->content->mRole_Permis = $this->role_perms_model->getByRoleID($id);
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
    	
    	url::redirect('admin_role');
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
			if ($this->role_model->delete($id) && $this->role_perms_model->delete($id))
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
			if ($this->role_model->set_status($id, $status))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
			
			url::redirect(uri::segment(1)); 
			die();
		}
    }
	
	//Check name role
	public function _check_role($array,$field)
	{		
		if (Model::factory('role')->field_value_exist('role_name', $array[$field]))
		{
			$array->add_error($field,'_check_role');
		}			
	}
	
	//Validation Save
	private function _get_frm_valid()
    {
    	$hd_id = $this->input->post('hd_id');
    	//Khởi tạo valid
		$form = array 
		(
			'hd_id'=> '',
			'hd_group' => '',
			'txt_name' => '',
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
			if(empty($hd_id)) {
				$post->add_callbacks('txt_name',array($this,'_check_role'));
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
				
				$errors = arr::overwrite($errors, $post->errors('role_validation'));
				$str_error = '';
				
				foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
				$this->session->set_flash('error_msg',$str_error);
				
				url::redirect($this->site['history']['current']);
				die();
			}
		}
    }
	
	//Get Values Permis of Role
	private function _get_Values_Permis($values)
	{
		$data = array();
		$arr = array();
		for($i= 0; $i < count($values); $i++) {
			$arr[] = explode('-',$values[$i]);
		}
		for($y = 0; $y < count($arr);$y++) {
			if( isset($data[ $arr[$y][0] ]) )
			{
				
				if($data[$arr[$y][0]] == $arr[$y][0]) { // kiem tra trong 
					$data[$arr[$y][0]][] = $arr[$y][1];
				} else {
					$data[$arr[$y][0]][] = $arr[$y][1];
				}
				
			} else {
				
				$data[$arr[$y][0]][]= $arr[$y][1];	
				
			}	
		}
		return $data;
	}
	//FUNCTION CHECK ROLE DELETE EDIT ADD VIEW
	private function _return_value($op) {
		switch($op[0]) {
			case 5:
				return '2|3|4|5';
				break;
			case 4:
				return '2|3|4';
				break;
			case 3:
				return '2|3';
				break;
			default :
				return '2';
				break;
		}
	}
	
	
	// Lay toan bo role
	private function showlist()
    {
    	$this->template->content = new View('admin_role/list');
    		
		//Assign
		$this->template->content->keyword = $this->search['keyword'];
		
		if ($this->search['keyword'] != "")
		{														
			$this->role_model->search($this->search);			
		}
		
		$total_items = count($this->role_model->get());
				
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => 'admin_role/search/',
		    'uri_segment'    => 'page',
		    'total_items'    => $total_items,
		    'items_per_page' => $this->site['config']['ADMIN_NUM_LINE'],
		    'style'          => 'classic',
		));		
		$this->role_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);	
    	
    	$this->role_model->search($this->search);
    	
    	$this->template->content->mlist = $this->role_model->get();
    }
	
}//Ket thuc class