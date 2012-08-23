<?php
class Role_Model extends Model {
	
	protected $table_name = 'role';
	protected $primary_key = 'role_id';
	protected $col_name = 'role_name';
	protected $col_status = 'role_status';
	
	public function get($id = '')
	{
		$this->db->select($this->table_name.'.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->primary_key, $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get($this->table_name)->result_array(false);
        
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
		if($search['keyword'])
    		$this->db->like($this->table_name.'.'.$this->col_name,$search['keyword']);
	}
	
	public function set_status($id, $status)
	{
		$set = array($this->col_status => $status);
		
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