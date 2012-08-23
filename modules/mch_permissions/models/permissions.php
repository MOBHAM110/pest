<?php
class Permissions_Model extends Model {
	
	protected $table_name = 'permissions';
	protected $primary_key = 'permissions_id';
	protected $col_status = 'permissions_status';
	protected $col_order = 'permissions_order';
	
	public function get($id = '')
	{
		$this->db->select('permissions.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('permissions.permissions_id', $id);
        
		$this->db->orderby($this->col_order,'asc');
		$result = $this->db->get('permissions')->result_array(false);
        
        if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}
	
	public function get_with_active()
	{
		$this->db->where($this->col_status,'1');
		return $this->get();
	}
	
	public function search($search)
	{
		if($search['keyword']) {
			$this->db->where("LCASE(permissions_name) LIKE '%".strtolower($search['keyword'])."%'");
			$this->db->orwhere("LCASE(permissions_code) LIKE '%".strtolower($search['keyword'])."%'");
		}
	}
	
	public function set_status($id, $status)
	{
		$set = array($this->table_name.'_status' => $status);
		
		$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);	
		
		return count($this->db->update($this->table_name, $set));
	}
	
	public function delete($id)
	{
		$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);
		return count($this->db->delete($this->table_name));
	}
}
?>