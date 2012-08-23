<?php $lang = new Language_Model();
	$lang_client  = $lang->get_with_active();
?>
<?php if(count($lang_client) > 1){ for($i=0; $i<count($lang_client); $i++){ ?>
&nbsp;<a href="<?php echo $this->site['base_url']?>languages/index/client/<?php echo $lang_client[$i]['languages_id'] ?>"><img align="absmiddle" src="<?php echo $this->site['base_url']?>uploads/language/<?php echo $lang_client[$i]['languages_image'] ?>" /></a>
<?php }//end for ?><?php }//end if?>
