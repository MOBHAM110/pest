<?php
class Permissions_orm_Model extends ORM {
	
	protected $table_name = 'permissions';
	protected $primary_key = 'permissions_id';
	protected $col_status = 'permissions_status';
	protected $col_order = 'permissions_order';
	protected $sorting = array('permissions_id' => 'desc','permissions_order' => 'asc');
	
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->orderby($this->primary_key,'desc');
		$query = $this->db->get();
		return $query->result_array(false);
	}
	
	public function get_with_active()
	{
		$this->db->where($this->col_status,'1');
		return $this->get();
	}
}
?>