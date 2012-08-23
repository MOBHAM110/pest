<!-- Banner Center Top -->
<table class="banner_center" align="center" cellpadding="0" cellspacing="0">
<tr><td class="banner_center_T"></td></tr>
<?php foreach ($layout['list_center_top_banner'] as $banner) { ?>
<tr>
    <td class="banner_center_M">
	<?php if ($banner['banner_type'] == 'image') { ?>
    	<?php if ($banner['banner_link'] != '') { ?><a href="<?php echo $banner['banner_link']?>" target="<?php echo $banner['banner_target'] ?>"><?php } ?>
    	<img src="<?php echo url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" border="0" <?php if ($banner['banner_width']>0) { ?>width="<?php echo $banner['banner_width']?>"<?php } ?> <?php if ($banner['banner_height']>0) { ?> height="<?php echo $banner['banner_height']?>"<?php } ?> alt="<?php echo $banner['banner_alt']?>"/> 
        <?php if ($banner['banner_link'] != '') { ?></a><?php } ?> 
	<?php } else { ?>
		<embed src="<?=url::base()?>uploads/banners/<?php echo $banner['banner_file']?>" width="600" height="100" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" <?php if ($banner['banner_width']>0) { ?>width="<?php echo $banner['banner_width']?>"<?php } ?> <?php if ($banner['banner_height']>0) { ?>height="<?php echo $banner['banner_height']?>"<?php } ?>></embed>
	<?php } // end if ?>
    </td>
<tr>
<?php } // end foreach ?>
<tr><td class="banner_center_B"></td></tr>
</table>