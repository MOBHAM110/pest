<?php
class Footer_Model extends Model
{
	protected $table_name = 'footer';
	protected $primary_key = 'footer_id';
	
	public function get()
	{		
		$result = $this->db->get($this->table_name)->result_array(false);
		
		return $result[0];		
	}
	
	public function update($set)
	{
		$footer = $this->get();
		
		return $this->update_mod_pk($footer['footer_id'], $set);
	}
}
?>