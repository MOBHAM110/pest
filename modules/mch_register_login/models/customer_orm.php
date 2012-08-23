<?php
class Customer_ORM_Model extends ORM {
	
	protected $table_name = 'customers';
	protected $primary_key = 'user_id';
	
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->orderby($this->primary_key,'desc');
		return $this->db->get();
	}
}
?>