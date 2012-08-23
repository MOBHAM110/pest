<?php
class Role_perms_Model extends Model {
	//Structure table Role_perms
	protected $table_name = 'role_perms';
	protected $primary_key = 'role_perms_id';
	protected $forein_key_permis = 'permission_id';
	protected $forein_key_role = 'role_id';
	protected $col_value = 'role_perms_value';
	
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
	
	public function getByRoleID($id)
	{
		
		$this->db->select($this->table_name.'.*');
        $this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->forein_key_role, $id);
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get($this->table_name)->result_array(false);
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
		$set = array($this->table_name.'_status' => $status);
		
		$this->db->{is_array($id)?'in':'where'}($this->primary_key, $id);	
		
		return count($this->db->update($this->table_name, $set));
	}
	
	public function delete($idRole, $idPermis = '')
	{
		$this->db->where($this->forein_key_role,$idRole);
		if($idPermis != '')
			$this->db->{is_array($idPermis)?'in':'where'}($this->forein_key_permis, $idPermis);
		return count($this->db->delete($this->table_name));
	}
	
}
?>