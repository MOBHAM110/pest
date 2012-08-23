<table width="100%" class="header" cellspacing="0" cellpadding="0">
<?php if (!empty($this->site['site_logo'])) { ?>
<tr>
	<td rowspan="2" class="header_logo" ><a href="<?php echo url::base() ?>"><img <?php echo $this->get_thumbnail_size(DOCROOT.'uploads/site/'.$this->site['site_logo'],250,80)?> src="<?php echo url::base()?>uploads/site/<?php echo $this->site['site_logo']?>" alt="<?php echo $this->site['site_name']?>"></a>
    </td>
</tr>
<?php } // end if ?>
<tr>
<!-- Top Menu -->
<?php if (count($layout['list_top_menu']) > 0) { ?>    
    <td class="header_menu_top"  align="right"><?php require('menu_top.php')?></td>
<?php } // end if top menu ?>

<!-- Header -->
<?php if ($layout['header_type'] != 1) { ?>
    <td class="header_banner">
<?php if ($layout['header_type'] == 0 && $layout['header_content']) { ?>
    <?php echo $layout['header_content'] ?>
<?php } elseif($layout['header_type'] == 3 && !empty($layout['header_flash'])) { ?>
	<embed class="banner_flash" src="<?=url::base()?>uploads/header/<?php echo $layout['header_flash']?>" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
<?php } elseif($layout['header_type'] == 2 && !empty($layout['header_image'])) { ?>
	<img src="<?php echo url::base()?>uploads/header/<?php echo $layout['header_image']?>" />
<?php } // end if ?></td> 
<?php } // end if ?></tr></table>