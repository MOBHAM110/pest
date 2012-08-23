<?php
class Role_perms_orm_Model extends ORM {
	
	protected $table_name = 'role_perms';
	protected $primary_key = 'role_perms_id';
	protected $forein_key_permis = 'permission_id';
	protected $forein_key_role = 'role_id';
	protected $col_value = 'role_perms_value';
	
	protected $sorting = array('role_perms_id' => 'desc');
	
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