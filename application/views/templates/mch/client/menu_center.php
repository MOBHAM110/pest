<table class="menu_center" cellspacing="0" cellpadding="0">
<tr>
    <td class="menu_center_L">&nbsp;</td>
    <td class="menu_center_Ce">
        <ul class="sf-menu_Ce">  
        <?php if ($layout['center_menu_level'] == 0) { ?> 
            <?php $root_page = ORM::factory('page_mptt')->__get('root');?>
            <?php $root_children = ORM::factory('page_mptt',$root_page->page_id)->__get('children')->as_array();?>	        
            <?php foreach($root_children as $child) { echo MCH_Core::multi_menu_mpt($child, $layout['list_center_menu']);} // end foreach ?>
        <?php } else { ?>
            <?php echo MCH_Core::one_level_menu($layout['list_center_menu'],'','<li>','</li>')?>
        <?php } // end if ?>             
        </ul>  
    </td>   
    <td class="menu_center_R"><?php $lang = new Language_Model();
	$lang_client  = $lang->get_with_active();
?>
<?php if(count($lang_client) > 1){ for($i=0; $i<count($lang_client); $i++){ ?>
&nbsp;<a href="<?php echo $this->site['base_url']?>languages/index/client/<?php echo $lang_client[$i]['languages_id'] ?>"><img align="absmiddle" src="<?php echo $this->site['base_url']?>uploads/language/<?php echo $lang_client[$i]['languages_image'] ?>" /></a>
<?php }//end for ?><?php }//end if?>
</td>
</tr>
</table>
<script> 
$(document).ready(function(){ 
	$("ul.sf-menu_Ce").superfish({ 
		pathClass:  'current' 
	}); 
}); 
</script>