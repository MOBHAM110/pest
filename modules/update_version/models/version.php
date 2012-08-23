<?php
class Version_Model extends Model
{
	protected $table_name = 'version';
	protected $primary_key = 'version_id';	
	
	public function get_version()
	{
		$result = $this->db->get('version')->result_array(false);
		
		return $result[0];
	}
	
	public function update_version($new_ver)
	{
		$cur_ver = Version_Model::get_version();
		
		$this->db->where('version_id', $cur_ver['version_id']);
		$result = $this->db->update('version', array('cur_version' => $new_ver));
		
		return (count($result) > 0) ? TRUE : FALSE;
	}
	
	public function update_database($sql)
	{
		$result = $this->db->query($sql);
		
		return (count($result) > 0) ? TRUE : FALSE;
	}
}
?>