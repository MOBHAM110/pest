<?php 
	$this->site['seo_title'] = (!empty($this->mr['page_title_seo'])?$this->mr['page_title_seo']
	:(!empty($this->mr['bbs_title'])?$this->mr['bbs_title']
	:(!empty($this->mr['page_title'])?$this->mr['page_title']:'')))
	.(!empty($this->site['site_title'])?' - '.$this->site['site_title']:'')
	.(!empty($this->site['site_slogan'])?' - '.$this->site['site_slogan']:'');
	$this->site['seo_title'] = strip_tags($this->site['seo_title']);
	
	$this->site['meta_key'] = !empty($this->mr['page_keyword'])?$this->mr['page_keyword']
	:(!empty($this->mr['bbs_title'])?$this->mr['bbs_title']
	:(!empty($this->mr['page_title'])?$this->mr['page_title']	
	:$this->site['site_keyword']));
	$this->site['meta_key'] = strip_tags($this->site['meta_key']);
	
	$this->site['meta_des'] = !empty($this->mr['page_description'])?$this->mr['page_description']
	:(!empty($this->mr['bbs_title'])?$this->mr['bbs_title']
	:(!empty($this->mr['page_title'])?$this->mr['page_title']	
	:$this->site['site_description']));
	$this->site['meta_des'] = strip_tags($this->site['meta_des']);
?>