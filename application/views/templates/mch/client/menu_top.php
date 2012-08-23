<table class="menu_top" cellspacing="0" cellpadding="0" align="right">

<tr>
    <td class="menu_top_L">&nbsp;</td>
    <td class="menu_top_Ce">
        <ul class="sf-menu">
        <?php if ($layout['top_menu_level'] == 0) { ?> 
            <?php $root_page = ORM::factory('page_mptt')->__get('root');?>
            <?php $root_children = ORM::factory('page_mptt',$root_page->page_id)->__get('children')->as_array();?>	        
            <?php foreach($root_children as $child) { echo MCH_Core::multi_menu_mpt($child, $layout['list_top_menu']);} // end foreach ?>
        <?php } else { ?>
            <?php echo MCH_Core::one_level_menu($layout['list_top_menu'],'','<li>','</li>')?>
        <?php } // end if ?>             
        </ul>
    </td>   
    <td class="menu_top_R">&nbsp;</td>
</tr>
</table>
