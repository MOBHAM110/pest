<?php
class File_type_Model extends Model
{
	protected $table_name = 'file_type';
	protected $primary_key = 'file_type_id';
	
	public function get_fid($type = '', $file_name = '')
	{
		if (!empty($file_name))		
		{
			$ext_file = substr($file_name, strrpos($file_name,'.') + 1);
			$list_ft = $this->get_mod();
			
			foreach ($list_ft as $ft)
			{
				if (strpos($ft['file_type_ext'], $ext_file) !== FALSE)
				{
					$type = $ft['file_type_detail'];
					break;
				}
			}			
		}
		
		$this->db->where('file_type_detail', $type);
		
		$result = $this->db->get($this->table_name)->result_array(false);
		
		return (count($result) == 1) ? $result[0]['file_type_id'] : FALSE; 
	}
	
	public function get_fext($type)
	{
		$this->db->where('file_type_detail', $type);
		
		$result = $this->db->get($this->table_name)->result_array(false);
		
		return (count($result) == 1) ? $result[0]['file_type_ext'] : FALSE; 
	}	
}
?>