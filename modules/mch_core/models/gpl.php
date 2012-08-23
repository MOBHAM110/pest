<?php
class Gpl_Model extends Model
{	
	public function get()	
	{		
		$this->db->where('page_id', ORM::factory('page_mptt')->__get('root')->page_id);
		
		$result = $this->db->get('page_layout')->result_array(false);
	
		return !empty($result[0])?$result[0]:false;
	}
	
	public function check_banner_used($id)	// id = banner id
	{
		$position = array('top','center','left','right','bottom');		
				
		foreach ($position as $pos)
			$this->db->orlike($pos.'_banner',$id);
		if (count($this->db->get('page_layout')->result_array(false)) > 0)				
			return TRUE; 		
	
		return FALSE;
	}
	
	public function update($set)
	{		
		$this->db->where('page_id', ORM::factory('page_mptt')->__get('root')->page_id);
		
		$result = $this->db->update('page_layout', $set);
		
		return (count($result) > 0 ? TRUE : FALSE);
	}
}
?>