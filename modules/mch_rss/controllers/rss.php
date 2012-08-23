<?php
class Rss_Controller extends Template_Controller
{
	public $template;	
	
    public function __construct()
    {
        parent::__construct();
        $this->template = new View('templates/'.$this->site['config']['TEMPLATE'].'/client/index');
        $this->template->layout = $this->get_MCH_layout();	// init layout for template controller        
        
        /* --------------------------------------get session if exist ------------------------------------------*/		
		$this->_get_session_msg();
		/* --------------------------------------------------------------------------------------------------- */
		
		/* ---------------------------------- Init properties of Controller ---------------------------------- */			
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
	}
	
	public function __call($method, $arguments)
    {
    	$this->warning_msg('wrong_pid');
    }
	
	public function index()
	{
		if (!empty($this->warning))
		{
			$this->warning_msg($this->warning);
		}
		else
		{
			$this->template->content = new View('templates/'.$this->site['config']['TEMPLATE'].'/rss/list');
			
			$page_model = new Page_Model();		
			
			$rss_pid = $page_model->get_page_type(array('bbs','blog','album','news'));
			
			if (empty($rss_pid)) $list_page = $rss_pid;
			else
			{
				$page_model->set_query('where','page_status',1);
				$list_page = $page_model->get_page_lang($this->get_client_lang(), $rss_pid,NULL,NULL,'');
			}
			
			$this->template->content->list_page = $list_page;
		}
	}
	
	public function get($page_id)
	{	
		$page_model = new Page_Model();
		$page = $page_model->get_page_lang($this->get_client_lang(), $page_id,NULL,'','');
		
		if (!empty($page_id) && !empty($page))
		{
			$this->auto_render = FALSE;
			
			$bbs_model = new Bbs_Model();				
			
			$bbs_model->limit_mod($this->site['config']['CLIENT_NUM_LINE']);
			
			if ($page['page_type_name'] == 'bbs')
				$list_bbs = $bbs_model->get(TRUE, $page_id,'',$this->get_client_lang());
			else
				$list_bbs = $bbs_model->get(FALSE, $page_id,'',$this->get_client_lang());
				
			for($i = 0; $i < count($list_bbs); $i++)
			{
				$list_file = ORM::factory('bbs_file_orm')->where('bbs_id', $list_bbs[$i]['bbs_id'])->orderby('bbs_file_order')->find_all()->as_array();
				$list_bbs[$i]['bbs_file'] = empty($list_file[0]->bbs_file_name) ? '' : $list_file[0]->bbs_file_name;
			}
				
			//echo Kohana::debug($list_bbs);die();
			$string = '<?xml version="1.0" encoding="UTF-8"?>
			<rss version="2.0">
			<channel>
			<title>'.$page['page_title'].' - '.$this->site['site_name'].'</title>'
            .'<description>'.$page['page_title'].' - '.$this->site['site_name'].'</description>'
            .'<copyright>'.$this->site['site_name'].'</copyright>'
			.'<link>'.$this->site['base_url'].'rss/get/'.$page_id.'</link>';
			for($i=0; $i<count($list_bbs); $i++)
			{
				$path_img = DOCROOT.'uploads/'.$page['page_type_name'].'/'.$list_bbs[$i]['bbs_file'];
				if (isset($list_bbs[$i]['bbs_file']) && !empty($list_bbs[$i]['bbs_file']) && file_exists($path_img))
				{
				$image='<img align="bottom" src="'.$this->site['base_url'].'uploads/'.$page['page_type_name'].'/'.$list_bbs[$i]['bbs_file'].'" '.$this->get_thumbnail_size($path_img,500,300).'/>';
				}
				else $image='';
				
				$string .= '<item>'; 
				$string .= '<title>'.$list_bbs[$i]['bbs_title'].'</title>';
				if ($page['page_type_name'] == 'bbs' || $page['page_type_name'] == 'news') 
					$string .= '<link>'.$this->site['base_url'].$page['page_type_name'].'/pid/'.$page_id.'/detail/id/'.$list_bbs[$i]['bbs_id'].'</link>';
				else
	 				$string .= '<link>'.$this->site['base_url'].$page['page_type_name'].'/pid/'.$page_id.'/detail/id/'.$list_bbs[$i]['bbs_id'].'</link>';
				$string .= '<description>'.htmlspecialchars($image.'<br>'.$list_bbs[$i]['bbs_content']).'</description>';
				$string .='</item>';		
			}
			
			$string .='</channel></rss>';
			//echo Kohana::debug($string);die(); 
			echo $string;
		}
		else 
		{	
			$this->warning_msg('wrong_pid');
		}
	}
}