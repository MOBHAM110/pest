<?php
class Page_Type_Model extends Model
{	
	protected $table_name = 'page_type';
	protected $primary_key = 'page_type_id';
	protected $status_key = 'page_type_status';
	
	public function get_all($id = '', $special = 1, $status = 1, $type_result = 'arr')
	{
		$this->db->where('page_type_special', $special);
		
		return $this->get_status($status, $id, $type_result);
	}	
}
?>