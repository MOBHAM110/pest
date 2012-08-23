<?php
class Page_special_Model extends Model
{
	public function ps_exist($lang_id)
	{
		$page_model = new Page_Model();
		$gpl = Gpl_Model::get();
		
		// Find all page special in page_type_detail table
		$all_ps = ORM::factory('page_type_orm')->where('page_type_special', 1)->find_all();
		// Find all page in page table
		$all_page = $page_model->get_page_lang($gpl['page_id'], $lang_id,'','','*','');
		
		// If page table haven't page special then return false
		if (count($all_page) == 0)	return FALSE;
		
		foreach ($all_ps as $ps)
		{
			// Find ps in page table
			$find_ps = $this->db->where('page_type_id', $ps->page_type_id)->get('page')->result_array(false);
			// If page table haven't page special then return false
			if (count($find_ps) == 0)	return FALSE;
		}
		
		return TRUE;	// Page table have all pages special
	}
	
	public function init_ps()
	{
		$page_model = new Page_Model();
		$gpl = Gpl_Model::get(Rhq_Model::get());
		// Find all page special in page_type_detail table
		$all_ps = ORM::factory('page_type_orm')->where('page_type_special', 1)->find_all();
		
		foreach ($all_ps as $ps)
		{
			$set = array
	    	(  	    	
				'page_title' => $ps->page_type_name,	       
		        'page_content' => '',
		        'page_type_id' => $ps->page_type_id, 
				'page_parent' => $gpl['page_id']  
			);
			$page_model->insert($set);
		}
	}
	
	public function get_page_special($type)
	{
		$lst_pt = $this->db->where('page_type_special',1)->get('page_type')->result_array(false);
		
		foreach ($lst_pt as $pt)
		{
			if ($pt['page_type_name'] == $type)
			{
				$gp = ORM::factory('page_mptt')->__get('root');
				$this->db->where('page.page_left > ', $gp->page_left);
				$this->db->where('page.page_right < ', $gp->page_right);
				$this->db->where('page_type_id', $pt['page_type_id']);
				$result = $this->db->get('page')->result_array(false);
				
				if (count($result) > 0) return $result[0];
			}
		}
		
		return array();
	}	
}
?>