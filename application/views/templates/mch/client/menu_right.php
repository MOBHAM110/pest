<table class="menu_right" cellspacing="0" cellpadding="0">
<tr><td class="menu_right_T"></td></tr>
<tr>
    <td valign="top" class="menu_right_M">
        <ul class="sf-menu_R sf-vertical">  
        <?php if ($layout['right_menu_level'] == 0) { ?> 
            <?php $root_page = ORM::factory('page_mptt')->__get('root');?>
            <?php $root_children = ORM::factory('page_mptt',$root_page->page_id)->__get('children')->as_array();?>	        
            <?php foreach($root_children as $child) { echo MCH_Core::multi_menu_mpt($child, $layout['list_right_menu']);} // end foreach ?>
        <?php } else { ?>
            <?php echo MCH_Core::one_level_menu($layout['list_right_menu'],'','<li>','</li>')?>
        <?php } // end if ?>             
        </ul>  
	</td>
</tr>
<tr><td class="menu_right_B"></td></tr>
</table>