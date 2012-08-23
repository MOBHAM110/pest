<table class="frame_content" width="100%" cellspacing="0" cellpadding="0">
<tr>
    <td class="frame_content_top"></td>
</tr>
<tr>
    <td class="frame_content_middle">
    	<?php if ($mr['calendar']) { ?>
    	<iframe src="http://www.google.com/calendar/embed?src=<?php echo $this->mr['email']?>" style="border:0;" scrolling="yes" width="550" height="400"></iframe>
        <?php } else { ?>
        	<?php echo Kohana::lang('errormsg_lang.error_google_calendar')?>
        <?php } // end if ?>
	</td>
</tr>
<tr><td class="frame_content_bottom">&nbsp;</td></tr>
</table>