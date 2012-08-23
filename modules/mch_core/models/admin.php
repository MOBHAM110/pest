<?php
class Admin_Model extends Model
{
	protected $table_name = 'admin';
	protected $primary_key = 'admin_id';	
	protected $foriegn_key_user = 'user_id';
    
	public function get($id = '')
	{
		$this->db->select($this->table_name.'.*');
        $this->db->select('user.*');		
		
		if($id != '')
			$this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->foriegn_key_user, $id);		
		$this->db->join('user', array('user.user_id' => $this->table_name.'.user_id'));		
		$result = $this->db->get($this->table_name)->result_array(false);
		
		if($id != '' && !is_array($id)) return isset($result[0])?$result[0]:false;		
		return $result;
	}
    
	public function delete($id)
	{
		$this->db->{is_array($id)?'in':'where'}($this->foriegn_key_user, $id);
		return count($this->db->delete($this->table_name));
	}
    
    public function get_frm()
    {
        $form = array
	    (
	    	'hd_id' => '',
			'txt_username' => '',	        	
	        'txt_pass' => '',
	        'txt_email' => '',
            'sel_role' => ''  
	    );
        return $form;
    }
}
?>