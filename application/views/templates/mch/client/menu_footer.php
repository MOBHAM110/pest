<?php /*if ($layout['bottom_menu_level'] == 0) { ?>
<ul class="sf-menu">
	<?php $root_page = ORM::factory('page_mptt')->__get('root');?>
	<?php $root_children = ORM::factory('page_mptt',$root_page->page_id)->__get('children')->as_array();?>	        
	<?php foreach($root_children as $child) { echo MCH_Core::multi_menu_mpt($child, $layout['list_bottom_menu']);} // end foreach ?>
</ul>
<?php } else { ?>
	<?php echo MCH_Core::one_level_menu($layout['list_bottom_menu'])?>
<?php } // end if */?>   
<?php echo MCH_Core::one_level_menu($layout['list_bottom_menu'])?>