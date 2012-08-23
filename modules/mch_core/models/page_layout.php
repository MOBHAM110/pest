<?php
class Page_Layout_Model extends Model
{	
	protected $table_name = 'page_layout';
	protected $primary_key = 'page_id';
		
	public function update($id, $set)
	{
		$this->db->where('page_id', $id);
		$result = $this->db->update('page_layout', $set);
		
		return count($result) ? TRUE : FALSE;
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
	
	public function get($page_id)
	{
		$this->db->where('page_id', $page_id);
		
		$result = $this->db->get('page_layout')->result_array(false);
		
		if (count($result) == 1) return $result[0];
		return array();
	}
	
	public function has_layout($page_id)
	{
		$this->db->where('page_id', $page_id);
		
		$result = $this->db->count_records('page_layout');
		
		if ($result > 0)	return TRUE;
		return FALSE;
	}
	
	public function create($page_id, $init = 'global')	// init global or new
	{
		$set = array();
		if ($init === 'global')
		{
			$set = Gpl_Model::get();			
		}
		
		$set['page_id'] = $page_id;		
		
		$result = $this->db->insert('page_layout', $set);
		
		if (count($result) > 0)	return TRUE;
		return FALSE;
	}
	
	public function delete($page_id)
	{
		if ($page_id != ORM::factory('page_mptt')->__get('root')->page_id)
		{
			$this->db->where('page_id', $page_id);
			$this->db->delete('page_layout');
		} else return FALSE;
		
		return TRUE;
	}
}
?>