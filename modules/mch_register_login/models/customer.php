<?php
class Customer_Model extends Model
{
	protected $table_name = 'customers';
	protected $primary_key = 'customers_id';
    protected $foriegn_key_user = 'user_id';
	
	public function get($id = '')
	{
		$this->db->select($this->table_name.'.*');
		$this->db->select('user.*');
		
		if($id != '')
			$this->db->{is_array($id)?'in':'where'}($this->table_name.'.'.$this->foriegn_key_user, $id);
		
		$this->db->join('user', array('user.user_id' => $this->table_name.'.user_id'),'','left');		
		$result = $this->db->get($this->table_name)->result_array(false);
	
		if($id != '' && !is_array($id)) return isset($result[0])?$result[0]:false;
		return $result;
	}
		
	public function search($search)
	{
		if(!empty($search['keyword']))
    		$this->db->like('user.user_name',$search['keyword']);
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
			'txt_first_name' => '',
			'txt_last_name' => '',			
			'txt_address' => '',			
			'txt_phone' => '',	
			'txt_fax' => '',
			'txt_city' => '',		
			'txt_state' => '',
			'txt_zipcode' => '',
			'chk_staff' => '',
			'sel_status' => ''
		);
        return $form;
    }
}
?>