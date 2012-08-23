<?php
class Role_orm_Model extends ORM {
	
	protected $table_name = 'role';
	protected $primary_key = 'role_id';
	protected $col_status = 'role_status';
	protected $sorting = array('role_id' => 'desc');
	
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