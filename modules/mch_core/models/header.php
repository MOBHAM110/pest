<?php
class Header_Model extends Model
{
	protected $table_name = 'header';
	protected $primary_key = 'page_id';
	
	public function create($page_id)
	{
		$set = array($this->primary_key => $page_id, 'header_type' => 0); // There 4 header type (0-3) 0 is text
		$new_hd = $this->db->insert($this->table_name, $set);	
		
		return $page_id;	
	}
	
	public function has_header($page_id)
	{
		$this->db->where($this->primary_key, $page_id);
		
		$result = $this->db->count_records($this->table_name);
		
		return ($result > 0) ? TRUE : FALSE;	
	}
}
?>