<?php
class Login_model extends Model 
{
	public function __construct()
	{
		parent::__construct();
		$this->session = Session::instance();	// init property session use Session
	}
	
	public function set($obj = 'customer', $val)
	{	
		$this->session->set('sess_'.$obj, $val);		
	}    
	
	public function get($obj = 'customer')
	{		
		if ($this->session->get('sess_'.$obj)) 
			return $this->session->get('sess_'.$obj);			
		
		return FALSE;
	}
	
	public function log_out($obj = 'customer')
	{
		$this->session->delete('sess_'.$obj);	
	}
	
	public function save_active_last($id)
	{
		$admin = ORM::factory('admin_orm')->find($id);
		
		$admin->admin_active_last = time();
		$admin->save();
	}
	
	public function save_admin_log($id)
	{
		$admin = ORM::factory('admin_orm')->find($id);		
		
		$admin->admin_log_sessid = $this->session->id();	
		$admin->save();
	}
}
?>