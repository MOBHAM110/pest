<?php
class Admin_layout_Controller extends Template_Controller
{
	public $template = 'admin/index';
	
	public function __construct()
    {
        parent::__construct();    
		
		$this->page_model = new Page_Model();
		$this->pl_model = new Page_Layout_Model();		
		$this->header_model = new Header_Model();			
		$this->banner_model = new Banner_Model();		
		
		$this->_get_session_msg();		
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
			
			if (isset($indata['sel_hd_type'])) $this->mr['header_type'] = $indata['sel_hd_type'];
			if (isset($indata['sel_ft_type'])) $this->mr['footer_type'] = $indata['sel_ft_type'];			
		}	
	}
	
	public function __call($method, $agruments)
	{
		if ($method !== 'index')
		{
			if ($this->pl_model->has_layout($method))
				$this->edit($method);
		}
		
		$this->edit();
	}
	
	public function edit($page_id = '')	
	{
		$this->template->content = new View('admin_layout/frm');
		
		$gpl = Gpl_Model::get();
		$title = Kohana::lang('global_lang.lbl_edit').' '.Kohana::lang('layout_lang.tt_page').' :: ';
		
		if ($page_id == '' || $gpl['page_id'] == $page_id)	
		{
			$pl = $gpl;
			$this->mr['page_id'] = $pl['page_id'];
			$this->mr['page_title'] = $title.Kohana::lang('layout_lang.lbl_global_page');
		}
		else	
		{
			$pl = $this->pl_model->get_mod($page_id);
			$this->mr = array_merge($this->mr, $this->page_model->get_page_lang($this->site['lang_id'], $page_id));
			$this->mr['page_title'] = $title.$this->mr['page_title'];
		}
		
		for ($i=1;$i<=15;$i++)
		{
			$arr_tmp = $list_tmp = array();
			$pos = $this->get_position($i);
			
			if ($i <= 5)
				$this->template->content->{'arr_'.$pos.'_menu'} = explode('|',$pl[$pos.'_menu']);
			
			if ($i == 2) continue;
			
			$arr_tmp = empty($pl[$pos.'_banner']) ? array() : explode('|',$pl[$pos.'_banner'],-1);		
			
			foreach ($arr_tmp as $key => $value)
			{				
			 	$ban = Model::factory('banner')->get_mod($value);				
				
			 	if (!empty($ban)) 
			 	{
			 		$ban['banner_type'] = ORM::factory('file_type_orm', $ban['file_type_id'])->file_type_detail;
			 		$ban['path_file'] = MCH_Core::format_path(DOCROOT.'uploads/banner/'.$ban['banner_file']);
			 		$list_tmp[$key] = $ban; 
				} 
			} 
			$this->template->content->{'list_'.$pos.'_banner'} = $list_tmp;			
		}
		
		$ph = Model::factory('header')->get_mod($this->mr['page_id']);
		
		if (count($ph) < 1)
			$ph = Model::factory('header')->get_mod($gpl['page_id']);
		
		$this->template->content->header = $ph;
		$this->template->content->footer = Model::factory('footer')->get();
		$this->template->content->mr = array_merge($this->mr, $pl);		
		$this->template->content->list_multi_menu = $this->page_model->get_page_lang($this->get_admin_lang());
		$this->template->content->list_one_menu = $this->page_model->get_page_lang($this->get_admin_lang(),'','','','','page_order');
	}
	
	public function create($page_id, $init = 'global')
	{
		$pl = $this->pl_model->get_mod($page_id);
		if (count($pl) > 0)
		{
			url::redirect('admin_layout/edit/'.$page_id);
			die();
		}
		
		if (Page_Layout_Model::create($page_id, $init))
		{			
			$this->template->success_msg = Kohana::lang('errormsg_lang.msg_data_add');
			
			url::redirect('admin_layout/edit/'.$page_id);		
		}
		else
		{
			$this->template->error_msg = Kohana::lang('errormsg_lang.error_data_add');
			
			url::redirect('admin_page');
			die();
		}
	}
	
	public function delete($page_id)
	{
		if ($this->pl_model->delete($page_id))
			$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_del'));
		else
			$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_del'));
		
		url::redirect('admin_page');
		die();
	}
	
	public function set_global($page_id)
	{
		$set = Gpl_Model::get();
		unset($set['page_id']);
		
		if ($this->pl_model->update($page_id, $set))
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_update'));
		else
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.error_data_update'));
			
		url::redirect('admin_page');
		die();
	}
	
	
	public function save_menu_layout()
	{		
		$gpl = Gpl_Model::get();
		$page_id = $this->input->post('hd_id');	// page_id
		$menu = array(
			'top' => $this->input->post('sel_top_menu'),
			'center' => $this->input->post('sel_center_menu'),
			'left' => $this->input->post('sel_left_menu'),
			'right' => $this->input->post('sel_right_menu'),
			'bottom' => $this->input->post('sel_bottom_menu'),
            'left_col' => $this->input->post('sel_status_left'),
            'right_col' => $this->input->post('sel_status_right')
		);
		
		$set = array
	    (  	    	   
	        'top_menu' => implode('|', $menu['top']==NULL?array():$menu['top']),
	        'top_menu_level' => $this->input->post('sel_top_menu_level'),
			'center_menu' => implode('|', $menu['center']==NULL?array():$menu['center']),
			'center_menu_level' => $this->input->post('sel_center_menu_level'),
			'left_menu' => implode('|', $menu['left']==NULL?array():$menu['left']),
			'left_menu_level' => $this->input->post('sel_left_menu_level'),
			'right_menu' => implode('|', $menu['right']==NULL?array():$menu['right']),
			'right_menu_level' => $this->input->post('sel_right_menu_level'),
			'bottom_menu' => implode('|', $menu['bottom']==NULL?array():$menu['bottom']),
			'bottom_menu_level' => $this->input->post('sel_bottom_menu_level'),
            'left_col' => $this->input->post('sel_status_left'),
            'right_col' => $this->input->post('sel_status_right'),
	    );
		
		if ($page_id == $gpl['page_id'])	// edit Global Page Layout
		{
			if (Gpl_Model::update($set))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_update'));
			else
				$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.error_data_update'));
			
			url::redirect(uri::segment(1));				
		}	
		else
		{
			if ($this->pl_model->update($page_id, $set))
				$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_update'));
			else
				$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.error_data_update'));
			
			url::redirect(uri::segment(1).'/edit/'.$page_id);
		}		
		
    	die();
	}	
	
	public function insert_banner($page_id, $position)
	{
		$this->template = new View('admin_layout/frm_insert_banner');
		
		$gpl = Gpl_Model::get();
		$pos = $this->get_position($position);
		$this->mr['position'] = $position;
				
		if ($page_id == $gpl['page_id'])
		{
			$this->mr['page_id'] = $gpl['page_id'];
			$this->mr['page_title'] = Kohana::lang('layout_lang.lbl_global_page').' : '.Kohana::lang('global_lang.lbl_banner').' '.$pos;			
		}
		else
		{		
			$this->mr = array_merge($this->mr, $this->page_model->get_page_lang($this->site['lang_id'],$page_id));
			$this->mr['page_title'] = $this->mr['page_title'].' : '.Kohana::lang('global_lang.lbl_banner').' '.$pos;
		}
			
		$this->mlist = $this->banner_model->get_mod();
		
		foreach ($this->mlist as $key => $list)
		{
			$this->mlist[$key]['banner_type'] = ORM::factory('file_type_orm', $list['file_type_id'])->file_type_detail;
			$this->mlist[$key]['path_file'] = DOCROOT.'uploads'.DIRECTORY_SEPARATOR.'banner'.DIRECTORY_SEPARATOR.$list['banner_file'];
		}
			
		$this->template->mr = $this->mr;
		$this->template->mlist = $this->mlist;	
		$this->template->top = new View('admin/top');
		$this->template->bottom = new View('admin/bottom');
		$this->template->error = new View('admin/error');
		
		if($this->session->get('error_msg'))
			$this->template->error->error_msg = $this->session->get('error_msg');	
		if($this->session->get('success_msg'))
			$this->template->error->success_msg = $this->session->get('success_msg');
		if($this->session->get('info_msg'))
			$this->template->error->info_msg = $this->session->get('info_msg');
	}  
	
	public function remove_banner($page_id, $position, $banner_id = '')
	{		
        if(!$this->permisController('delete')) {
    		$this->session->set_flash('error_msg',Kohana::lang('global_lang.lbl_authorized'));
			url::redirect(uri::segment(1));
			die();
		} else {
    		$gpl = Gpl_Model::get();
    		
    		$pos = $this->get_position($position);
    		
    		$pl = $this->pl_model->get_mod($page_id);
    		
    		$arr_ban = explode('|',$pl[$pos.'_banner'],-1);
    		
    		unset($arr_ban[array_search($banner_id, $arr_ban)]);		
    	
    		if (empty($banner_id) || empty($arr_ban)) $str = '';
    		else $str = implode('|', $arr_ban).'|';	
    		
    		$this->pl_model->update($page_id,array($this->get_position($position).'_banner' => $str));    	
        		
        	if ($gpl['page_id'] == $page_id) url::redirect(uri::segment(1));
        	
    		url::redirect(uri::segment(1).'/edit/'.$page_id);
        	die();
        }
	}
	
	public function save_banner_layout($page_id, $position)
	{		
		$banners_id = $this->input->post('chk_banner');
		$pos = $this->get_position($position);
		
		if ($banners_id == NULL)
		{
			$this->session->set_flash('info_msg', Kohana::lang('errormsg_lang.msg_no_check'));
			
			url::redirect(uri::segment(1)."/insert_banner/$page_id/$position");
			die();
		}
			
		$pl = $this->pl_model->get_mod($page_id);
			
		$str_banners = implode('|',$banners_id).'|';
						
	 	$result = $this->pl_model->update($page_id,array($pos.'_banner' => $pl[$pos.'_banner'].$str_banners));
    	
    	if ($result)
    		$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_add'));
    	else
    		$this->session->set_flash('error_msg', Kohana::lang('errormsg_lang.error_data_add'));    	
    	
    	url::redirect(uri::segment(1)."/insert_banner/$page_id/$position");
    	die();
	}
	
	public function chbo($page_id, $position) // change banner order
	{
		$gpl = Gpl_Model::get();
		$pl = $this->pl_model->get($page_id);		 
		$pos = $this->get_position($position);	
    	
    	/*if ($position == 2)
    	{    		
  			$this->pl_model->update($page_id,array($pos.'_banner_order' => $pl['content_'.$pos.'_order']));
   			$this->pl_model->update($page_id,array('content_'.$pos.'_order' => $pl[$pos.'_banner_order']));
    	}    	
    	else*/
		if ($position == 1) 	
    	{  			
			$this->pl_model->update($page_id,array($pos.'_banner_order' => $pl['center_menu_order']));
			$this->pl_model->update($page_id,array('center_menu_order' => $pl[$pos.'_banner_order']));
		}	
		else // 3, 4
	  	{	
			$this->pl_model->update($page_id,array($pos.'_banner_order' => $pl[$pos.'_menu_order']));
			$this->pl_model->update($page_id,array($pos.'_menu_order' => $pl[$pos.'_banner_order']));
		}    	
    	
    	if ($gpl['page_id'] == $page_id) url::redirect(uri::segment(1));
    	
		url::redirect(uri::segment(1).'/edit/'.$page_id);
    	die();    
	}
	
	public function order_banner($action, $page_id, $position, $banner_id)
	{
		$gpl = Gpl_Model::get();
		$pos = $this->get_position($position);
		$pl = $this->pl_model->get($page_id);
			
		$list_banners = explode('|', $pl[$pos.'_banner']);
		
		for ($i=0;$i<count($list_banners);$i++)
		{
			if ($list_banners[$i] == $banner_id)	
			{				
				$j = $action=='down'?($i+1):($i-1);						
				$list_banners[$i] = $list_banners[$j];
				$list_banners[$j] = $banner_id;
				break;
			}		
		}
		$pl[$pos.'_banner'] = implode('|', $list_banners);
		
		$this->pl_model->update($page_id,array($pos.'_banner' => $pl[$pos.'_banner']));	
		
		if ($gpl['page_id'] == $page_id) url::redirect(uri::segment(1));
    	
		url::redirect(uri::segment(1).'/edit/'.$page_id);
    	die();	
	}
	
	public function header($page_id)
	{
		// edit header
		$this->template = new View(uri::segment(1).'/header');	
		
		$gpl = Gpl_Model::get();
		
		if ($gpl['page_id'] != $page_id)	// if edit normal page, get page info
			$page = Page_Model::get_page_lang('',$this->get_admin_lang(),$page_id,'','','pd.page_title');
		else
			$page = array('page_id' => $gpl['page_id'], 'page_title' => Kohana::lang('layout_lang.lbl_global_page'));
		
		$ph = Model::factory('header')->get_mod($page['page_id']);	
		
		$this->template->mr = array_merge($page, $ph, $this->mr);
		
		$this->template->top = new View('admin/top');
		$this->template->bottom = new View('admin/bottom');
		$this->template->error = new View('admin/error');
		
		if($this->session->get('error_msg'))
			$this->template->error->error_msg = $this->session->get('error_msg');	
		if($this->session->get('success_msg'))
			$this->template->error->success_msg = $this->session->get('success_msg');	
	}
	
	private function _get_header_valid()
	{
		$page_id = $this->input->post('hd_id');
		$valid_image = Model::factory('file_type')->get_fext('image');
		$valid_flash = Model::factory('file_type')->get_fext('flash');
		
		$form = array(
			'txt_text' => '',
			'attach_image' => '',
			'attach_flash' => '',
			'sel_hd_type' => '',
			'hd_id' => ''
		);
		
		$post = new Validation(array_merge($_FILES,$_POST));		
		
		$post->add_rules('attach_image', 'upload::type['.$valid_image.']', 'upload::size[1M]');
		$post->add_rules('attach_flash', 'upload::type['.$valid_flash.']', 'upload::size[1M]');
	
 		$form = arr::overwrite($form, $post->as_array());
 		
		if ($post->validate()) return $form;
		else
		{
			$this->session->set_flash('input_data',$form);
			
			$errors = $header_valid->errors('header_footer_validation');
			$str_error = '';
				
			foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
			$this->session->set_flash('error_msg',$str_error);
				
			url::redirect(uri::segment(1).'/header/'.$page_id);
			die();
		}	
	}
	
	public function save_header()
	{		
		$frm = $this->_get_header_valid();
		$arr_type = array('text','image','image','flash');		
		$msg = array('error' => '', 'success' => '');
		$page_id = $frm['hd_id'];
		$type = $arr_type[$frm['sel_hd_type']];	
		
		$set = array(
			'header_content' => $frm['txt_text'],
			'header_type' => $frm['sel_hd_type']
		);	
		
		if (isset($frm['attach_'.$type]['error']) && $frm['attach_'.$type]['error'] == 0)
		{	
			$path_dir = DOCROOT.'uploads/header/';				
		
			$path_file = Upload::save('attach_'.$type, NULL, $path_dir);
			$file_name = basename($path_file);				
		
			$msg['success'] .= Kohana::lang('errormsg_lang.msg_file_upload').'<br>';
			$set['header_'.$type] = $file_name;
		
		}
		elseif (isset($frm['attach_'.$type]['error']) && $frm['attach_'.$type]['error'] != 4) 
			$msg['error'] .= Kohana::lang('errormsg_lang.error_file_upload').'<br>';	
			
		if (Model::factory('header')->update_mod_pk($page_id, $set))
			$msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save').'<br>';
		else		
			$msg['error'] .= Kohana::lang('errormsg_lang.error_data_save').'<br>';
			
		if ($msg['error'] !== '')
			$this->session->set_flash('error_msg', $msg['error']);
		
		if ($msg['success'] !== '')
			$this->session->set_flash('success_msg', $msg['success']);		
		
		url::redirect(uri::segment(1).'/header/'.$page_id);
		die();
	}
			
	public function del_header_file($page_id, $type)
	{
		$header = Model::factory('header')->get_mod($page_id);
		$msg = array('success' => '', 'error' => '');
		
		if (!empty($header['header_'.$type]))
		{		
			$ht_path_file = DOCROOT.'uploads/header/'.$header['header_'.$type];
			
			if (is_file($ht_path_file) && file_exists($ht_path_file));
			{				
				if (@unlink($ht_path_file))
					$msg['success'] = Kohana::lang('errormsg_lang.msg_hd_'.$type.'_del').'<br>';	
				else
					$msg['error'] = Kohana::lang("errormsg_lang.msg_del_file_error").'<br>';	
			}
			if (Model::factory('header')->update_mod_pk($header['page_id'], array('header_'.$type => '')))
				$msg['success'] .= Kohana::lang('errormsg_lang.msg_data_del').'<br>';
			else
				$msg['error'] .= Kohana::lang('errormsg_lang.msg_del').'<br>';	
				
			if ($msg['error'] !== '')
				$this->session->set_flash('error_msg', $msg['error']);
		
			if ($msg['success'] !== '')
				$this->session->set_flash('success_msg', $msg['success']);			
		}
		
		url::redirect(uri::segment(1).'/header/'.$page_id);
		die();
	}
	
	public function footer()
	{
		// edit footer
		$this->template = new View(uri::segment(1).'/footer');	
		
		$this->template->mr = array_merge(Model::factory('footer')->get(), $this->mr);
		$this->template->top = new View('admin/top');
		$this->template->bottom = new View('admin/bottom');
		$this->template->error = new View('admin/error');
		
		if($this->session->get('error_msg'))
			$this->template->error->error_msg = $this->session->get('error_msg');	
		if($this->session->get('success_msg'))
			$this->template->error->success_msg = $this->session->get('success_msg');
	}
	
	private function _get_footer_valid()
	{
		$page_id = $this->input->post('hd_id');
		$valid_image = Model::factory('file_type')->get_fext('image');
		$valid_flash = Model::factory('file_type')->get_fext('flash');
		
		$form = array(
			'txt_text' => '',
			'attach_image' => '',
			'attach_flash' => '',
			'sel_ft_type' => '',
			'hd_id' => ''
		);
		
		$post = new Validation(array_merge($_FILES,$_POST));		
		
		$post->add_rules('attach_image', 'upload::type['.$valid_image.']', 'upload::size[1M]');
		$post->add_rules('attach_flash', 'upload::type['.$valid_flash.']', 'upload::size[1M]');
	
 		$form = arr::overwrite($form, $post->as_array());
 		
		if ($post->validate()) return $form;
		else
		{
			$this->session->set_flash('input_data',$form);
			
			$errors = $post->errors('header_footer_validation');
			$str_error = '';
				
			foreach($errors as $id => $name) if($name) $str_error.=$name.'<br>';
			$this->session->set_flash('error_msg',$str_error);
				
			url::redirect(uri::segment(1).'/footer/'.$page_id);
			die();
		}	
	}
	
	public function save_footer()
	{		
		$frm = $this->_get_footer_valid();
		$arr_type = array('text','image','image','flash');		
		$msg = array('error' => '', 'success' => '');
		$page_id = $frm['hd_id'];
		$type = $arr_type[$frm['sel_ft_type']];	
		
		$set = array(
			'footer_content' => $frm['txt_text'],
			'footer_type' => $frm['sel_ft_type']
		);	
		
		if (isset($frm['attach_'.$type]['error']) && $frm['attach_'.$type]['error'] == 0)
		{	
			$path_dir = DOCROOT.'uploads/footer/';				
		
			$path_file = Upload::save('attach_'.$type, NULL, $path_dir);
			$file_name = basename($path_file);				
		
			$msg['success'] .= Kohana::lang('errormsg_lang.msg_file_upload').'<br>';
			$set['footer_'.$type] = $file_name;
		
		}
		elseif (isset($frm['attach_'.$type]['error']) && $frm['attach_'.$type]['error'] != 4) 
			$msg['error'] .= Kohana::lang('errormsg_lang.error_file_upload').'<br>';	
			
		if (Model::factory('footer')->update($set))
			$msg['success'] .= Kohana::lang('errormsg_lang.msg_data_save').'<br>';
		else		
			$msg['error'] .= Kohana::lang('errormsg_lang.error_data_save').'<br>';
			
		if ($msg['error'] !== '')
			$this->session->set_flash('error_msg', $msg['error']);
		
		if ($msg['success'] !== '')
			$this->session->set_flash('success_msg', $msg['success']);		
		
		url::redirect(uri::segment(1).'/footer/'.$page_id);
		die();
	}	
	
	public function del_footer_file($type)
	{		
		$footer = Model::factory('footer')->get();
		$msg = array('success' => '', 'error' => '');
		
		if (!empty($footer['footer_'.$type]))
		{		
			$path_file = DOCROOT.'uploads/footer/'.$footer['footer_'.$type];
			
			if (is_file($path_file) && file_exists($path_file));
			{				
				if (@unlink($path_file))
					$msg['success'] = Kohana::lang('errormsg_lang.msg_del_file').'<br>';	
				else
					$msg['error'] = Kohana::lang("errormsg_lang.error_del_file").'<br>';	
			}
			if (Model::factory('footer')->update(array('footer_'.$type => '')))
				$msg['success'] .= Kohana::lang('errormsg_lang.msg_data_del').'<br>';
			else
				$msg['error'] .= Kohana::lang('errormsg_lang.msg_del').'<br>';	
				
			if ($msg['error'] !== '')
				$this->session->set_flash('error_msg', $msg['error']);
		
			if ($msg['success'] !== '')
				$this->session->set_flash('success_msg', $msg['success']);			
		}
		
		url::redirect(uri::segment(1).'/footer');
		die();
	}
	
	public function copy_to(){
		$chk_id = $this->input->post('chk_id');
        $dest_pid = $this->input->post('sel_move');
		if (empty($chk_id) || !is_array($chk_id) || !valid::digit($dest_pid)) {
            $this->session->set_flash('warning_msg', Kohana::lang('errormsg_lang.error_parameter'));
            url::redirect('admin_page');
            die();
        }
		if (!$this->pl_model->has_layout($dest_pid)){
			$this->session->set_flash('warning_msg', Kohana::lang('errormsg_lang.error_empty_layout'));
            url::redirect('admin_page');
            die();
		}
		
		foreach ($chk_id as $i => $pid) {
			if ($this->pl_model->has_layout($pid)){
				$set = Gpl_Model::get($dest_pid);
				$set['page_id'] = $pid;
				$this->pl_model->update($pid, $set);
			} else {
				$this->pl_model->create($pid, 'copy', $dest_pid);
			}
		}
		$this->session->set_flash('success_msg', Kohana::lang('errormsg_lang.msg_data_update'));
		url::redirect('admin_page');
        die();
	}
}
?>