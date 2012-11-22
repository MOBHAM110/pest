<table class="banner_top" cellpadding="0" cellspacing="0">
<tr><td class="banner_top_T"></td></tr>
<tr>
    <td class="banner_top_M">
    <?php if(!empty($layout['list_top_B_banner'])){?>
    <?php require('banner_top_B_js.php')?>
    <div id="banner_top_M">
    <?php foreach ($layout['list_top_B_banner'] as $banner) { ?>
	<?php if ($banner['banner_type'] == 'image') { ?>
    	<?php if ($banner['banner_link'] != '') { ?><a href="<?php echo $banner['banner_link']?>" target="<?php echo $banner['banner_target'] ?>"><?php } ?>
    	<img src="<?php echo url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" border="0" <?php if ($banner['banner_width']>0) { ?>width="<?php echo $banner['banner_width']?>"<?php } ?> <?php if ($banner['banner_height']>0) { ?> height="<?php echo $banner['banner_height']?>"<?php } ?> alt="<?php echo $banner['banner_alt']?>"/>
        <?php if ($banner['banner_link'] != '') { ?></a><?php } ?>
    <?php } // end if ?>
    <?php } // end foreach ?>
    </div>
    <?php } // end if ?>    
	<?php foreach ($layout['list_top_B_banner'] as $banner) { ?>
    <?php if ($banner['banner_type'] != 'image') { ?>
		<embed src="<?=url::base()?>uploads/banner/<?php echo $banner['banner_file']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash" <?php if ($banner['banner_width']>0) { ?>width="<?php echo $banner['banner_width']?>"<?php } ?> <?php if ($banner['banner_height']>0) { ?>height="<?php echo $banner['banner_height']?>"<?php } ?> wmode="transparent" align="middle" style="text-align:left"></embed>
	<?php } // end if ?>
    <?php } // end foreach ?>
    </td></tr>
<tr><td class="banner_top_B"></td></tr>
</table>