<table class="footer" width="100%" cellspacing="0" cellpadding="0" style="<?php if ($layout['footer_type']==1) { ?>background-image:url(<?php echo url::base()?>uploads/footer/<?php echo $layout['footer_image']?>); background-repeat:no-repeat;<?php } // end if ?>">
<!-- Bottom Menu -->
<?php if (!empty($layout['list_bottom_menu'])) { ?>
<tr><td class="menu_footer">
<div style="width:100%; float:left;"><?php require('menu_footer.php')?></div>
<div class="social_network">
<?php if(!empty($this->site['site_facebook'])){?>
<a target="_blank" href="<?php echo $this->site['site_facebook']?>"><img src="<?php echo $this->site['theme_url']?>index/pics/facebook.png" /></a>
<?php }//site_facebook?>
<?php if(!empty($this->site['site_twitter'])){?>
<a target="_blank" href="<?php echo $this->site['site_twitter']?>"><img src="<?php echo $this->site['theme_url']?>index/pics/twitter.png" /></a>
<?php }//site_twitter?>
<?php if(!empty($this->site['site_youtube'])){?>
<a target="_blank" href="<?php echo $this->site['site_youtube']?>"><img src="<?php echo $this->site['theme_url']?>index/pics/youtube.png" /></a>
<?php }//site_youtube?>
<?php if(!empty($this->site['site_linkedin'])){?>
<a target="_blank" href="<?php echo $this->site['site_linkedin']?>"><img src="<?php echo $this->site['theme_url']?>index/pics/linkedin.png" /></a>
<?php }//site_linkedin?>
</div>
</td>
</tr>
<?php } // end if bottom menu ?>
<!-- Footer -->
<tr>
    <td class="footer_content">
      <div style="width:100%; float:left;">
      <?php if ($layout['footer_type'] == 0 && !empty($layout['footer_content'])) { echo $layout['footer_content']?>		
      <?php } elseif($layout['footer_type'] == 3 && !empty($layout['footer_flash'])) { ?>
      <embed src="<?=url::base()?>uploads/footer/<?php echo $layout['footer_flash']?>" width="1024px" height="480px" quality="high" pluginspage="http://www.macromedia.com/go/getflashplayer" type="application/x-shockwave-flash"></embed>
      <?php } elseif($layout['footer_type'] == 2 && !empty($layout['footer_image'])) { ?>
      <img src="<?=url::base()?>uploads/footer/<?php echo $layout['footer_image']?>" />
      <?php } // end if ?>
      </div>
    </td>
  </tr>
</table>