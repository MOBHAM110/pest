<?php
class Page_MPTT_Model extends ORM_MPTT 
{
	protected $table_name = 'page';
	protected $primary_key = 'page_id';
	protected $left_column = 'page_left';
	protected $right_column = 'page_right';
	protected $level_column = 'page_level';
	
	public function is_first_leaf()
	{
		if ($this->is_leaf())
		{
			$parent = $this->__get('parent');	// get parent node of current node
			$leaves = $parent->__get('leaves');	// get list of leaf nodes of parent node			
			
			return ($this->{$this->primary_key} == $leaves[0]->{$this->primary_key});			
		}
		return FALSE;
	}
	
	public function is_last_leaf()
	{
		if ($this->is_leaf())
		{
			$parent = $this->__get('parent');	// get parent node of current node
			$leaves = $parent->__get('leaves');	// get list of leaf nodes of parent node			
			
			return ($this->{$this->primary_key} == $leaves[count($leaves)-1]->{$this->primary_key});			
		}
		return FALSE;
	}
	
	public function is_mid_leaf()
	{
		if (!$this->is_first_leaf() && !$this->is_last_leaf())
			return TRUE;
		return FALSE;
	}
	
	public function is_first_child()
	{
		//$parent = $this->__get('parent');	// get parent node of current node		
		return ($this->{$this->left_column} === $this->parent->{$this->left_column} + 1);
	}
	
	public function is_last_child()
	{
		//$parent = $this->__get('parent');	// get parent node of current node
		return ($this->{$this->right_column} === $this->parent->{$this->right_column} - 1);
	}
	
	public function next_sibling()
	{
		return self::factory($this->object_name)->where($this->left_column,$this->{$this->right_column} + 1)->find();
	}
	
	public function prev_sibling()
	{
		return self::factory($this->object_name)->where($this->right_column,$this->{$this->left_column} - 1)->find();
	}
}
?>