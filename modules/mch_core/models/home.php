<?php
class Home_Model extends Model 
{	
	protected $table_name = 'home';
	protected $primary_key = 'home_id';
	
	public function get_home_layout()
	{			
		$this->db->select('home_id,home_type,home_content');
		$this->db->select('page_layout.*');
		$this->db->join('page_layout',array($this->primary_key => 'page_id'));
		
		$result = $this->db->get($this->table_name)->result_array(false);
	
		return $result[0];
	}
	
	public function update_home_layout($home_id,$set)
	{
		$page_layout_model = new Page_layout_Model();
		
		return $page_layout_model->update($home_id,$set);
	}
	
	public function update($set,$where)
	{
		$this->db->where($where);
		
		$result = $this->db->update($this->table_name,$set);
		
		return count($result) ? TRUE : FALSE; 
	}
}
?>