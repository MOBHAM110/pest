<table class="login_account" cellpadding="0" cellspacing="0">
<?php if ($this->sess_cus['level'] < 3) { ?> 
<tr>
    <td><?php echo Kohana::lang('client_lang.lbl_welcome')?>, <?php echo $this->sess_cus['username']?><br />
        <a href="<?php echo url::base()?>login/log_out"><?php echo Kohana::lang('client_lang.lbl_logout')?></a>
    </td>
</tr>
<?php } else { ?>
<tr>
    <td align="center" nowrap="nowrap"><?php echo Kohana::lang('client_lang.lbl_welcome')?>, <?php echo $this->sess_cus['username']?></td>
</tr>
<tr>
    <td nowrap="nowrap"><img src="<?php echo $this->site['theme_url']?>index/pics/dot.gif" border="0" hspace="5px" /><a href="<?php echo url::base()?>mypage/viewaccount"><?php echo Kohana::lang('account_lang.tt_my_account')?></a></td>
</tr>
<?php if (isset($this->site['config']['GOOGLE_CALENDAR']) && $this->site['config']['GOOGLE_CALENDAR']) { ?>
<tr>
    <td nowrap="nowrap"><img src="<?php echo $this->site['theme_url']?>index/pics/dot.gif" border="0" hspace="5px" /><a href="<?php echo url::base()?>mypage/calendar"><?php echo Kohana::lang('account_lang.lbl_google_calendar')?></a></td>
</tr> 
<?php } // end if not google calendar ?>
<tr>
    <td><img src="<?php echo $this->site['theme_url']?>index/pics/dot.gif" border="0" hspace="5px" /><a href="<?php echo url::base()?>login/log_out"><?php echo Kohana::lang('account_lang.lbl_logout')?></a></td>
</tr>
<?php } // end if ?>
</table>