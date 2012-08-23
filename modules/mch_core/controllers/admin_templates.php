<?php
class Admin_templates_Controller extends Template_Controller
{
	public $template = 'admin/index';	
	
    public function __construct()
    {
    	parent::__construct();
		
		$this->templates_model = new templates_Model();
		
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
	
	public function index()
	{
		$this->template->content = new View('admin_templates/list');
		
		$this->mlist = $this->templates_model->get_mod();		
		
		//Pagination
    	$this->pagination = new Pagination(array(
    		'base_url'    => uri::segment(1).'/search',
		    'uri_segment'    => 'page',
		    'total_items'    => count($this->mlist),
		    'items_per_page' => $this->site['config']['ADMIN_NUM_LINE'],
		    'style'          => 'digg',
		));
		$this->templates_model->limit_mod($this->pagination->items_per_page,$this->pagination->sql_offset);
		$this->mlist = $this->templates_model->get_mod();
		
		$this->mr['title'] = Kohana::lang('themes_templates_lang.tt_templates');
		
		$this->template->content->mr = $this->mr;
		$this->template->content->mlist = $this->mlist;
	}
	
	public function create()
	{
		$this->template->content = new View('admin_templates/frm');
		
		$this->mr['title'] = Kohana::lang('themes_templates_lang.tt_templates').' :: '.Kohana::lang('global_lang.lbl_create');
		
		$this->template->content->mr = $this->mr;
	}
	
	public function edit($id)
	{
		$this->template->content = new View('admin_templates/frm');
		
		$this->mr = array_merge($this->mr, $this->templates_model->get_mod($id));
		$this->mr['title'] = Kohana::lang('themes_templates_lang.tt_templates').' :: '.Kohana::lang('global_lang.lbl_edit');
		
		$this->template->content->mr = $this->mr;		
	}
	
	private function _get_frm_valid()
	{
		$file_type = ORM::factory('file_type_orm')->where('file_type_detail','compress')->find();
		$hd_id = $this->input->post('hd_id');
	
		$form = Array 
		(
			'hd_id' => '',
			'txt_name' => '',			
			'sel_status' => '',
			'attach_file' => ''					
		);
		
		$errors = $form;
		
		$post = new Validation(array_merge($_POST,$_FILES));
		
		$post->pre_filter('trim', TRUE);
		$post->add_rules('txt_name', 'required', 'length[1,200]');
		if (empty($hd_id))	$post->add_rules('attach_file', 'upload::required');	
		$post->add_rules('attach_file','upload::type['.$file_type->file_type_ext.']','upload::size[10M]');	
			
		$form = arr::overwrite($form,$post->as_array());
		
		if ($post->validate())		// Data validation	
			return $form;
		else
		{
			$this->session->set_flash('input_data',$form);
			
			$errors = arr::overwrite($errors, $post->errors('templates_templates_validation'));
			$str_error = '';
				
			foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
			$this->session->set_flash('error_msg',$str_error);
			
			url::redirect($this->site['history']['current']);
			die();
		}
	}
	
	public function save()
	{
		$zip_file_id = ORM::factory('file_type_orm')->where('file_type_detail','compress')->find()->file_type_id;		
		$msg = array('error' => '', 'success' => '');	
		$frm = $this->_get_frm_valid();
		$path_templates = APPPATH.'views/templates/';
		$zip = new ZipArchive();
		
		$set = array(
			'templates_name' => $frm['txt_name'],
			'templates_status' => $frm['sel_status']			
		);
		
		if (isset($frm['attach_file']['error']) && $frm['attach_file']['error'] == 0)
		{
			$path_file = Upload::save('attach_file', NULL, $path_templates);			
			
			if ($zip->open($path_file) === TRUE)
			{
				$dir_name = substr($zip->getNameIndex(0),0,-1);
				
				if (array_search($dir_name, $dir_themes) === FALSE)
				{
					if (!$zip->extractTo($path_templates)) 
					{	
						$msg['error'] = Kohana::lang('errormsg_lang.error_file_upload');
					}
					else
					{
						$set['templates_dir'] = $dir_name;
						$msg['success'] = Kohana::lang('errormsg_lang.msg_file_up').'<br>';
						
						@unlink($path_file);
					}
				}
				else $msg['error'] = Kohana::lang('errormsg_lang.error_dir_exist');
			}
			else $msg['error'] = Kohana::lang('errormsg_lang.error_file_upload');
		}
		elseif (isset($frm['attach_file']['error']) && $frm['attach_file']['error'] != 4)
		{
			$msg['error'] = Kohana::lang('errormsg_lang.error_file_upload');			
		}
		
		if ($msg['error'] !== '')
		{
			$this->session->set_flash('error_msg', $msg['error']);
			
			url::redirect($this->site['history']['current']);
			die();
		}
		
		if (empty($frm['hd_id'])) // create new
		{
			if ($this->templates_model->insert_mod($set))
				$msg['success'] .= Kohana::lang('errormsg_lang.msg_data_add').'<br>';
			else
			{
				$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_add_save'));
				url::redirect($this->site['history']['current']);
			}
		}
		else // edit
		{
			// delete old templates
			$templates = $this->templates_model->get_mod($frm['hd_id']);
			if (isset($set['templates_dir']) && $set['templates_dir'] != $templates['templates_dir'])
				$this->rrmdir($path_templates.$templates['templates_dir']);
			
			if ($this->templates_model->update_mod($set, array('templates_id' => $frm['hd_id'])))
				$msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save').'<br>';
			else
				$msg['error'] .= Kohana::lang('errormsg_lang.error_data_save').'<br>';
		}
		
		if ($msg['error'] !== '')
			$this->session->set_flash('error_msg', $msg['error']);
			
		if ($msg['success'] !== '')
			$this->session->set_flash('success_msg', $msg['success']);
		
		if (isset($_POST['btn_save_add']))
			url::redirect(uri::segment(1).'/create');
		elseif (!empty($frm['hd_id'])) // edit		
			url::redirect($this->site['history']['current']);
		else	// create new
			url::redirect(uri::segment(1));
		
		die();
	}
	
	public function change_status($id)
	{
		$templates = ORM::factory('templates_orm', $id);
		
		if (empty($templates->templates_id))
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
		else
		{
			$templates->templates_status = abs(1 - $templates->templates_status);
			$templates->save();
			
			if ($templates->saved)
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}
		
		url::redirect($this->site['history']['back']);
		die();
	}
	
	public function delete($id)
	{
		$templates = ORM::factory('templates_orm', $id);
		
		if (empty($templates->templates_id))
		{
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
		}
		elseif ($templates->templates_dir == $this->site['config']['TEMPLATE'])
		{
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_template_used'));
		}
		else
		{
			$this->rrmdir(APPPATH.'views/templates/'.$templates->templates_dir);
			$templates->delete();
			
			if (empty($templates->templates_id))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}
		
		url::redirect($this->site['history']['current']);
		die();
	}
	
	public function change_default($id)
	{
		$templates = ORM::factory('templates_orm', $id);
		
		if (empty($templates->templates_id))
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_parameter'));
		else
		{
			$result = Model::factory('configuration')->update_value('TEMPLATE', $templates->templates_dir);
			
			if ($result->count() > 0)
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
			else
				$this->session->set_flash('error_msg',Kohana::lang('errormsg_lang.error_data_save'));
		}
		
		url::redirect($this->site['history']['back']);
		die();
	}
}
?>