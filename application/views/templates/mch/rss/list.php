<table class="rss" cellpadding="0" cellspacing="0">
<tr><td class="rss_top"></td></tr>
<?php foreach ($list_page as $page) { ?>
<tr>
	<td class="rss_middle"><img src="<?php echo $this->site['theme_url']?>index/pics/icon_rss.png" />&nbsp;&nbsp;<a href="<?php echo url::base()?>rss/get/<?php echo $page['page_id']?>"><?php echo $page['page_title']?></td>
</tr>
<?php } // end foreach ?>
<tr><td class="rss_bottom"></td></tr>
</table>
