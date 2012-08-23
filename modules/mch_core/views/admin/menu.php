<link rel="stylesheet" media="screen" href="<?php echo url::base()?>themes/admin/menu/superfish.css" /> 
<link rel="stylesheet" media="screen" href="<?php echo url::base()?>themes/admin/menu/superfish-navbar.css" /> 
<script src="<?php echo url::base()?>themes/admin/menu/hoverIntent.js"></script> 
<script src="<?php echo url::base()?>themes/admin/menu/superfish.js"></script> 
<script> 
    $(document).ready(function(){ 
        $("ul.sf-menu").superfish({ 
            pathClass:  'current' 
        }); 
    }); 
</script>

<?php
	function cate_multi_menu_mpt($node,$languages_id,&$str) 
	{
		$arr_where = array('menu_categories_id'=>$node->menu_categories_id,'languages_id'=>$languages_id);
		$node_name = ORM::factory('menu_category_description_orm')->where($arr_where)->find()->menu_categories_name;
		$root = ORM::factory('menu_category_orm')->__get('root');
		if ($node->is_leaf() && !$node->is_child($root)){ 
		?>
		<li><a href="<?php echo url::base().$node->menu_categories_link ?>"><span><?php echo $node_name ?></span></a></li>
		<?php 
		}else 
		{
		   ?>
           <?php if($node->menu_categories_link !="") { ?>
		   <li><a class="sf-with-ul" href="<?php echo url::base().$node->menu_categories_link ?>"><span><?php echo $node_name ?></span></a>
           <?php }else { ?>
            <li><a class="sf-with-ul" href="#"><span><?php echo $node_name ?></span></a>
           <?php } ?>
			<ul>
			<?php 
            $node_children = ORM::factory('menu_category_orm',$node->menu_categories_id)->__get('children'); 
			foreach ($node_children as $nc) 
			{
				if($nc->menu_categories_status==1)
				cate_multi_menu_mpt($nc,$languages_id,$str); 
			} // end foreach
         	?>
            </ul></li>
		<?php } // end if	
        } // end function 
   ?>
 <?php
 	$language_id =$this->get_admin_lang();
	$root_cate = ORM::factory('menu_category_orm')->__get('root');
	$root_children = $root_cate->__get('children');
  ?>
   <ul id="sample-menu-4" class="sf-menu sf-navbar">
		 <?php
		foreach($root_children as $children)
		{
			if($children->menu_categories_status==1)
			cate_multi_menu_mpt($children,$language_id,$str);
		}
		?>
    </ul>