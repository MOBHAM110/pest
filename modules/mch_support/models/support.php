<?php
class Support_Model extends Model {
	
	protected $table_name = 'support';
	protected $primary_key = 'support_id';
	protected $col_sort_order = 'support_sort_order';
	protected $col_status = 'support_status';
	
	public function get($id = '')
	{
		$this->db->select('support.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('support.support_id', $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get('support')->result_array(false);
        
        if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}
	
	public function get_with_active()
	{
		$this->db->where($this->col_status,'1');
		return $this->get();
	}
}
?>