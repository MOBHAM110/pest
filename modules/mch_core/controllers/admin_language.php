<?php
class Admin_language_Controller extends Template_Controller {

	public $template = 'admin/index';	

	public function __construct()
    {
        parent::__construct(); // This must be included 
       
        $this->_get_session_msg();
    }
	
	public function __call($method, $arguments)
	{
		$this->template->error_msg = Kohana::lang('errormsg_lang.error_parameter');
		
		$this->index();
	}
	
	function _get_session_msg()
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
		$this->showlist();	
	}
	
	private function showlist()
	{
		$this->template->content = new View('admin_language/list');
	
		$this->template->content->mlist = $this->db->get('languages')->result_array(false);
	}
	
	public function set_lang($lang_id)
    {			
		$this->session->set('sess_admin_lang', $lang_id);		
			
		url::redirect($this->site['history']['back']);
		die();
    }
	
    private function search()
    {    	
    	//Get    	
    	if($this->session->get('sess_search')){			
			$this->search = $this->session->get('sess_search');
		}
		if($this->input->post('txt_keyword')){    		
			$this->search['keyword'] = $this->input->post('txt_keyword');
		} else 
			$this->search['keyword'] = '';
		
		//Page
		$page = $this->uri->segment('page');
		if($page)
			$this->search['page'] = $page;
			
		//Set
		$this->session->set_flash('sess_search',$this->search);
		
		$this->showlist();
    }
    
	private function create()
	{
		$this->template->content = new View('admin_language/frm');		
		$this->_get_submit();
	}
	
	private function edit($id)
	{
		$this->template->content = new View('admin_language/frm');
		$this->template->content->mr = ORM::factory('language')->find($id)->as_array();
	}
	
	private function _get_record()
	{		
	    $form = array
	    (	
	    	'hd_id'          => '',
	    	'hd_old_image'   => '',
	        'txt_name'       => '',
	        'txt_code'       => '',
	        'txt_directory'  => '',
	        'txt_order'      => '',
	        'chk_delete'     => '',
	    );
	 
	    $errors = $form;
	 	
	    if ($_POST)
	    {
	        $post = new Validation($_POST);
	 		
	        $post->pre_filter('trim', TRUE);
	        $post->add_rules('txt_name','required');
	        $post->add_rules('txt_code','required');
	        
	        if($post->validate())
	        {
	        	$form = arr::overwrite($form, $post->as_array());
     			return $form;
	        } else {
	        	$form = arr::overwrite($form, $post->as_array());
	            $errors = arr::overwrite($errors, $post->errors('language_validation'));
	            
				if($errors['txt_name'])
	            	$this->session->set_flash('error_msg', $errors['txt_name']);
				
				if ($form['hd_id'])
					url::redirect('admin_language/edit/'.$form['hd_id']); 
				else
					url::redirect('admin_language/create'); 
	        }
	    }
	}
	
	private function _get_image($chk_delete,$hd_old_image)
	{
		//Check image old
		if($hd_old_image)
			$path_filedel = './uploads/language/'.$hd_old_image;
		else	
			$path_filedel = '';
	
		if($chk_delete)
		{
			if(file_exists($path_filedel)) unlink($path_filedel);			
			return '';
		}	
		//Upload
		//Uses Kohana upload helper
		$_FILES = Validation::factory($_FILES)
			->add_rules('txt_image', 'upload::valid', 'upload::type[gif,jpg,png]', 'upload::size[1M]');
		if ($_FILES['txt_image']['error']==0)
		{
			//Delete file if have upload image
			if(file_exists($path_filedel)) unlink($path_filedel);
			
			//Temporary file name
			$filename = upload::save('txt_image');
			//Resize, sharpen, and save the image
			Image::factory($filename)
				//->resize(20, 20, Image::AUTO)
				->save(DOCROOT.'uploads/language/'.md5(basename($filename)).'.png');
		 	
			//Remove the temporary file
			unlink($filename);
		 
			//Redirect back to the account page
			return md5(basename($filename)).'.png';
		} else {
			if ($hd_old_image)
				return $hd_old_image;
			else
				return '';
		}	
	}
	
	private function save()
	{
		$hd_id = $this->input->post('hd_id');
		
		$record = $this->_get_record();
		
		if($record)
		{
			if(!$hd_id){
				$result = ORM::factory('language');
			} else {
				$result = ORM::factory('language',$hd_id);
			} 
			$result->languages_name = $record['txt_name'];
			$result->languages_code = $record['txt_code'];
			$result->languages_image = $this->_get_image($record['chk_delete'],$record['hd_old_image']);
			//$result->languages_directory = $record['txt_directory'];
			$result->languages_sort_order = $record['txt_order'];
			$hd_id = $result->save();
		}
		
		if(!$this->input->post('hd_id'))
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_add'));
		else
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
		
		url::redirect('admin_language/edit/'.$hd_id);     
    }
	
	private function saveall()
	{
		$arr_id = $this->input->post('chk_id');
		$status = array();
		if(is_array($arr_id))
		{			
			//do with action select
			$sel_action = $this->input->post('sel_action');
		
			if($sel_action == 'delete')
			{
				for($i=0;$i<count($arr_id);$i++)
				{
					$status = $this->db->delete('languages', array('languages_id' => $arr_id[$i]));
				}
			}
		} else {
			$this->session->set_flash('warning_msg',Kohana::lang('errormsg_lang.msg_data_error'));
		}
		
		if(count($status)>0)
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_data_save'));
		
		url::redirect('admin_language');
	}
	
	private function delete($id)
	{
		$status = $this->db->delete('languages', array('languages_id' => $id));
		// count how many rows were deleted
		if(count($status)>0)
		{
			$this->session->set_flash('success_msg', 'Delete success');
		}
		url::redirect('admin_language');     
	}
	
	private function delete_image($id)
	{
		$result = ORM::factory('language',$id);
		$result->languages_image = $this->_get_image(1,$result->languages_image);
		$result->save();
		url::redirect('admin_language/edit/'.$id); 
	}
	
    public function setstatus($id,$status)
    {
    	$status = $this->db->update('languages',array('languages_status' => $status),array('languages_id' => $id));
    	if(count($status)>0)
		{
			$this->session->set_flash('success_msg',Kohana::lang('errormsg_lang.msg_status_change'));
		}
		
    	url::redirect(uri::segment(1)); 
    }
}