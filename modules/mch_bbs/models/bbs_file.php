<?php
class Bbs_file_Model extends Model
{
	protected $table_name = 'bbs_file';
	protected $primary_key = 'bbs_file_id';
	protected $order_key = 'bbs_file_order';
	
	public function get($bbs_id, $order = 'asc')
	{
		$this->db->where('bbs_id', $bbs_id);
		$this->db->orderby($this->order_key, $order);
		
		return $this->db->get($this->table_name)->result_array(false);
	}
		
}
?>