<?php $root_page = ORM::factory('page_mptt')->__get('root');?>
<?php $root_children = ORM::factory('page_mptt',uri::segment(3))->__get('parents')->as_array(); ?>	        
<?php			
$path_title = '';
$str = '';
$images = '<img src="'.$this->site['theme_url'].'index/pics/icon_breadcrumb_separator.png"/>';
foreach($root_children as $child) {
	if($child->page_id !=1)
	{
	$page = $this->page_model->get_page_lang($this->get_client_lang(),$child->page_id);
	if($page['page_type_special'] ==1)
		$path_title .= $images.'<a href ="'.url::base().$page['page_type_name'].'">'.$page['page_title'].'</a>';
	else
	{
		if($page['page_type_name'] =='menu')
		$path_title .= $images.$page['page_title'];
		else
		$path_title .= $images.'<a href ="'.url::base().$page['page_type_name'].'/pid/'.$page['page_id'].'">'.$page['page_title'].'</a>';
	}
	}
   }
 ?>
 <?php 
 if(!empty($path_title)){
	 $str .= '<a href="'.$this->site['base_url'].'">'.Kohana::lang('client_lang.tt_home').'</a>';
	 $str .= $path_title;
	 $str .= $images.@$this->mr['page_title'];
	 $str .= '';
	 echo $str;
 }
else
{
	$str .= '<a href="'.$this->site['base_url'].'">'.Kohana::lang('client_lang.tt_home').'</a>';
	$str .= $images.@$this->mr['page_title'];
	echo $str;
}
?>