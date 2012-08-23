<?php
class Support_orm_Model extends ORM {
	
	protected $table_name = 'support';
	protected $primary_key = 'support_id';
	protected $col_sort_order = 'support_sort_order';
	protected $col_status = 'support_status';
	protected $sorting = array('support_id' => 'desc');
	
	public function get()
	{
		$this->db->from($this->table_name);
		$this->db->select('*');
		$this->db->orderby($this->col_sort_order,'desc');
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