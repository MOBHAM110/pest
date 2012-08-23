<?php
class Comment_Model extends Model
{
	public $table_name = 'comment';
	public $primary_key = 'comment_id';	
	public $col_status = 'comment_status';

	public function get($id = '')
	{
		$this->db->select('comment.*');
        
        if($id != '')
			$this->db->{is_array($id)?'in':'where'}('comment.comment_id', $id);
        
		$this->db->orderby($this->primary_key,'desc');
		$result = $this->db->get('comment')->result_array(false);
        
        if($id != '' && !is_array($id))
            return $result[0];
            
		return $result;
	}
	
	public function get_with_active()
	{
		$this->db->where($this->col_status,'1');
		return $this->get();
	}
	public function get_com($bbs_id)
	{		
		$this->db->where('bbs_id', $bbs_id);
		$this->db->where($this->col_status,'1');
		$this->db->orderby('comment_time','desc');
		
		return $this->db->get($this->table_name)->result_array(false);	
	}
}
?>